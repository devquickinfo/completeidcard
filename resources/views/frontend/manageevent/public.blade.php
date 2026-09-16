<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>{{ $manageEvent->event_name }}</title>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f7f9fc;
        color: #263238;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
                     Roboto, Helvetica, Arial, sans-serif;
    }

    .event-page {
        max-width: 760px;
        margin: 0 auto;
        padding: 20px 15px 110px;
    }

    /* Main Card */
    .event-card {
        background: #ffffff;
        border: 1px solid #e9edf3;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(31, 45, 61, 0.07);
    }

    /* Header */
    .event-header {
        text-align: center;
        padding: 30px 20px 25px;
        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #f8fbff 100%
        );
        border-bottom: 1px solid #edf0f4;
    }

    .logo-wrapper {
        width: 115px;
        height: 115px;
        margin: 0 auto 18px;
        background: #ffffff;
        border: 1px solid #e5eaf0;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.05);
    }

    .event-logo {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .event-logo-placeholder {
        color: #aab4c0;
        font-size: 42px;
    }

    .event-title {
        margin: 0;
        font-size: 27px;
        line-height: 1.3;
        font-weight: 700;
        color: #17212b;
    }

    .event-code {
        display: inline-block;
        margin-top: 10px;
        padding: 5px 12px;
        border-radius: 20px;
        background: #eef5ff;
        color: #2878d4;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .4px;
    }

    /* Content */
    .event-content {
        padding: 22px;
    }

    /* Date Box */
    .date-box {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #edf0f4;
        border-radius: 14px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .date-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        border-radius: 12px;
        background: #eaf3ff;
        color: #2878d4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        margin-right: 13px;
    }

    .date-content {
        flex: 1;
    }

    .date-label {
        color: #8a96a3;
        font-size: 12px;
        margin-bottom: 3px;
    }

    .date-value {
        color: #263238;
        font-size: 15px;
        font-weight: 600;
    }

    .date-separator {
        color: #b5bec8;
        margin: 0 7px;
    }

    /* Information */
    .info-section {
        margin-top: 8px;
    }

    .section-title {
        font-size: 17px;
        font-weight: 700;
        color: #202b36;
        margin-bottom: 13px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        padding: 14px 0;
        border-bottom: 1px solid #edf0f3;
    }

    .info-item:last-child {
        border-bottom: 0;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 10px;
        background: #f2f6fa;
        color: #5d7185;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 15px;
    }

    .info-content {
        flex: 1;
        min-width: 0;
    }

    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #9aa5af;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: 15px;
        line-height: 1.5;
        color: #303b46;
        word-break: break-word;
    }

    /* Description */
    .description-box {
        background: #f8fafc;
        border: 1px solid #edf0f4;
        border-radius: 13px;
        padding: 16px;
        color: #4d5965;
        font-size: 14px;
        line-height: 1.7;
    }

    /* Register */
    .register-area {
        padding: 18px 22px 22px;
        border-top: 1px solid #edf0f4;
        background: #ffffff;
    }

    .register-btn {
        width: 100%;
        border: 0;
        border-radius: 12px;
        padding: 14px 20px;
        background: #2878d4;
        color: #ffffff !important;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 15px rgba(40, 120, 212, 0.22);
        transition: all .2s ease;
    }

    .register-btn i {
        margin-right: 9px;
    }

    .register-btn:hover {
        background: #216bbf;
        transform: translateY(-1px);
    }

    .register-note {
        text-align: center;
        color: #9aa4ae;
        font-size: 11px;
        margin-top: 9px;
        margin-bottom: 0;
    }

    /* Desktop */
    @media (min-width: 768px) {

        .event-page {
            padding-top: 45px;
        }

        .event-header {
            padding: 40px 30px 30px;
        }

        .event-content {
            padding: 28px 32px;
        }

        .register-area {
            padding: 20px 32px 25px;
        }

        .register-btn {
            width: auto;
            min-width: 260px;
            margin: 0 auto;
        }

    }

    /* Mobile */
    @media (max-width: 575.98px) {

        .event-page {
            padding: 10px 10px 100px;
        }

        .event-card {
            border-radius: 15px;
        }

        .event-header {
            padding: 25px 16px 22px;
        }

        .logo-wrapper {
            width: 100px;
            height: 100px;
        }

        .event-title {
            font-size: 23px;
        }

        .event-content {
            padding: 18px 16px;
        }

        .date-box {
            padding: 13px;
        }

        .date-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            margin-right: 10px;
        }

        .date-value {
            font-size: 14px;
        }

        .register-area {
            padding: 15px 16px 18px;
        }

    }

</style>


</head>

<body>

<div class="event-page">


<div class="event-card">


    {{-- ========================= --}}
    {{-- EVENT HEADER --}}
    {{-- ========================= --}}

    <div class="event-header">

        <div class="logo-wrapper">

            @if(!empty($manageEvent->logo))

                <img src="{{ asset('storage/' . $manageEvent->logo) }}"
                     class="event-logo"
                     alt="{{ $manageEvent->event_name }}">

            @else

                <i class="fas fa-calendar-alt event-logo-placeholder"></i>

            @endif

        </div>


        <h1 class="event-title">

            {{ $manageEvent->event_name }}

        </h1>


        @if(!empty($manageEvent->unique_code))

            <span class="event-code">

                EVENT CODE:
                {{ $manageEvent->unique_code }}

            </span>

        @endif

    </div>


    {{-- ========================= --}}
    {{-- EVENT CONTENT --}}
    {{-- ========================= --}}

    <div class="event-content">


        {{-- Dates --}}
        <div class="date-box">

            <div class="date-icon">

                <i class="far fa-calendar-alt"></i>

            </div>

            <div class="date-content">

                <div class="date-label">
                    Event Date
                </div>

                <div class="date-value">

                    {{ \Carbon\Carbon::parse($manageEvent->start_date)->format('d M Y') }}

                    @if($manageEvent->start_date != $manageEvent->end_date)

                        <span class="date-separator">
                            —
                        </span>

                        {{ \Carbon\Carbon::parse($manageEvent->end_date)->format('d M Y') }}

                    @endif

                </div>

            </div>

        </div>


        {{-- Event Information --}}
        <div class="info-section">

            <div class="section-title">
                Event Information
            </div>


            {{-- Organizer --}}
            @if(!empty($manageEvent->organizer_name))

                <div class="info-item">

                    <div class="info-icon">

                        <i class="fas fa-user-tie"></i>

                    </div>

                    <div class="info-content">

                        <div class="info-label">
                            Organizer
                        </div>

                        <div class="info-value">
                            {{ $manageEvent->organizer_name }}
                        </div>

                    </div>

                </div>

            @endif


            {{-- Contact --}}
            @if(!empty($manageEvent->contact_person1))

                <div class="info-item">

                    <div class="info-icon">

                        <i class="fas fa-phone-alt"></i>

                    </div>

                    <div class="info-content">

                        <div class="info-label">
                            Contact Person
                        </div>

                        <div class="info-value">
                            {{ $manageEvent->contact_person1 }}
                        </div>

                    </div>

                </div>

            @endif


            {{-- Address --}}
            @if(!empty($manageEvent->address))

                <div class="info-item">

                    <div class="info-icon">

                        <i class="fas fa-map-marker-alt"></i>

                    </div>

                    <div class="info-content">

                        <div class="info-label">
                            Venue / Address
                        </div>

                        <div class="info-value">

                            {!! nl2br(e($manageEvent->address)) !!}

                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- Description --}}
        @if(!empty($manageEvent->description))

            <div class="info-section mt-4">

                <div class="section-title">
                    About This Event
                </div>

                <div class="description-box">

                    {!! nl2br(e($manageEvent->description)) !!}

                </div>

            </div>

        @endif

    </div>


    {{-- ========================= --}}
    {{-- REGISTER BUTTON --}}
    {{-- ========================= --}}

    <div class="register-area">

        <a href="{{ route('events.register', $manageEvent->unique_code) }}"
           class="register-btn">

            <i class="fas fa-user-plus"></i>

            Register for Event

        </a>

        <p class="register-note">

            Click above to complete your event registration

        </p>

    </div>


</div>


</div>

</body>

</html>
