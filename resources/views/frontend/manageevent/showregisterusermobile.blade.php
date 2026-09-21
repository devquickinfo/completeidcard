<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

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

</body>

</html>
