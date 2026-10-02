<?php

namespace App\Http\Controllers;

use App\Models\BudgetMonth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BudgetReportController extends Controller
{
    // Joins main and sub category names into one key; a control character can't appear in a name.
    private const KEY_SEP = "\x1F";

    public function month(Request $request, string $month)
    {
        abort_unless($this->isMonth($month), 404);
        $data = $this->buildMonthReport($month, $this->compareMonth($request, $month));
        $data['generatedBy'] = $request->user()->name;

        $suffix = $data['compare'] ? '-vs-' . $data['compare']['month'] : '';

        return Pdf::loadView('budget.report-month-pdf', $data)
            ->setPaper('a4')
            ->download("budget-report-{$month}{$suffix}.pdf");
    }

    // Interactive analysis page with charts and an optional comparison month.
    public function analysis(Request $request)
    {
        $months = BudgetMonth::query()->orderByDesc('month')->pluck('month');
        abort_if($months->isEmpty(), 404, 'No budget months yet.');

        $month = $this->isMonth($request->get('month')) && $months->contains($request->get('month'))
            ? $request->get('month')
            : ($months->contains(now()->format('Y-m')) ? now()->format('Y-m') : $months->first());

        $data = $this->buildMonthReport($month, $this->compareMonth($request, $month));
        $data['months'] = $months->map(fn ($m) => ['value' => $m, 'label' => Carbon::createFromFormat('Y-m-d', $m . '-01')->format('F Y')]);

        return view('budget.analysis', $data);
    }

    private function isMonth($value): bool
    {
        return is_string($value) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $value) === 1;
    }

    // ?compare=YYYY-MM picks a month, ?compare=none turns comparison off, and no value means the previous month.
    private function compareMonth(Request $request, string $month): ?string
    {
        $requested = $request->get('compare');
        if ($requested === 'none') {
            return null;
        }
        $candidate = $this->isMonth($requested)
            ? $requested
            : Carbon::createFromFormat('Y-m-d', $month . '-01')->subMonth()->format('Y-m');

        return $candidate !== $month && BudgetMonth::query()->where('month', $candidate)->exists() ? $candidate : null;
    }

    private function buildMonthReport(string $month, ?string $compareKey): array
    {
        $data = $this->analyseMonth(BudgetMonth::query()->where('month', $month)->firstOrFail());
        $compare = $compareKey ? $this->analyseMonth(BudgetMonth::query()->where('month', $compareKey)->firstOrFail()) : null;

        $data['compare'] = $compare;
        $data['comparison'] = $compare ? $this->comparison($data, $compare) : null;
        // Only compare in the insights when a comparison month is selected.
        $data['previous'] = $compare
            ? ['month' => $compare['month'], 'label' => $compare['monthLabel'], 'spent' => $compare['spent']]
            : null;
        $data['trend'] = $this->trend($month, 6);
        $data['insights'] = array_merge($this->monthInsights($data), $data['comparison'] ? $this->comparisonInsights($data) : []);

        return $data;
    }

    // Lines up two months by main/sub category name.
    private function comparison(array $a, array $b): array
    {
        $delta = fn ($x, $y) => ['a' => $x, 'b' => $y, 'diff' => $x - $y, 'pct' => $y != 0 ? ($x - $y) / abs($y) * 100 : null];

        $groupNames = $a['groups']->pluck('name')->merge($b['groups']->pluck('name'))->unique()->values();
        $groups = $groupNames->map(function ($name) use ($a, $b, $delta) {
            $ga = $a['groups']->firstWhere('name', $name);
            $gb = $b['groups']->firstWhere('name', $name);
            return ['name' => $name, 'budgetA' => $ga['budget'] ?? 0, 'budgetB' => $gb['budget'] ?? 0]
                + $delta($ga['spent'] ?? 0, $gb['spent'] ?? 0);
        });

        $flat = fn ($d) => $d['groups']->flatMap(fn ($g) => $g['cats']->map(fn ($c) => $c + ['group' => $g['name']]));
        $catsA = $flat($a)->keyBy(fn ($c) => $c['group'] . self::KEY_SEP . $c['name']);
        $catsB = $flat($b)->keyBy(fn ($c) => $c['group'] . self::KEY_SEP . $c['name']);
        $cats = $catsA->keys()->merge($catsB->keys())->unique()->values()->map(function ($k) use ($catsA, $catsB, $delta) {
            [$group, $name] = explode(self::KEY_SEP, $k, 2);
            return ['group' => $group, 'name' => $name, 'budget' => $catsA[$k]['budget'] ?? 0, 'status' => $catsA[$k]['status'] ?? 'Not in this month']
                + $delta($catsA[$k]['spent'] ?? 0, $catsB[$k]['spent'] ?? 0);
        });

        return [
            'income' => $delta($a['income'], $b['income']),
            'budget' => $delta($a['budget'], $b['budget']),
            'spent' => $delta($a['spent'], $b['spent']),
            'left' => $delta($a['left'], $b['left']),
            'entries' => $delta($a['entryCount'], $b['entryCount']),
            'groups' => $groups,
            'cats' => $cats,
        ];
    }

    private function comparisonInsights(array $d): array
    {
        $rs = fn ($n) => 'Rs ' . number_format(round($n));
        $c = $d['comparison'];
        $label = $d['compare']['monthLabel'];
        $out = [];

        $up = $c['groups']->where('diff', '>', 0)->sortByDesc('diff')->first();
        $down = $c['groups']->where('diff', '<', 0)->sortBy('diff')->first();
        if ($up) {
            $out[] = ['warn', "Biggest increase vs {$label}: {$up['name']}, up {$rs($up['diff'])}" . ($up['pct'] !== null ? ' (' . round($up['pct']) . '%)' : '') . '.'];
        }
        if ($down) {
            $out[] = ['good', "Biggest decrease vs {$label}: {$down['name']}, down {$rs(-$down['diff'])}" . ($down['pct'] !== null ? ' (' . round(abs($down['pct'])) . '%)' : '') . '.'];
        }
        $new = $c['cats']->filter(fn ($x) => $x['a'] > 0 && $x['b'] == 0);
        if ($new->isNotEmpty()) {
            $out[] = ['info', 'New spending this month that ' . $label . ' did not have: ' . $new->pluck('name')->implode(', ') . '.'];
        }
        if ($c['income']['diff'] != 0) {
            $out[] = [$c['income']['diff'] > 0 ? 'good' : 'warn', 'Income is ' . ($c['income']['diff'] > 0 ? 'up ' : 'down ') . $rs(abs($c['income']['diff'])) . " compared with {$label}."];
        }

        return $out;
    }

    public function history(Request $request)
    {
        $months = BudgetMonth::query()
            ->with(['incomes', 'groups.categories', 'entries'])
            ->where(fn ($q) => $q->whereNotNull('touched_at')->orWhereHas('entries'))
            ->orderBy('month')
            ->get();
        if ($months->isEmpty()) {
            return redirect()->route('budget.index')->with('error', 'There is nothing to report yet. Add income, budgets or spending first.');
        }

        $rows = $months->map(fn ($m) => $this->summary($m));
        $withSpend = $rows->where('spent', '>', 0);

        // Spending per main category, summed by name across months.
        $groupTotals = $months->flatMap(function (BudgetMonth $m) {
            $spentByCat = $m->entries->groupBy('budget_category_id')->map->sum('amount');
            return $m->groups->map(fn ($g) => [
                'name' => $g->name,
                'budget' => (float) $g->categories->sum('budget'),
                'spent' => (float) $g->categories->sum(fn ($c) => $spentByCat[$c->id] ?? 0),
            ]);
        })->groupBy('name')->map(fn ($items, $name) => [
            'name' => $name,
            'budget' => $items->sum('budget'),
            'spent' => $items->sum('spent'),
        ])->sortByDesc('spent')->values();

        $totals = [
            'income' => $rows->sum('income'),
            'budget' => $rows->sum('budget'),
            'spent' => $rows->sum('spent'),
            'left' => $rows->sum('left'),
            'entries' => $rows->sum('entries'),
        ];

        $data = [
            'rows' => $rows,
            'totals' => $totals,
            'groupTotals' => $groupTotals,
            'avgSpent' => $withSpend->count() ? $withSpend->avg('spent') : 0,
            'highest' => $withSpend->sortByDesc('spent')->first(),
            'lowest' => $withSpend->sortBy('spent')->first(),
            'generatedBy' => $request->user()->name,
        ];
        $data['insights'] = $this->historyInsights($data);

        return Pdf::loadView('budget.report-history-pdf', $data)
            ->setPaper('a4')
            ->download('budget-history-report.pdf');
    }

    private function analyseMonth(BudgetMonth $m): array
    {
        $m->load(['incomes', 'groups.categories', 'entries.category.group']);
        $start = Carbon::createFromFormat('Y-m-d', $m->month . '-01')->startOfDay();
        $daysInMonth = $start->daysInMonth;
        $spentByCat = $m->entries->groupBy('budget_category_id')->map->sum('amount');
        $countByCat = $m->entries->groupBy('budget_category_id')->map->count();

        $income = (float) $m->incomes->sum('amount');
        $budget = (float) $m->groups->flatMap->categories->sum('budget');
        $spent = (float) $m->entries->sum('amount');

        $groups = $m->groups->map(function ($g) use ($spentByCat, $countByCat, $spent) {
            $cats = $g->categories->map(function ($c) use ($spentByCat, $countByCat) {
                $s = (float) ($spentByCat[$c->id] ?? 0);
                $b = (float) $c->budget;
                return [
                    'name' => $c->name,
                    'budget' => $b,
                    'spent' => $s,
                    'left' => $b - $s,
                    'entries' => (int) ($countByCat[$c->id] ?? 0),
                    'used' => $b > 0 ? $s / $b * 100 : null,
                    'status' => $b <= 0 ? ($s > 0 ? 'Unbudgeted' : 'No budget') : ($s > $b ? 'Over budget' : ($s >= $b * 0.9 ? 'Nearly used' : ($s > 0 ? 'On track' : 'Not used'))),
                ];
            });
            $gb = $cats->sum('budget');
            $gs = $cats->sum('spent');
            return [
                'name' => $g->name,
                'budget' => $gb,
                'spent' => $gs,
                'left' => $gb - $gs,
                'used' => $gb > 0 ? $gs / $gb * 100 : null,
                'share' => $spent > 0 ? $gs / $spent * 100 : 0,
                'cats' => $cats,
            ];
        });

        // Spending pace: compare where we are against how far through the month we are.
        $today = now()->startOfDay();
        $state = $today->lt($start) ? 'future' : ($today->gt($start->copy()->endOfMonth()) ? 'past' : 'current');
        $daysElapsed = $state === 'current' ? $today->day : ($state === 'past' ? $daysInMonth : 0);
        $projected = $state === 'current' && $daysElapsed > 0 ? $spent / $daysElapsed * $daysInMonth : $spent;

        $daily = array_fill(1, $daysInMonth, 0.0);
        foreach ($m->entries as $e) {
            $daily[(int) $e->date->format('j')] += (float) $e->amount;
        }
        $weeks = [];
        foreach ($daily as $day => $amount) {
            $w = intdiv($day - 1, 7) + 1;
            $weeks[$w] ??= ['label' => 'Week ' . $w . ' (' . (($w - 1) * 7 + 1) . '–' . min($w * 7, $daysInMonth) . ')', 'spent' => 0.0];
            $weeks[$w]['spent'] += $amount;
        }
        $busiestDay = $spent > 0 ? array_search(max($daily), $daily, true) : null;

        return [
            'month' => $m->month,
            'monthLabel' => $start->format('F Y'),
            'income' => $income,
            'budget' => $budget,
            'spent' => $spent,
            'left' => $income - $spent,
            'unplanned' => $income - $budget,
            'used' => $budget > 0 ? $spent / $budget * 100 : 0,
            'savingsRate' => $income > 0 ? ($income - $spent) / $income * 100 : null,
            'groups' => $groups,
            'incomes' => $m->incomes,
            'entries' => $m->entries->sortBy([['date', 'asc'], ['id', 'asc']])->values(),
            'topEntries' => $m->entries->sortByDesc('amount')->take(5)->values(),
            'entryCount' => $m->entries->count(),
            'state' => $state,
            'daysInMonth' => $daysInMonth,
            'daysElapsed' => $daysElapsed,
            'projected' => $projected,
            'weeks' => array_values($weeks),
            'daily' => array_values($daily),
            'busiestDay' => $busiestDay ? ['label' => $start->copy()->day($busiestDay)->format('D, j M'), 'amount' => $daily[$busiestDay]] : null,
            'avgPerDay' => $daysElapsed > 0 ? $spent / $daysElapsed : 0,
        ];
    }

    private function summary(BudgetMonth $m): array
    {
        $income = (float) $m->incomes->sum('amount');
        $budget = (float) $m->groups->flatMap->categories->sum('budget');
        $spent = (float) $m->entries->sum('amount');

        return [
            'month' => $m->month,
            'label' => Carbon::createFromFormat('Y-m-d', $m->month . '-01')->format('M Y'),
            'income' => $income,
            'budget' => $budget,
            'spent' => $spent,
            'left' => $income - $spent,
            'used' => $budget > 0 ? $spent / $budget * 100 : 0,
            'entries' => $m->entries->count(),
        ];
    }

    private function trend(string $month, int $count): Collection
    {
        $from = Carbon::createFromFormat('Y-m-d', $month . '-01')->subMonths($count - 1)->format('Y-m');

        return BudgetMonth::query()
            ->with(['incomes', 'groups.categories', 'entries'])
            ->whereBetween('month', [$from, $month])
            ->orderBy('month')
            ->get()
            ->map(fn ($m) => $this->summary($m));
    }

    private function monthInsights(array $d): array
    {
        $rs = fn ($n) => 'Rs ' . number_format(round($n));
        $out = [];

        if ($d['entryCount'] === 0) {
            $out[] = ['info', 'No spending has been logged for this month yet, so most of the analysis below is empty.'];
        }

        if ($d['budget'] > 0 && $d['state'] === 'current' && $d['spent'] > 0) {
            $timePct = $d['daysElapsed'] / $d['daysInMonth'] * 100;
            $line = sprintf('%d%% of the budget is used with %d%% of the month gone (day %d of %d).', round($d['used']), round($timePct), $d['daysElapsed'], $d['daysInMonth']);
            if ($d['projected'] > $d['budget']) {
                $out[] = ['bad', $line . ' At this pace, spending will reach ' . $rs($d['projected']) . ', which is ' . $rs($d['projected'] - $d['budget']) . ' over budget.'];
            } else {
                $out[] = ['good', $line . ' At this pace, the month ends around ' . $rs($d['projected']) . ', within budget.'];
            }
        } elseif ($d['budget'] > 0 && $d['state'] === 'past') {
            $out[] = $d['spent'] > $d['budget']
                ? ['bad', 'The month ended ' . $rs($d['spent'] - $d['budget']) . ' over budget (' . round($d['used']) . '% used).']
                : ['good', 'The month ended within budget, using ' . round($d['used']) . '% and leaving ' . $rs($d['budget'] - $d['spent']) . ' unspent.'];
        }

        if ($d['unplanned'] < 0) {
            $out[] = ['bad', 'The plan is ' . $rs(-$d['unplanned']) . ' more than income. Either raise income or trim budgets to balance it.'];
        } elseif ($d['income'] > 0 && $d['unplanned'] > 0) {
            $out[] = ['info', $rs($d['unplanned']) . ' of income (' . round($d['unplanned'] / $d['income'] * 100) . '%) is not assigned to any budget yet.'];
        } elseif ($d['income'] <= 0) {
            $out[] = ['info', 'No income is recorded for this month, so savings and "left to spend" figures are not meaningful.'];
        }

        $cats = $d['groups']->flatMap(fn ($g) => $g['cats']->map(fn ($c) => $c + ['group' => $g['name']]));
        $over = $cats->where('status', 'Over budget')->sortByDesc(fn ($c) => $c['spent'] - $c['budget']);
        if ($over->isNotEmpty()) {
            $list = $over->take(3)->map(fn ($c) => $c['name'] . ' (' . $rs($c['spent'] - $c['budget']) . ' over)')->implode(', ');
            $out[] = ['bad', $over->count() . ' sub ' . ($over->count() > 1 ? 'categories are' : 'category is') . ' over budget: ' . $list . '.'];
        }
        $unbudgeted = $cats->where('status', 'Unbudgeted');
        if ($unbudgeted->isNotEmpty()) {
            $out[] = ['warn', $rs($unbudgeted->sum('spent')) . ' was spent in sub categories with no budget (' . $unbudgeted->pluck('name')->implode(', ') . '). Consider giving them a budget.'];
        }
        $nearly = $cats->where('status', 'Nearly used');
        if ($nearly->isNotEmpty()) {
            $out[] = ['warn', 'Close to the limit (90%+ used): ' . $nearly->pluck('name')->implode(', ') . '.'];
        }

        $top = $d['groups']->sortByDesc('spent')->first();
        if ($top && $top['spent'] > 0) {
            $out[] = ['info', $top['name'] . ' is the biggest area of spending at ' . $rs($top['spent']) . ' (' . round($top['share']) . '% of the month).'];
        }

        if ($d['previous'] && $d['previous']['spent'] > 0 && $d['spent'] > 0) {
            $diff = $d['spent'] - $d['previous']['spent'];
            $pct = abs($diff) / $d['previous']['spent'] * 100;
            $out[] = [$diff > 0 ? 'warn' : 'good', sprintf('Spending is %s%d%% (%s) compared with %s%s.',
                $diff > 0 ? 'up ' : 'down ', round($pct), $rs(abs($diff)), $d['previous']['label'], $d['state'] === 'current' ? ' (this month is not finished yet)' : '')];
        }

        if ($d['savingsRate'] !== null && $d['spent'] > 0) {
            $out[] = [$d['savingsRate'] >= 20 ? 'good' : ($d['savingsRate'] < 0 ? 'bad' : 'info'),
                'Savings rate so far is ' . round($d['savingsRate']) . '% of income (' . $rs($d['left']) . ' not spent).'];
        }

        if ($d['busiestDay']) {
            $out[] = ['info', 'Busiest day was ' . $d['busiestDay']['label'] . ' with ' . $rs($d['busiestDay']['amount']) . ' spent.'];
        }

        return $out;
    }

    private function historyInsights(array $d): array
    {
        $rs = fn ($n) => 'Rs ' . number_format(round($n));
        $out = [];
        $rows = $d['rows'];

        $out[] = ['info', sprintf('%d %s covered, with %d spend entries totalling %s.',
            $rows->count(), $rows->count() > 1 ? 'months' : 'month', $d['totals']['entries'], $rs($d['totals']['spent']))];

        if ($d['highest'] && $d['lowest'] && $d['highest']['month'] !== $d['lowest']['month']) {
            $out[] = ['info', 'Highest spending: ' . $d['highest']['label'] . ' (' . $rs($d['highest']['spent']) . '). Lowest: ' . $d['lowest']['label'] . ' (' . $rs($d['lowest']['spent']) . '). Average: ' . $rs($d['avgSpent']) . ' a month.'];
        }

        $overMonths = $rows->filter(fn ($r) => $r['budget'] > 0 && $r['spent'] > $r['budget']);
        if ($overMonths->isNotEmpty()) {
            $out[] = ['bad', 'Over budget in ' . $overMonths->count() . ' ' . ($overMonths->count() > 1 ? 'months' : 'month') . ': ' . $overMonths->pluck('label')->implode(', ') . '.'];
        } elseif ($rows->where('spent', '>', 0)->isNotEmpty()) {
            $out[] = ['good', 'Spending stayed within budget in every month with entries.'];
        }

        if ($d['totals']['income'] > 0) {
            $rate = ($d['totals']['income'] - $d['totals']['spent']) / $d['totals']['income'] * 100;
            $out[] = [$rate >= 20 ? 'good' : ($rate < 0 ? 'bad' : 'info'), 'Overall, ' . round($rate) . '% of income was not spent (' . $rs($d['totals']['left']) . ').'];
        }

        $top = $d['groupTotals']->first();
        if ($top && $top['spent'] > 0) {
            $out[] = ['info', $top['name'] . ' is the biggest area of spending overall at ' . $rs($top['spent']) . ' (' . round($top['spent'] / max($d['totals']['spent'], 1) * 100) . '%).'];
        }

        $withSpend = $rows->where('spent', '>', 0)->values();
        if ($withSpend->count() >= 2) {
            $last = $withSpend->last();
            $before = $withSpend[$withSpend->count() - 2];
            $diff = $last['spent'] - $before['spent'];
            $out[] = [$diff > 0 ? 'warn' : 'good', sprintf('Latest month with entries (%s) spent %s %s than %s.', $last['label'], $rs(abs($diff)), $diff > 0 ? 'more' : 'less', $before['label'])];
        }

        return $out;
    }
}
