@extends('layouts.app')
@section('title', 'Budget Planner')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
  .budget-page {
    /* Site palette from layouts/app.blade.php */
    --primary:#0a3f8f; --primary-deep:#072d6b; --accent:#0f66c3; --accent-soft:#e8efff;
    --ink:#111827; --ink-2:#475569; --ink-3:#64748b;
    --card:#FFFFFF; --line:#e3e9f2; --field:#f5f8fd;
    --good:#16a34a; --bad:#dc2626; --focus:#0f66c3;
    /* Group colors: blues and teals that sit with the brand */
    --g1:#0a3f8f; --g2:#0f66c3; --g3:#3b82f6; --g4:#0ea5e9; --g5:#0d9488; --g6:#4f46e5; --g7:#1e40af; --g8:#06b6d4; --g9:#64748b;
    color: var(--ink);
    max-width: 1280px;
  }
  .budget-page h1, .budget-page h2, .budget-page .bp-display { font-family: "Space Grotesk", sans-serif; letter-spacing: -.01em; margin: 0; }
  .budget-page button { cursor: pointer; }
  .budget-page :focus-visible { outline: 2px solid var(--focus); outline-offset: 2px; border-radius: 6px; }
  .budget-page .num { font-variant-numeric: tabular-nums; }
  .budget-page input[readonly] { cursor: default; }

  .bp-top { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
  .bp-month { display:flex; align-items:center; gap:6px; }
  .bp-month h1 { font-size:clamp(26px,4vw,40px); font-weight:700; line-height:1.05; min-width:7ch; }
  .bp-iconbtn { width:38px; height:38px; border-radius:50%; border:1px solid var(--line); background:var(--card); display:grid; place-items:center; font-size:18px; line-height:1; }
  .bp-iconbtn:hover { border-color:var(--ink-3); }
  .bp-status { font-size:13px; color:var(--ink-3); }
  .bp-toolbar { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
  .bp-btn { border:1px solid var(--line); background:var(--card); padding:8px 14px; border-radius:10px; font-weight:600; font-size:14px; }
  .bp-btn:hover { border-color:var(--ink-3); }
  .bp-btn.sm { padding:5px 10px; font-size:13px; }
  .bp-btn-report { background:linear-gradient(135deg, var(--accent), var(--primary)); color:#fff; border-color:var(--primary); }
  .bp-btn-report:hover { color:#fff; filter:brightness(1.08); }
  .bp-tabs { display:inline-flex; background:var(--card); border:1px solid var(--line); border-radius:12px; padding:3px; gap:2px; }
  .bp-tabs button { border:0; background:none; padding:7px 14px; border-radius:9px; font-weight:700; font-size:14px; color:var(--ink-2); }
  .bp-tabs button[aria-selected="true"] { background:linear-gradient(135deg, var(--accent), var(--primary)); color:#fff; }

  .bp-hero { margin-top:20px; background:var(--card); border:1px solid var(--line); border-radius:22px; padding:24px 24px 20px; }
  .bp-sentence { font-family:"Space Grotesk",sans-serif; font-size:clamp(19px,2.6vw,26px); font-weight:500; line-height:1.3; color:var(--ink-2); max-width:60ch; margin:0; }
  .bp-sentence b { color:var(--ink); font-weight:700; }
  .bp-ribbon { margin-top:18px; display:flex; height:58px; border-radius:14px; overflow:hidden; background:var(--field); gap:3px; }
  .bp-seg { position:relative; min-width:6px; background:color-mix(in srgb, var(--c) 28%, var(--card)); transition:flex-grow .5s ease; border:0; padding:0; }
  .bp-seg .fill { position:absolute; inset:0 auto 0 0; background:var(--c); transition:width .5s ease; }
  .bp-seg .lbl { position:absolute; left:8px; bottom:6px; font-size:12px; font-weight:700; color:#fff; text-shadow:0 1px 2px rgba(0,0,0,.35); white-space:nowrap; pointer-events:none; }
  .bp-seg.free { background:repeating-linear-gradient(135deg, var(--field) 0 6px, var(--line) 6px 8px); }
  .bp-seg.over .fill { background:var(--bad); }
  .bp-legend { display:flex; flex-wrap:wrap; gap:6px 16px; margin-top:12px; font-size:13px; color:var(--ink-2); }
  .bp-legend span { display:inline-flex; align-items:center; gap:6px; }
  .bp-legend i { width:10px; height:10px; border-radius:3px; background:var(--c); display:inline-block; }
  .bp-stats { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; margin-top:20px; }
  .bp-stat { padding:12px 14px; border-radius:14px; background:var(--field); }
  .bp-stat .k { font-size:13px; color:var(--ink-2); }
  .bp-stat .v { font-family:"Space Grotesk",sans-serif; font-size:22px; font-weight:700; }
  .bp-stat.left .v { color:var(--good); }
  .bp-stat.neg .v { color:var(--bad); }

  .bp-quick { margin-top:18px; display:grid; grid-template-columns:1.4fr 1fr 1.6fr 1fr auto; gap:10px; background:linear-gradient(135deg, var(--primary-deep), var(--primary)); color:#fff; padding:14px; border-radius:18px; align-items:end; }
  .bp-quick label { font-size:12px; opacity:.75; display:block; margin:0 0 4px 2px; }
  .bp-quick input, .bp-quick select { width:100%; border:0; border-radius:10px; padding:10px 12px; background:rgba(255,255,255,.12); color:#fff; color-scheme:dark; }
  .bp-quick select option { color:#16243A; background:#fff; }
  .bp-quick input::placeholder { color:rgba(255,255,255,.5); }
  .bp-quick .add { background:#fff; color:var(--primary); border:0; border-radius:10px; padding:10px 18px; font-weight:800; height:42px; }
  .bp-quick .add:hover { filter:brightness(1.05); }
  .bp-quick .add:disabled { opacity:.6; }

  .bp-grid { display:grid; grid-template-columns:minmax(0,1.65fr) minmax(0,1fr); gap:20px; margin-top:20px; align-items:start; }
  .bp-col { display:flex; flex-direction:column; gap:16px; }
  .bp-panel { background:var(--card); border:1px solid var(--line); border-radius:18px; overflow:hidden; }
  .bp-ghead { display:flex; align-items:center; gap:10px; padding:14px 16px; border-bottom:1px solid var(--line); }
  .bp-gicon, .bp-cicon { display:grid; place-items:center; flex:none; border:0; padding:0; color:var(--c, var(--accent)); background:color-mix(in srgb, var(--c, var(--accent)) 12%, #fff); }
  .bp-gicon { width:34px; height:34px; border-radius:10px; font-size:17px; }
  .bp-cicon { width:30px; height:30px; border-radius:9px; font-size:15px; }
  .bp-gicon.pickable, .bp-cicon.pickable { cursor:pointer; transition:box-shadow .15s; }
  .bp-gicon.pickable:hover, .bp-cicon.pickable:hover { box-shadow:0 0 0 2px var(--c, var(--accent)); }
  .bp-iconpick { flex:none; width:34px; height:34px; display:grid; place-items:center; border:1px dashed var(--ink-3); border-radius:8px; background:var(--card); color:var(--ink-3); font-size:15px; padding:0; }
  .bp-iconpick:hover, .bp-iconpick.chosen { border:1px solid var(--accent); color:var(--accent); background:var(--accent-soft); }
  .bp-withicon { display:flex; gap:6px; }
  .bp-withicon input { flex:1; min-width:0; }
  .bp-iconpop { position:absolute; z-index:1090; width:min(340px, calc(100vw - 16px)); background:#fff; border:1px solid var(--line, #e3e9f2); border-radius:14px; box-shadow:0 12px 32px rgba(10,63,143,.18); padding:10px; }
  .bp-iconpop-title { font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.04em; margin:2px 4px 8px; }
  .bp-iconpop-grid { display:grid; grid-template-columns:repeat(8, 1fr); gap:4px; max-height:260px; overflow:auto; }
  .bp-iconpop-grid button { aspect-ratio:1; border:1px solid transparent; border-radius:8px; background:#f5f8fd; color:#0a3f8f; font-size:17px; display:grid; place-items:center; padding:0; cursor:pointer; }
  .bp-iconpop-grid button:hover, .bp-iconpop-grid button:focus-visible { background:#e8efff; border-color:#0f66c3; outline:none; }
  .bp-iconpop-grid button.on { background:linear-gradient(135deg, #0f66c3, #0a3f8f); color:#fff; }
  .bp-ghead h2 { font-size:17px; font-weight:700; flex:1; }
  .bp-ghead .sub { font-size:13px; color:var(--ink-2); }
  .bp-ghead .sub b { color:var(--ink); }
  .bp-line { display:grid; grid-template-columns:32px minmax(0,1fr) 120px 110px; gap:10px; align-items:center; padding:10px 16px; border-bottom:1px solid var(--line); }
  .bp-line:last-of-type { border-bottom:0; }

  .bp-line .name { font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; background:none; border:0; text-align:left; padding:0; color:inherit; max-width:100%; }
  .bp-line .name:hover { text-decoration:underline; text-decoration-color:var(--ink-3); }
  .bp-line .meta { font-size:12.5px; color:var(--ink-3); margin-top:2px; }
  .bp-bar { height:6px; border-radius:4px; background:var(--field); margin-top:6px; overflow:hidden; }
  .bp-bar > div { height:100%; background:var(--c); border-radius:4px; transition:width .4s ease; }
  .bp-bar.over > div { background:var(--bad); }
  .bp-budget-wrap { display:flex; align-items:center; gap:4px; margin:0; background:var(--field); border:1px solid var(--line); border-radius:8px; padding:0 8px; }
  .bp-budget-wrap:focus-within { border-color:var(--focus); box-shadow:0 0 0 2px color-mix(in srgb, var(--focus) 20%, transparent); }
  .bp-budget-wrap span { font-size:12.5px; color:var(--ink-3); flex:none; }
  .budget-page .bp-budget-in { width:100%; min-width:0; text-align:right; border:0; background:none; padding:6px 0; outline:none; box-shadow:none; -moz-appearance:textfield; appearance:textfield; }
  .budget-page .bp-budget-in:focus, .budget-page .bp-budget-in:focus-visible { outline:none; box-shadow:none; }
  .bp-budget-in::-webkit-outer-spin-button, .bp-budget-in::-webkit-inner-spin-button { -webkit-appearance:none; margin:0; }
  .bp-budget-in::placeholder { color:var(--ink-3); font-size:13px; }
  .bp-budget-wrap:has(input[readonly]) { border-color:transparent; }
  .bp-colnames { padding-top:8px; padding-bottom:6px; font-size:11.5px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:var(--ink-3); }
  .bp-colnames span:nth-child(3), .bp-colnames span:nth-child(4) { text-align:right; }
  .bp-setbudget { border:1px dashed var(--ink-3); background:none; color:var(--focus); border-radius:8px; padding:3px 8px; font-weight:700; font-size:13px; }
  .bp-setbudget:hover { border-style:solid; }
  .bp-addcat .bp-newbudget { flex:0 0 130px; }
  .bp-namerow { display:flex; align-items:center; gap:4px; min-width:0; }
  .bp-namerow .name { min-width:0; }
  .bp-tools { display:inline-flex; gap:2px; flex:none; }
  .bp-tool { background:none; border:0; color:var(--ink-3); font-size:14px; line-height:1; padding:4px 6px; border-radius:6px; }
  .bp-tool:hover { color:var(--ink); background:var(--field); }
  .bp-tool-del:hover { color:var(--bad); }
  .bp-namerow .bp-tools { opacity:0; }
  .bp-line:hover .bp-tools, .bp-namerow .bp-tools:focus-within { opacity:1; }
  @media (hover: none) { .bp-namerow .bp-tools { opacity:1; } }
  .bp-rename { flex:1; min-width:0; font-weight:600; border:1px solid var(--focus); background:var(--card); border-radius:8px; padding:4px 8px; }
  .bp-ghead .bp-rename { font-size:16px; }
  .bp-newgroup-form.nobudget { grid-template-columns:1.3fr 1.3fr auto; }
  .bp-plannote { display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; padding:10px 16px; border-top:1px solid var(--line); background:var(--field); font-size:13px; color:var(--ink-2); }
  .bp-newgroup { border-style:dashed; }
  .bp-newgroup-form { display:grid; grid-template-columns:1.3fr 1.3fr .8fr auto; gap:10px; align-items:end; padding:14px 16px; }
  .bp-newgroup-form label { display:block; font-size:12px; color:var(--ink-2); margin:0 0 4px 2px; }
  .bp-newgroup-form label span { color:var(--ink-3); }
  .bp-newgroup-form input { width:100%; border:1px solid var(--line); background:var(--field); border-radius:8px; padding:7px 10px; }
  .bp-newgroup-form .bp-btn { background:linear-gradient(135deg, var(--accent), var(--primary)); color:#fff; border-color:var(--primary); height:38px; }
  @media (max-width: 700px) { .bp-newgroup-form { grid-template-columns:1fr; } }
  .bp-left-v { text-align:right; font-weight:700; font-size:14px; }
  .bp-left-v.ok { color:var(--good); } .bp-left-v.bad { color:var(--bad); } .bp-left-v.zero { color:var(--ink-3); font-weight:500; }
  .bp-addcat { display:flex; gap:8px; padding:10px 16px; background:var(--field); }
  .bp-addcat input { flex:1; min-width:0; border:1px solid var(--line); background:var(--card); border-radius:8px; padding:6px 10px; }
  .bp-addcat button { border:1px solid var(--line); background:var(--card); border-radius:8px; padding:6px 12px; font-weight:600; font-size:13px; }
  .bp-del { background:none; border:0; color:var(--ink-3); font-size:16px; padding:2px 6px; border-radius:6px; }
  .bp-del:hover { color:var(--bad); background:var(--field); }
  .bp-colhead { display:flex; justify-content:space-between; align-items:center; gap:10px; padding:14px 16px; border-bottom:1px solid var(--line); }
  .bp-colhead h2 { font-size:17px; }
  .bp-inc { display:grid; grid-template-columns:minmax(0,1fr) 120px 26px; gap:8px; align-items:center; padding:8px 16px; border-bottom:1px solid var(--line); }
  .bp-inc.ro { grid-template-columns:minmax(0,1fr) 120px; }
  .bp-inc input { border:1px solid transparent; background:var(--field); border-radius:8px; padding:6px 8px; width:100%; }
  .bp-inc input.amt { text-align:right; }
  .bp-inc-total { display:flex; justify-content:space-between; padding:12px 16px; font-weight:700; font-family:"Space Grotesk",sans-serif; font-size:18px; }
  .bp-log { max-height:520px; overflow:auto; }
  .bp-entry { display:grid; grid-template-columns:30px minmax(0,1fr) auto 26px; gap:10px; align-items:center; padding:10px 16px; border-bottom:1px solid var(--line); }
  .bp-entry.ro { grid-template-columns:30px minmax(0,1fr) auto; }
  .bp-entry .t { font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
  .bp-entry .s { font-size:12.5px; color:var(--ink-3); }
  .bp-entry .a { font-weight:700; }
  .bp-empty { padding:24px 16px; color:var(--ink-2); font-size:14px; }
  .bp-filter { display:flex; align-items:center; gap:8px; padding:8px 16px; background:var(--field); font-size:13px; }
  .bp-filter button { border:0; background:none; color:var(--focus); font-weight:700; padding:0; }
  .bp-daily { padding:14px 16px 10px; }
  .bp-daily svg { width:100%; height:auto; display:block; }
  .bp-daily .cap { display:flex; justify-content:space-between; gap:8px; flex-wrap:wrap; font-size:13px; color:var(--ink-2); margin-bottom:6px; }
  .bp-daily .cap b { color:var(--ink); }
  .bp-dayhead { display:flex; justify-content:space-between; padding:8px 16px; background:var(--field); font-size:13px; font-weight:700; color:var(--ink-2); position:sticky; top:0; }
  .bp-toast { position:fixed; left:50%; bottom:20px; transform:translateX(-50%) translateY(20px); color:#fff; padding:10px 16px; border-radius:12px; font-weight:600; font-size:14px; opacity:0; transition:.25s; pointer-events:none; z-index:1080; background:var(--primary-deep); }
  .bp-toast.show { opacity:1; transform:translateX(-50%) translateY(0); }
  .bp-toast.err { background:var(--bad); }
  @keyframes bp-pulse { 0% { box-shadow:0 0 0 0 color-mix(in srgb, var(--accent) 60%, transparent); } 100% { box-shadow:0 0 0 14px transparent; } }
  .bp-flash { animation:bp-pulse .7s ease-out; }

  .bp-hist { margin-top:20px; }
  .bp-hist .bp-panel { margin-bottom:16px; }
  .bp-htable { width:100%; border-collapse:collapse; font-size:14px; }
  .bp-htable th { text-align:right; font-size:12.5px; color:var(--ink-2); font-weight:600; padding:10px 14px; border-bottom:1px solid var(--line); white-space:nowrap; }
  .bp-htable th:first-child, .bp-htable td:first-child { text-align:left; }
  .bp-htable td { text-align:right; padding:11px 14px; border-bottom:1px solid var(--line); white-space:nowrap; }
  .bp-htable tr.open-m { cursor:pointer; }
  .bp-htable tr.open-m:hover td { background:var(--field); }
  .bp-htable tr.cur td { font-weight:700; }
  .bp-htable tfoot td { font-weight:700; font-family:"Space Grotesk",sans-serif; border-bottom:0; }
  .bp-tw { overflow-x:auto; }
  .bp-pos { color:var(--good); } .bp-neg { color:var(--bad); }
  .bp-mbar { display:inline-block; width:70px; height:6px; border-radius:4px; background:var(--field); vertical-align:middle; overflow:hidden; margin-left:8px; }
  .bp-mbar i { display:block; height:100%; background:var(--accent); }
  .bp-mbar.over i { background:var(--bad); }
  .bp-hsel { display:flex; gap:10px; align-items:center; padding:12px 16px; border-bottom:1px solid var(--line); flex-wrap:wrap; }
  .bp-hsel select { border:1px solid var(--line); background:var(--field); border-radius:8px; padding:6px 10px; }

  @media (max-width: 1100px) {
    .bp-grid { grid-template-columns:1fr; }
    .bp-quick { grid-template-columns:1fr 1fr; }
    .bp-quick .note { grid-column:1/-1; }
    .bp-quick .add { grid-column:1/-1; width:100%; }
    .bp-stats { grid-template-columns:repeat(2,minmax(0,1fr)); }
  }
  @media (max-width: 560px) {
    .bp-hero { padding:18px 16px; }
    .bp-line { grid-template-columns:30px minmax(0,1fr) 96px; }
    .bp-line .bp-left-v { grid-column:2/-1; text-align:left; }
    .bp-colnames { display:none; }
    .bp-addcat { flex-wrap:wrap; }
    .bp-addcat input[data-newcat] { flex:1 1 calc(100% - 44px); }
    .bp-seg .lbl { display:none; }
  }
  @media (prefers-reduced-motion: reduce) { .budget-page *, .bp-toast { transition:none !important; animation:none !important; } }
</style>

<div class="budget-page">
  <header class="bp-top">
    <div class="bp-month">
      <button class="bp-iconbtn" id="bpPrev" type="button" aria-label="Previous month">&lsaquo;</button>
      <h1 id="bpMonthTitle"></h1>
      <button class="bp-iconbtn" id="bpNext" type="button" aria-label="Next month">&rsaquo;</button>
    </div>
    <div class="bp-toolbar">
      <div class="bp-tabs" role="tablist">
        <button role="tab" type="button" id="bpTabMonth" aria-selected="true">This month</button>
        <button role="tab" type="button" id="bpTabHist" aria-selected="false">History</button>
      </div>
      <span class="bp-status" id="bpStatus"></span>
      <a class="bp-btn text-decoration-none text-reset" id="bpAnalysis" href="{{ route('budget.analysis', ['month' => $month]) }}"><i class="bi bi-bar-chart-line" aria-hidden="true"></i> Analysis</a>
      <button class="bp-btn bp-btn-report" type="button" id="bpExport"><i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i> Download report</button>
    </div>
  </header>

  <div id="bpMonthView">
    <section class="bp-hero" aria-label="Month overview">
      <p class="bp-sentence" id="bpSentence"></p>
      <div class="bp-ribbon" id="bpRibbon" role="img" aria-label="Budget split by group"></div>
      <div class="bp-legend" id="bpLegend"></div>
      <div class="bp-stats" id="bpStats"></div>
    </section>

    @if($canManage)
      <form class="bp-quick" id="bpQuick" autocomplete="off" onsubmit="return false">
        <div><label for="bpCat">Category</label><select id="bpCat"></select></div>
        <div><label for="bpAmt">Amount (Rs)</label><input id="bpAmt" type="number" inputmode="decimal" min="0" step="any" placeholder="0"></div>
        <div class="note"><label for="bpNote">What was it for?</label><input id="bpNote" type="text" maxlength="255" placeholder="e.g. Full tank, office supplies"></div>
        <div><label for="bpDate">Date</label><input id="bpDate" type="date"></div>
        <button class="add" id="bpAdd" type="button">Log spend</button>
      </form>
    @endif

    <div class="bp-grid">
      <div class="bp-col" id="bpGroups"></div>
      <aside class="bp-col">
        <section class="bp-panel">
          <div class="bp-colhead"><h2>Income</h2>@if($canManage)<button class="bp-btn sm" type="button" id="bpAddInc">Add source</button>@endif</div>
          <div id="bpIncome"></div>
        </section>
        <section class="bp-panel">
          <div class="bp-colhead"><h2>Day by day</h2></div>
          <div class="bp-daily" id="bpDaily"></div>
        </section>
        <section class="bp-panel">
          <div class="bp-colhead"><h2>Spending log</h2><span class="bp-status" id="bpLogCount"></span></div>
          <div id="bpFilterBar"></div>
          <div class="bp-log" id="bpLog"></div>
        </section>
      </aside>
    </div>
  </div>
  <div id="bpHistView" class="bp-hist" hidden></div>
</div>
<div class="bp-toast" id="bpToast" role="status" aria-live="polite"></div>

<script>
(function () {
  const BASE = @json(url('/budget'));
  const CSRF = @json(csrf_token());
  const CAN_MANAGE = @json($canManage);
  const CAN_CATS = @json($canCategories);
  const COLORS = ['--g1','--g2','--g3','--g4','--g5','--g6','--g7','--g8','--g9'];
  const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const rs = n => 'Rs ' + Math.round(n || 0).toLocaleString('en-US');
  const $ = id => document.getElementById(id);
  const pad = n => String(n).padStart(2, '0');

  let state = @json($initial);
  let cur = { y: +state.month.slice(0, 4), m: +state.month.slice(5, 7) - 1 };
  let filterCat = null;
  let renaming = null; // 'g:ID' or 'c:ID' while a name is being edited
  const ICONS = @json($icons);
  const newIcons = {}; // icons chosen in the "add" forms, keyed by picker target
  const iconHtml = name => `<i class="bi bi-${esc(name || 'tag')}" aria-hidden="true"></i>`;
  const groupColor = g => `var(${COLORS[Math.max(0, state.groups.indexOf(g)) % COLORS.length]})`;
  let history_ = [];
  let histCat = '';

  const key = c => `${c.y}-${pad(c.m + 1)}`;
  const setStatus = t => { $('bpStatus').textContent = t; };
  let toastT;
  function toast(t, err) {
    const el = $('bpToast');
    el.textContent = t; el.classList.toggle('err', !!err); el.classList.add('show');
    clearTimeout(toastT); toastT = setTimeout(() => el.classList.remove('show'), 2000);
  }

  async function api(method, path, body) {
    const res = await fetch(BASE + path, {
      method,
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
      body: body ? JSON.stringify(body) : undefined,
    });
    if (!res.ok) {
      let msg = 'Something went wrong. Please try again.';
      try { const j = await res.json(); msg = j.errors ? Object.values(j.errors)[0][0] : (j.message || msg); } catch (e) {}
      throw new Error(msg);
    }
    return res.json();
  }
  async function write(method, path, body, okMsg) {
    setStatus('Saving…');
    try {
      state = await api(method, path, body);
      setStatus('Saved');
      render();
      if (state.notice) toast(state.notice);
      else if (okMsg) toast(okMsg);
      return true;
    } catch (e) {
      setStatus('Not saved');
      toast(e.message, true);
      render();
      return false;
    }
  }

  // ---------- calc ----------
  const spentOf = catId => state.expenses.filter(e => e.catId === catId).reduce((a, e) => a + (+e.amount || 0), 0);
  const allCats = () => state.groups.flatMap(g => g.cats);
  function findCat(id) { for (const g of state.groups) for (const c of g.cats) if (c.id === id) return { c, g }; return null; }
  function todayStr() { const t = new Date(); return `${t.getFullYear()}-${pad(t.getMonth() + 1)}-${pad(t.getDate())}`; }
  function isCurMonth() { const t = new Date(); return t.getFullYear() === cur.y && t.getMonth() === cur.m; }
  function daysIn(c) { return new Date(c.y, c.m + 1, 0).getDate(); }
  function spentOn(d) { return state.expenses.filter(e => e.date === d).reduce((a, e) => a + (+e.amount || 0), 0); }
  function fmtDay(d) { return d ? new Date(d + 'T00:00').toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short' }) : 'No date'; }
  function defaultDate() { return isCurMonth() ? todayStr() : `${key(cur)}-01`; }

  // ---------- render ----------
  function render() {
    $('bpMonthTitle').textContent = `${MONTHS[cur.m]} ${cur.y}`;
    const income = state.income.reduce((a, i) => a + (+i.amount || 0), 0);
    const budget = allCats().reduce((a, c) => a + (+c.budget || 0), 0);
    const spent = state.expenses.reduce((a, e) => a + (+e.amount || 0), 0);
    const left = income - spent, unplanned = income - budget;

    $('bpSentence').innerHTML = `<b>${rs(income)}</b> coming in, <b>${rs(budget)}</b> planned, <b>${rs(spent)}</b> spent so far.` +
      (unplanned < 0 ? ` You've planned <b>${rs(-unplanned)}</b> more than you earn.` : '');

    // ribbon
    const total = Math.max(income, budget, 1);
    let rib = '', leg = '';
    state.groups.forEach((g, i) => {
      const gb = g.cats.reduce((a, c) => a + (+c.budget || 0), 0);
      const gs = g.cats.reduce((a, c) => a + spentOf(c.id), 0);
      if (gb <= 0 && gs <= 0) return;
      const col = `var(${COLORS[i % COLORS.length]})`;
      const pct = gb > 0 ? Math.min(100, gs / gb * 100) : 100;
      rib += `<button type="button" class="bp-seg${gs > gb ? ' over' : ''}" style="--c:${col};flex-grow:${Math.max(gb, gs) / total * 100}" data-jump="${g.id}" title="${esc(g.name)}: ${rs(gs)} of ${rs(gb)}" aria-label="${esc(g.name)}"><div class="fill" style="width:${pct}%"></div><span class="lbl">${gb / total > .08 ? esc(g.name) : ''}</span></button>`;
      leg += `<span><i style="--c:${col}"></i>${esc(g.name)} ${Math.round(gb / Math.max(income, budget, 1) * 100)}%</span>`;
    });
    if (unplanned > 0) {
      rib += `<div class="bp-seg free" style="flex-grow:${unplanned / total * 100}" title="Not planned yet: ${rs(unplanned)}"></div>`;
      leg += `<span><i style="--c:var(--line)"></i>Not planned ${rs(unplanned)}</span>`;
    }
    $('bpRibbon').innerHTML = rib; $('bpLegend').innerHTML = leg;

    const usedPct = budget > 0 ? Math.round(spent / budget * 100) : 0;
    $('bpStats').innerHTML = `
      <div class="bp-stat ${left < 0 ? 'neg' : 'left'}"><div class="k">Left to spend</div><div class="v num">${rs(left)}</div></div>
      <div class="bp-stat"><div class="k">Budget used</div><div class="v num">${usedPct}%</div></div>
      <div class="bp-stat ${unplanned < 0 ? 'neg' : ''}"><div class="k">${unplanned < 0 ? 'Over-planned' : 'Not planned yet'}</div><div class="v num">${rs(Math.abs(unplanned))}</div></div>
      <div class="bp-stat"><div class="k">${isCurMonth() ? 'Spent today' : 'Daily average'}</div><div class="v num">${rs(isCurMonth() ? spentOn(todayStr()) : spent / daysIn(cur))}</div></div>`;
    renderDaily();

    // quick add
    if (CAN_MANAGE) {
      const sel = $('bpCat'), prev = sel.value;
      sel.innerHTML = state.groups.map(g => `<optgroup label="${esc(g.name)}">${g.cats.map(c => `<option value="${c.id}">${esc(c.name)}</option>`).join('')}</optgroup>`).join('');
      if (findCat(+prev)) sel.value = prev;
      const d = $('bpDate');
      d.min = `${key(cur)}-01`; d.max = `${key(cur)}-${pad(daysIn(cur))}`;
      if (!d.value || !d.value.startsWith(key(cur))) d.value = defaultDate();
    }

    // groups
    const editable = CAN_MANAGE && state.persisted;
    const catsEditable = CAN_CATS && state.persisted;
    const nameInput = (kind, id, value, label) => `<input class="bp-rename" maxlength="255" value="${esc(value)}" data-rename="${kind}:${id}" aria-label="${label}">`;
    const tools = (kind, id, name, label) => catsEditable ? `<span class="bp-tools">` +
      `<button type="button" class="bp-tool" data-edit="${kind}:${id}" title="Rename ${label}" aria-label="Rename ${label} ${esc(name)}">&#9998;</button>` +
      `<button type="button" class="bp-tool bp-tool-del" data-del${kind === 'g' ? 'group' : 'cat'}="${id}" title="Delete ${label}" aria-label="Delete ${label} ${esc(name)}">&times;</button></span>` : '';
    // Icon chip; admins click it to pick another icon.
    const chip = (cls, icon, target, label) => catsEditable
      ? `<button type="button" class="${cls} pickable" data-pickicon="${target}" title="Change icon" aria-label="Change icon for ${esc(label)}">${iconHtml(icon)}</button>`
      : `<span class="${cls}" aria-hidden="true">${iconHtml(icon)}</span>`;
    const pickBtn = (target, label) => `<button type="button" class="bp-iconpick" data-pickicon="${target}" title="Choose icon (optional, otherwise picked from the name)" aria-label="Choose icon for ${label}">${iconHtml(newIcons[target] || 'image')}</button>`;
    $('bpGroups').innerHTML = state.groups.map((g, i) => {
      const col = `var(${COLORS[i % COLORS.length]})`;
      const gb = g.cats.reduce((a, c) => a + (+c.budget || 0), 0);
      const gs = g.cats.reduce((a, c) => a + spentOf(c.id), 0);
      return `<section class="bp-panel" id="bp-g-${g.id}" style="--c:${col}">
        <div class="bp-ghead">${chip('bp-gicon', g.icon, `g:${g.id}`, g.name)}${renaming === `g:${g.id}` ? nameInput('g', g.id, g.name, 'Main category name') : `<h2>${esc(g.name)}</h2>`}<span class="sub num"><b>${rs(gs)}</b> spent of ${rs(gb)} budget</span>${tools('g', g.id, g.name, 'main category')}</div>
        ${g.cats.length ? '<div class="bp-line bp-colnames" aria-hidden="true"><span></span><span>Sub category</span><span>Monthly budget</span><span>Left</span></div>' : '<div class="bp-empty" style="padding:14px 16px">No sub categories yet. Add one below.</div>'}
        ${g.cats.map(c => {
          const s = spentOf(c.id), b = +c.budget || 0, l = b - s, p = b > 0 ? Math.min(100, s / b * 100) : (s > 0 ? 100 : 0);
          const lc = b === 0 && s === 0 ? 'zero' : (l < 0 ? 'bad' : 'ok');
          const n = state.expenses.filter(e => e.catId === c.id).length;
          return `<div class="bp-line">
            ${chip('bp-cicon', c.icon, `c:${c.id}`, c.name)}
            <div style="min-width:0"><div class="bp-namerow">${renaming === `c:${c.id}` ? nameInput('c', c.id, c.name, 'Sub category name') : `<button type="button" class="name" data-filter="${c.id}" title="Show entries">${esc(c.name)}</button>${tools('c', c.id, c.name, 'sub category')}`}</div>
              <div class="meta num">${rs(s)} spent${n ? ` in ${n} entr${n > 1 ? 'ies' : 'y'}` : ''}</div>
              <div class="bp-bar${l < 0 ? ' over' : ''}"><div style="width:${p}%"></div></div></div>
            <label class="bp-budget-wrap"><span>Rs</span><input class="bp-budget-in num" type="number" min="0" step="any" value="${b || ''}" placeholder="${editable ? 'Set budget' : '0'}" data-budget="${c.id}" aria-label="Budget for ${esc(c.name)}" ${editable ? '' : 'readonly'}></label>
            <div class="bp-left-v num ${lc}">${l < 0 ? 'Over ' + rs(-l) : (b === 0 && s === 0 ? (editable ? `<button type="button" class="bp-setbudget" data-setbudget="${c.id}">Set budget</button>` : 'No budget') : rs(l) + ' left')}</div>
          </div>`;
        }).join('')}
        ${catsEditable ? `<div class="bp-addcat">${pickBtn(`newcat:${g.id}`, 'new sub category')}<input placeholder="New sub category in ${esc(g.name)}" maxlength="255" data-newcat="${g.id}" aria-label="New sub category name">${CAN_MANAGE ? `<input class="bp-newbudget num" type="number" min="0" step="any" placeholder="Budget (Rs)" data-newbudget="${g.id}" aria-label="Budget for new sub category">` : ''}<button type="button" data-addcat="${g.id}">Add</button></div>` : ''}
      </section>`;
    }).join('') + (catsEditable ? `<section class="bp-panel bp-newgroup">
        <div class="bp-colhead"><h2>Add main category</h2></div>
        <div class="bp-newgroup-form${CAN_MANAGE ? '' : ' nobudget'}">
          <div><label for="bpGroupName">Main category</label><div class="bp-withicon">${pickBtn('newgroup', 'new main category')}<input id="bpGroupName" maxlength="255" placeholder="e.g. Staff & salaries"></div></div>
          <div><label for="bpGroupSub">First sub category <span>(optional)</span></label><div class="bp-withicon">${pickBtn('newgroupsub', 'first sub category')}<input id="bpGroupSub" maxlength="255" placeholder="e.g. Driver salaries"></div></div>
          ${CAN_MANAGE ? '<div><label for="bpGroupBudget">Budget (Rs)</label><input id="bpGroupBudget" class="num" type="number" min="0" step="any" placeholder="0"></div>' : ''}
          <button type="button" class="bp-btn" id="bpAddGroup">Add main category</button>
        </div>
        <div class="bp-plannote">
          <span>Main categories, sub categories and budgets are shared by every month. Changes here update all months.</span>
          <button type="button" class="bp-btn sm" id="bpSyncPlan">Use this month's plan for all months</button>
        </div>
      </section>` : '');

    // income
    const ro = !(CAN_MANAGE && state.persisted);
    $('bpIncome').innerHTML = state.income.map(i => `<div class="bp-inc${ro ? ' ro' : ''}">
        <input value="${esc(i.name)}" maxlength="255" data-incname="${i.id}" aria-label="Income source name" ${ro ? 'readonly' : ''}>
        <input class="amt num" type="number" min="0" step="any" value="${+i.amount || 0}" data-incamt="${i.id}" aria-label="Amount" ${ro ? 'readonly' : ''}>
        ${ro ? '' : `<button type="button" class="bp-del" data-delinc="${i.id}" aria-label="Remove ${esc(i.name)}">&times;</button>`}</div>`).join('') +
      `<div class="bp-inc-total"><span>Total</span><span class="num">${rs(income)}</span></div>`;

    // log
    let list = [...state.expenses];
    const fc = filterCat && findCat(filterCat);
    if (fc) {
      list = list.filter(e => e.catId === filterCat);
      $('bpFilterBar').innerHTML = `<div class="bp-filter">Showing ${iconHtml(fc.c.icon)} ${esc(fc.c.name)} only · <button type="button" id="bpClearFilter">Show all</button></div>`;
    } else {
      $('bpFilterBar').innerHTML = '';
    }
    $('bpLogCount').textContent = list.length ? `${list.length} · ${rs(list.reduce((a, e) => a + (+e.amount || 0), 0))}` : '';
    let lastDay = null;
    $('bpLog').innerHTML = list.length ? list.map(e => {
      const f = findCat(e.catId);
      let head = '';
      if (e.date !== lastDay) {
        lastDay = e.date;
        const dt = list.filter(x => x.date === e.date).reduce((a, x) => a + (+x.amount || 0), 0);
        head = `<div class="bp-dayhead"><span>${fmtDay(e.date)}</span><span class="num">${rs(dt)}</span></div>`;
      }
      return head + `<div class="bp-entry${CAN_MANAGE ? '' : ' ro'}">
        <span class="bp-cicon" style="--c:${f ? groupColor(f.g) : 'var(--g9)'}" aria-hidden="true">${iconHtml(f?.c.icon || 'tag')}</span>
        <div style="min-width:0"><div class="t">${esc(e.note || f?.c.name || 'Spend')}</div><div class="s">${esc(f?.c.name || 'Removed category')}</div></div>
        <span class="a num">${rs(e.amount)}</span>
        ${CAN_MANAGE ? `<button type="button" class="bp-del" data-delexp="${e.id}" aria-label="Delete entry">&times;</button>` : ''}</div>`;
    }).join('')
      : `<div class="bp-empty">${fc ? 'Nothing logged here yet.' : (CAN_MANAGE ? 'No spending logged yet. Use the dark bar above to add your first one, and every total updates on its own.' : 'No spending logged for this month.')}</div>`;
  }

  function renderDaily() {
    const n = daysIn(cur), vals = [];
    for (let d = 1; d <= n; d++) vals.push(spentOn(`${key(cur)}-${pad(d)}`));
    const max = Math.max(...vals, 1), tot = vals.reduce((a, b) => a + b, 0);
    const busiest = vals.indexOf(Math.max(...vals));
    const tday = isCurMonth() ? new Date().getDate() : -1;
    const W = n * 12, H = 90;
    let bars = `<line x1="0" x2="${W}" y1="${H - 14}" y2="${H - 14}" stroke="var(--line)"/>`;
    vals.forEach((v, i) => {
      const h = v > 0 ? Math.max(3, v / max * (H - 18)) : 0;
      const c = i + 1 === tday ? 'var(--primary-deep)' : 'var(--accent)';
      bars += `<rect x="${i * 12 + 1.5}" y="${H - 14 - h}" width="9" height="${h}" rx="2" fill="${c}"><title>${i + 1} ${MONTHS[cur.m].slice(0, 3)}: ${rs(v)}</title></rect>`;
      if ((i + 1) % 5 === 0 || i === 0) bars += `<text x="${i * 12 + 6}" y="${H - 2}" font-size="8" text-anchor="middle" fill="var(--ink-3)">${i + 1}</text>`;
    });
    $('bpDaily').innerHTML = `<div class="cap"><span>Busiest day: <b>${tot ? `${busiest + 1} ${MONTHS[cur.m].slice(0, 3)}, ${rs(vals[busiest])}` : 'none yet'}</b></span><span>Avg <b>${rs(tot / n)}</b>/day</span></div>
      <svg viewBox="0 0 ${W} ${H}" role="img" aria-label="Spending per day this month">${bars}</svg>`;
  }

  async function renderHistory() {
    $('bpHistView').innerHTML = '<div class="bp-panel"><div class="bp-empty">Loading history…</div></div>';
    try { history_ = await api('GET', '/history'); } catch (e) { $('bpHistView').innerHTML = `<div class="bp-panel"><div class="bp-empty">${esc(e.message)}</div></div>`; return; }
    drawHistory();
  }
  function drawHistory() {
    const T = { income: 0, budget: 0, spent: 0, left: 0 };
    const rows = history_.map(m => {
      const left = m.income - m.spent;
      T.income += m.income; T.budget += m.budget; T.spent += m.spent; T.left += left;
      const [y, mm] = m.month.split('-');
      const p = m.budget > 0 ? m.spent / m.budget : 0;
      return `<tr class="open-m ${m.month === key(cur) ? 'cur' : ''}" data-open="${m.month}" tabindex="0"><td>${MONTHS[+mm - 1]} ${y}</td><td class="num">${rs(m.income)}</td><td class="num">${rs(m.budget)}</td>
        <td class="num">${rs(m.spent)}<span class="bp-mbar${p > 1 ? ' over' : ''}"><i style="width:${Math.min(100, p * 100)}%"></i></span></td>
        <td class="num ${left < 0 ? 'bp-neg' : 'bp-pos'}">${rs(left)}</td><td class="num">${m.entries}</td></tr>`;
    }).join('');

    const names = [...new Set(history_.flatMap(m => m.cats.map(c => c.name)))];
    if (!names.includes(histCat)) histCat = names.find(n => n === 'Fuel') || names[0] || '';
    const catRows = history_.map(m => {
      const cs = m.cats.filter(c => c.name === histCat);
      const b = cs.reduce((a, c) => a + c.budget, 0), sp = cs.reduce((a, c) => a + c.spent, 0);
      const [y, mm] = m.month.split('-');
      return `<tr><td>${MONTHS[+mm - 1]} ${y}</td><td class="num">${rs(b)}</td><td class="num">${rs(sp)}</td><td class="num ${b - sp < 0 ? 'bp-neg' : 'bp-pos'}">${rs(b - sp)}</td></tr>`;
    }).join('');

    $('bpHistView').innerHTML = `
      <section class="bp-panel"><div class="bp-colhead"><h2>Every month</h2><a class="bp-btn sm text-decoration-none text-reset" href="${BASE}/report/history"><i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i> Download history report</a></div>
        <div class="bp-tw"><table class="bp-htable"><thead><tr><th>Month</th><th>Income</th><th>Budgeted</th><th>Spent</th><th>Left over</th><th>Entries</th></tr></thead>
        <tbody>${rows || '<tr><td colspan="6">No months yet.</td></tr>'}</tbody>
        <tfoot><tr><td>All time</td><td class="num">${rs(T.income)}</td><td class="num">${rs(T.budget)}</td><td class="num">${rs(T.spent)}</td><td class="num ${T.left < 0 ? 'bp-neg' : 'bp-pos'}">${rs(T.left)}</td><td></td></tr></tfoot></table></div>
        <div class="bp-empty" style="padding:10px 16px">Click a month to open it.</div></section>
      <section class="bp-panel"><div class="bp-hsel"><h2 style="font-size:17px;flex:1">One category over time</h2>
        <select id="bpHistCat" aria-label="Category">${names.map(n => `<option ${n === histCat ? 'selected' : ''}>${esc(n)}</option>`).join('')}</select></div>
        <div class="bp-tw"><table class="bp-htable"><thead><tr><th>Month</th><th>Budget</th><th>Spent</th><th>Left</th></tr></thead><tbody>${catRows}</tbody></table></div></section>`;
  }

  function showView(v) {
    const h = v === 'hist';
    $('bpMonthView').hidden = h; $('bpHistView').hidden = !h;
    $('bpTabMonth').setAttribute('aria-selected', !h); $('bpTabHist').setAttribute('aria-selected', h);
    if (h) renderHistory();
  }

  async function loadMonth() {
    setStatus('Loading…');
    try {
      state = await api('GET', '/' + key(cur));
      filterCat = null;
      history.replaceState(null, '', `${BASE}?month=${key(cur)}`);
      $('bpAnalysis').href = `${BASE}/analysis?month=${key(cur)}`;
      if (CAN_MANAGE) $('bpDate').value = '';
      render();
      setStatus(state.persisted ? '' : 'Preview of carried-over plan');
    } catch (e) {
      setStatus('');
      toast(e.message, true);
    }
  }

  // ---------- events ----------
  if (CAN_MANAGE) {
    $('bpAdd').addEventListener('click', async () => {
      const amt = parseFloat($('bpAmt').value);
      if (!(amt > 0)) { $('bpAmt').focus(); toast('Enter an amount above zero', true); return; }
      const catId = +$('bpCat').value, f = findCat(catId);
      $('bpAdd').disabled = true;
      const ok = await write('POST', `/${key(cur)}/entries`, {
        budget_category_id: catId, amount: amt, note: $('bpNote').value.trim() || null, date: $('bpDate').value || defaultDate(),
      }, `Logged ${rs(amt)} to ${f?.c.name || 'category'}`);
      $('bpAdd').disabled = false;
      if (ok) {
        $('bpAmt').value = ''; $('bpNote').value = '';
        const p = $(`bp-g-${f?.g.id}`);
        if (p) { p.classList.remove('bp-flash'); void p.offsetWidth; p.classList.add('bp-flash'); }
      }
      $('bpAmt').focus();
    });
    ['bpAmt', 'bpNote'].forEach(id => $(id).addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); $('bpAdd').click(); } }));
    $('bpAddInc').addEventListener('click', () => write('POST', `/${key(cur)}/incomes`));
  }

  document.addEventListener('change', e => {
    const t = e.target;
    if (!t.closest('.budget-page')) return;
    if (t.id === 'bpHistCat') { histCat = t.value; drawHistory(); return; }
    if (!CAN_MANAGE || t.readOnly) return;
    if (t.dataset.budget) {
      const budget = Math.max(0, parseFloat(t.value) || 0);
      write('PUT', `/categories/${t.dataset.budget}`, { budget }, `Budget set to ${rs(budget)}`);
    }
    else if (t.dataset.incamt) write('PUT', `/incomes/${t.dataset.incamt}`, { amount: Math.max(0, parseFloat(t.value) || 0) });
    else if (t.dataset.incname) write('PUT', `/incomes/${t.dataset.incname}`, { name: t.value.trim() });
  });

  function addCategory(groupId) {
    const inp = document.querySelector(`[data-newcat="${groupId}"]`);
    const name = inp.value.trim();
    if (!name) { inp.focus(); return; }
    const budget = Math.max(0, parseFloat(document.querySelector(`[data-newbudget="${groupId}"]`)?.value) || 0);
    const target = `newcat:${groupId}`;
    const icon = newIcons[target] || null;
    delete newIcons[target];
    write('POST', `/groups/${groupId}/categories`, { name, budget, icon }, budget ? `Added ${name} with ${rs(budget)} budget` : `Added ${name}`);
  }

  async function addGroup() {
    const name = $('bpGroupName').value.trim();
    if (!name) { $('bpGroupName').focus(); toast('Enter a main category name', true); return; }
    const sub = $('bpGroupSub').value.trim();
    const budget = Math.max(0, parseFloat($('bpGroupBudget')?.value) || 0);
    const icons = { icon: newIcons.newgroup || null, sub_icon: newIcons.newgroupsub || null };
    delete newIcons.newgroup; delete newIcons.newgroupsub;
    const ok = await write('POST', `/${key(cur)}/groups`, { name, sub: sub || null, budget, ...icons }, `Added ${name}`);
    if (ok) state.groups.length && $(`bp-g-${state.groups[state.groups.length - 1].id}`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  // ---------- icon picker ----------
  const pop = document.createElement('div');
  pop.className = 'bp-iconpop';
  pop.hidden = true;
  pop.setAttribute('role', 'dialog');
  pop.setAttribute('aria-label', 'Choose an icon');
  pop.innerHTML = `<div class="bp-iconpop-title">Choose an icon</div><div class="bp-iconpop-grid">${ICONS.map(n =>
    `<button type="button" data-icon="${n}" title="${n.replace(/-/g, ' ')}" aria-label="${n.replace(/-/g, ' ')}">${iconHtml(n)}</button>`).join('')}</div>`;
  document.body.appendChild(pop);
  let pickTarget = null, pickTrigger = null;

  function openPicker(trigger, target) {
    if (!pop.hidden && pickTarget === target) { closePicker(); return; }
    pickTarget = target; pickTrigger = trigger;
    const current = target.includes(':') && /^[gc]:/.test(target)
      ? (target[0] === 'g' ? state.groups.find(g => g.id === +target.slice(2))?.icon : findCat(+target.slice(2))?.c.icon)
      : newIcons[target];
    pop.querySelectorAll('[data-icon]').forEach(b => b.classList.toggle('on', b.dataset.icon === current));
    pop.hidden = false;
    const r = trigger.getBoundingClientRect(), w = pop.offsetWidth;
    pop.style.top = `${window.scrollY + r.bottom + 6}px`;
    pop.style.left = `${Math.max(8, Math.min(window.scrollX + r.left, window.scrollX + document.documentElement.clientWidth - w - 8))}px`;
    pop.querySelector('[data-icon].on, [data-icon]')?.focus();
  }
  function closePicker() {
    pop.hidden = true;
    pickTrigger?.isConnected && pickTrigger.focus();
    pickTarget = pickTrigger = null;
  }
  pop.addEventListener('click', e => {
    const b = e.target.closest('[data-icon]');
    if (!b) return;
    const icon = b.dataset.icon, target = pickTarget, trigger = pickTrigger;
    closePicker();
    if (/^g:\d+$/.test(target)) write('PUT', `/groups/${target.slice(2)}`, { icon }, 'Icon updated');
    else if (/^c:\d+$/.test(target)) write('PUT', `/categories/${target.slice(2)}`, { icon }, 'Icon updated');
    else { newIcons[target] = icon; if (trigger) { trigger.innerHTML = iconHtml(icon); trigger.classList.add('chosen'); } }
  });
  document.addEventListener('mousedown', e => {
    if (!pop.hidden && !pop.contains(e.target) && !e.target.closest('[data-pickicon]')) closePicker();
  });

  document.addEventListener('click', async e => {
    const tr = e.target.closest('[data-open]');
    if (tr) {
      const [y, m] = tr.dataset.open.split('-');
      cur = { y: +y, m: +m - 1 };
      await loadMonth(); showView('month'); window.scrollTo({ top: 0 });
      return;
    }
    const t = e.target.closest('.budget-page button');
    if (!t) return;
    if (t.dataset.filter) {
      filterCat = filterCat === +t.dataset.filter ? null : +t.dataset.filter;
      render();
      if (window.innerWidth < 1100) $('bpLog').scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else if (t.id === 'bpClearFilter') { filterCat = null; render(); }
    else if (t.dataset.delexp) {
      if (confirm('Delete this entry?')) write('DELETE', `/entries/${t.dataset.delexp}`, null, 'Entry deleted');
    } else if (t.dataset.delinc) {
      if (confirm('Remove this income source?')) write('DELETE', `/incomes/${t.dataset.delinc}`);
    } else if (t.dataset.addcat) addCategory(t.dataset.addcat);
    else if (t.dataset.setbudget) document.querySelector(`[data-budget="${t.dataset.setbudget}"]`)?.focus();
    else if (t.dataset.pickicon) openPicker(t, t.dataset.pickicon);
    else if (t.dataset.edit) {
      renaming = t.dataset.edit;
      render();
      const inp = document.querySelector(`[data-rename="${renaming}"]`);
      if (inp) { inp.focus(); inp.select(); }
    }
    else if (t.id === 'bpAddGroup') addGroup();
    else if (t.id === 'bpSyncPlan') {
      if (confirm(`Make every month use ${MONTHS[cur.m]} ${cur.y}'s main categories, sub categories and budgets?\n\nOther categories are removed from other months, except ones with spending logged.`)) {
        write('POST', `/${key(cur)}/sync-plan`);
      }
    }
    else if (t.dataset.delgroup) {
      const f = state.groups.find(g => g.id === +t.dataset.delgroup);
      if (confirm(`Delete main category "${f?.name}" and its ${f?.cats.length || 0} sub categories from every month?`)) write('DELETE', `/groups/${t.dataset.delgroup}`, null, 'Main category deleted');
    } else if (t.dataset.delcat) {
      const f = findCat(+t.dataset.delcat);
      if (confirm(`Delete sub category "${f?.c.name}" from every month?`)) write('DELETE', `/categories/${t.dataset.delcat}`, null, 'Sub category deleted');
    }
    else if (t.dataset.jump) $(`bp-g-${t.dataset.jump}`)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !pop.hidden) { e.preventDefault(); closePicker(); return; }
    if (e.target.dataset?.rename) {
      if (e.key === 'Enter') { e.preventDefault(); e.target.blur(); }
      else if (e.key === 'Escape') { e.preventDefault(); e.target.dataset.cancel = '1'; e.target.blur(); }
      return;
    }
    if (e.key === 'Enter' && e.target.dataset?.newcat) { e.preventDefault(); addCategory(e.target.dataset.newcat); }
    if (e.key === 'Enter' && e.target.dataset?.newbudget) { e.preventDefault(); addCategory(e.target.dataset.newbudget); }
    if (e.key === 'Enter' && ['bpGroupName', 'bpGroupSub', 'bpGroupBudget'].includes(e.target.id)) { e.preventDefault(); addGroup(); }
    // Enter saves a budget or income amount (fires the change handler).
    if (e.key === 'Enter' && (e.target.dataset?.budget || e.target.dataset?.incamt || e.target.dataset?.incname)) { e.preventDefault(); e.target.blur(); }
    if (e.key === 'Enter' && e.target.matches?.('tr[data-open]')) e.target.click();
  });

  document.addEventListener('focusout', e => {
    const t = e.target;
    if (!t.dataset?.rename || renaming !== t.dataset.rename) return;
    const [kind, id] = t.dataset.rename.split(':');
    const name = t.value.trim();
    const current = kind === 'g' ? state.groups.find(g => g.id === +id)?.name : findCat(+id)?.c.name;
    renaming = null;
    if (t.dataset.cancel || !name || name === current) { render(); return; }
    write('PUT', kind === 'g' ? `/groups/${id}` : `/categories/${id}`, { name }, `Renamed to ${name}`);
  });

  $('bpTabMonth').addEventListener('click', () => showView('month'));
  $('bpTabHist').addEventListener('click', () => showView('hist'));
  $('bpPrev').addEventListener('click', () => { cur = cur.m === 0 ? { y: cur.y - 1, m: 11 } : { y: cur.y, m: cur.m - 1 }; loadMonth(); });
  $('bpNext').addEventListener('click', () => { cur = cur.m === 11 ? { y: cur.y + 1, m: 0 } : { y: cur.y, m: cur.m + 1 }; loadMonth(); });
  $('bpExport').addEventListener('click', () => {
    if (!state.persisted) { toast('Nothing saved for this month yet', true); return; }
    toast("Preparing report…"); window.location.href = `${BASE}/${key(cur)}/report`;
  });

  render();
  if (!state.persisted) setStatus('Preview of carried-over plan');
})();
</script>
@endsection
