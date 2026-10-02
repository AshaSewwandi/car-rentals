<?php

namespace App\Http\Controllers;

use App\Models\BudgetCategory;
use App\Models\BudgetEntry;
use App\Models\BudgetGroup;
use App\Models\BudgetIncome;
use App\Models\BudgetMonth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{
    // Default main/sub categories, used when no month exists yet. Budgets start at zero.
    private const DEFAULT_GROUPS = [
        'Loans & finance' => ['CAV-3227 Finance'],
        'Property' => ['Hiniduma Home', 'Galle Home', 'Kaduwela Home rent and Bills'],
        'Household' => ['Kaduwela home foods', 'Fridge'],
        'Transport' => ['Car service', 'Fuel', 'Vehicle repair'],
        'Life Style' => ['Trip', 'Anniversary', 'Shopping', 'New year budget'],
        'Savings' => ['Savings'],
        'Business' => ['Car accessory business'],
        'Miscellaneous' => ['Other'],
    ];

    private const DEFAULT_INCOME = ['Rental income'];

    // Bootstrap Icons admins can pick from for main and sub categories.
    public const ICONS = [
        'wallet2', 'credit-card', 'bank', 'piggy-bank', 'cash-coin', 'coin', 'receipt', 'graph-up-arrow',
        'house', 'house-door', 'house-heart', 'building', 'buildings', 'lightning-charge', 'droplet', 'wifi',
        'phone', 'tv', 'laptop', 'snow', 'cart', 'basket', 'bag', 'gift',
        'cup-hot', 'egg-fried', 'car-front', 'fuel-pump', 'wrench-adjustable', 'tools', 'truck', 'bus-front',
        'airplane', 'globe', 'suitcase-lg', 'umbrella', 'balloon-heart', 'heart', 'stars', 'flower1',
        'music-note-beamed', 'film', 'controller', 'book', 'mortarboard', 'heart-pulse', 'capsule', 'shield-check',
        'people', 'person-badge', 'briefcase', 'shop', 'megaphone', 'calendar-event', 'tag', 'box-seam',
    ];

    // First matching keyword wins, so more specific words come first.
    private const GROUP_ICON_WORDS = [
        'loan|finance|debt' => 'bank', 'propert' => 'buildings', 'household|home' => 'house-heart',
        'transport|vehicle|car|fleet' => 'car-front', 'life|leisure|fun' => 'balloon-heart', 'saving' => 'piggy-bank',
        'business|work' => 'briefcase', 'market|ads' => 'megaphone', 'staff|salar' => 'people',
        'educat|school|class' => 'mortarboard', 'health|medical' => 'heart-pulse', 'misc|other' => 'box-seam',
    ];

    private const CATEGORY_ICON_WORDS = [
        'food|grocer|meal' => 'basket', 'fridge' => 'snow', 'finance|loan|lease|emi' => 'credit-card',
        'fuel|petrol|diesel' => 'fuel-pump', 'repair' => 'wrench-adjustable', 'service|accessor' => 'tools',
        'trip|travel|flight' => 'airplane', 'anniversar|wedding' => 'heart', 'shopping|cloth' => 'bag',
        'new year|party|festiv' => 'stars', 'saving' => 'piggy-bank', 'business|shop' => 'shop',
        'rent|home|house' => 'house-door', 'bill|electric|water' => 'receipt', 'tv' => 'tv',
        'class|fee|school|book' => 'book', 'insur' => 'shield-check', 'gift' => 'gift', 'ads|market' => 'megaphone',
        'staff|salar' => 'people', 'other|misc' => 'box-seam',
    ];

    public static function guessIcon(string $name, bool $group = false): string
    {
        foreach ($group ? self::GROUP_ICON_WORDS : self::CATEGORY_ICON_WORDS as $words => $icon) {
            if (preg_match('/' . $words . '/i', $name)) {
                return $icon;
            }
        }

        return $group ? 'box-seam' : 'tag';
    }

    public function index(Request $request)
    {
        $month = $this->validMonth($request->get('month')) ?? now()->format('Y-m');
        $canManage = $request->user()->canManageData();
        $canCategories = $request->user()->isDashboardAdmin();
        $initial = $this->monthPayload($month, $canCategories);
        $icons = self::ICONS;

        return view('budget.index', compact('month', 'canManage', 'canCategories', 'initial', 'icons'));
    }

    public function show(Request $request, string $month)
    {
        return response()->json($this->monthPayload($month, $request->user()->isDashboardAdmin()));
    }

    public function history()
    {
        $current = now()->format('Y-m');

        $months = BudgetMonth::query()
            ->with(['incomes', 'groups.categories', 'entries'])
            ->where(fn ($q) => $q->whereNotNull('touched_at')->orWhereHas('entries')->orWhere('month', $current))
            ->orderByDesc('month')
            ->get();

        return response()->json($months->map(function (BudgetMonth $m) {
            $spentByCat = $m->entries->groupBy('budget_category_id')->map->sum('amount');

            return [
                'month' => $m->month,
                'income' => (float) $m->incomes->sum('amount'),
                'budget' => (float) $m->groups->flatMap->categories->sum('budget'),
                'spent' => (float) $m->entries->sum('amount'),
                'entries' => $m->entries->count(),
                'cats' => $m->groups->flatMap->categories->map(fn ($c) => [
                    'name' => $c->name,
                    'budget' => (float) $c->budget,
                    'spent' => (float) ($spentByCat[$c->id] ?? 0),
                ])->values(),
            ];
        })->values());
    }

    public function storeEntry(Request $request, string $month)
    {
        $budgetMonth = $this->resolveMonth($month);
        $categoryIds = BudgetCategory::query()
            ->whereIn('budget_group_id', $budgetMonth->groups()->pluck('id'))
            ->pluck('id');

        $data = $request->validate([
            'budget_category_id' => ['required', Rule::in($categoryIds->all())],
            'amount' => ['required', 'numeric', 'gt:0'],
            'note' => ['nullable', 'string', 'max:255'],
            'date' => ['required', 'date_format:Y-m-d', 'starts_with:' . $month],
        ]);

        $budgetMonth->entries()->create($data + ['created_by' => $request->user()->id]);

        return $this->changed($budgetMonth);
    }

    public function destroyEntry(BudgetEntry $entry)
    {
        $budgetMonth = $entry->month;
        $entry->delete();

        return $this->changed($budgetMonth);
    }

    public function storeIncome(string $month)
    {
        $budgetMonth = $this->resolveMonth($month);
        $budgetMonth->incomes()->create([
            'name' => 'New source',
            'amount' => 0,
            'sort' => (int) $budgetMonth->incomes()->max('sort') + 1,
        ]);

        return $this->changed($budgetMonth);
    }

    public function updateIncome(Request $request, BudgetIncome $income)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
        ]);
        if (array_key_exists('name', $data)) {
            $data['name'] = trim((string) $data['name']) ?: 'Income';
        }

        $income->update($data);

        return $this->changed($income->month);
    }

    public function destroyIncome(BudgetIncome $income)
    {
        $budgetMonth = $income->month;
        $income->delete();

        return $this->changed($budgetMonth);
    }

    // ---- Main and sub categories: one shared plan, every change applies to all months ----

    public function storeGroup(Request $request, string $month)
    {
        $budgetMonth = $this->resolveMonth($month);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', Rule::in(self::ICONS)],
            'sub' => ['nullable', 'string', 'max:255'],
            'sub_icon' => ['nullable', Rule::in(self::ICONS)],
            'budget' => ['nullable', 'numeric', 'min:0'],
        ]);
        $budget = $request->user()->canManageData() ? ($data['budget'] ?? 0) : 0;

        DB::transaction(function () use ($data, $budget) {
            $groupKey = (string) Str::uuid();
            $subKey = filled($data['sub'] ?? null) ? (string) Str::uuid() : null;
            $name = trim($data['name']);
            $icon = $data['icon'] ?? self::guessIcon($name, true);

            foreach (BudgetMonth::query()->get() as $m) {
                $group = $m->groups()->create([
                    'plan_key' => $groupKey,
                    'name' => $name,
                    'icon' => $icon,
                    'sort' => (int) $m->groups()->max('sort') + 1,
                ]);
                if ($subKey) {
                    $sub = trim($data['sub']);
                    $group->categories()->create([
                        'plan_key' => $subKey, 'name' => $sub, 'icon' => $data['sub_icon'] ?? self::guessIcon($sub),
                        'budget' => $budget, 'sort' => 0,
                    ]);
                }
            }
        });

        return $this->changed($budgetMonth);
    }

    public function updateGroup(Request $request, BudgetGroup $group)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'icon' => ['sometimes', 'required', Rule::in(self::ICONS)],
        ]);
        if (isset($data['name'])) {
            $data['name'] = trim($data['name']);
        }
        $this->groupCopies($group)->each->update($data);

        return $this->changed($group->month);
    }

    public function destroyGroup(BudgetGroup $group)
    {
        abort_if($this->hasEntries($group->categories()->pluck('id')), 422,
            'This main category has spending logged this month. Delete those entries first.');

        $kept = 0;
        DB::transaction(function () use ($group, &$kept) {
            foreach ($this->groupCopies($group) as $copy) {
                foreach ($copy->categories as $cat) {
                    $this->hasEntries([$cat->id]) ? $cat->update(['plan_key' => null]) : $cat->delete();
                }
                // A month that logged spending keeps its copy (unlinked) so no entries are lost.
                if ($copy->categories()->exists()) {
                    $copy->update(['plan_key' => null]);
                    $kept++;
                } else {
                    $copy->delete();
                }
            }
        });

        return $this->changed($group->month, $this->keptNotice('Main category deleted', $kept));
    }

    public function storeCategory(Request $request, BudgetGroup $group)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', Rule::in(self::ICONS)],
            'budget' => ['nullable', 'numeric', 'min:0'],
        ]);
        $budget = $request->user()->canManageData() ? ($data['budget'] ?? 0) : 0;

        DB::transaction(function () use ($group, $data, $budget) {
            $key = (string) Str::uuid();
            $name = trim($data['name']);
            $icon = $data['icon'] ?? self::guessIcon($name);
            foreach ($this->groupCopies($group) as $copy) {
                $copy->categories()->create([
                    'plan_key' => $key,
                    'name' => $name,
                    'icon' => $icon,
                    'budget' => $budget,
                    'sort' => (int) $copy->categories()->max('sort') + 1,
                ]);
            }
        });

        return $this->changed($group->month);
    }

    public function updateCategory(Request $request, BudgetCategory $category)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'icon' => ['sometimes', 'required', Rule::in(self::ICONS)],
            'budget' => ['sometimes', 'required', 'numeric', 'min:0'],
        ]);

        // Admins can rename and change icons; only super admins change budget amounts.
        abort_if(array_key_exists('budget', $data) && !$request->user()->canManageData(), 403, 'Only a super admin can change budgets.');

        if (isset($data['name'])) {
            $data['name'] = trim($data['name']);
        }
        $this->categoryCopies($category)->each->update($data);

        return $this->changed($category->group->month);
    }

    public function destroyCategory(BudgetCategory $category)
    {
        abort_if($this->hasEntries([$category->id]), 422,
            'This sub category has spending logged this month. Delete those entries first.');

        $kept = 0;
        DB::transaction(function () use ($category, &$kept) {
            foreach ($this->categoryCopies($category) as $copy) {
                if ($this->hasEntries([$copy->id])) {
                    $copy->update(['plan_key' => null]);
                    $kept++;
                } else {
                    $copy->delete();
                }
            }
        });

        return $this->changed($category->group->month, $this->keptNotice('Sub category deleted', $kept));
    }

    // Makes every other month use this month's main/sub categories and budgets.
    // Old sub categories with spending logged stay in their month so no entries are lost.
    public function syncPlan(string $month)
    {
        abort_unless($this->validMonth($month), 404);
        $source = BudgetMonth::query()->with('groups.categories')->where('month', $month)->firstOrFail();
        $kept = 0;

        DB::transaction(function () use ($source, &$kept) {
            foreach ($source->groups as $g) {
                $g->plan_key ??= (string) Str::uuid();
                $g->save();
                foreach ($g->categories as $c) {
                    $c->plan_key ??= (string) Str::uuid();
                    $c->save();
                }
            }

            $others = BudgetMonth::query()->with('groups.categories')->where('id', '!=', $source->id)->get();
            foreach ($others as $m) {
                $groups = $m->groups;
                $cats = $groups->flatMap->categories;
                $usedGroups = [];
                $usedCats = [];

                foreach ($source->groups as $gi => $g) {
                    $target = $groups->first(fn ($x) => !in_array($x->id, $usedGroups) && $x->plan_key === $g->plan_key)
                        ?? $groups->first(fn ($x) => !in_array($x->id, $usedGroups) && strcasecmp($x->name, $g->name) === 0)
                        ?? $m->groups()->make();
                    $target->fill(['plan_key' => $g->plan_key, 'name' => $g->name, 'icon' => $g->icon, 'sort' => $gi])->save();
                    $usedGroups[] = $target->id;

                    foreach ($g->categories as $ci => $c) {
                        $match = $cats->first(fn ($x) => !in_array($x->id, $usedCats) && $x->plan_key === $c->plan_key)
                            ?? $cats->first(fn ($x) => !in_array($x->id, $usedCats) && $x->budget_group_id === $target->id && strcasecmp($x->name, $c->name) === 0)
                            ?? $cats->first(fn ($x) => !in_array($x->id, $usedCats) && strcasecmp($x->name, $c->name) === 0)
                            ?? new BudgetCategory();
                        $match->fill([
                            'budget_group_id' => $target->id, 'plan_key' => $c->plan_key, 'name' => $c->name,
                            'icon' => $c->icon, 'budget' => $c->budget, 'sort' => $ci,
                        ])->save();
                        $usedCats[] = $match->id;
                    }
                }

                foreach ($cats->whereNotIn('id', $usedCats) as $old) {
                    if ($this->hasEntries([$old->id])) {
                        $old->update(['plan_key' => null]);
                        $kept++;
                    } else {
                        $old->delete();
                    }
                }
                foreach ($groups->whereNotIn('id', $usedGroups) as $old) {
                    $old->categories()->exists() ? $old->update(['plan_key' => null]) : $old->delete();
                }
            }
        });

        return $this->changed($source, $kept
            ? "Plan applied to all months. Kept {$kept} old sub " . ($kept > 1 ? 'categories' : 'category') . ' that have spending logged.'
            : 'Plan applied to all months.');
    }

    private function groupCopies(BudgetGroup $group)
    {
        return $group->plan_key ? BudgetGroup::query()->where('plan_key', $group->plan_key)->get() : collect([$group]);
    }

    private function categoryCopies(BudgetCategory $category)
    {
        return $category->plan_key ? BudgetCategory::query()->where('plan_key', $category->plan_key)->get() : collect([$category]);
    }

    private function hasEntries($categoryIds): bool
    {
        return BudgetEntry::query()->whereIn('budget_category_id', $categoryIds)->exists();
    }

    private function keptNotice(string $done, int $kept): ?string
    {
        return $kept ? "{$done}. Kept in {$kept} other " . ($kept > 1 ? 'months' : 'month') . ' that have spending logged.' : null;
    }

    public function export(string $month)
    {
        abort_unless($this->validMonth($month), 404);
        $budgetMonth = BudgetMonth::query()->where('month', $month)->firstOrFail();
        $budgetMonth->load(['incomes', 'groups.categories', 'entries.category']);
        $spentByCat = $budgetMonth->entries->groupBy('budget_category_id')->map->sum('amount');

        $rows = [['Section', 'Group', 'Category', 'Budget', 'Spent', 'Left']];
        foreach ($budgetMonth->groups as $group) {
            foreach ($group->categories as $cat) {
                $spent = (float) ($spentByCat[$cat->id] ?? 0);
                $rows[] = ['Budget', $group->name, $cat->name, $cat->budget, $spent, $cat->budget - $spent];
            }
        }
        $rows[] = [];
        $rows[] = ['Section', 'Source', 'Amount'];
        foreach ($budgetMonth->incomes as $income) {
            $rows[] = ['Income', $income->name, $income->amount];
        }
        $rows[] = [];
        $rows[] = ['Section', 'Date', 'Category', 'Note', 'Amount'];
        foreach ($budgetMonth->entries->sortBy('date') as $entry) {
            $rows[] = ['Spend', $entry->date->format('Y-m-d'), $entry->category?->name ?? '', $entry->note, $entry->amount];
        }

        return $this->csv("budget-{$month}.csv", $rows);
    }

    public function exportAll()
    {
        $rows = [['Month', 'Date', 'Group', 'Category', 'Note', 'Amount']];

        BudgetEntry::query()
            ->with(['month', 'category.group'])
            ->orderBy('date')
            ->orderBy('id')
            ->each(function (BudgetEntry $entry) use (&$rows) {
                $rows[] = [
                    $entry->month->month,
                    $entry->date->format('Y-m-d'),
                    $entry->category?->group?->name ?? '',
                    $entry->category?->name ?? '',
                    $entry->note,
                    $entry->amount,
                ];
            });

        return $this->csv('budget-history.csv', $rows);
    }

    private function validMonth(?string $month): ?string
    {
        return $month && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month) ? $month : null;
    }

    // Finds the month, or creates it by copying the shared plan from the nearest month (without entries).
    private function resolveMonth(string $month): BudgetMonth
    {
        abort_unless($this->validMonth($month), 404);

        if ($existing = BudgetMonth::query()->firstWhere('month', $month)) {
            return $existing;
        }

        return DB::transaction(function () use ($month) {
            $template = $this->templateFor($month);
            $budgetMonth = BudgetMonth::create(['month' => $month]);

            foreach ($template['income'] as $i => $income) {
                $budgetMonth->incomes()->create(['name' => $income['name'], 'amount' => $income['amount'], 'sort' => $i]);
            }
            foreach ($template['groups'] as $gi => $group) {
                $newGroup = $budgetMonth->groups()->create([
                    'plan_key' => $group['key'] ?? null, 'name' => $group['name'], 'icon' => $group['icon'], 'sort' => $gi,
                ]);
                foreach ($group['cats'] as $ci => $cat) {
                    $newGroup->categories()->create([
                        'plan_key' => $cat['key'] ?? null, 'name' => $cat['name'], 'icon' => $cat['icon'], 'budget' => $cat['budget'], 'sort' => $ci,
                    ]);
                }
            }

            return $budgetMonth;
        });
    }

    private function templateFor(string $month): array
    {
        $nearest = BudgetMonth::query()->with(['incomes', 'groups.categories'])->where('month', '<', $month)->orderByDesc('month')->first()
            ?? BudgetMonth::query()->with(['incomes', 'groups.categories'])->where('month', '>', $month)->orderBy('month')->first();

        if ($nearest) {
            return $this->structure($nearest);
        }

        return [
            'income' => array_map(fn ($name) => ['id' => null, 'name' => $name, 'amount' => 0], self::DEFAULT_INCOME),
            'groups' => collect(self::DEFAULT_GROUPS)->map(fn ($cats, $name) => [
                'id' => null,
                'name' => $name,
                'icon' => self::guessIcon($name, true),
                'cats' => array_map(fn ($c) => ['id' => null, 'name' => $c, 'icon' => self::guessIcon($c), 'budget' => 0], $cats),
            ])->values()->all(),
        ];
    }

    private function structure(BudgetMonth $m): array
    {
        return [
            'income' => $m->incomes->map(fn ($i) => ['id' => $i->id, 'name' => $i->name, 'amount' => (float) $i->amount])->all(),
            'groups' => $m->groups->map(fn ($g) => [
                'id' => $g->id,
                'key' => $g->plan_key,
                'name' => $g->name,
                'icon' => $g->icon ?: self::guessIcon($g->name, true),
                'cats' => $g->categories->map(fn ($c) => [
                    'id' => $c->id, 'key' => $c->plan_key, 'name' => $c->name,
                    'icon' => $c->icon ?: self::guessIcon($c->name), 'budget' => (float) $c->budget,
                ])->all(),
            ])->all(),
        ];
    }

    private function monthPayload(string $month, bool $canCreate): array
    {
        abort_unless($this->validMonth($month), 404);

        $budgetMonth = $canCreate
            ? $this->resolveMonth($month)
            : BudgetMonth::query()->firstWhere('month', $month);

        // Read-only users see the plan that would carry over, without creating the month.
        if (!$budgetMonth) {
            return ['month' => $month, 'persisted' => false, 'expenses' => []] + $this->templateFor($month);
        }

        $budgetMonth->load(['incomes', 'groups.categories', 'entries']);

        return ['month' => $month, 'persisted' => true] + $this->structure($budgetMonth) + [
            'expenses' => $budgetMonth->entries
                ->sortByDesc(fn ($e) => $e->date->format('Y-m-d') . sprintf('%012d', $e->id))
                ->map(fn ($e) => [
                    'id' => $e->id,
                    'catId' => $e->budget_category_id,
                    'amount' => (float) $e->amount,
                    'note' => $e->note,
                    'date' => $e->date->format('Y-m-d'),
                ])->values()->all(),
        ];
    }

    private function changed(BudgetMonth $budgetMonth, ?string $notice = null)
    {
        $budgetMonth->forceFill(['touched_at' => now()])->save();

        return response()->json($this->monthPayload($budgetMonth->month, true) + ['notice' => $notice]);
    }

    private function csv(string $filename, array $rows)
    {
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            foreach ($rows as $row) {
                // Stop spreadsheet apps from running notes as formulas.
                fputcsv($out, array_map(fn ($v) => is_string($v) && preg_match('/^[=+\-@\t\r]/', $v) ? "'" . $v : $v, $row));
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
