<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Request a Custom Trip | R&A Auto Rentals',
        'description' => 'Tell us your pickup point, stops, and destination. Our team will match the right vehicle and give you a fair price quote.',
        'keywords' => [
            'custom trip request',
            'multi-stop trip Sri Lanka',
            'chauffeur trip planning',
            'vehicle hire quote',
        ],
        'robots' => 'noindex,follow',
    ])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --line: #dbe6f3;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #0a3f8f;
            --primary-2: #0f66c3;
            --radius: 14px;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            color: var(--text);
            background: var(--bg);
        }
        .container { width: min(1180px, calc(100% - 2rem)); margin: 0 auto; }
        .page { padding: 1.4rem 0 3rem; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            border: 1px solid transparent;
            text-decoration: none;
            font-weight: 700;
            padding: .68rem 1.05rem;
            border-radius: 10px;
            font-size: .92rem;
            cursor: pointer;
            font-family: inherit;
        }
        .btn .btn-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #ffffff;
            border-radius: 999px;
            animation: btn-spin .7s linear infinite;
        }
        .btn.is-loading .btn-spinner { display: inline-block; }
        .btn.is-loading { pointer-events: none; }
        @keyframes btn-spin { to { transform: rotate(360deg); } }
        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            box-shadow: 0 8px 18px rgba(10, 63, 143, 0.22);
        }
        .btn-light {
            color: #334155;
            background: #f8fbff;
            border-color: var(--line);
        }

        /* Step indicator */
        .wizard-steps {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: .9rem 1.1rem;
            margin-bottom: 1.1rem;
            overflow-x: auto;
        }
        .wizard-step { display: flex; align-items: center; gap: .55rem; flex-shrink: 0; }
        .wizard-step .num {
            width: 30px; height: 30px; border-radius: 999px;
            display: flex; align-items: center; justify-content: center;
            background: #eef2f8; color: var(--muted); font-weight: 800; font-size: .85rem;
            flex-shrink: 0;
        }
        .wizard-step strong { display: block; font-size: .86rem; color: var(--muted); }
        .wizard-step small { color: #94a3b8; font-size: .74rem; }
        .wizard-step.active .num { background: var(--primary); color: #fff; }
        .wizard-step.active strong { color: var(--primary); }
        .wizard-step.done .num { background: #16a34a; color: #fff; }
        .wizard-arrow { color: #cbd5e1; font-weight: 700; flex-shrink: 0; }

        /* Hero */
        .trip-hero {
            background: linear-gradient(135deg, #eef4ff, #f8fbff);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 1.6rem 1.7rem;
            margin-bottom: 1.1rem;
        }
        .trip-hero h1 {
            margin: 0 0 .2rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.7rem;
            color: var(--text);
        }
        .trip-hero h2 {
            margin: 0 0 .6rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.4rem;
            color: var(--primary);
        }
        .trip-hero p { margin: 0 0 1.1rem; color: var(--muted); max-width: 60ch; }
        .feature-strip { display: grid; grid-template-columns: repeat(4, 1fr); gap: .8rem; }
        .feature-item { display: flex; flex-direction: column; align-items: flex-start; gap: .4rem; }
        .feature-item .icon {
            width: 38px; height: 38px; border-radius: 999px;
            background: #fff; border: 1px solid var(--line);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; color: var(--primary); font-size: .95rem;
        }
        .feature-item span.label { font-size: .82rem; font-weight: 700; color: var(--text); }

        /* Panels */
        .card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: 1.3rem; }
        .wizard-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 1.1rem; align-items: start; }
        .section-title { font-weight: 800; font-size: .95rem; margin: 0 0 .6rem; display: flex; align-items: center; gap: .4rem; }
        .field { margin-bottom: 1rem; }
        .field label { display: block; font-size: .78rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .35rem; }
        .field input, .field textarea, .field select {
            width: 100%; border: 1px solid #c8d7ea; background: #f8fbff; border-radius: 10px;
            padding: .62rem .7rem; font: inherit; color: var(--text);
        }
        .field textarea { min-height: 90px; resize: vertical; }
        .field-error { display: none; margin-top: .35rem; color: #b91c1c; font-size: .8rem; font-weight: 600; }
        .field-error.show { display: block; }
        .input-error { border-color: #dc2626 !important; background: #fff7f7 !important; }

        .stop-row { display: flex; align-items: center; gap: .5rem; margin-bottom: .55rem; }
        .stop-row .stop-badge {
            width: 26px; height: 26px; border-radius: 999px; background: var(--primary); color: #fff;
            display: flex; align-items: center; justify-content: center; font-size: .78rem; font-weight: 800;
            flex-shrink: 0;
        }
        .stop-row input { flex: 1; }
        .stop-row .stop-remove {
            border: 1px solid var(--line); background: #fff; color: #b91c1c; border-radius: 8px;
            width: 34px; height: 34px; flex-shrink: 0; cursor: pointer; font-weight: 700;
        }
        .destination-row .stop-badge { background: #dc2626; }
        .stop-count { font-size: .82rem; color: var(--muted); margin: .3rem 0 1rem; }

        .info-panel {
            background: #eef4ff; border: 1px solid #d5e3fb; border-radius: 12px; padding: .9rem 1rem; margin-top: .3rem;
        }
        .info-panel strong { display: block; margin-bottom: .3rem; font-size: .86rem; }
        .info-panel p { margin: 0; color: var(--muted); font-size: .84rem; line-height: 1.5; }

        .trip-recap { background: #f8fbff; border: 1px solid var(--line); border-radius: 12px; padding: .9rem 1rem; margin-bottom: 1.1rem; font-size: .88rem; line-height: 1.7; }
        .trip-recap strong { color: var(--text); }

        .wizard-actions { display: flex; justify-content: space-between; gap: .6rem; margin-top: 1.2rem; }
        .wizard-actions .spacer { flex: 1; }

        .help-bar {
            margin-top: 1.1rem;
            background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius);
            padding: 1rem 1.2rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .8rem;
        }
        .help-bar .help-text strong { display: block; }
        .help-bar .help-text span { color: var(--muted); font-size: .88rem; }
        .help-actions { display: flex; gap: .55rem; flex-wrap: wrap; }

        .success-card { text-align: center; padding: 2.6rem 1.5rem; }
        .success-card .icon-badge {
            width: 64px; height: 64px; border-radius: 999px; background: #dcfce7; color: #16a34a;
            display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 1rem;
        }
        .success-card h2 { margin: 0 0 .5rem; font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif; }
        .success-card p { color: var(--muted); max-width: 46ch; margin: 0 auto 1.4rem; }

        @media (max-width: 860px) {
            .wizard-grid { grid-template-columns: 1fr; }
            .feature-strip { grid-template-columns: repeat(2, 1fr); }
            .wizard-steps { gap: .3rem; }
            .wizard-step small { display: none; }
        }
    </style>
</head>
<body>
    @include('partials.public-header')

    <main class="page">
        <div class="container">
            @php
                $step2HasError = $errors->has('name') || $errors->has('phone') || $errors->has('email');
                $initialStep = $step2HasError ? 2 : 1;
                $oldStops = collect(old('stops', request()->query('stops', [])))->filter(fn ($s) => trim((string) $s) !== '')->values();
                $requestSent = session('success') && !$errors->any();
            @endphp

            <div class="wizard-steps" id="wizardSteps">
                <div class="wizard-step" data-step="1"><span class="num">1</span><div><strong>Trip Details</strong><small>Your journey &amp; stops</small></div></div>
                <div class="wizard-arrow">&rarr;</div>
                <div class="wizard-step" data-step="2"><span class="num">2</span><div><strong>Your Details</strong><small>Contact information</small></div></div>
                <div class="wizard-arrow">&rarr;</div>
                <div class="wizard-step" data-step="3"><span class="num">3</span><div><strong>Request Sent</strong><small>We'll contact you</small></div></div>
            </div>

            @if($requestSent)
                <div class="card success-card">
                    <div class="icon-badge">&#10003;</div>
                    <h2>Your trip request has been sent!</h2>
                    <p>{{ session('success') }} Our team will review your trip, match a suitable vehicle, and send you a fair price quote by phone or email.</p>
                    <a href="{{ route('rent-requests.create') }}" class="btn btn-light">Plan Another Trip</a>
                    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
                </div>
            @else
                <div class="trip-hero">
                    <h1>Tell Us Your Trip Plan</h1>
                    <h2>We'll Take Care of the Rest!</h2>
                    <p>Add your pickup point, stops, and destination. Our team will plan the route, match the right vehicle, and give you a fair price &mdash; no need to search or book yourself.</p>
                    <div class="feature-strip">
                        <div class="feature-item"><span class="icon">&#128506;</span><span class="label">Tell Us Your Route</span></div>
                        <div class="feature-item"><span class="icon">&#128663;</span><span class="label">Vehicle Matched by Our Team</span></div>
                        <div class="feature-item"><span class="icon">Rs</span><span class="label">Fair Custom Pricing</span></div>
                        <div class="feature-item"><span class="icon">&#9742;</span><span class="label">24/7 Support</span></div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="card" style="border-color:#fecaca;background:#fef2f2;margin-bottom:1.1rem;">
                        <div style="font-weight:700;color:#991b1b;margin-bottom:.4rem;">Please fix the following:</div>
                        <ul style="margin:0;padding-left:1.1rem;color:#991b1b;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    $prefillHints = collect([
                        request()->query('vehicle_type') ? 'Preferred vehicle: ' . request()->query('vehicle_type') : null,
                        request()->query('window') ? 'Travel window: ' . request()->query('window') : null,
                        request()->query('vehicle') ? 'Interested in: ' . request()->query('vehicle') : null,
                        request()->query('note'),
                    ])->filter()->implode(' | ');
                @endphp
                <form method="post" action="{{ route('rent-requests.store') }}" id="tripRequestForm" novalidate>
                    @csrf

                    @if($prefillHints !== '')
                        <div class="info-panel" style="margin-bottom:1rem;">
                            <strong>&#10003; Carried over from your search</strong>
                            <p>{{ $prefillHints }}. Your pickup and destination are already filled in below &mdash; feel free to adjust anything before you submit.</p>
                        </div>
                    @endif

                    <div class="card" id="stepPanel1" data-panel="1">
                        <div class="wizard-grid">
                            <div>
                                <div class="section-title">&#128205; Starting Point</div>
                                <div class="field">
                                    <input type="text" name="start_location" id="startLocation" placeholder="City, Airport, or Address" value="{{ old('start_location', request()->query('start_location')) }}" required>
                                    <small class="field-error" id="startLocationError"></small>
                                </div>

                                <div class="section-title">&#128506; Add Your Stops <span style="font-weight:400;color:var(--muted);font-size:.78rem;">(optional)</span></div>
                                <div id="stopsList"></div>
                                <button type="button" class="btn btn-light" id="addStopBtn">+ Add Stop</button>
                                <div class="stop-count"><span id="stopCountLabel">0</span> stop(s) added</div>

                                <div class="section-title" style="margin-top:1rem;">&#127937; Destination</div>
                                <div class="field">
                                    <input type="text" name="final_destination" id="finalDestination" placeholder="Where does the trip end?" value="{{ old('final_destination', request()->query('destination')) }}" required>
                                    <small class="field-error" id="finalDestinationError"></small>
                                </div>
                            </div>

                            <div>
                                <div class="field">
                                    <label for="passengerCount">Number of Passengers</label>
                                    <input type="number" min="1" name="passenger_count" id="passengerCount" value="{{ old('passenger_count', 1) }}" required>
                                    <small class="field-error" id="passengerCountError"></small>
                                </div>
                                <div class="field">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" name="start_date" id="startDate" value="{{ old('start_date') }}" required>
                                    <small class="field-error" id="startDateError"></small>
                                </div>
                                <div class="field">
                                    <label for="endDate">End Date</label>
                                    <input type="date" name="end_date" id="endDate" value="{{ old('end_date') }}" required>
                                    <small class="field-error" id="endDateError"></small>
                                </div>

                                <div class="info-panel">
                                    <strong>What happens next?</strong>
                                    <p>Our team will review your trip, match a suitable vehicle for the route and passenger count, and contact you with a fair price quote.</p>
                                </div>
                            </div>
                        </div>

                        <div class="wizard-actions">
                            <span class="spacer"></span>
                            <button type="button" class="btn btn-primary" id="toStep2Btn">Next: Your Details &rarr;</button>
                        </div>
                    </div>

                    <div class="card" id="stepPanel2" data-panel="2" style="display:none;">
                        <div class="trip-recap" id="tripRecap"></div>

                        <div class="section-title">Your Details</div>
                        <div class="wizard-grid">
                            <div>
                                <div class="field">
                                    <label for="requestName">Name</label>
                                    <input type="text" name="name" id="requestName" value="{{ old('name', auth()->user()?->name) }}" required>
                                    <small class="field-error" id="requestNameError"></small>
                                </div>
                                <div class="field">
                                    <label for="requestPhone">Phone</label>
                                    <input type="text" name="phone" id="requestPhone" placeholder="+94 ..." value="{{ old('phone', auth()->user()?->phone) }}">
                                    <small class="field-error" id="requestPhoneError"></small>
                                </div>
                            </div>
                            <div>
                                <div class="field">
                                    <label for="requestEmail">Email</label>
                                    <input type="email" name="email" id="requestEmail" placeholder="you@example.com" value="{{ old('email', auth()->user()?->email) }}">
                                    <small class="field-error" id="requestEmailError"></small>
                                </div>
                                <div class="field">
                                    <label for="requestMessage">Additional Note (Optional)</label>
                                    <textarea name="message" id="requestMessage" placeholder="Any special request, driver preference, luggage, etc.">{{ old('message', $prefillHints) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="wizard-actions">
                            <button type="button" class="btn btn-light" id="backToStep1Btn">&larr; Back</button>
                            <span class="spacer"></span>
                            <button type="submit" class="btn btn-primary js-loading-submit" data-loading-text="Sending Request...">
                                <span class="btn-spinner" aria-hidden="true"></span>
                                <span class="btn-label">Send Request</span>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="help-bar">
                    <div class="help-text">
                        <strong>Need help planning your trip?</strong>
                        <span>Our team is here to help you plan the best journey.</span>
                    </div>
                    <div class="help-actions">
                        <a class="btn btn-light" href="tel:+94775998951">&#9742; Call 077 599 8951</a>
                    </div>
                </div>
            @endif
        </div>
    </main>

    @include('partials.public-footer')

    <script>
        (function () {
            const form = document.getElementById('tripRequestForm');
            if (!form) {
                return;
            }

            const wizardSteps = document.getElementById('wizardSteps');
            const stepPanel1 = document.getElementById('stepPanel1');
            const stepPanel2 = document.getElementById('stepPanel2');
            const stopsListEl = document.getElementById('stopsList');
            const addStopBtn = document.getElementById('addStopBtn');
            const stopCountLabel = document.getElementById('stopCountLabel');
            const startLocationEl = document.getElementById('startLocation');
            const finalDestinationEl = document.getElementById('finalDestination');
            const passengerCountEl = document.getElementById('passengerCount');
            const startDateEl = document.getElementById('startDate');
            const endDateEl = document.getElementById('endDate');
            const requestNameEl = document.getElementById('requestName');
            const requestPhoneEl = document.getElementById('requestPhone');
            const requestEmailEl = document.getElementById('requestEmail');
            const toStep2Btn = document.getElementById('toStep2Btn');
            const backToStep1Btn = document.getElementById('backToStep1Btn');
            const tripRecap = document.getElementById('tripRecap');
            const submitBtn = form.querySelector('.js-loading-submit');

            const oldStops = @json($oldStops);

            const updateStopCount = () => {
                const count = stopsListEl.querySelectorAll('.stop-row').length;
                stopCountLabel.textContent = String(count);
            };

            const renumberStops = () => {
                stopsListEl.querySelectorAll('.stop-row').forEach((row, index) => {
                    row.querySelector('.stop-badge').textContent = String(index + 1);
                });
            };

            const addStopRow = (value) => {
                const row = document.createElement('div');
                row.className = 'stop-row';

                const badge = document.createElement('span');
                badge.className = 'stop-badge';
                badge.textContent = '1';

                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'stops[]';
                input.placeholder = 'e.g. Kandy';
                input.value = value || '';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'stop-remove';
                removeBtn.textContent = '×';
                removeBtn.addEventListener('click', () => {
                    row.remove();
                    updateStopCount();
                    renumberStops();
                });

                row.appendChild(badge);
                row.appendChild(input);
                row.appendChild(removeBtn);
                stopsListEl.appendChild(row);
                updateStopCount();
                renumberStops();
            };

            addStopBtn.addEventListener('click', () => addStopRow());

            if (oldStops.length > 0) {
                oldStops.forEach((stop) => addStopRow(stop));
            }

            const showError = (inputEl, errorEl, message) => {
                if (inputEl) inputEl.classList.add('input-error');
                if (errorEl) {
                    errorEl.textContent = message;
                    errorEl.classList.add('show');
                }
            };

            const clearError = (inputEl, errorEl) => {
                if (inputEl) inputEl.classList.remove('input-error');
                if (errorEl) {
                    errorEl.textContent = '';
                    errorEl.classList.remove('show');
                }
            };

            const validateStep1 = () => {
                let valid = true;
                clearError(startLocationEl, document.getElementById('startLocationError'));
                clearError(finalDestinationEl, document.getElementById('finalDestinationError'));
                clearError(passengerCountEl, document.getElementById('passengerCountError'));
                clearError(startDateEl, document.getElementById('startDateError'));
                clearError(endDateEl, document.getElementById('endDateError'));

                if (!startLocationEl.value.trim()) {
                    showError(startLocationEl, document.getElementById('startLocationError'), 'Please enter a pickup location.');
                    valid = false;
                }
                if (!finalDestinationEl.value.trim()) {
                    showError(finalDestinationEl, document.getElementById('finalDestinationError'), 'Please enter your final destination.');
                    valid = false;
                }
                if (!passengerCountEl.value || Number(passengerCountEl.value) < 1) {
                    showError(passengerCountEl, document.getElementById('passengerCountError'), 'Please enter the number of passengers.');
                    valid = false;
                }
                if (!startDateEl.value) {
                    showError(startDateEl, document.getElementById('startDateError'), 'Please select a start date.');
                    valid = false;
                }
                if (!endDateEl.value) {
                    showError(endDateEl, document.getElementById('endDateError'), 'Please select an end date.');
                    valid = false;
                } else if (startDateEl.value && endDateEl.value < startDateEl.value) {
                    showError(endDateEl, document.getElementById('endDateError'), 'End date must be on or after start date.');
                    valid = false;
                }

                return valid;
            };

            const validateStep2 = () => {
                let valid = true;
                clearError(requestNameEl, document.getElementById('requestNameError'));
                clearError(requestPhoneEl, document.getElementById('requestPhoneError'));
                clearError(requestEmailEl, document.getElementById('requestEmailError'));

                if (!requestNameEl.value.trim()) {
                    showError(requestNameEl, document.getElementById('requestNameError'), 'Please enter your name.');
                    valid = false;
                }

                const phoneValue = (requestPhoneEl.value || '').trim();
                const emailValue = (requestEmailEl.value || '').trim();
                if (!phoneValue && !emailValue) {
                    showError(requestPhoneEl, document.getElementById('requestPhoneError'), 'Please enter phone or email.');
                    valid = false;
                }
                if (emailValue) {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(emailValue)) {
                        showError(requestEmailEl, document.getElementById('requestEmailError'), 'Please enter a valid email address.');
                        valid = false;
                    }
                }

                return valid;
            };

            const buildRecap = () => {
                const stops = Array.from(stopsListEl.querySelectorAll('input[name="stops[]"]'))
                    .map((el) => el.value.trim())
                    .filter((v) => v !== '');

                let html = '<strong>Pickup:</strong> ' + (startLocationEl.value.trim() || '-') + '<br>';
                if (stops.length > 0) {
                    html += '<strong>Stops:</strong> ' + stops.join(', ') + '<br>';
                }
                html += '<strong>Destination:</strong> ' + (finalDestinationEl.value.trim() || '-') + '<br>';
                html += '<strong>Dates:</strong> ' + (startDateEl.value || '-') + ' to ' + (endDateEl.value || '-') + '<br>';
                html += '<strong>Passengers:</strong> ' + (passengerCountEl.value || '-');

                tripRecap.innerHTML = html;
            };

            const setActiveStep = (step) => {
                wizardSteps.querySelectorAll('.wizard-step').forEach((el) => {
                    const elStep = Number(el.dataset.step);
                    el.classList.toggle('active', elStep === step);
                    el.classList.toggle('done', elStep < step);
                });
            };

            const goToStep2 = () => {
                if (!validateStep1()) {
                    return;
                }
                buildRecap();
                stepPanel1.style.display = 'none';
                stepPanel2.style.display = '';
                setActiveStep(2);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };

            const goToStep1 = () => {
                stepPanel2.style.display = 'none';
                stepPanel1.style.display = '';
                setActiveStep(1);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };

            toStep2Btn.addEventListener('click', goToStep2);
            backToStep1Btn.addEventListener('click', goToStep1);

            form.addEventListener('submit', (event) => {
                if (!validateStep1()) {
                    goToStep1();
                    event.preventDefault();
                    return;
                }
                if (!validateStep2()) {
                    event.preventDefault();
                    return;
                }
                if (submitBtn) {
                    submitBtn.classList.add('is-loading');
                    submitBtn.disabled = true;
                }
            });

            const initialStep = {{ $initialStep }};
            if (initialStep === 2) {
                buildRecap();
                stepPanel1.style.display = 'none';
                stepPanel2.style.display = '';
                setActiveStep(2);
            } else {
                setActiveStep(1);
            }
        })();
    </script>
</body>
</html>
