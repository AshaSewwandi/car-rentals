@php
    $defaultKeywords = [
        'car rental',
        'auto rental',
        'vehicle hire',
        'rent a car',
        'daily car rental',
        'monthly car rental',
        'airport pickup car rental',
        'self drive rental',
        'chauffeur service',
        'rental fleet',
        'booking confirmation',
        'online transfer payment',
        'cash on pickup',
        'trip availability',
        'Sri Lanka car rental',
        'Galle car rental',
        'R&A Auto Rentals',
    ];

    $keywordSource = $keywords ?? $defaultKeywords;
    $tagSource = $tags ?? $keywordSource;

    $keywordContent = is_array($keywordSource) ? implode(', ', $keywordSource) : (string) $keywordSource;
    $tagContent = is_array($tagSource) ? implode(', ', $tagSource) : (string) $tagSource;

    $metaTitle = $title ?? 'R&A Auto Rentals | Car Rental Platform';
    $metaDescription = $description ?? 'Book reliable daily or monthly rental vehicles with R&A Auto Rentals. Check availability, confirm trips, and manage bookings online.';
    $metaType = $type ?? 'website';
    $metaRobots = $robots ?? 'index,follow';
    $metaImage = $image ?? asset('images/logo.png');
    $canonical = $canonical ?? url()->current();
@endphp
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $keywordContent }}">
<meta name="tags" content="{{ $tagContent }}">
<meta name="robots" content="{{ $metaRobots }}">
<meta name="format-detection" content="telephone=no">
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:type" content="{{ $metaType }}">
<meta property="og:site_name" content="R&A Auto Rentals">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-rounded-64.png') }}">
<link rel="shortcut icon" href="{{ asset('images/logo-rounded-64.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/logo-rounded-64.png') }}">
<style>
    img,
    video,
    iframe,
    canvas,
    svg {
        max-width: 100%;
        height: auto;
    }

    table {
        max-width: 100%;
    }

    input[type="date"],
    input[type="datetime-local"] {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%236b7f9a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right .7rem center;
        background-size: 18px 18px;
        padding-right: 2.3rem !important;
    }

    .field-control input[type="date"] {
        background-image: none;
    }

    @media (max-width: 920px) {
        body {
            overflow-x: hidden;
        }

        .container {
            width: min(1180px, calc(100% - 1rem)) !important;
        }

        input,
        select,
        textarea,
        button {
            max-width: 100%;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const containers = [document, ...document.querySelectorAll('form')];
        const normalize = (value) => (value || '').toLowerCase().replace(/[\s_\-\[\]]/g, '');
        const isStartLike = (input) => {
            const key = normalize((input.name || '') + ' ' + (input.id || ''));
            return key.includes('startdate') || key.includes('datefrom') || key.includes('fromdate') || key.includes('tripdatefrom') || key.includes('availabilitystartdate') || key.includes('requeststartdate');
        };
        const isEndLike = (input) => {
            const key = normalize((input.name || '') + ' ' + (input.id || ''));
            return key.includes('enddate') || key.includes('dateto') || key.includes('todate') || key.includes('tripdateto') || key.includes('availabilityenddate') || key.includes('requestenddate') || key.includes('returndate');
        };

        const findByNameOrId = (container, value) => {
            if (!value) return null;
            return container.querySelector(`input[type="date"][name="${value}"], input[type="datetime-local"][name="${value}"], #${value}`);
        };

        const findStartMatch = (container, endInput) => {
            const endName = endInput.getAttribute('name') || '';
            const endId = endInput.getAttribute('id') || '';
            const candidates = [
                endName.replace('end_date', 'start_date'),
                endName.replace('date_to', 'date_from'),
                endName.replace('to_date', 'from_date'),
                endId.replace('end_date', 'start_date'),
                endId.replace('date_to', 'date_from'),
                endId.replace('To', 'From'),
                endId.replace('End', 'Start'),
            ].filter(Boolean);

            for (const candidate of candidates) {
                const match = findByNameOrId(container, candidate);
                if (match && match !== endInput) return match;
            }

            const allInputs = Array.from(container.querySelectorAll('input[type="date"], input[type="datetime-local"]'));
            const endIndex = allInputs.indexOf(endInput);
            for (let i = endIndex - 1; i >= 0; i -= 1) {
                if (isStartLike(allInputs[i])) return allInputs[i];
            }

            return findByNameOrId(container, 'start_date')
                || findByNameOrId(container, 'date_from')
                || findByNameOrId(container, 'from_date');
        };

        const applyMin = (startInput, endInput) => {
            if (!endInput.dataset.baseMin) {
                endInput.dataset.baseMin = endInput.getAttribute('min') || '';
            }

            const baseMin = endInput.dataset.baseMin;
            const startValue = startInput.value || '';
            const nextMin = [baseMin, startValue].filter(Boolean).sort().pop() || '';

            if (nextMin) {
                endInput.setAttribute('min', nextMin);
            } else {
                endInput.removeAttribute('min');
            }

            if (endInput.value && nextMin && endInput.value < nextMin) {
                endInput.value = '';
            }
        };

        containers.forEach((container) => {
            const inputs = Array.from(container.querySelectorAll('input[type="date"], input[type="datetime-local"]'));
            const endInputs = inputs.filter((input) => isEndLike(input));

            endInputs.forEach((endInput) => {
                const startInput = findStartMatch(container, endInput);
                if (!startInput || startInput === endInput) return;

                const sync = () => applyMin(startInput, endInput);
                startInput.addEventListener('input', sync);
                startInput.addEventListener('change', sync);
                sync();
            });
        });
    });
</script>
