<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>{{ $evenidcard->name ?? 'Event ID Cards' }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #eee;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* =========================
           PRINT SETTINGS
        ========================== */

        @page {
            size: {{ $evenidcard->paper_size ?? 'A4' }};
            margin: 0;
        }

        .print-container {
            width: 100%;
            margin: 0 auto;
        }

        /* =========================
           PAPER
        ========================== */

        /*.paper {
            position: relative;

            @if(strtoupper($evenidcard->paper_size ?? 'A4') === 'A4')
                width: 210mm;
                min-height: 297mm;
            @elseif(strtoupper($evenidcard->paper_size ?? '') === 'A5')
                width: 148mm;
                min-height: 210mm;
            @elseif(strtoupper($evenidcard->paper_size ?? '') === 'LETTER')
                width: 215.9mm;
                min-height: 279.4mm;
            @else
                width: 210mm;
                min-height: 297mm;
            @endif

            margin: 0 auto;
            padding: 10mm;

            display: grid;

           
            @if((int)($evenidcard->cardperpage ?? 1) === 1)
                grid-template-columns: 1fr;
            @elseif((int)($evenidcard->cardperpage ?? 1) === 2)
                grid-template-columns: repeat(2, 1fr);
            @elseif((int)($evenidcard->cardperpage ?? 1) === 3)
                grid-template-columns: repeat(3, 1fr);
            @elseif((int)($evenidcard->cardperpage ?? 1) === 4)
                grid-template-columns: repeat(2, 1fr);
            @elseif((int)($evenidcard->cardperpage ?? 1) === 5)
                grid-template-columns: repeat(2, 1fr);
            @elseif((int)($evenidcard->cardperpage ?? 1) === 6)
                grid-template-columns: repeat(3, 1fr);
            @elseif((int)($evenidcard->cardperpage ?? 1) === 8)
                grid-template-columns: repeat(4, 1fr);
            @elseif((int)($evenidcard->cardperpage ?? 1) === 9)
                grid-template-columns: repeat(3, 1fr);
            @elseif((int)($evenidcard->cardperpage ?? 1) === 10)
                grid-template-columns: repeat(5, 1fr);
            @else
                grid-template-columns: repeat(2, 1fr);
            @endif

            gap: 8mm;

            align-content: start;
        }*/

        .paper {
            position: relative;
            margin: 0 auto;
            padding: 10mm;
            display: grid;
            @switch(strtoupper($evenidcard->paper_size ?? 'A4'))

                @case('A3')
                    width: 297mm;
                    min-height: 420mm;
                    @break

                @case('A4')
                    width: 210mm;
                    min-height: 297mm;
                    @break

                @case('A5')
                    width: 148mm;
                    min-height: 210mm;
                    @break

                @case('A6')
                    width: 105mm;
                    min-height: 148mm;
                    @break

                @default
                    width: 210mm;
                    min-height: 297mm;
            @endswitch
            @php
                $perPage = (int) ($evenidcard->cardperpage ?? 1);
                $columns = match ($perPage) {
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 2,
                    5 => 2,
                    6 => 3,
                    7 => 3,
                    8 => 4,
                    9 => 3,
                    10 => 5,
                    12 => 4,
                    default => 2,
                };
            @endphp
            grid-template-columns: repeat({{ $columns }}, auto);
            justify-content: center;
            align-content: start;
            gap: 8mm;
        }

        /* =========================
           CARD
        ========================== */

        .id-card {
            position: relative;

            width: {{ $evenidcard->width }}mm;
            height: {{ $evenidcard->height }}mm;

            overflow: hidden;

            background-color: #fff;

            background-image:
                @if(!empty($layout->background))
                    url('{{ asset('storage/' . $layout->background) }}');
                @else
                    none;
                @endif

            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;

            flex-shrink: 0;

            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* =========================
           LAYOUT ELEMENT
        ========================== */

        .layout-element {
            position: absolute;
            overflow: hidden;
        }

        .layout-text {
            white-space: nowrap;
        }

        .layout-image {
            display: block;
            object-fit: contain;
        }

        /* =========================
           PHOTO
        ========================== */

        .user-photo {
            width: 100%;
            height: 100%;

            object-fit: cover;
            display: block;
        }

        /* =========================
           TABLE
        ========================== */

        .user-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .user-table td {
            padding: 2px 4px;
            vertical-align: top;
        }

        /* =========================
           QR
        ========================== */

        .qr-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* =========================
           SCREEN TOOLBAR
        ========================== */

        .toolbar {
            position: sticky;
            top: 0;
            z-index: 9999;

            padding: 12px;
            background: #222;

            text-align: center;
        }

        .toolbar button {
            border: 0;
            padding: 10px 20px;
            margin: 0 5px;

            border-radius: 5px;

            cursor: pointer;

            font-size: 14px;
        }

        .print-btn {
            background: #198754;
            color: #fff;
        }

        .close-btn {
            background: #dc3545;
            color: #fff;
        }

        /* =========================
           PRINT MODE
        ========================== */

        @media print {

            html,
            body {
                background: #fff;
                margin: 0;
                padding: 0;
            }

            .toolbar {
                display: none !important;
            }

            .paper {
                margin: 0;
                padding: 10mm;

                page-break-after: always;
                break-after: page;
            }

            .paper:last-child {
                page-break-after: auto;
                break-after: auto;
            }

            .id-card {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        /* =========================
           SCREEN PREVIEW
        ========================== */

        @media screen {

            .paper {
                margin-top: 20px;
                margin-bottom: 20px;

                background: #fff;

                box-shadow: 0 0 10px rgba(0,0,0,.2);
            }
        }
    </style>
</head>

<body>

<div class="toolbar">

    <button class="print-btn" onclick="window.print()">
        🖨 Print ID Cards
    </button>

    <button class="close-btn" onclick="window.close()">
        ✕ Close
    </button>

</div>

@php

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */

    $cardLayout = $layout->layout;

    if (is_string($cardLayout)) {
        $cardLayout = json_decode($cardLayout, true);
    }

    $fields = $cardLayout['fields'] ?? [];

    $tablePosition = $cardLayout['tablePosition'] ?? null;

    /*
    |--------------------------------------------------------------------------
    | Card Per Page
    |--------------------------------------------------------------------------
    */

    $cardPerPage = max(
        1,
        (int)($evenidcard->cardperpage ?? 1)
    );

    /*
    |--------------------------------------------------------------------------
    | Split users into pages
    |--------------------------------------------------------------------------
    */

    $userPages = $alluser->chunk($cardPerPage);

@endphp


<div class="print-container">

@foreach($userPages as $users)

    <div class="paper">

        @foreach($users as $user)

            @php

                /*
                |--------------------------------------------------------------------------
                | Photo
                |--------------------------------------------------------------------------
                */

                $photoUrl = '';

                if (!empty($user->photo)) {
                    $photoUrl = asset('storage/' . $user->photo);
                }

                /*
                |--------------------------------------------------------------------------
                | QR
                |--------------------------------------------------------------------------
                */

                $qrData = $user->user_unique_code;

                $qrUrl =
                    'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='
                    . urlencode($qrData);

            @endphp


            <div class="id-card">

                {{-- ==========================================
                     BACKGROUND
                =========================================== --}}

                @foreach($fields as $fieldKey => $field)

                    @php

                        $visible = $field['visible'] ?? true;

                        if (!$visible) {
                            continue;
                        }

                        $type = $field['type'] ?? 'text';

                        $x = (float)($field['x'] ?? 0);
                        $y = (float)($field['y'] ?? 0);

                        $width = (float)($field['width'] ?? 100);
                        $height = (float)($field['height'] ?? 30);

                        /*
                        |--------------------------------------------------------------------------
                        | Stored layout uses pixels.
                        | Convert based on original card dimensions.
                        |--------------------------------------------------------------------------
                        */

                        $layoutWidth =
                            (float)($cardLayout['cardWidth'] ?? 294.803);

                        $layoutHeight =
                            (float)($cardLayout['cardHeight'] ?? 472.44);

                        $cardWidth =
                            (float)$evenidcard->width;

                        $cardHeight =
                            (float)$evenidcard->height;

                        /*
                        |--------------------------------------------------------------------------
                        | Convert PX to percentage.
                        |--------------------------------------------------------------------------
                        */

                        $leftPercent =
                            ($x / $layoutWidth) * 100;

                        $topPercent =
                            ($y / $layoutHeight) * 100;

                        $widthPercent =
                            ($width / $layoutWidth) * 100;

                        $heightPercent =
                            ($height / $layoutHeight) * 100;

                    @endphp


                    {{-- ==========================================
                         LOGO
                    =========================================== --}}

                    @if($fieldKey === 'logo' && !empty($field['src']))

                        <div
                            class="layout-element"
                            style="
                                left: {{ $leftPercent }}%;
                                top: {{ $topPercent }}%;
                                width: {{ $widthPercent }}%;
                                height: {{ $heightPercent }}%;
                            "
                        >

                            <img
                                src="{{ asset('storage/' . $field['src']) }}"
                                class="layout-image"
                                style="
                                    width:100%;
                                    height:100%;
                                    object-fit:contain;
                                    {{ $field['css'] ?? '' }}
                                "
                            >

                        </div>

                    {{-- ==========================================
                         USER PHOTO
                    =========================================== --}}

                    @elseif($fieldKey === 'photo')

                        @if($photoUrl)

                            <div
                                class="layout-element"
                                style="
                                    left: {{ $leftPercent }}%;
                                    top: {{ $topPercent }}%;
                                    width: {{ $widthPercent }}%;
                                    height: {{ $heightPercent }}%;

                                    border-radius: {{ $field['borderRadius'] ?? 0 }}px;
                                    overflow:hidden;
                                "
                            >

                                <img
                                    src="{{ $photoUrl }}"
                                    class="user-photo"
                                    style="
                                        border-radius: {{ $field['borderRadius'] ?? 0 }}px;
                                        {{ $field['css'] ?? '' }}
                                    "
                                >

                            </div>

                        @endif


                    {{-- ==========================================
                         QR CODE
                    =========================================== --}}

                    @elseif($fieldKey === 'qr')

                        <div
                            class="layout-element"
                            style="
                                left: {{ $leftPercent }}%;
                                top: {{ $topPercent }}%;
                                width: {{ $widthPercent }}%;
                                height: {{ $heightPercent }}%;
                            "
                        >

                            <img
                                src="{{ $qrUrl }}"
                                class="qr-image"
                                alt="QR Code"
                                style="{{ $field['css'] ?? '' }}"
                            >

                        </div>


                    {{-- ==========================================
                         TEXT FIELDS
                    =========================================== --}}

                    @elseif($type === 'text')

                        @php

                            $text = $field['text'] ?? '';

                            /*
                            |--------------------------------------------------------------------------
                            | Replace known values with registration data
                            |--------------------------------------------------------------------------
                            */

                            switch ($fieldKey) {

                                case 'name':
                                    $text = $user->name;
                                    break;

                                case 'email':
                                    $text = $user->email;
                                    break;

                                case 'mobile':
                                    $text = $user->mobile;
                                    break;

                                case 'organization':
                                    $text = $user->organization;
                                    break;

                                case 'address':
                                    //$text = $user->address;
                                    break;

                                case 'user_unique_code':
                                    $text = $user->user_unique_code;
                                    break;

                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Replace placeholders too
                            |--------------------------------------------------------------------------
                            */

                            $text = str_replace(
                                [
                                    '{{name}}',
                                    '{{email}}',
                                    '{{mobile}}',
                                    '{{organization}}',
                                    '{{address}}',
                                    '{{user_unique_code}}'
                                ],
                                [
                                    $user->name,
                                    $user->email,
                                    $user->mobile,
                                    $user->organization,
                                    $user->address,
                                    $user->user_unique_code
                                ],
                                $text
                            );

                        @endphp


                        <div
                            class="layout-element layout-text"
                            style="
                                left: {{ $leftPercent }}%;
                                top: {{ $topPercent }}%;
                                width: {{ $widthPercent }}%;
                                height: {{ $heightPercent }}%;

                                font-size: {{ $field['fontSize'] ?? 12 }}px;

                                color: {{ $field['color'] ?? '#000' }};

                                font-weight: {{ $field['fontWeight'] ?? '400' }};

                                text-align: {{ $field['textAlign'] ?? 'left' }};

                                {{ $field['css'] ?? '' }}
                            "
                        >
                            {{ $text }}
                        </div>

                    @endif

                @endforeach


                {{-- ==========================================
                     TABLE DATA
                =========================================== --}}

                @if($tablePosition)

                    @php

                        $tableLeft =
                            ((float)($tablePosition['left'] ?? 0)
                            / (float)($cardLayout['cardWidth'] ?? 294.803))
                            * 100;

                        $tableTop =
                            ((float)($tablePosition['top'] ?? 0)
                            / (float)($cardLayout['cardHeight'] ?? 472.44))
                            * 100;

                        $tableWidth =
                            ((float)($tablePosition['width'] ?? 150)
                            / (float)($cardLayout['cardWidth'] ?? 294.803))
                            * 100;

                    @endphp

                    <div
                        class="layout-element"
                        style="
                            left: {{ $tableLeft }}%;
                            top: {{ $tableTop }}%;
                            width: {{ $tableWidth }}%;
                        "
                    >

                        <table class="user-table">

                            <tr>
                                <td><strong>Name</strong></td>
                                <td>{{ $user->name }}</td>
                            </tr>

                            <tr>
                                <td><strong>Mobile</strong></td>
                                <td>{{ $user->mobile }}</td>
                            </tr>

                            @if(!empty($user->email))
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                            @endif

                            @if(!empty($user->organization))
                                <tr>
                                    <td><strong>Organization</strong></td>
                                    <td>{{ $user->organization }}</td>
                                </tr>
                            @endif

                        </table>

                    </div>

                @endif

            </div>

        @endforeach

    </div>

@endforeach

</div>


<script>
    /*
    |--------------------------------------------------------------------------
    | Automatically open print dialog
    |--------------------------------------------------------------------------
    */

    window.addEventListener('load', function () {

        setTimeout(function () {
            window.print();
        }, 500);

    });
</script>

</body>
</html>

