<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>{{ @$evenidcard->name ?? 'Event ID Cards' }}</title>

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
        @page {
            size: {{ @$paper_size ?? 'A4' }};
            margin: 0;
        }

        /*.print-container {
            width: 100%;
            margin: 0 auto;
        }*/

        .print-container {
            width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .paper {
            position: relative;
            margin: 20px auto;
            padding: 10mm;
            display: grid;
            @php
                $customWidth = request('custom_width');
                $customHeight = request('custom_height');
            @endphp

            @if(!empty($customWidth) && !empty($customHeight))

                width: {{ $customWidth }}mm;
                min-height: {{ $customHeight }}mm;

            @else
            @switch(strtoupper(@$paper_size ?? 'A4'))

                @case('A1')
                    width: 594mm;
                    min-height: 841mm;
                    @break

                @case('A2')
                    width: 420mm;
                    min-height: 594mm;
                    @break

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
            @endif
            @php
                $perPage = (int) (@$cardperpage ?? 1);
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

            width: {{ @$evenidcard->width }}mm;
            height: {{ @$evenidcard->height }}mm;

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

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        #printSettingsForm {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .toolbar button {
            border: 0;
            padding: 10px 20px;
            margin: 0 5px;

            border-radius: 5px;

            cursor: pointer;

            font-size: 14px;
        }

        .card-control {
            height: 38px;
            min-width: 120px;

            padding: 6px 10px;

            border: 1px solid #ced4da;
            border-radius: 5px;

            background: #fff;
            color: #333;

            font-size: 14px;
            outline: none;
        }

        #cardperpage {
            width: 120px;
        }

        .apply-btn {
            height: 38px;

            padding: 0 15px;

            border: 0;
            border-radius: 5px;

            background: #28a745;
            color: #fff;

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

        .card-control {
            height: 38px;
            min-width: 130px;
            padding: 6px 10px;

            border: 1px solid #ced4da;
            border-radius: 5px;

            background: #fff;
            color: #333;

            font-size: 14px;
            outline: none;
        }

        .card-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
        }

        select.card-control {
            cursor: pointer;
        }

        input.card-control {
            width: 130px;
        }


    </style>
</head>

<body>

{{--<div class="toolbar">
    @if($layout)
        <button type="button"
                class="print-btn"
                onclick="window.print()">
            🖨 Print ID Cards
        </button>
        <form action="{{ route('event.idcard.create') }}"
              method="GET"
              id="printSettingsForm">
            <input type="hidden"
                   name="id"
                   value="{{ $event->id }}">
            <input type="hidden"
                   name="print_type"
                   value="{{ request('print_type', 'all') }}">
            @foreach(request('selected_users', []) as $userId)
                <input type="hidden"
                       name="selected_users[]"
                       value="{{ $userId }}">
            @endforeach
            <select name="paper_size"
                    id="papersize"
                    class="card-control"
                    onchange="this.form.submit()">
                @foreach($papers as $paper)
                    <option value="{{ $paper->size }}"
                        {{ $paper_size == $paper->size ? 'selected' : '' }}>
                        {{ $paper->size }}
                    </option>
                @endforeach
            </select>
            <input type="number"
                   name="cardperpage"
                   id="cardperpage"
                   class="card-control"
                   value="{{ $cardperpage }}"
                   min="1"
                   step="1"
                   placeholder="Card Per Page"
                   onchange="this.form.submit()">

        </form>
        <button type="button"
                class="close-btn"
                onclick="window.close()">
            ✕ Close
        </button>
    @endif
</div>--}}

<div class="toolbar">

    @if($layout)

        <button type="button"
                class="print-btn"
                onclick="window.print()">
            🖨 Print ID Cards
        </button>

        <form action="{{ route('event.idcard.create') }}"
              method="GET"
              id="printSettingsForm">

            <input type="hidden"
                   name="id"
                   value="{{ $event->id }}">

            <input type="hidden"
                   name="print_type"
                   value="{{ request('print_type', 'all') }}">

            @foreach(request('selected_users', []) as $userId)
                <input type="hidden"
                       name="selected_users[]"
                       value="{{ $userId }}">
            @endforeach


            {{-- Paper Size --}}
            <select name="paper_size"
                    class="card-control"
                    onchange="this.form.submit()">

                @foreach($papers as $paper)
                    <option value="{{ $paper->size }}"
                        {{ $paper_size == $paper->size ? 'selected' : '' }}>
                        {{ $paper->size }}
                    </option>
                @endforeach

            </select>

            <input type="number"
                   name="cardperpage"
                   class="card-control"
                   value="{{ $cardperpage }}"
                   min="1"
                   step="1"
                   placeholder="Cards" onchange="this.form.submit()">


            {{-- Custom Width --}}
            <input type="number"
                   name="custom_width"
                   class="card-control custom-size"
                   value="{{ request('custom_width') }}"
                   min="1"
                   step="1"
                   placeholder="Width">


            {{-- Custom Height --}}
            <input type="number"
                   name="custom_height"
                   class="card-control custom-size"
                   value="{{ request('custom_height') }}"
                   min="1"
                   step="1"
                   placeholder="Height">


           <!--  <button type="submit"
                    class="apply-btn">
                Apply
            </button> -->

        </form>


        <button type="button"
                class="close-btn"
                onclick="window.close()">
            ✕ Close
        </button>

    @endif

</div>

@php
    /*$cardLayout = $layout->layout;
    if (is_string($cardLayout)) {
        $cardLayout = json_decode($cardLayout, true);
    }
    $fields = $cardLayout['fields'] ?? [];
    $tablePosition = $cardLayout['tablePosition'] ?? null;
    $cardPerPage = max(
        1,
        (int)($cardperpage ?? 1)
    );
    $userPages = $alluser->chunk($cardPerPage);*/
@endphp

@if(!$layout)
   <div style="
        width: 100%;
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        ">
    <div style="
        width: 100%;
        max-width: 500px;
        padding: 40px 25px;
    ">
        <div class="mb-3">
            <i class="fas fa-id-card text-danger"
               style="font-size: 70px;"></i>
        </div>

        <h3 class="mb-3">
            Card Layout Not Found
        </h3>

        <p class="text-muted mb-4">
            Please create a card layout before printing ID cards.
        </p>

        <button type="button"
                class="btn btn-primary"
                onclick="window.close()">

            <i class="fas fa-arrow-left mr-1"></i>
            Go Back

        </button>

    </div>

</div>
@else
    @php
        $cardLayout = $layout->layout;
        if (is_string($cardLayout)) {
            $cardLayout = json_decode($cardLayout, true);
        }
        $cardLayout = is_array($cardLayout)
            ? $cardLayout
            : [];
        $fields = $cardLayout['fields'] ?? [];
        $tablePosition = $cardLayout['tablePosition'] ?? null;
        $cardPerPage = max(
            1,
            (int)($cardperpage ?? 1)
        );
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

                        //$qrData = $user->user_unique_code;

                        //$qrUrl ='https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='. urlencode($qrData);

                        $qrValue = $user->user_unique_code ?? null; $qrLink = $qrValue ? route('show.register.user.mobile', [ 'id' => $qrValue ]) : null; $qrUrl = $qrLink ? 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrLink) : null;

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
@endif
</body>
</html>

