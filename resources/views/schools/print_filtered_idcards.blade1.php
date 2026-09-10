<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Filtered ID Cards - Print</title>

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 5mm;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            background: #eeeeee;
            font-family: Arial, Helvetica, sans-serif;
        }

        .print-button {
            display: block;
            margin: 15px auto;
            padding: 10px 25px;
            border: 0;
            border-radius: 5px;
            background: #147abb;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        .print-info {
            text-align: center;
            margin: 10px auto;
            font-size: 13px;
            color: #666;
        }

        /*
        |--------------------------------------------------------------------------
        | A4 PAGE
        |--------------------------------------------------------------------------
        */

        .a4-page {
            width: 210mm;
            height: 297mm;

            margin: 5mm auto;
            padding: 5mm;

            display: grid;

            column-gap: 3mm;
            row-gap: 3mm;

            align-content: start;
            justify-content: start;

            background: #fff;

            overflow: hidden;

            page-break-after: always;
            break-after: page;
        }

        /*
        |--------------------------------------------------------------------------
        | HORIZONTAL
        |--------------------------------------------------------------------------
        */

        .a4-page.orientation-horizontal {
            grid-template-columns: repeat(2, 98mm);
            grid-template-rows: repeat(5, 55mm);
        }

        /*
        |--------------------------------------------------------------------------
        | VERTICAL
        |--------------------------------------------------------------------------
        */

        .a4-page.orientation-vertical {
            grid-template-columns: repeat(3, 54mm);
            grid-template-rows: repeat(3, 84mm);
        }

        /*
        |--------------------------------------------------------------------------
        | PHYSICAL CARD CONTAINER
        |--------------------------------------------------------------------------
        */

        .id-card-container {
            position: relative;

            overflow: hidden;

            page-break-inside: avoid;
            break-inside: avoid;
        }

        /*
        |--------------------------------------------------------------------------
        | EDITOR CANVAS
        |--------------------------------------------------------------------------
        */

        .id-card {
            position: absolute;

            top: 0;
            left: 0;

            overflow: hidden;

            transform-origin: top left;
        }

        /*
        |--------------------------------------------------------------------------
        | NO STUDENTS
        |--------------------------------------------------------------------------
        */

        .no-students {
            padding: 40px;
            text-align: center;
            background: #fff;
            margin: 20px auto;
            border-radius: 5px;
            max-width: 600px;
            box-shadow: 0 0 8px rgba(0, 0, 0, .15);
        }

        .no-students h3 {
            color: #d9534f;
            margin: 0 0 10px 0;
        }

        .no-students p {
            color: #666;
            margin: 0;
        }

        @media screen {
            .a4-page {
                box-shadow: 0 0 8px rgba(0, 0, 0, .15);
            }
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
                background: #fff;
            }

            .print-button,
            .print-info {
                display: none !important;
            }

            .a4-page {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 5mm;

                box-shadow: none;

                page-break-after: always;
                break-after: page;
            }
        }
    </style>
</head>

<body>

<button class="print-button" onclick="window.print()">
    🖨 Print ID Cards
</button>

<div class="print-info">

    Total Cards: {{ $students->count() }}

    | Class: {{ $classFilter ?? 'All' }}

    | Section: {{ $sectionFilter ?? 'All' }}

    | Orientation: {{ ucfirst($orientation) }}

    | Photo Filter: {{ $photoFilter ?? 'All' }}

</div>


@if($students->count() > 0)

    @php

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE ORIENTATION
        |--------------------------------------------------------------------------
        */

        $orientation = strtolower($orientation ?? 'horizontal');

        if (!in_array($orientation, ['horizontal', 'vertical'])) {
            $orientation = 'horizontal';
        }


        /*
        |--------------------------------------------------------------------------
        | PHYSICAL PRINT SIZE
        |--------------------------------------------------------------------------
        */

        if ($orientation === 'vertical') {

            $cardsPerPage = 9;

            $printWidthMm = 54;
            $printHeightMm = 84;

        } else {

            $cardsPerPage = 10;

            $printWidthMm = 98;
            $printHeightMm = 55;
        }


        /*
        |--------------------------------------------------------------------------
        | SAVED EDITOR LAYOUT
        |--------------------------------------------------------------------------
        */

        if (is_string($layout)) {
            $layout = json_decode($layout, true) ?? [];
        }

        if (!is_array($layout)) {
            $layout = [];
        }


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        |
        | Your actual saved layout is:
        |
        | cardWidth  = 317
        | cardHeight = 204
        |
        |--------------------------------------------------------------------------
        */

        $editorWidth = (float) ($layout['cardWidth'] ?? 317);
        $editorHeight = (float) ($layout['cardHeight'] ?? 204);


        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */

        $fields = $layout['fields'] ?? [];

        if (!is_array($fields)) {
            $fields = [];
        }


        /*
        |--------------------------------------------------------------------------
        | SCALE
        |--------------------------------------------------------------------------
        |
        | We need to convert the editor's 317x204 canvas
        | into the physical 98x55mm card.
        |
        | IMPORTANT:
        |
        | Do NOT scale X and Y independently.
        |
        | One uniform scale keeps:
        |
        | x
        | y
        | width
        | height
        |
        | exactly proportional to the editor.
        |--------------------------------------------------------------------------
        */

        $pxPerMm = 96 / 25.4;

        $printWidthPx = $printWidthMm * $pxPerMm;
        $printHeightPx = $printHeightMm * $pxPerMm;

        $scaleX = $printWidthPx / $editorWidth;
        $scaleY = $printHeightPx / $editorHeight;

        $scale = min($scaleX, $scaleY);


        /*
        |--------------------------------------------------------------------------
        | BACKGROUND
        |--------------------------------------------------------------------------
        */

        if (!empty($design->background)) {

            $bgUrl = asset(
                'storage/' . ltrim($design->background, '/')
            );

        } elseif ($sample && !empty($sample->file_path)) {

            $bgUrl = asset(
                'storage/' . ltrim($sample->file_path, '/')
            );

        } else {

            $bgUrl = '';
        }


        /*
        |--------------------------------------------------------------------------
        | LAYOUT CHECK
        |--------------------------------------------------------------------------
        */

        $hasLayout = count($fields) > 0;

        /*
        |--------------------------------------------------------------------------
        | TABLE DATA (unrelated to $fields — same key names/tokens are reused)
        |--------------------------------------------------------------------------
        */

        $tabledataHtmlRaw = $layout['tabledata'] ?? '';
        $tablePos = $layout['tablePosition'] ?? [];

        $tableLeft   = isset($tablePos['left'])   ? (float) $tablePos['left']   : 30;
        $tableTop    = isset($tablePos['top'])    ? (float) $tablePos['top']    : 120;
        $tableWidth  = isset($tablePos['width'])  ? (float) $tablePos['width']  : max(120, $editorWidth - 60);
        $tableHeight = isset($tablePos['height']) ? (float) $tablePos['height'] : 0;

        $hasTableLayout = is_string($tabledataHtmlRaw) && trim(strip_tags($tabledataHtmlRaw)) !== '';

    @endphp


    @if(!$hasLayout && !$hasTableLayout)

        <div class="no-students">

            <h3>
                ⚠ No Card Layout Found
            </h3>

            <p>

                School:
                {{ $school->school_name ?? 'N/A' }}

                <br>

                Orientation:
                {{ ucfirst($orientation) }}

                <br>

                Design Record:
                {{ $design ? 'Found' : 'Not Found' }}

                <br>

                Layout Fields:
                {{ count($fields) }}

            </p>

            <p>

                <a
                    href="{{ route('idcard.editor', ['orientation' => $orientation]) }}"
                    target="_blank"
                >
                    Open ID Card Editor
                </a>

            </p>

        </div>

    @else


        {{-- ============================================================ --}}
        {{-- STUDENT PAGES --}}
        {{-- ============================================================ --}}

        @foreach($students->chunk($cardsPerPage) as $pageStudents)

            <div class="a4-page orientation-{{ $orientation }}">


                {{-- ==================================================== --}}
                {{-- STUDENTS --}}
                {{-- ==================================================== --}}

                @foreach($pageStudents as $student)


                    {{-- ================================================= --}}
                    {{-- PHYSICAL CARD --}}
                    {{-- ================================================= --}}

                    <div
                        class="id-card-container"
                        style="
                            width: {{ $printWidthMm }}mm;
                            height: {{ $printHeightMm }}mm;
                        "
                    >


                        {{-- ============================================= --}}
                        {{-- EDITOR CANVAS --}}
                        {{-- ============================================= --}}

                        <div
                            class="id-card"
                            style="
                                width: {{ $editorWidth }}px;
                                height: {{ $editorHeight }}px;

                                transform:
                                    scale({{ $scale }});
                            "
                        >


                            {{-- ========================================= --}}
                            {{-- BACKGROUND --}}
                            {{-- ========================================= --}}

                            @if($bgUrl)

                                <div
                                    style="
                                        position:absolute;

                                        left:0;
                                        top:0;

                                        width:100%;
                                        height:100%;

                                        background-image:url('{{ $bgUrl }}');

                                        background-size:100% 100%;

                                        background-position:0 0;

                                        background-repeat:no-repeat;

                                        z-index:0;
                                    "
                                ></div>

                            @else

                                <div
                                    style="
                                        position:absolute;

                                        left:0;
                                        top:0;

                                        width:100%;
                                        height:100%;

                                        background:#f5f5f5;

                                        z-index:0;
                                    "
                                ></div>

                            @endif
                            @foreach($fields as $key => $field)

                                @php
                                    $type = strtolower(
                                        $field['type'] ?? 'text'
                                    );
                                    $fieldKey = strtolower(
                                        (string) $key
                                    );
                                    $fieldKeyBase = preg_replace(
                                        '/_copy_.*$/',
                                        '',
                                        $fieldKey
                                    );
                                    $fieldText = strtolower(
                                        (string) ($field['text'] ?? '')
                                    );

                                    $fieldId = strtolower(
                                        (string) ($field['id'] ?? '')
                                    );

                                    $left = isset($field['x'])
                                        ? (float) $field['x']
                                        : 0;

                                    $top = isset($field['y'])
                                        ? (float) $field['y']
                                        : 0;

                                    $width = isset($field['width'])
                                        ? (float) $field['width']
                                        : null;

                                    $height = isset($field['height'])
                                        ? (float) $field['height']
                                        : null;

                                    $visible =
                                        $field['visible'] ?? true;

                                    if ($type === 'shape') {

                                        $zIndex = 1;

                                    } elseif ($type === 'image') {

                                        $zIndex = 5;

                                    } else {

                                        $zIndex = 10;
                                    }

                                    $style = "
                                        position:absolute;
                                        left:{$left}px;
                                        top:{$top}px;
                                        z-index:{$zIndex};
                                    ";

                                    if ($width !== null) {
                                        $style .= "
                                            width:{$width}px;
                                        ";
                                    }
                                    if ($height !== null) {

                                        $style .= "
                                            height:{$height}px;
                                        ";
                                    }


                                    if (!$visible) {

                                        $style .= "
                                            display:none;
                                        ";
                                    }

                                    $fieldValue =
                                        $field['text'] ?? '';
                                    $matched = false;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | NOTE ON "address" vs "studentAddress":
                                    |
                                    | "address"        -> ALWAYS school address ($school->address)
                                    | "studentAddress" -> ALWAYS student address ($student->address)
                                    |
                                    | These are deliberately two separate cases so they never collide.
                                    |--------------------------------------------------------------------------
                                    */

                                    switch ($fieldKeyBase) {

                                        case 'name':
                                        case 'studentname':
                                        case 'student_name':

                                            $fieldValue = trim(
                                                ($student->first_name ?? '') .
                                                ' ' .
                                                ($student->last_name ?? '')
                                            );

                                            $matched = true;
                                            break;


                                        case 'schoolname':
                                        case 'school_name':

                                            $fieldValue =
                                                ucwords($school->school_name ?? ($field['text'] ?? ''));

                                            $matched = true;
                                            break;


                                        case 'father':
                                        case 'fathername':
                                        case 'father_name':

                                            $fieldValue =
                                                $student->father_name ?? '-';

                                            $matched = true;
                                            break;


                                        case 'mother':
                                        case 'mothername':
                                        case 'mother_name':

                                            $fieldValue =
                                                $student->mother_name ?? '-';

                                            $matched = true;
                                            break;


                                        case 'class':

                                            $fieldValue =
                                                optional($student->studentClass)->name ?? '-';

                                            $matched = true;
                                            break;


                                        case 'section':

                                            $fieldValue =
                                                optional($student->section) ?? '-';

                                            $matched = true;
                                            break;


                                        case 'adm':
                                        case 'admission':
                                        case 'admission_no':
                                        case 'roll':
                                        case 'rollno':
                                        case 'roll_no':

                                            $fieldValue =
                                                $student->admission_no ?? '-';

                                            $matched = true;
                                            break;


                                        case 'dob':
                                        case 'date_of_birth':
                                        case 'birth':
                                        case 'birthdate':

                                            if (!empty($student->date_of_birth)) {

                                                try {

                                                    $fieldValue = \Carbon\Carbon::parse(
                                                        $student->date_of_birth
                                                    )->format('d-m-Y');

                                                } catch (\Exception $e) {

                                                    $fieldValue = '-';
                                                }

                                            } else {

                                                $fieldValue = '-';
                                            }

                                            $matched = true;
                                            break;


                                        case 'phone':
                                        case 'contact':
                                        case 'contact_no':
                                        case 'contactno':

                                            $fieldValue =
                                                $student->phone ?? '-';

                                            $matched = true;
                                            break;


                                        case 'blood':
                                        case 'bloodgroup':
                                        case 'blood_group':


                                            $fieldValue = sprintf(
                                                'Blood Group: %s  |  Ph: %s',
                                                $student->blood_group ?? '-',
                                                $student->phone ?? '-'
                                            );

                                            $matched = true;
                                            break;


                                        /*
                                        |--------------------------------------------------------------------------
                                        | STUDENT ADDRESS — always $student->address
                                        |--------------------------------------------------------------------------
                                        */

                                        case 'studentaddress':
                                        case 'student_address':

                                            $fieldValue =
                                                $student->address ?? '-';

                                            $matched = true;
                                            break;


                                        /*
                                        |--------------------------------------------------------------------------
                                        | SCHOOL ADDRESS — always $school->address, never the student's
                                        |--------------------------------------------------------------------------
                                        */

                                        case 'address':
                                        case 'schooladdress':
                                        case 'school_address':

                                            $fieldValue =
                                                $school->address ?? '-';

                                            $matched = true;
                                            break;
                                    }




                                    if (!$matched) {

                                        $probe = $fieldKeyBase . ' ' . $fieldText . ' ' . $fieldId;

                                        $wb = function ($needle) use ($probe) {
                                            return (bool) preg_match(
                                                '/(^|[_\s])' . preg_quote($needle, '/') . '([_\s]|$)/',
                                                $probe
                                            );
                                        };

                                        if ($wb('name') && !$wb('schoolname') && !$wb('school')) {

                                            $fieldValue = trim(
                                                ($student->first_name ?? '') .
                                                ' ' .
                                                ($student->last_name ?? '')
                                            );

                                        } elseif ($wb('school')) {

                                            $fieldValue =
                                                $school->school_name ?? ($field['text'] ?? '');

                                        } elseif ($wb('father')) {

                                            $fieldValue =
                                                $student->father_name ?? '-';

                                        } elseif ($wb('mother')) {

                                            $fieldValue =
                                                $student->mother_name ?? '-';

                                        } elseif ($wb('class')) {

                                            $fieldValue =
                                                optional($student->studentClass)->name ?? '-';

                                        } elseif ($wb('section')) {

                                            $fieldValue =
                                                optional($student->section) ?? '-';

                                        } elseif ($wb('admission') || $wb('adm') || $wb('roll')) {

                                            $fieldValue =
                                                $student->admission_no ?? '-';

                                        } elseif ($wb('dob') || $wb('date_of_birth')) {

                                            if (!empty($student->date_of_birth)) {

                                                try {

                                                    $fieldValue = \Carbon\Carbon::parse(
                                                        $student->date_of_birth
                                                    )->format('d-m-Y');

                                                } catch (\Exception $e) {

                                                    $fieldValue = '-';
                                                }

                                            } else {

                                                $fieldValue = '-';
                                            }

                                        } elseif ($wb('phone') || $wb('contact')) {

                                            $fieldValue =
                                                $student->phone ?? '-';

                                        } elseif ($wb('blood')) {

                                            $fieldValue = sprintf(
                                                'Blood Group: %s  |  Ph: %s',
                                                $student->blood_group ?? '-',
                                                $student->phone ?? '-'
                                            );

                                        /*
                                        |--------------------------------------------------------------------------
                                        | Check "studentaddress" BEFORE the generic "address" check below,
                                        | since "student address" (with a space/underscore) DOES satisfy the
                                        | word-boundary regex for "address" too. Order matters here.
                                        |--------------------------------------------------------------------------
                                        */

                                        } elseif (
                                            $wb('studentaddress') ||
                                            (
                                                preg_match('/(^|[_\s])student([_\s])address([_\s]|$)/', $probe)
                                            )
                                        ) {

                                            $fieldValue =
                                                $student->address ?? '-';

                                        } elseif ($wb('address')) {

                                            $fieldValue =
                                                $school->address ?? '-';
                                        }
                                    }




                                    if (
                                        is_string($fieldValue) &&
                                        strpos(
                                            $fieldValue,
                                            '{{'
                                        ) !== false
                                    ) {

                                        $fieldValue = str_replace(
                                            '{{student_name}}',
                                            trim(
                                                ($student->first_name ?? '') .
                                                ' ' .
                                                ($student->last_name ?? '')
                                            ),
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{first_name}}',
                                            $student->first_name ?? '',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{last_name}}',
                                            $student->last_name ?? '',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{father_name}}',
                                            $student->father_name ?? '-',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{mother_name}}',
                                            $student->mother_name ?? '-',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{class}}',
                                            optional(
                                                $student->studentClass
                                            )->name ?? '-',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{section}}',
                                            optional(
                                                $student->section
                                            )->name ?? '-',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{admission_no}}',
                                            $student->admission_no ?? '-',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{date_of_birth}}',
                                            $student->date_of_birth
                                                ? \Carbon\Carbon::parse(
                                                    $student->date_of_birth
                                                )->format('d-m-Y')
                                                : '-',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{phone}}',
                                            $student->phone ?? '-',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{blood_group}}',
                                            $student->blood_group ?? '-',
                                            $fieldValue
                                        );

                                        // {{address}} -> SCHOOL address only
                                        $fieldValue = str_replace(
                                            '{{address}}',
                                            $school->address ?? '-',
                                            $fieldValue
                                        );

                                        // {{student_address}} -> STUDENT address only
                                        $fieldValue = str_replace(
                                            '{{student_address}}',
                                            $student->address ?? '-',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{school_name}}',
                                            $school->school_name ?? 'School Name',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{school_address}}',
                                            $school->address ?? '',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{school_phone}}',
                                            $school->phone ?? '',
                                            $fieldValue
                                        );

                                        $fieldValue = str_replace(
                                            '{{session}}',
                                            $school->session ?? '2026-27',
                                            $fieldValue
                                        );
                                    }

                                @endphp


                                {{-- ================================================= --}}
                                {{-- IMAGE --}}
                                {{-- ================================================= --}}

                                @if($type === 'image')

                                    @php

                                        $src = '';


                                        /*
                                        |--------------------------------------------------------------------------
                                        | IS THIS THE STUDENT PHOTO?
                                        |--------------------------------------------------------------------------
                                        */

                                        $isPhotoField =
                                            $fieldKeyBase === 'photo' ||

                                            preg_match('/(^|[_\s])photo([_\s]|$)/', $fieldKeyBase . ' ' . $fieldId);


                                        /*
                                        |--------------------------------------------------------------------------
                                        | STUDENT PHOTO
                                        |--------------------------------------------------------------------------
                                        */

                                        if ($isPhotoField) {

                                            if (
                                                !empty(
                                                    $student->capturephoto
                                                )
                                            ) {

                                                $src = asset(
                                                    'storage/' .
                                                    ltrim(
                                                        $student->capturephoto,
                                                        '/'
                                                    )
                                                );

                                            } elseif (
                                                !empty(
                                                    $student->photo
                                                )
                                            ) {


                                                $photoPath =
                                                    $student->photo;

                                                $directory =
                                                    pathinfo(
                                                        $photoPath,
                                                        PATHINFO_DIRNAME
                                                    );

                                                $filename =
                                                    pathinfo(
                                                        $photoPath,
                                                        PATHINFO_FILENAME
                                                    );


                                                if (
                                                    $directory === '.'
                                                ) {

                                                    $jpgPath =
                                                        $filename . '.jpg';

                                                } else {

                                                    $jpgPath =
                                                        $directory .
                                                        '/' .
                                                        $filename .
                                                        '.jpg';
                                                }


                                                $src = asset(
                                                    'storage/' .
                                                    ltrim(
                                                        $jpgPath,
                                                        '/'
                                                    )
                                                );
                                            }

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | STATIC IMAGE
                                        |--------------------------------------------------------------------------
                                        */

                                        elseif (
                                            !empty(
                                                $field['src']
                                            )
                                        ) {

                                            $fieldSrc =
                                                $field['src'];


                                            if (
                                                preg_match(
                                                    '/^https?:\/\//',
                                                    $fieldSrc
                                                )
                                            ) {

                                                $src = $fieldSrc;

                                            } else {

                                                $src =
                                                    \Illuminate\Support\Facades\Storage::disk(
                                                        'public'
                                                    )->url(
                                                        ltrim(
                                                            $fieldSrc,
                                                            '/'
                                                        )
                                                    );
                                            }
                                        }

                                    @endphp


                                    @if($src)

                                        <img
                                            src="{{ $src }}"
                                            alt="{{ $key }}"
                                            style="
                                                {{ $style }}

                                                object-fit:contain;

                                                object-position:center;
                                            "
                                        >

                                    @else

                                        <div
                                            style="
                                                {{ $style }}

                                                background:#e0e0e0;

                                                display:flex;

                                                align-items:center;

                                                justify-content:center;

                                                font-size:20px;

                                                color:#999;
                                            "
                                        >
                                            👤
                                        </div>

                                    @endif


                                {{-- ================================================= --}}
                                {{-- SHAPE --}}
                                {{-- ================================================= --}}

                                @elseif($type === 'shape')

                                    @php

                                        $backgroundColor =
                                            $field['backgroundColor']
                                            ?? 'transparent';

                                        $opacity =
                                            isset($field['opacity'])
                                            ? (float) $field['opacity']
                                            : 1;

                                        $borderRadius =
                                            isset($field['borderRadius'])
                                            ? (float) $field['borderRadius']
                                            : 0;


                                        $style .= "

                                            background-color:
                                            {$backgroundColor};

                                            opacity:
                                            {$opacity};

                                            border-radius:
                                            {$borderRadius}px;

                                            box-sizing:border-box;
                                        ";

                                    @endphp


                                    <div style="{{ $style }}"></div>


                                {{-- ================================================= --}}
                                {{-- TEXT --}}
                                {{-- ================================================= --}}

                                @else

                                    @php

                                        if (
                                            isset(
                                                $field['fontSize']
                                            )
                                        ) {

                                            $style .=
                                                'font-size:' .
                                                (float)
                                                $field['fontSize'] .
                                                'px;';
                                        }


                                        if (
                                            !empty(
                                                $field['color']
                                            )
                                        ) {

                                            $style .=
                                                'color:' .
                                                $field['color'] .
                                                ';';
                                        }


                                        if (
                                            !empty(
                                                $field['fontWeight']
                                            )
                                        ) {

                                            $style .=
                                                'font-weight:' .
                                                $field['fontWeight'] .
                                                ';';
                                        }


                                        if (
                                            !empty(
                                                $field['fontFamily']
                                            )
                                        ) {

                                            $style .=
                                                'font-family:' .
                                                $field['fontFamily'] .
                                                ';';
                                        }


                                        if (
                                            !empty(
                                                $field['textAlign']
                                            )
                                        ) {

                                            $style .=
                                                'text-align:' .
                                                $field['textAlign'] .
                                                ';';
                                        }


                                        if (
                                            !empty(
                                                $field['textTransform']
                                            )
                                        ) {

                                            $style .=
                                                'text-transform:' .
                                                $field['textTransform'] .
                                                ';';
                                        }


                                        if (
                                            isset(
                                                $field['lineHeight']
                                            )
                                        ) {

                                            $style .=
                                                'line-height:' .
                                                (float)
                                                $field['lineHeight'] .
                                                ';';
                                        }


                                        if (
                                            isset(
                                                $field['letterSpacing']
                                            )
                                        ) {

                                            $style .=
                                                'letter-spacing:' .
                                                (float)
                                                $field['letterSpacing'] .
                                                'px;';
                                        }



                                        if ($width !== null) {

                                            $style .= "
                                                white-space:pre-wrap;
                                                word-wrap:break-word;
                                                overflow:hidden;
                                            ";

                                        } else {

                                            $maxWidth = max(0, $editorWidth - $left);

                                            $style .= "
                                                max-width:{$maxWidth}px;
                                                white-space:nowrap;
                                                overflow:hidden;
                                                text-overflow:ellipsis;
                                            ";
                                        }

                                    @endphp


                                    <div style="{{ $style }}">
                                        {!! e($fieldValue) !!}
                                    </div>


                                @endif


                            @endforeach


                            {{-- ============================================= --}}
                            {{-- TABLE DATA (independent of $fields loop above) --}}
                            {{-- ============================================= --}}

                            @if($hasTableLayout)

                                @php

                                    $tablePlaceholders = [
                                        '{{student_name}}'    => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                                        '{{first_name}}'      => $student->first_name ?? '',
                                        '{{last_name}}'       => $student->last_name ?? '',
                                        '{{father_name}}'     => $student->father_name ?? '-',
                                        '{{mother_name}}'     => $student->mother_name ?? '-',
                                        '{{class}}'           => optional($student->studentClass)->name ?? '-',
                                        '{{section}}'         => optional($student->section)->name ?? '-',
                                        '{{admission_no}}'    => $student->admission_no ?? '-',
                                        '{{date_of_birth}}'   => $student->date_of_birth
                                                                    ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y')
                                                                    : '-',
                                        '{{phone}}'           => $student->phone ?? '-',
                                        '{{blood_group}}'     => $student->blood_group ?? '-',

                                        // School address only
                                        '{{address}}'         => $school->address ?? '-',
                                        '{{school_address}}'  => $school->address ?? '',

                                        // Student address only
                                        '{{student_address}}' => $student->address ?? '-',

                                        '{{school_name}}'     => $school->school_name ?? 'School Name',
                                        '{{school_phone}}'    => $school->phone ?? '',
                                        '{{session}}'         => $school->session ?? '2026-27',
                                    ];

                                    $tabledataHtml = str_replace(
                                        array_keys($tablePlaceholders),
                                        array_values($tablePlaceholders),
                                        $tabledataHtmlRaw
                                    );

                                @endphp

                                <div
                                    style="
                                        position:absolute;
                                        left:{{ $tableLeft }}px;
                                        top:{{ $tableTop }}px;
                                        width:{{ $tableWidth }}px;
                                        @if($tableHeight > 0) height:{{ $tableHeight }}px; @endif
                                        z-index:10;
                                        overflow:hidden;
                                        box-sizing:border-box;
                                    "
                                >
                                    {!! $tabledataHtml !!}
                                </div>

                            @endif


                        </div>

                    </div>


                @endforeach


            </div>

        @endforeach


    @endif


@else


    <div class="no-students">

        <h3>
            No Students Found
        </h3>

        <p>
            No students match the selected filters.
            Please adjust your filters and try again.
        </p>

    </div>


@endif

</body>

</html>
