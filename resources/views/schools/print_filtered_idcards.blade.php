<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Filtered ID Cards - Print</title>

     <style>
        @php
            $isVertical = strtolower(trim($orientation)) === 'vertical';
        @endphp
        @page {
            size: A4 {{ $isVertical ? 'landscape' : 'portrait' }};
            margin: 0;
        }
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;

            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
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
         .print-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin: 15px auto;
        }

        .print-button {
            padding: 10px 25px;
            border: 0;
            border-radius: 5px;
            background: #147abb;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        @media print {
            .print-buttons {
                display: none;
            }
        }
        .print-info {
            text-align: center;

            margin: 10px auto;

            font-size: 13px;
            color: #666;
        }
        .a4-page {
            width: {{ $isVertical ? '297mm' : '210mm' }};
            height: {{ $isVertical ? '210mm' : '297mm' }};
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

        

        .a4-page.orientation-vertical {
            width: 297mm !important;
            height: 210mm !important;

            margin: 0 !important;
            padding: 14mm 7.5mm !important;

            display: grid !important;

            grid-template-columns: repeat(5, 54mm) !important;
            grid-template-rows: repeat(2, 84mm) !important;

            column-gap: 3mm !important;
            row-gap: 14mm !important;

            align-content: start !important;
            justify-content: start !important;

            overflow: hidden !important;
        }

        .a4-page.orientation-vertical .id-card-container {
            width: 54mm !important;
            height: 84mm !important;

            min-width: 54mm !important;
            max-width: 54mm !important;

            min-height: 84mm !important;
            max-height: 84mm !important;

            position: relative !important;

            overflow: hidden !important;

            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
       

        .a4-page.orientation-horizontal {
            width: 210mm;
            height: 297mm;

            padding: 5mm;

            display: grid;

            grid-template-columns: repeat(2, 98mm);
            grid-template-rows: repeat(5, 55mm);

            column-gap: 3mm;
            row-gap: 3mm;

            align-content: start;
            justify-content: start;

            overflow: hidden;
        }

    
        .a4-page.orientation-horizontal .id-card-container {
            width: 98mm;
            height: 55mm;

            min-width: 98mm;
            max-width: 98mm;

            min-height: 55mm;
            max-height: 55mm;

            position: relative;

            overflow: hidden;

            page-break-inside: avoid;
            break-inside: avoid;
        }

        

        .id-card {
            position: absolute;

            top: 0;
            left: 0;

            overflow: hidden;

            transform-origin: top left;

            page-break-inside: avoid;
            break-inside: avoid;
        }

        

        .no-students {
            padding: 40px;

            text-align: center;

            background: #fff;

            margin: 20px auto;

            border-radius: 5px;

            max-width: 600px;

            box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
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
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
            }

        }

        

        @media print {

            @page {
                size: A4 {{ $isVertical ? 'landscape' : 'portrait' }};
                margin: 0;
            }

            html,
            body {
                width: {{ $isVertical ? '297mm' : '210mm' }};
                height: {{ $isVertical ? '210mm' : '297mm' }};

                margin: 0 !important;
                padding: 0 !important;

                background: #fff;
            }

            .print-button,
            .print-info {
                display: none !important;
            }

          

            .a4-page {
                width: {{ $isVertical ? '297mm' : '210mm' }} !important;
                height: {{ $isVertical ? '210mm' : '297mm' }} !important;

                margin: 0 !important;
                padding: 5mm !important;

                box-shadow: none !important;

                page-break-after: always;
                break-after: page;

                overflow: hidden !important;
            }

            

            .a4-page.orientation-vertical {
                width: 297mm !important;
                height: 210mm !important;

                margin: 0 !important;
                padding: 14mm 7.5mm !important;

                display: grid !important;

                grid-template-columns: repeat(5, 54mm) !important;
                grid-template-rows: repeat(2, 84mm) !important;

                column-gap: 3mm !important;
                row-gap: 14mm !important;

                align-content: start !important;
                justify-content: start !important;

                overflow: hidden !important;
            }

            .a4-page.orientation-vertical .id-card-container {
                width: 54mm !important;
                height: 84mm !important;

                min-width: 54mm !important;
                max-width: 54mm !important;

                min-height: 84mm !important;
                max-height: 84mm !important;

                position: relative !important;

                overflow: hidden !important;

                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

          

            .a4-page.orientation-horizontal {
                width: 210mm !important;
                height: 297mm !important;

                padding: 5mm !important;

                display: grid !important;

                grid-template-columns:
                    98mm 98mm !important;

                grid-template-rows:
                    55mm 55mm 55mm 55mm 55mm !important;

                column-gap: 3mm !important;
                row-gap: 3mm !important;

                align-content: start !important;
                justify-content: start !important;

                overflow: hidden !important;
            }

            .a4-page.orientation-horizontal .id-card-container {
                width: 98mm !important;
                height: 55mm !important;

                min-width: 98mm !important;
                max-width: 98mm !important;

                min-height: 55mm !important;
                max-height: 55mm !important;

                position: relative !important;

                overflow: hidden !important;

                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

           

            .id-card {
                position: absolute !important;

                top: 0 !important;
                left: 0 !important;

                overflow: hidden !important;

                transform-origin: top left !important;

                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

        }
    </style>
</head>

<body>

<div class="print-buttons">

    <button type="button" class="print-button" onclick="window.print()">
        🖨 Print ID Cards
    </button>

    <button type="button" class="print-button" onclick="">
        🖨 Mark All Printed
    </button>

</div>

<div class="print-info">

    Total Cards: {{ $students->count() }}

    | Class: {{ $classFilter ?? 'All' }}

    | Section: {{ $sectionFilter ?? 'All' }}

    | Orientation: {{ ucfirst($orientation) }}

    | Photo Filter: {{ $photoFilter ?? 'All' }}

</div>


@if($students->count() > 0)
    @php
        $orientation = strtolower($orientation ?? 'horizontal');
        if (!in_array($orientation, ['horizontal', 'vertical'])) {
            $orientation = 'horizontal';
        }
        if ($orientation === 'vertical') {
            $cardsPerPage = 10;
            $printWidthMm =  54;
            $printHeightMm = 84;

        } else {
            $cardsPerPage = 10;
            $printWidthMm = 98;
            $printHeightMm = 55;
        }
        if (is_string($layout)) {
            $layout = json_decode($layout, true) ?? [];
        }
        if (!is_array($layout)) {
            $layout = [];
        }
        $editorWidth = (float) ($layout['cardWidth'] ?? 317);
        $editorHeight = (float) ($layout['cardHeight'] ?? 204);
        $fields = $layout['fields'] ?? [];
        if (!is_array($fields)) {
            $fields = [];
        }
        $pxPerMm = 96 / 25.4;
        $printWidthPx = $printWidthMm * $pxPerMm;
        $printHeightPx = $printHeightMm * $pxPerMm;
        $scaleX = $printWidthPx / $editorWidth;
        $scaleY = $printHeightPx / $editorHeight;
        $scale = min($scaleX, $scaleY);
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
        $hasLayout = count($fields) > 0;
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


       

        @foreach($students->chunk($cardsPerPage) as $pageStudents)
            <div class="a4-page orientation-{{ $orientation }}">
                @foreach($pageStudents as $student)
                    <div class="id-card-container" style=" width: {{ $printWidthMm }}mm; height: {{ $printHeightMm }}mm;">
                        <div class="id-card" style="width:{{ $editorWidth }}px; height:{{ $editorHeight }}px; transform:scale({{ $scale }});">
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
                                        z-index:0;">
                                </div>
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
                                    $borderRadius = isset($field['borderRadius'])
                                    ? (int) $field['borderRadius']
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

                                    if ($borderRadius !== null) {

                                        $style .= "
                                            border-radius:{$borderRadius}%;
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


                                       

                                        case 'studentaddress':
                                        case 'student_address':

                                            $fieldValue =
                                                $student->address ?? '-';

                                            $matched = true;
                                            break;



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



                                        $fieldCss = strtolower((string) ($field['css'] ?? ''));
                                        $usesAlign = (bool) preg_match('/text-align\s*:\s*(center|right)/', $fieldCss);

                                        if ($width !== null) {

                                            // explicit width was saved — always use a real box
                                            $style .= "
                                                white-space:pre-wrap;
                                                word-wrap:break-word;
                                                overflow:hidden;
                                            ";

                                        } elseif ($usesAlign) {

                                           

                                            $autoWidth = max(0, $editorWidth - $left - 6);

                                            $style .= "
                                                width:{$autoWidth}px;
                                                white-space:pre-wrap;
                                                word-wrap:break-word;
                                                overflow:hidden;
                                            ";

                                        } else {

                                            // left-aligned text with no width — shrink-wrap is fine here
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

                                    $tablePlaceholderValues = [
                                        'student_name'    => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                                        'first_name'      => $student->first_name ?? '',
                                        'last_name'       => $student->last_name ?? '',
                                        'father_name'     => $student->father_name ?? '-',
                                        'mother_name'     => $student->mother_name ?? '-',
                                        'class'           => optional($student->studentClass)->name ?? '-',
                                        'section'         => optional($student->section)->name ?? '-',
                                        'admission_no'    => $student->admission_no ?? '-',
                                        'date_of_birth'   => $student->date_of_birth
                                                                ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y')
                                                                : '-',
                                        'phone'           => $student->phone ?? '-',
                                        'blood_group'     => $student->blood_group ?? '-',

                                        // School address only
                                        'address'         => $school->address ?? '-',
                                        'school_address'  => $school->address ?? '',

                                        // Student address only
                                        'student_address' => $student->address ?? '-',

                                        'school_name'     => $school->school_name ?? 'School Name',
                                        'school_phone'    => $school->phone ?? '',
                                        'session'         => $school->session ?? '2026-27',
                                    ];

                                    $tabledataHtml = preg_replace_callback(
                                        '/\{\{\s*([A-Za-z0-9_]+)\s*\}\}/',
                                        function ($matches) use ($tablePlaceholderValues) {
                                            $rawKey = strtolower(trim($matches[1]));

                                            $normalizedKey = preg_replace('/[^a-z0-9]+/', '_', $rawKey);
                                            $normalizedKey = trim($normalizedKey, '_');
                                            $normalizedKey = preg_replace('/_+/', '_', $normalizedKey);

                                            if (isset($tablePlaceholderValues[$normalizedKey])) {
                                                return (string) $tablePlaceholderValues[$normalizedKey];
                                            }

                                            $camelCaseKey = strtolower(
                                                preg_replace('/(?<!^)([A-Z])/', '_$1', $rawKey)
                                            );
                                            $camelCaseKey = preg_replace('/[^a-z0-9]+/', '_', $camelCaseKey);
                                            $camelCaseKey = trim($camelCaseKey, '_');
                                            $camelCaseKey = preg_replace('/_+/', '_', $camelCaseKey);

                                            if (isset($tablePlaceholderValues[$camelCaseKey])) {
                                                return (string) $tablePlaceholderValues[$camelCaseKey];
                                            }

                                            return $matches[0];
                                        },
                                        $tabledataHtmlRaw
                                    );

                                    $containsTablePlaceholders = (bool) preg_match('/\{\{\s*[A-Za-z0-9_]+\s*\}\}/', $tabledataHtmlRaw);

                                    if (!$containsTablePlaceholders && preg_match('/<table\b/i', $tabledataHtml)) {

                                        $normalizeTableText = function ($value) {
                                            $value = preg_replace('/<[^>]+>/', ' ', (string) $value);
                                            $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                            $value = preg_replace('/\s+/', ' ', $value);
                                            $value = trim($value);
                                            $value = strtolower($value);
                                            $value = preg_replace('/[^a-z0-9]+/', ' ', $value);
                                            $value = trim($value);

                                            return $value;
                                        };

                                        $labelMap = [
                                            'student name' => 'student_name',
                                            'name' => 'student_name',
                                            'father' => 'father_name',
                                            'father name' => 'father_name',
                                            'mother' => 'mother_name',
                                            'mother name' => 'mother_name',
                                            'class' => 'class',
                                            'section' => 'section',
                                            'admission' => 'admission_no',
                                            'admission no' => 'admission_no',
                                            'roll' => 'admission_no',
                                            'roll no' => 'admission_no',
                                            'dob' => 'date_of_birth',
                                            'date of birth' => 'date_of_birth',
                                            'phone' => 'phone',
                                            'contact' => 'phone',
                                            'contact no' => 'phone',
                                            'blood group' => 'blood_group',
                                            'student address' => 'student_address',
                                            'school address' => 'school_address',
                                            'address' => 'student_address',
                                            'school name' => 'school_name',
                                        ];

                                        $dom = new DOMDocument();
                                        libxml_use_internal_errors(true);
                                        $dom->loadHTML(
                                            '<?xml encoding="UTF-8">' . $tabledataHtml,
                                            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
                                        );

                                        $table = $dom->getElementsByTagName('table')->item(0);

                                        if ($table) {
                                            $rows = $table->getElementsByTagName('tr');

                                            foreach ($rows as $row) {
                                                $cells = $row->getElementsByTagName('td');
                                                $cellCount = $cells->length;

                                                if ($cellCount === 1) {
                                                    $cells->item(0)->nodeValue = $tablePlaceholderValues['student_name'] ?? '-';
                                                    continue;
                                                }

                                                for ($i = 0; $i < $cellCount; $i++) {
                                                    $cell = $cells->item($i);
                                                    if (!$cell) continue;

                                                    $cellText = $normalizeTableText($cell->textContent ?? '');
                                                    if (!isset($labelMap[$cellText])) continue;

                                                    $mappedKey = $labelMap[$cellText];
                                                    $replacement = $tablePlaceholderValues[$mappedKey] ?? '';

                                                    for ($j = $i + 1; $j < $cellCount; $j++) {
                                                        $valueCell = $cells->item($j);
                                                        if (!$valueCell) continue;

                                                        $valueText = $normalizeTableText($valueCell->textContent ?? '');

                                                        if ($valueText === '' || in_array($valueText, ['colon', 'dash', 'ndash', 'mdash'], true)) {
                                                            continue;
                                                        }

                                                        if (in_array($valueText, ['student', 'school', 'father', 'mother', 'class', 'section', 'admission', 'dob', 'phone', 'address'], true)) {
                                                            continue;
                                                        }

                                                        $valueCell->nodeValue = $replacement;
                                                        break;
                                                    }
                                                }
                                            }

                                            $tabledataHtml = $dom->saveHTML($table);
                                        }
                                    }

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
