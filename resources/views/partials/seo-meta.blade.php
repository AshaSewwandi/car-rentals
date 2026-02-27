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
