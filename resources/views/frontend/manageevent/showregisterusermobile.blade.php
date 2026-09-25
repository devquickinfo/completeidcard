<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $manageEvent->name ?? 'User Details' }}
    </title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            min-height: 100vh;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Helvetica,
                Arial,
                sans-serif;

            background: #f5f8fc;
            color: #263238;
        }


        /* =========================================================
           PAGE
        ========================================================== */

        .profile-page {

            width: 100%;
            max-width: 520px;

            min-height: 100vh;

            margin: 0 auto;

            padding: 14px 12px 30px;
        }


        /* =========================================================
           MAIN CARD
        ========================================================== */

        .profile-card {

            width: 100%;

            background: #ffffff;

            border-radius: 22px;

            overflow: hidden;

            border: 1px solid #e7edf4;

            box-shadow:
                0 10px 35px rgba(30, 55, 90, 0.08);
        }


        /* =========================================================
           HEADER
        ========================================================== */

        .profile-header {

            position: relative;

            text-align: center;

            padding: 28px 20px 24px;

            background:
                linear-gradient(
                    180deg,
                    #eef6ff 0%,
                    #ffffff 100%
                );

            border-bottom: 1px solid #edf1f5;
        }


        .verified-badge {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            padding: 6px 12px;

            border-radius: 30px;

            background: #eaf8f0;

            color: #27804d;

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .6px;

            margin-bottom: 18px;
        }


        .verified-badge i {
            font-size: 12px;
        }


        /* =========================================================
           PHOTO
        ========================================================== */

        .profile-photo-wrapper {

            width: 125px;
            height: 150px;

            margin: 0 auto 17px;

            border-radius: 14px;

            padding: 4px;

            background: #ffffff;

            border: 1px solid #dfe7ef;

            box-shadow:
                0 6px 18px rgba(30, 55, 90, 0.10);

            overflow: hidden;
        }


        .profile-photo {

            width: 100%;
            height: 100%;

            object-fit: cover;

            border-radius: 10px;
        }


        .profile-photo-placeholder {

            width: 100%;
            height: 100%;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #f1f5f9;

            color: #9aa7b4;

            font-size: 45px;

            border-radius: 10px;
        }


        /* =========================================================
           NAME
        ========================================================== */

        .profile-name {

            margin: 0;

            font-size: 26px;

            line-height: 1.3;

            font-weight: 700;

            color: #17212b;

            word-break: break-word;
        }


        .profile-event {

            margin: 8px 0 0;

            color: #7a8794;

            font-size: 13px;

            line-height: 1.5;
        }


        .event-badge {

            display: inline-block;

            margin-top: 11px;

            padding: 6px 12px;

            border-radius: 20px;

            background: #eef5ff;

            color: #2878d4;

            font-size: 11px;

            font-weight: 600;
        }


        /* =========================================================
           CONTENT
        ========================================================== */

        .profile-content {

            padding: 20px 18px;
        }


        .section-title {

            margin: 0 0 14px;

            font-size: 14px;

            font-weight: 700;

            color: #343d46;
        }


        /* =========================================================
           INFORMATION ITEM
        ========================================================== */

        .info-list {

            display: flex;

            flex-direction: column;

            gap: 10px;
        }


        .info-item {

            display: flex;

            align-items: center;

            gap: 12px;

            padding: 13px 12px;

            background: #f8fafc;

            border: 1px solid #edf1f5;

            border-radius: 12px;

            min-width: 0;
        }


        .info-icon {

            flex: 0 0 38px;

            width: 38px;
            height: 38px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: #eaf3ff;

            color: #2878d4;

            font-size: 15px;
        }


        .info-content {

            min-width: 0;

            flex: 1;
        }


        .info-label {

            display: block;

            margin-bottom: 2px;

            color: #8a96a3;

            font-size: 10px;

            font-weight: 600;

            text-transform: uppercase;

            letter-spacing: .5px;
        }


        .info-value {

            color: #263238;

            font-size: 14px;

            font-weight: 600;

            line-height: 1.45;

            overflow-wrap: anywhere;

            word-break: break-word;
        }


        .info-value.empty {

            color: #a6afb8;

            font-weight: 500;
        }


        /* =========================================================
           ADDRESS
        ========================================================== */

        .address-section {

            margin-top: 22px;
        }


        .address-box {

            display: flex;

            align-items: flex-start;

            gap: 12px;

            padding: 14px;

            background: #fff9f9;

            border: 1px solid #f3dddd;

            border-left: 4px solid #dc3545;

            border-radius: 12px;

            color: #4c555d;

            font-size: 13px;

            line-height: 1.6;

            overflow-wrap: anywhere;
        }


        .address-icon {

            color: #dc3545;

            margin-top: 3px;

            flex: 0 0 auto;
        }


        /* =========================================================
           ACTION BUTTONS
        ========================================================== */

        .action-buttons {

            display: flex;

            gap: 10px;

            margin-top: 20px;
        }


        .action-btn {

            flex: 1;

            min-height: 45px;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            border-radius: 11px;

            text-decoration: none !important;

            font-size: 13px;

            font-weight: 600;
        }


        .call-btn {

            background: #eaf8f0;

            color: #27804d !important;

            border: 1px solid #d6efdf;
        }


        .email-btn {

            background: #eef5ff;

            color: #2878d4 !important;

            border: 1px solid #dceaff;
        }


        .action-btn:active {

            transform: scale(.98);
        }


        /* =========================================================
           REGISTRATION STATUS
        ========================================================== */

        .status-box {

            margin-top: 20px;

            padding: 14px;

            border-radius: 12px;

            background: #f1fbf5;

            border: 1px solid #d9f1e2;

            text-align: center;

            color: #28734a;

            font-size: 13px;

            line-height: 1.5;
        }


        .status-box i {

            margin-right: 5px;
        }


        /* =========================================================
           FOOTER
        ========================================================== */

        .profile-footer {

            padding: 17px 18px 20px;

            border-top: 1px solid #edf1f5;

            text-align: center;

            background: #ffffff;
        }


        .footer-text {

            margin: 0;

            color: #9aa5af;

            font-size: 11px;

            line-height: 1.6;
        }


        .footer-brand {

            margin-top: 5px;

            color: #6c757d;

            font-size: 11px;

            font-weight: 600;
        }


        /* =========================================================
           MOBILE
        ========================================================== */

        @media (max-width: 575.98px) {

            .profile-page {

                padding: 8px;

            }


            .profile-card {

                border-radius: 18px;

            }


            .profile-header {

                padding: 24px 16px 21px;

            }


            .profile-photo-wrapper {

                width: 115px;

                height: 138px;

            }


            .profile-name {

                font-size: 23px;

            }


            .profile-content {

                padding: 18px 14px;

            }


            .info-item {

                padding: 12px 10px;

            }


            .info-icon {

                flex-basis: 36px;

                width: 36px;

                height: 36px;

            }

        }
             
        /* =========================================================
           EVENT ID CARD SECTION
        ========================================================= */

        .idcard-section {
            padding: 20px 18px 24px;

            background: #ffffff;

            border-top: 1px solid #edf1f5;
        }


        /* =========================================================
           SECTION HEADER
        ========================================================= */

        .idcard-section-header {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 12px;

            margin-bottom: 16px;
        }


        .idcard-title-wrap {
            display: flex;

            align-items: center;

            gap: 11px;

            min-width: 0;
        }


        .idcard-title-icon {
            width: 40px;
            height: 40px;

            flex: 0 0 40px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 11px;

            background: linear-gradient(
                135deg,
                #eaf3ff,
                #f3f7ff
            );

            color: #2878d4;

            font-size: 17px;

            box-shadow:
                0 4px 12px rgba(40, 120, 212, 0.08);
        }


        .idcard-title {
            margin: 0;

            color: #1f2937;

            font-size: 15px;

            font-weight: 700;

            line-height: 1.3;
        }


        .idcard-subtitle {
            margin: 3px 0 0;

            color: #94a3b8;

            font-size: 11px;

            line-height: 1.4;
        }


        /* =========================================================
           VERIFIED BADGE
        ========================================================= */

        .idcard-status {
            flex: 0 0 auto;

            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 6px 9px;

            border-radius: 20px;

            background: #ecfdf3;

            border: 1px solid #d1fae5;

            color: #16834d;

            font-size: 10px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .3px;
        }


        /* =========================================================
           PREVIEW AREA
        ========================================================= */

        .idcard-preview {
            position: relative;

            width: 100%;

            padding: 24px 12px 20px;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            overflow: hidden;

            /* Clean light background */
            background: #f4f7fb;

            border: 1px solid #e3eaf2;

            border-radius: 18px;

            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.9),
                0 8px 24px rgba(30, 55, 90, 0.06);
        }


        /* =========================================================
           DECORATIVE GLOW
        ========================================================= */

        


        /* =========================================================
           RESPONSIVE CARD WRAPPER
        ========================================================= */

        .idcard-responsive-wrapper {

            position: relative;

            width: var(--card-width);

            height: var(--card-height);

            max-width: 100%;

            /*
             * The actual ID card remains at its original dimensions.
             * Only the visual wrapper is scaled.
             */
            transform-origin: center center;

            display: flex;

            align-items: center;

            justify-content: center;

            /*
             * Prevent blurry browser scaling where possible.
             */
            image-rendering: auto;
        }


        /* =========================================================
           ACTUAL ID CARD
        ========================================================= */

        .event-id-card {
            position: relative;

            flex: 0 0 auto;

            overflow: hidden;

            border-radius: 8px;

            /* IMPORTANT */
            background-color: #ffffff;

            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% 100%;

            border: 1px solid #d9e1ea;

            box-shadow:
                0 12px 30px rgba(15, 23, 42, 0.15),
                0 3px 8px rgba(15, 23, 42, 0.08);

            transform: translateZ(0);

            backface-visibility: hidden;

            -webkit-font-smoothing: antialiased;

            text-rendering: geometricPrecision;
        }


        /* =========================================================
           PREVIEW LABEL
        ========================================================= */

        .idcard-preview-hint {
            position: relative;

            z-index: 5;

            margin-top: 16px;

            padding: 6px 11px;

            border-radius: 20px;

            background: rgba(255,255,255,.85);

            border: 1px solid #e1e8f0;

            color: #7b8794;

            font-size: 10px;

            font-weight: 600;

            letter-spacing: .2px;
        }

        .idcard-preview-hint i {
            margin-right: 4px;

            color: #2878d4;
        }




        .event-id-card svg {
            display: block;

            width: 100%;

            height: 100%;

            shape-rendering: crispEdges;

            shape-rendering: geometricPrecision;
        }




        @media (max-width: 575.98px) {

            .idcard-section {
                padding: 18px 14px 22px;
            }


            .idcard-section-header {
                margin-bottom: 14px;
            }


            .idcard-title-icon {
                width: 36px;
                height: 36px;

                flex-basis: 36px;

                font-size: 15px;
            }


            .idcard-title {
                font-size: 14px;
            }


            .idcard-subtitle {
                font-size: 10px;
            }


            .idcard-status {
                padding: 5px 8px;

                font-size: 9px;
            }


            .idcard-preview {
                padding: 18px 8px 16px;

                border-radius: 15px;
            }

        }



        @media (max-width: 380px) {

            .idcard-section {
                padding-left: 10px;
                padding-right: 10px;
            }


            .idcard-preview {
                padding-left: 5px;
                padding-right: 5px;
            }


            .idcard-title-wrap {
                gap: 8px;
            }


            .idcard-title-icon {
                width: 34px;
                height: 34px;

                flex-basis: 34px;
            }


            .idcard-status {
                display: none;
            }

        }

                /* =========================================================
           ID CARD ACTION BUTTONS
        ========================================================= */

        .idcard-actions {
            position: relative;

            z-index: 10;

            width: 100%;

            max-width: 380px;

            display: flex;

            gap: 10px;

            margin: 18px auto 0;
        }


        .idcard-action-btn {
            flex: 1;

            min-height: 44px;

            border: none;

            border-radius: 11px;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            padding: 10px 14px;

            font-size: 12px;

            font-weight: 700;

            cursor: pointer;

            transition:
                transform .15s ease,
                box-shadow .15s ease,
                opacity .15s ease;

            outline: none;
        }


        .idcard-action-btn:hover {
            transform: translateY(-1px);
        }


        .idcard-action-btn:active {
            transform: scale(.97);
        }


        .idcard-action-btn:disabled {
            opacity: .65;

            cursor: not-allowed;

            transform: none;
        }


        /* Download */

        .download-idcard-btn {
            background: #ffffff;

            color: #2878d4;

            border: 1px solid #cfe0f5;

            box-shadow:
                0 4px 12px rgba(40, 120, 212, .08);
        }


        /* Print */

        .print-idcard-btn {
            background: #2878d4;

            color: #ffffff;

            border: 1px solid #2878d4;

            box-shadow:
                0 5px 14px rgba(40, 120, 212, .20);
        }


        .idcard-action-btn i {
            font-size: 13px;
        }


        /* Mobile */

        @media (max-width: 575.98px) {

            .idcard-actions {
                max-width: 100%;

                gap: 8px;

                margin-top: 15px;
            }

            .idcard-action-btn {
                min-height: 42px;

                font-size: 11px;

                padding: 9px 10px;
            }

        }

    </style>

</head>


<body>

<div class="profile-page">

    <div class="profile-card">


        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="profile-header">

            <div class="verified-badge">

                <i class="fas fa-check-circle"></i>

                Registered User

            </div>


            {{-- PHOTO --}}

            <div class="profile-photo-wrapper">

                @if(!empty($manageEvent->photo))

                    <img src="{{ asset('storage/' . $manageEvent->photo) }}"
                         alt="{{ $manageEvent->name ?? 'User Photo' }}"
                         class="profile-photo">

                @else

                    <div class="profile-photo-placeholder">

                        <i class="fas fa-user"></i>

                    </div>

                @endif

            </div>
             

                   






            {{-- NAME --}}

            <h1 class="profile-name">

                {{ $manageEvent->name ?? 'User' }}

            </h1>


            {{-- EVENT --}}

            @if(!empty($manageEvent->event_name))

                <p class="profile-event">

                    Registered for

                    <strong>
                        {{ $manageEvent->event_name }}
                    </strong>

                </p>

            @endif


            <span class="event-badge">

                <i class="fas fa-calendar-check mr-1"></i>

                Event Registration

            </span>

        </div>

        
        {{-- =========================================================
             EVENT ID CARD
        ========================================================= --}}

        @if(isset($layout))

            @php

                $cardData = is_array($layout->layout)
                    ? $layout->layout
                    : json_decode($layout->layout, true);

                $fields = $cardData['fields'] ?? [];
                $tablePosition = $cardData['tablePosition'] ?? [];

                $cardWidth = (int) ($cardData['cardWidth'] ?? 317);
                $cardHeight = (int) ($cardData['cardHeight'] ?? 204);

                $background = $layout->background ?? null;


                /*
                |--------------------------------------------------------------------------
                | Dynamic Values
                |--------------------------------------------------------------------------
                */

                $dynamicValues = [
                    'name'         => $manageEvent->name ?? '-',
                    'mobile'       => $manageEvent->mobile ?? '-',
                    'email'        => $manageEvent->email ?? '-',
                    'organization' => $manageEvent->organization ?? '-',
                    'address'      => $manageEvent->address ?? '-',
                    'photo'        => $manageEvent->photo ?? null,
                ];


                /*
                |--------------------------------------------------------------------------
                | Image URL Resolver
                |--------------------------------------------------------------------------
                */

                $resolveCardImageUrl = function ($value) {

                    if (!$value) {
                        return null;
                    }

                    if (
                        str_starts_with($value, 'http://') ||
                        str_starts_with($value, 'https://') ||
                        str_starts_with($value, 'data:')
                    ) {
                        return $value;
                    }

                    return asset('storage/' . ltrim($value, '/'));
                };


                /*
                |--------------------------------------------------------------------------
                | Dynamic Fields
                |--------------------------------------------------------------------------
                */

                $dynamicImageFields = [
                    'photo' => 'photo',
                ];

                $dynamicTextFields = [
                    'name'         => 'name',
                    'mobile'       => 'mobile',
                    'email'        => 'email',
                    'organization' => 'organization',
                    //'address'      => 'address',
                ];


                $backgroundUrl = $resolveCardImageUrl($background);


                /*
                |--------------------------------------------------------------------------
                | QR URL
                |--------------------------------------------------------------------------
                */

                $qrValue = $manageEvent->user_unique_code ?? null;

                $qrUrl = $qrValue
                    ? route('show.register.user.mobile', [
                        'id' => $qrValue
                    ])
                    : null;

            @endphp


            <div class="idcard-section">

                {{-- Section Header --}}
                <div class="idcard-section-header">

                    <div class="idcard-title-wrap">

                        <div class="idcard-title-icon">
                            <i class="fas fa-id-card"></i>
                        </div>

                        <div>
                            <h2 class="idcard-title">
                                Event ID Card
                            </h2>

                            <p class="idcard-subtitle">
                                Official registration identity card
                            </p>
                        </div>

                    </div>


                    <div class="idcard-status">
                        <i class="fas fa-check-circle"></i>
                        Verified
                    </div>

                </div>


                {{-- Card Preview Area --}}
                <div class="idcard-preview">

                    <div class="idcard-preview-glow"></div>


                    {{-- Responsive Card Wrapper --}}
                    <div
                        class="idcard-responsive-wrapper"
                        style="
                            --card-width: {{ $cardWidth }}px;
                            --card-height: {{ $cardHeight }}px;
                        "
                    >

                        <div id="eventIdCard"
                            class="event-id-card"
                            style="
                                width: {{ $cardWidth }}px;
                                height: {{ $cardHeight }}px;

                                @if($backgroundUrl)
                                    background-image: url('{{ $backgroundUrl }}');
                                    background-size: 100% 100%;
                                    background-position: center;
                                    background-repeat: no-repeat;
                                @else
                                    background-color: #ffffff;
                                @endif
                            "
                        >

                            {{-- =================================================
                                 CARD FIELDS
                            ================================================== --}}

                            @foreach($fields as $fieldKey => $field)

                                @if(($field['visible'] ?? true) === true)

                                    @php

                                        $type = $field['type'] ?? 'text';

                                        $x = (float) ($field['x'] ?? 0);
                                        $y = (float) ($field['y'] ?? 0);

                                        $width = isset($field['width'])
                                            ? (float) $field['width']
                                            : null;

                                        $height = isset($field['height'])
                                            ? (float) $field['height']
                                            : null;

                                        $fontSize = (float) ($field['fontSize'] ?? 12);

                                        $color = $field['color'] ?? '#000000';

                                        $fontWeight = $field['fontWeight'] ?? '400';

                                        $borderRadius = (float) ($field['borderRadius'] ?? 0);

                                        $customCss = trim($field['css'] ?? '');


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Base Position
                                        |--------------------------------------------------------------------------
                                        */

                                        $fieldStyle = "
                                            position:absolute;
                                            left:{$x}px;
                                            top:{$y}px;
                                            z-index:10;
                                            box-sizing:border-box;
                                        ";


                                        if ($width !== null) {
                                            $fieldStyle .= "
                                                width:{$width}px;
                                            ";
                                        }


                                        if ($height !== null) {
                                            $fieldStyle .= "
                                                height:{$height}px;
                                            ";
                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Dynamic Image
                                        |--------------------------------------------------------------------------
                                        */

                                        $dynImageKey =
                                            $dynamicImageFields[$fieldKey] ?? null;

                                        $liveImageValue =
                                            $dynImageKey
                                                ? ($dynamicValues[$dynImageKey] ?? null)
                                                : null;


                                        $resolvedSrc =
                                            $liveImageValue
                                                ? $resolveCardImageUrl($liveImageValue)
                                                : $resolveCardImageUrl($field['src'] ?? null);


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Dynamic Text
                                        |--------------------------------------------------------------------------
                                        */

                                        $dynTextKey =
                                            $dynamicTextFields[$fieldKey] ?? null;


                                        $resolvedText =
                                            ($dynTextKey && !empty($dynamicValues[$dynTextKey]))
                                                ? $dynamicValues[$dynTextKey]
                                                : ($field['text'] ?? '');


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Text Width
                                        |--------------------------------------------------------------------------
                                        */

                                        $textWrapWidth =
                                            $width ??
                                            max($cardWidth - $x - 4, 20);


                                        $textFieldStyle = $fieldStyle . "

                                            width:{$textWrapWidth}px;

                                            font-size:{$fontSize}px;

                                            color:{$color};

                                            font-weight:{$fontWeight};

                                            line-height:1.2;

                                            white-space:normal;

                                            overflow-wrap:break-word;

                                            word-break:break-word;

                                        ";


                                        /*
                                        |--------------------------------------------------------------------------
                                        | QR
                                        |--------------------------------------------------------------------------
                                        */

                                        $isQrField =
                                            $fieldKey === 'qr';


                                        $qrSize = max(
                                            (int) ($width ?? $height ?? 130),
                                            120
                                        );

                                    @endphp


                                    {{-- =================================================
                                         QR CODE
                                    ================================================== --}}

                                    @if($isQrField && $qrUrl)

                                        <div
                                            style="
                                                {{ $fieldStyle }}

                                                width:{{ $qrSize }}px;
                                                height:{{ $qrSize }}px;

                                                background:#ffffff;

                                                padding:4px;

                                                display:flex;

                                                align-items:center;

                                                justify-content:center;

                                                overflow:hidden;

                                                border-radius:{{ $borderRadius }}px;

                                                {{ $customCss }}
                                            "
                                        >

                                            {!! QrCode::format('svg')
                                                ->size($qrSize - 8)
                                                ->margin(1)
                                                ->errorCorrection('H')
                                                ->generate($qrUrl)
                                            !!}

                                        </div>


                                    {{-- =================================================
                                         IMAGE
                                    ================================================== --}}

                                    @elseif($type === 'image' && $resolvedSrc)

                                        <img
                                            src="{{ $resolvedSrc }}"
                                            alt=""
                                            draggable="false"
                                            style="
                                                {{ $fieldStyle }}

                                                object-fit:cover;

                                                border-radius:{{ $borderRadius }}px;

                                                display:block;

                                                max-width:none;

                                                {{ $customCss }}
                                            "
                                        >


                                    {{-- =================================================
                                         TEXT
                                    ================================================== --}}

                                    @elseif($type === 'text')

                                        <div
                                            style="
                                                {{ $textFieldStyle }}

                                                {{ $customCss }}
                                            "
                                        >
                                            {{ $resolvedText }}
                                        </div>

                                    @endif

                                @endif

                            @endforeach


                            {{-- =================================================
                                 TABLE DATA
                            ================================================== --}}

                            @if(!empty($cardData['tabledata']))

                                @php

                                    $tableLeft =
                                        (float) ($tablePosition['left'] ?? 0);

                                    $tableTop =
                                        (float) ($tablePosition['top'] ?? 0);

                                    $tableWidth =
                                        (float) ($tablePosition['width'] ?? 180);

                                    $tableHeight =
                                        $tablePosition['height'] ?? null;


                                    $tableHtml =
                                        $cardData['tabledata'];


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Dynamic Variables
                                    |--------------------------------------------------------------------------
                                    */

                                    foreach ($dynamicValues as $key => $value) {

                                        $tableHtml = str_ireplace(
                                            '{{' . $key . '}}',
                                            e($value ?? '-'),
                                            $tableHtml
                                        );

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Legacy Sample Values
                                    |--------------------------------------------------------------------------
                                    */

                                    $legacySampleReplacements = [

                                        'Rahul Kumar'
                                            => $dynamicValues['name'] ?? '-',

                                        '9632587410'
                                            => $dynamicValues['mobile'] ?? '-',

                                        'admin@gmail.com'
                                            => $dynamicValues['email'] ?? '-',

                                    ];


                                    foreach (
                                        $legacySampleReplacements
                                        as $sample => $liveValue
                                    ) {

                                        $tableHtml = str_replace(
                                            $sample,
                                            e($liveValue),
                                            $tableHtml
                                        );

                                    }


                                    $tableMaxWidth = min(
                                        $tableWidth,
                                        max(
                                            $cardWidth - $tableLeft - 4,
                                            20
                                        )
                                    );

                                @endphp


                                <div
                                    style="
                                        position:absolute;

                                        left:{{ $tableLeft }}px;

                                        top:{{ $tableTop }}px;

                                        width:{{ $tableMaxWidth }}px;

                                        z-index:20;

                                        color:#4b5563;

                                        overflow-wrap:break-word;

                                        word-break:break-word;

                                        box-sizing:border-box;

                                        @if($tableHeight)
                                            height:{{ $tableHeight }}px;
                                            overflow:hidden;
                                        @endif
                                    "
                                >

                                    {!! $tableHtml !!}

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- Preview Hint --}}
                    <div class="idcard-preview-hint">

                        <i class="fas fa-search-plus"></i>

                        ID card preview

                    </div>

                </div>

            </div>


            {{-- =========================================================
             ID CARD ACTION BUTTONS
             ========================================================= --}}

            <div class="idcard-actions">

                <button type="button"
                        class="idcard-action-btn download-idcard-btn"
                        id="downloadIdCard">

                    <i class="fas fa-download"></i>

                    <span>Download</span>

                </button>


                <button type="button"
                        class="idcard-action-btn print-idcard-btn"
                        id="printIdCard">

                    <i class="fas fa-print"></i>

                    <span>Print ID Card</span>

                </button>

            </div>

        @endif

        


        {{-- =====================================================
             INFORMATION
        ====================================================== --}}

        <div class="profile-content">

            <h2 class="section-title">

                <i class="fas fa-user-circle mr-1 text-primary"></i>

                User Information

            </h2>


            <div class="info-list">


                {{-- MOBILE --}}

                <div class="info-item">

                    <div class="info-icon">

                        <i class="fas fa-mobile-alt"></i>

                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Mobile
                        </span>

                        @if(!empty($manageEvent->mobile))

                            <div class="info-value">
                                {{ $manageEvent->mobile }}
                            </div>

                        @else

                            <div class="info-value empty">
                                -
                            </div>

                        @endif

                    </div>

                </div>


                {{-- EMAIL --}}

                <div class="info-item">

                    <div class="info-icon">

                        <i class="fas fa-envelope"></i>

                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Email
                        </span>

                        @if(!empty($manageEvent->email))

                            <div class="info-value">
                                {{ $manageEvent->email }}
                            </div>

                        @else

                            <div class="info-value empty">
                                -
                            </div>

                        @endif

                    </div>

                </div>


                {{-- ORGANIZATION --}}

                <div class="info-item">

                    <div class="info-icon">

                        <i class="fas fa-building"></i>

                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Organization
                        </span>

                        @if(!empty($manageEvent->organization))

                            <div class="info-value">
                                {{ $manageEvent->organization }}
                            </div>

                        @else

                            <div class="info-value empty">
                                -
                            </div>

                        @endif

                    </div>

                </div>


            </div>


            {{-- =================================================
                 ADDRESS
            ================================================== --}}

            <div class="address-section">

                <h2 class="section-title">

                    <i class="fas fa-map-marker-alt mr-1 text-danger"></i>

                    Address

                </h2>


                <div class="address-box">

                    <i class="fas fa-map-marker-alt address-icon"></i>

                    <div>

                        @if(!empty($manageEvent->address))

                            {{ $manageEvent->address }}

                        @else

                            <span class="text-muted">
                                -
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ACTION BUTTONS
            ================================================== --}}

            @if(!empty($manageEvent->mobile) || !empty($manageEvent->email))

                <div class="action-buttons">

                    @if(!empty($manageEvent->mobile))

                        <a href="tel:{{ $manageEvent->mobile }}"
                           class="action-btn call-btn">

                            <i class="fas fa-phone-alt"></i>

                            Call

                        </a>

                    @endif


                    @if(!empty($manageEvent->email))

                        <a href="mailto:{{ $manageEvent->email }}"
                           class="action-btn email-btn">

                            <i class="fas fa-envelope"></i>

                            Email

                        </a>

                    @endif

                </div>

            @endif


            {{-- =================================================
                 STATUS
            ================================================== --}}

            <div class="status-box">

                <i class="fas fa-check-circle"></i>

                This registration has been successfully completed.

            </div>

        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="profile-footer">

            <p class="footer-text">

                This page contains the registration details
                associated with the scanned QR code.

            </p>

            <div class="footer-brand">

                <i class="fas fa-qrcode mr-1"></i>

                QR Registration Profile

            </div>

        </div>


    </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const card = document.getElementById('eventIdCard');

    const downloadBtn = document.getElementById('downloadIdCard');

    const printBtn = document.getElementById('printIdCard');


    if (!card) {
        return;
    }


    /* =========================================================
       DOWNLOAD ID CARD
    ========================================================= */

    if (downloadBtn) {

        downloadBtn.addEventListener('click', async function () {

            const originalText = this.innerHTML;

            this.disabled = true;

            this.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                <span>Preparing...</span>
            `;


            try {

                /*
                 * Capture the REAL card size.
                 * The responsive CSS scale does not affect
                 * the downloaded image dimensions.
                 */

                const canvas = await html2canvas(card, {

                    width: card.offsetWidth,

                    height: card.offsetHeight,

                    scale: 3,

                    useCORS: true,

                    allowTaint: false,

                    backgroundColor: '#ffffff',

                    logging: false,

                    imageTimeout: 15000,

                    scrollX: 0,

                    scrollY: 0
                });


                const image = canvas.toDataURL(
                    'image/png',
                    1.0
                );


                const link = document.createElement('a');

                link.href = image;

                link.download =
                    '{{ \Illuminate\Support\Str::slug($manageEvent->name ?? "event-id-card") }}-id-card.png';


                document.body.appendChild(link);

                link.click();

                document.body.removeChild(link);


            } catch (error) {

                console.error(
                    'ID Card Download Error:',
                    error
                );

                alert(
                    'Unable to download the ID card. Please try again.'
                );

            } finally {

                this.disabled = false;

                this.innerHTML = originalText;

            }

        });

    }


    /* =========================================================
       PRINT ID CARD
    ========================================================= */

    if (printBtn) {

        printBtn.addEventListener('click', function () {

            printIdCard();

        });

    }


    function printIdCard() {

        const card = document.getElementById('eventIdCard');

        if (!card) {
            return;
        }


        /*
         * Clone the card so the original page is not modified.
         */

        const printCard = card.cloneNode(true);


        /*
         * Remove responsive transformations.
         */

        printCard.style.transform = 'none';

        printCard.style.margin = '0';

        printCard.style.boxShadow = 'none';


        /*
         * Create print window.
         */

        const printWindow = window.open(
            '',
            '_blank',
            'width=900,height=700'
        );


        if (!printWindow) {

            alert(
                'Please allow popups to print the ID card.'
            );

            return;
        }


        printWindow.document.open();


        printWindow.document.write(`

            <!doctype html>

            <html>

            <head>

                <meta charset="UTF-8">

                <title>
                    ID Card - {{ $manageEvent->name ?? 'User' }}
                </title>

                <style>

                    * {
                        box-sizing: border-box;
                    }

                    html,
                    body {
                        margin: 0;
                        padding: 0;
                        width: 100%;
                        min-height: 100%;
                    }

                    body {
                        background: #ffffff;

                        display: flex;

                        align-items: flex-start;

                        justify-content: center;

                        padding-top: 20px;
                    }

                    .print-card {
                        position: relative;

                        overflow: hidden;

                        background-color: #ffffff;

                        print-color-adjust: exact;

                        -webkit-print-color-adjust: exact;
                    }

                    @page {
                        margin: 0;
                    }

                    @media print {

                        body {
                            padding: 0;

                            background: #ffffff;
                        }

                        .print-card {
                            box-shadow: none !important;
                        }

                    }

                </style>

            </head>

            <body>

                <div
                    class="print-card"
                    style="
                        width:${card.offsetWidth}px;
                        height:${card.offsetHeight}px;
                    "
                >

                    ${printCard.innerHTML}

                </div>

            </body>

            </html>

        `);


        printWindow.document.close();


        /*
         * Wait for images and QR SVG to render.
         */

        setTimeout(function () {

            printWindow.focus();

            printWindow.print();

            /*
             * Close after print dialog is finished.
             */

            printWindow.onafterprint = function () {

                printWindow.close();

            };

        }, 700);

    }

});
</script>
</body>

</html>
