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

        html, body {
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

        .a4-page {
            width: 210mm;
            height: 297mm;
            margin: 5mm auto;
            display: grid;
            column-gap: 3mm;
            row-gap: 3mm;
            page-break-after: always;
            break-after: page;
            overflow: hidden;
            padding: 5mm;
            background: #fff;
        }

        /* Horizontal: 2 columns x 5 rows = 10 cards */
        .a4-page.orientation-horizontal {
            grid-template-columns: repeat(2, 98mm);
            grid-template-rows: repeat(5, 55mm);
        }

        /* Vertical: 3 columns x 3 rows = 9 cards */
        .a4-page.orientation-vertical {
            grid-template-columns: repeat(3, 54mm);
            grid-template-rows: repeat(3, 84mm);
        }

        .id-card-container {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
            page-break-inside: avoid;
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
        }

        .id-card {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
            transform-origin: top left;
        }

        @media print {
            html, body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
                background: #fff;
            }

            .print-button, .print-info {
                display: none !important;
            }

            .a4-page {
                margin: 0;
                padding: 5mm;
                page-break-after: always;
                break-after: page;
            }

            .id-card {
                page-break-inside: avoid;
            }
        }

        @media screen {
            .a4-page {
                box-shadow: 0 0 8px rgba(0,0,0,.15);
            }
        }

        .no-students {
            padding: 40px;
            text-align: center;
            background: #fff;
            margin: 20px auto;
            border-radius: 5px;
            max-width: 600px;
            box-shadow: 0 0 8px rgba(0,0,0,.15);
        }

        .no-students h3 {
            color: #d9534f;
            margin: 0 0 10px 0;
        }

        .no-students p {
            color: #666;
            margin: 0;
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
        // Use exact same dimensions as editor
        $HORIZONTAL_CARD_W = 317;
        $HORIZONTAL_CARD_H = 204;
        $VERTICAL_CARD_W = 204;
        $VERTICAL_CARD_H = 317;
        
        if ($orientation === 'vertical') {
            $cardsPerPage = 9;
            $displayW = 54;   // mm
            $displayH = 84;   // mm
            $cardW = $VERTICAL_CARD_W;
            $cardH = $VERTICAL_CARD_H;
        } else {
            $cardsPerPage = 10;
            $displayW = 98;   // mm
            $displayH = 55;   // mm
            $cardW = $HORIZONTAL_CARD_W;
            $cardH = $HORIZONTAL_CARD_H;
        }
        
        // Calculate scale to fit in display area (convert mm to px, assuming 96 DPI)
        $dpiToMM = 96 / 25.4;
        $displayWPx = $displayW * $dpiToMM;
        $displayHPx = $displayH * $dpiToMM;
        
        $scaleW = $displayWPx / max($cardW, 1);
        $scaleH = $displayHPx / max($cardH, 1);
        $scale = min($scaleW, $scaleH, 1);
        
        // Get fields - ensure it's an array
        $fields = $layout['fields'] ?? [];
        if (!is_array($fields)) {
            $fields = [];
        }
        
        // Debug: Check if we have fields
        // If no fields found, provide detailed info in comment
        $hasLayout = count($fields) > 0;
        
        // Get background
        if (!empty($design->background)) {
            $bgUrl = asset('storage/' . $design->background);
        } elseif ($sample) {
            $bgUrl = asset('storage/' . $sample->file_path);
        } else {
            $bgUrl = '';
        }
    @endphp

    @if(!$hasLayout)
    <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 5px; padding: 15px; margin: 20px auto; max-width: 600px;">
        <h4 style="color: #856404; margin-top: 0;">⚠ No Card Layout Found</h4>
        <p style="color: #856404; margin: 5px 0;">
            School: {{ $school->school_name ?? 'N/A' }}<br>
            Orientation: {{ ucfirst($orientation) }}<br>
            Design Record: {{ $design ? 'Found' : 'Not Found' }}<br>
            Layout Fields Count: {{ count($fields) }}
        </p>
        <p style="color: #856404; font-size: 12px; margin: 5px 0;">
            Please design an ID card in the <a href="{{ route('idcard.editor', ['orientation' => $orientation]) }}" target="_blank">ID Card Editor</a> first.
        </p>
    </div>
    @else

    @foreach($students->chunk($cardsPerPage) as $pageStudents)

        <div class="a4-page orientation-{{ $orientation }}">

            @foreach($pageStudents as $student)

                <div class="id-card-container">
                    <div class="id-card" style="
                        width:{{ $cardW }}px;
                        height:{{ $cardH }}px;
                        transform:scale({{ $scale }});
                        transform-origin:top left;
                    ">

                        {{-- BACKGROUND --}}
                        @if($bgUrl)
                            <div style="
                                position:absolute;
                                top:0;
                                left:0;
                                width:100%;
                                height:100%;
                                background-image:url('{{ $bgUrl }}');
                                background-size:100% 100%;
                                background-repeat:no-repeat;
                                z-index:0;
                            "></div>
                        @else
                            <div style="
                                position:absolute;
                                top:0;
                                left:0;
                                width:100%;
                                height:100%;
                                background:#f5f5f5;
                                z-index:0;
                            "></div>
                        @endif

                        {{-- RENDER LAYOUT FIELDS --}}
                        @foreach($fields as $key => $field)

                            @php
                                $type = $field['type'] ?? 'text';
                                $left = isset($field['x']) ? (float)$field['x'] : 0;
                                $top = isset($field['y']) ? (float)$field['y'] : 0;
                                $width = isset($field['width']) ? (float)$field['width'] : null;
                                $height = isset($field['height']) ? (float)$field['height'] : null;
                                $visible = $field['visible'] ?? true;
                                $zIndex = $type === 'shape' ? 1 : 10;

                                // Build style string with proper positioning
                                $style = "position:absolute;left:{$left}px;top:{$top}px;z-index:{$zIndex};";

                                if ($width) {
                                    $style .= "width:{$width}px;";
                                }
                                if ($height) {
                                    $style .= "height:{$height}px;";
                                }
                                if (!$visible) {
                                    $style .= "display:none;";
                                }


                                // Determine if this field should show dynamic student data
                                $fieldKey = strtolower($key);
                                $fieldText = strtolower($field['text'] ?? '');
                                $fieldId = strtolower($field['id'] ?? '');
                                
                                // Initialize field value with original text
                                $fieldValue = $field['text'] ?? '';
                                $isDynamicField = false;
                                
                                // Detect dynamic fields based on key, text content, or id
                                if (
                                    strpos($fieldKey, 'name') !== false ||
                                    strpos($fieldText, 'name') !== false ||
                                    strpos($fieldId, 'name') !== false ||
                                    in_array($fieldKey, ['student_name', 'name', 'fullname', 'full_name'])
                                ) {
                                    $fieldValue = $student->first_name . ' ' . $student->last_name;
                                    $isDynamicField = true;
                                } 
                                elseif (
                                    strpos($fieldKey, 'father') !== false ||
                                    strpos($fieldText, 'father') !== false ||
                                    strpos($fieldId, 'father') !== false
                                ) {
                                    $fieldValue = $student->father_name ?? '-';
                                    $isDynamicField = true;
                                }
                                elseif (
                                    strpos($fieldKey, 'class') !== false ||
                                    strpos($fieldText, 'class') !== false ||
                                    strpos($fieldId, 'class') !== false
                                ) {
                                    $fieldValue = optional($student->studentClass)->name ?? '-';
                                    $isDynamicField = true;
                                }
                                elseif (
                                    strpos($fieldKey, 'section') !== false ||
                                    strpos($fieldText, 'section') !== false ||
                                    strpos($fieldId, 'section') !== false
                                ) {
                                    $fieldValue = optional($student->section)->name ?? '-';
                                    $isDynamicField = true;
                                }
                                elseif (
                                    strpos($fieldKey, 'admission') !== false ||
                                    strpos($fieldText, 'admission') !== false ||
                                    strpos($fieldId, 'admission') !== false ||
                                    strpos($fieldKey, 'roll') !== false ||
                                    strpos($fieldText, 'roll') !== false
                                ) {
                                    $fieldValue = $student->admission_no ?? '-';
                                    $isDynamicField = true;
                                }
                                elseif (
                                    strpos($fieldKey, 'dob') !== false ||
                                    strpos($fieldKey, 'birth') !== false ||
                                    strpos($fieldText, 'dob') !== false ||
                                    strpos($fieldText, 'birth') !== false
                                ) {
                                    $fieldValue = $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y') : '-';
                                    $isDynamicField = true;
                                }
                                elseif (
                                    strpos($fieldKey, 'phone') !== false ||
                                    strpos($fieldText, 'phone') !== false ||
                                    strpos($fieldKey, 'contact') !== false
                                ) {
                                    $fieldValue = $student->phone ?? '-';
                                    $isDynamicField = true;
                                }
                                elseif (
                                    strpos($fieldKey, 'address') !== false ||
                                    strpos($fieldText, 'address') !== false
                                ) {
                                    $fieldValue = $student->address ?? '-';
                                    $isDynamicField = true;
                                }
                                
                                // Also check for placeholder syntax
                                if (strpos($fieldValue, '{{') !== false) {
                                    $fieldValue = str_replace('{{student_name}}', $student->first_name . ' ' . $student->last_name, $fieldValue);
                                    $fieldValue = str_replace('{{first_name}}', $student->first_name, $fieldValue);
                                    $fieldValue = str_replace('{{last_name}}', $student->last_name, $fieldValue);
                                    $fieldValue = str_replace('{{father_name}}', $student->father_name ?? '-', $fieldValue);
                                    $fieldValue = str_replace('{{class}}', optional($student->studentClass)->name ?? '-', $fieldValue);
                                    $fieldValue = str_replace('{{section}}', optional($student->section)->name ?? '-', $fieldValue);
                                    $fieldValue = str_replace('{{admission_no}}', $student->admission_no ?? '-', $fieldValue);
                                    $fieldValue = str_replace('{{date_of_birth}}', $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y') : '-', $fieldValue);
                                    $fieldValue = str_replace('{{phone}}', $student->phone ?? '-', $fieldValue);
                                    $fieldValue = str_replace('{{address}}', $student->address ?? '-', $fieldValue);
                                    $fieldValue = str_replace('{{school_name}}', $school->school_name ?? 'School Name', $fieldValue);
                                    $fieldValue = str_replace('{{school_address}}', $school->address ?? '', $fieldValue);
                                    $fieldValue = str_replace('{{school_phone}}', $school->phone ?? '', $fieldValue);
                                    $fieldValue = str_replace('{{session}}', $school->session ?? '2026-27', $fieldValue);
                                    $isDynamicField = true;
                                }
                            @endphp

                            {{-- IMAGE FIELD --}}
                            @if($type === 'image')

                                @php
                                    $src = '';
                                    
                                    // Check if this is a student photo field
                                    $isPhotoField = (
                                        strpos($fieldKey, 'photo') !== false ||
                                        strpos($fieldText, 'photo') !== false ||
                                        strpos($fieldId, 'photo') !== false ||
                                        strpos($fieldKey, 'image') !== false && strpos($fieldKey, 'student') !== false
                                    );
                                    
                                    if ($isPhotoField) {
                                        // Show student photo
                                        if ($student->capturephoto) {
                                            $src = asset('storage/' . $student->capturephoto);
                                        } elseif ($student->photo) {
                                            $src = asset('storage/' . $student->photo);
                                        }
                                    } elseif (!empty($field['src'])) {
                                        // Show static image from field
                                        if (preg_match('/^https?:\/\//', $field['src'])) {
                                            $src = $field['src'];
                                        } else {
                                            $src = Storage::disk('public')->url($field['src']);
                                        }
                                    }
                                @endphp

                                @if($src)
                                    <img src="{{ $src }}" alt="{{ $key }}"
                                        style="{{ $style }}object-fit:contain;">
                                @else
                                    <div style="{{ $style }}background:#e0e0e0;display:flex;align-items:center;justify-content:center;font-size:20px;color:#999;">
                                        👤
                                    </div>
                                @endif

                            {{-- SHAPE FIELD --}}
                            @elseif($type === 'shape')

                                @php
                                    $backgroundColor = $field['backgroundColor'] ?? 'transparent';
                                    $opacity = isset($field['opacity']) ? (float)$field['opacity'] : 1;
                                    $borderRadius = isset($field['borderRadius']) ? (int)$field['borderRadius'] : 0;
                                    
                                    $style .= "
                                        background-color:{$backgroundColor};
                                        opacity:{$opacity};
                                        border-radius:{$borderRadius}px;
                                        box-sizing:border-box;
                                    ";
                                @endphp

                                <div style="{{ $style }}"></div>

                            {{-- TEXT FIELD --}}
                            @else

                                @php
                                    if (isset($field['fontSize'])) {
                                        $style .= 'font-size:' . (int)$field['fontSize'] . 'px;';
                                    }
                                    if (!empty($field['color'])) {
                                        $style .= 'color:' . $field['color'] . ';';
                                    }
                                    if (!empty($field['fontWeight'])) {
                                        $style .= 'font-weight:' . $field['fontWeight'] . ';';
                                    }
                                    if (!empty($field['fontFamily'])) {
                                        $style .= 'font-family:' . $field['fontFamily'] . ';';
                                    }
                                    if (!empty($field['textAlign'])) {
                                        $style .= 'text-align:' . $field['textAlign'] . ';';
                                    }
                                    if (!empty($field['textTransform'])) {
                                        $style .= 'text-transform:' . $field['textTransform'] . ';';
                                    }
                                    $style .= 'white-space:pre-wrap;word-wrap:break-word;overflow:hidden;';
                                @endphp

                                <div style="{{ $style }}">
                                    {!! e($fieldValue) !!}
                                </div>

                            @endif

                        @endforeach

                    </div>
                </div>

            @endforeach

        </div>

    @endforeach
    @endif

@else

    <div class="no-students">
        <h3>No Students Found</h3>
        <p>No students match the selected filters. Please adjust your filters and try again.</p>
    </div>

@endif

</body>

</html>
