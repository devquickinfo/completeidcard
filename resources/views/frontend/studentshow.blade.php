@extends('frontend.layout.applayout')
@section('title', 'Student Details')
@section('content')
@php
    $defaultOrientation = \App\Models\Mainidcard::where(
        'school_id',
        auth()->user()->school_id ?? session('viewing_school')
    )->latest('id')->value('orientation') ?? 'vertical';
@endphp
<style>
    /*.student-photo-wrapper {
        position: relative;
        width: 200px;
        height: 200px;
        margin: 0 auto;
    }

    .student-photo-wrapper .student-photo {
        width: 200px;
        height: 200px;
        object-fit: cover;
        display: block;
    }

    .photo-hover-options {
        position: absolute;
        top: 0;
        left: 0;
        width: 200px;
        height: 200px;

        background: rgba(0, 0, 0, 0.55);
        border-radius: 50%;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;

        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .student-photo-wrapper:hover .photo-hover-options {
        opacity: 1;
    }

    .photo-hover-options .btn {
        min-width: 125px;
    }*/
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-body box-profile">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            @if($student->capturephoto || $student->photo)
                            @php
                            $studentPhoto = $student->capturephoto
                            ? $student->capturephoto
                            : $student->photo;
                             
                            $studentPhoto = pathinfo($studentPhoto, PATHINFO_DIRNAME) . '/' .
                                             pathinfo($studentPhoto, PATHINFO_FILENAME) . '.jpg';
                               
                                if (pathinfo($studentPhoto, PATHINFO_DIRNAME) === '.') {
                                    $studentPhoto = pathinfo($studentPhoto, PATHINFO_FILENAME) . '.jpg';
                                }
                            $studentPhotoUrl = asset('storage/' . $studentPhoto);
                            @endphp
                            <div class="student-photo-wrapper">
                                <img src="{{ $studentPhotoUrl }}" alt="Student Photo"
                                    class="profile-user-img img-fluid img-circle student-photo"
                                    style="width: 180px; height: 180px; object-fit: cover;">
                                <div class="photo-hover-options mt-2">
                                    <a href="{{ $studentPhotoUrl }}" class="btn btn-sm btn-light" data-toggle="modal"
                                        data-target="#viewPhotoModal">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Image
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary capture-student-btn"
                                        data-toggle="modal" data-target="#photoModal"
                                        data-student-id="{{ $student->id }}">
                                        <i class="fas fa-camera mr-1"></i>
                                        Change Image
                                    </a>
                                </div>
                            </div>
                            @else
                            <a href="javascript:void(0);" class="capture-student-btn d-inline-flex align-items-center justify-content-center
                                            bg-light border rounded-circle text-decoration-none" data-toggle="modal"
                                data-target="#photoModal" data-student-id="{{ $student->id }}"
                                style="width:180px;height:180px;">
                                <i class="fas fa-user fa-4x text-muted"></i>
                            </a>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <h2 class="font-weight-bold mb-1">
                                {{ $student->first_name }}
                                {{ $student->last_name }}
                            </h2>
                            <p class="text-muted mb-2">
                                <i class="fas fa-id-card mr-1"></i>
                                Admission No:
                                <strong>
                                    {{ $student->admission_no ?? 'N/A' }}
                                </strong>
                            </p>
                            <p class="mb-2">
                                <span class="badge badge-primary px-3 py-2">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    {{ $student->studentClass->name ?? 'N/A' }}
                                </span>

                                <span class="badge badge-info px-3 py-2 ml-1">
                                    Section:
                                    {{ $student->section ?? 'N/A' }}
                                </span>
                            </p>
                            @if($student->idcardprinted == 'yes')
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check-circle mr-1"></i>
                                ID Card Printed
                            </span>
                            @else
                            <span class="badge badge-danger px-3 py-2">
                                <i class="fas fa-times-circle mr-1"></i>
                                ID Card Not Printed
                            </span>

                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="card card-info">
                                <div class="card-header d-flex align-items-center">
                                    <h3 class="card-title mb-0">Live ID Card Preview</h3>

                                </div>
                                <div class="card-body p-2" style="overflow-x:auto;">
                                    <ul class="nav nav-tabs mb-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link {{ $defaultOrientation === 'vertical' ? 'active' : '' }}" id="student-vertical-tab" data-toggle="tab"
                                                href="#student-vertical-card" role="tab">
                                                <i class="fas fa-mobile-alt mr-1"></i>
                                                Vertical
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ $defaultOrientation === 'horizontal' ? 'active' : '' }}" id="student-horizontal-tab" data-toggle="tab"
                                                href="#student-horizontal-card" role="tab">
                                                <i class="fas fa-mobile-alt fa-rotate-90 mr-1"></i>
                                                Horizontal
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade {{ $defaultOrientation === 'vertical' ? 'show active' : '' }}" id="student-vertical-card"
                                            role="tabpanel">

                                            @if($verticalDesign &&
                                            is_array($verticalDesign->layout))

                                            @php

                                            $layout = $verticalDesign->layout;

                                            $cardWidth = (int) (
                                            $verticalDesign->card_width
                                            ?? ($layout['cardWidth'] ?? 204)
                                            );

                                            $cardHeight = (int) (
                                            $verticalDesign->card_height
                                            ?? ($layout['cardHeight'] ?? 317)
                                            );

                                            $previewWidth = 204;
                                            $previewHeight = 317;

                                            $scale = min(
                                            $previewWidth / max($cardWidth, 1),
                                            $previewHeight / max($cardHeight, 1)
                                            );

                                            $background = $verticalDesign->background
                                            ?: ($verticalSample->file_path ?? null);

                                            $backgroundUrl = $background
                                            ? (preg_match('/^https?:\\/\\//', $background)
                                                ? $background
                                                : asset('storage/' . $background))
                                            : null;

                                            $fields = $layout['fields'] ?? [];

                                            $tabledataHtmlRaw = $layout['tabledata'] ?? '';
                                            $tablePos = $layout['tablePosition'] ?? [];

                                            $tableLeft = isset($tablePos['left']) ? (float) $tablePos['left'] : 30;
                                            $tableTop = isset($tablePos['top']) ? (float) $tablePos['top'] : 120;
                                            $tableWidth = isset($tablePos['width']) ? (float) $tablePos['width'] : max(120, $cardWidth - 60);
                                            $tableHeight = isset($tablePos['height']) ? (float) $tablePos['height'] : 0;

                                            $hasTableData = is_string($tabledataHtmlRaw) && trim(strip_tags($tabledataHtmlRaw)) !== '';

                                            $tablePlaceholderValues = [
                                                'student_name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                                                'first_name' => $student->first_name ?? '',
                                                'last_name' => $student->last_name ?? '',
                                                'father_name' => $student->father_name ?? '-',
                                                'mother_name' => $student->mother_name ?? '-',
                                                'class' => optional($student->studentClass)->name ?? '-',
                                                'section' => optional($student->section)->name ?? '-',
                                                'admission_no' => $student->admission_no ?? '-',
                                                'date_of_birth' => $student->date_of_birth ? $student->date_of_birth->format('d-M-Y') : '-',
                                                'phone' => $student->phone ?? '-',
                                                'blood_group' => $student->blood_group ?? '-',
                                                'address' => $school->address ?? '-',
                                                'school_address' => $school->address ?? '',
                                                'student_address' => $student->address ?? '-',
                                                'school_name' => $school->school_name ?? 'School Name',
                                                'school_phone' => $school->phone ?? '',
                                                'session' => $school->session ?? '2026-27',
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
                                                    'address' => 'school_address',
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
                                                    $cells = $table->getElementsByTagName('td');
                                                    $cellCount = $cells->length;

                                                    if ($cellCount === 1) {
                                                        $cells->item(0)->nodeValue = $tablePlaceholderValues['student_name'] ?? '-';
                                                    } else {
                                                        for ($i = 0; $i < $cellCount; $i++) {
                                                            $cell = $cells->item($i);

                                                            if (!$cell) {
                                                                continue;
                                                            }

                                                            $cellText = $normalizeTableText($cell->textContent ?? '');

                                                            if (!isset($labelMap[$cellText])) {
                                                                continue;
                                                            }

                                                            $mappedKey = $labelMap[$cellText];
                                                            $replacement = $tablePlaceholderValues[$mappedKey] ?? '';

                                                            for ($j = $i + 1; $j < $cellCount; $j++) {
                                                                $valueCell = $cells->item($j);

                                                                if (!$valueCell) {
                                                                    continue;
                                                                }

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

                                            uksort($fields, function ($first, $second) use ($fields)
                                            {

                                            $firstShape =
                                            ($fields[$first]['type'] ?? '') === 'shape';

                                            $secondShape =
                                            ($fields[$second]['type'] ?? '') === 'shape';

                                            return $secondShape <=> $firstShape;
                                                });

                                                @endphp


                                                <div class="id-card-preview" style="
                                                    width:{{ $previewWidth }}px;
                                                    height:{{ $previewHeight }}px;
                                                    position:relative;
                                                    margin:auto;
                                                ">

                                                    <div style="
                                                    position:absolute;
                                                    left:50%;
                                                    top:50%;
                                                    width:{{ $cardWidth }}px;
                                                    height:{{ $cardHeight }}px;

                                                    transform:
                                                        translate(-50%, -50%)
                                                        scale({{ $scale }});

                                                    transform-origin:center;

                                                    background:
                                                        {{ $backgroundUrl
                                                            ? " url('" . $backgroundUrl . "')" : '#fff' }} center /
                                                        100% 100% no-repeat; overflow:hidden; ">


                                                    @foreach($fields as $key => $field)

                                                        @php

                                                            $type = $field['type'] ?? 'text';

                                                            $fieldType = strtolower(
                                                                str_replace(
                                                                    [' ', '-'],
                                                                    '_',
                                                                    $field['fieldType']
                                                                    ?? $field['field_type']
                                                                    ?? $key
                                                                )
                                                            );

                                                            $fieldLabel = strtolower(
                                                                $field['label']
                                                                ?? $field['text']
                                                                ?? ''
                                                            );


                                                            /*
                                                            |--------------------------------------------------------------------------
                                                            | Detect field type
                                                            |--------------------------------------------------------------------------
                                                            */

                                                            if (
                                                                str_contains($fieldType, 'father') ||
                                                                str_contains($fieldLabel, 'father')
                                                            ) {
                                                                $fieldType = 'father_name';
                                                            }

                                                            if (
                                                                $fieldType === 'adm' ||
                                                                str_contains($fieldType, 'admission') ||
                                                                str_contains($fieldLabel, 'admission') ||
                                                                str_contains($fieldLabel, 'adm no') ||
                                                                str_contains($fieldLabel, 'roll no')
                                                            ) {
                                                                $fieldType = 'admission_no';
                                                            }


                                                            /*
                                                            |--------------------------------------------------------------------------
                                                            | Student value
                                                            |--------------------------------------------------------------------------
                                                            */

                                                            $value = match ($fieldType) {

                                                                'student_name',
                                                                'name'
                                                                    => trim(
                                                                        ($student->first_name ?? '') .
                                                                        ' ' .
                                                                        ($student->last_name ?? '')
                                                                    ),

                                                                'first_name'
                                                                    => $student->first_name ?? '',

                                                                'last_name'
                                                                    => $student->last_name ?? '',

                                                                'father_name'
                                                                    => $student->father_name ?? '',

                                                                'admission_no'
                                                                    => $student->admission_no ?? '',

                                                                'class' => 'Class ' . trim(
                                                                    ($student->studentClass->name ?? '') .
                                                                    ' - ' .
                                                                    ($student->section ?? '')
                                                                ),

                                                                'dob',
                                                                'date_of_birth'
                                                                    => $student->date_of_birth
                                                                        ? $student->date_of_birth->format('d-M-Y')
                                                                        : '',

                                                                'gender'
                                                                    => $student->gender ?? '',

                                                                'blood_group'
                                                                    => $student->blood_group ?? '',

                                                                'phone'
                                                                    => $student->phone ?? '',

                                                                'school_name'
                                                                    => $school->school_name ?? '',

                                                                'principal_name'
                                                                    => $school->principal_name ?? '',

                                                                default
                                                                    => $field['text'] ?? '',
                                                            };


                                                            $left = (int)($field['x'] ?? 0);
                                                            $top = (int)($field['y'] ?? 0);

                                                            $width = isset($field['width'])
                                                                ? 'width:' . (int)$field['width'] . 'px;'
                                                                : '';

                                                            $height =isset($field['height'])
                                                                ? 'height:' . (int)$field['height'] . 'px;'
                                                                : '';

                                                            $zIndex = $type === 'shape'
                                                                ? 1
                                                                : 10;
                                                            $borderRadius = isset($field['borderRadius'])
                                                                ? 'border-radius:' .(int) $field['borderRadius'] . '%;'
                                                                : null;

                                                            $style = " position:absolute; left:{$left}px; top:{$top}px;
                                                        z-index:{$zIndex}; {$width} {$height} {$borderRadius} ";

                                                            if (!($field['visible'] ?? true)) {
                                                                $style .= 'display:none;';
                                                            }
                                                      
                                                        @endphp


                                                        {{-- IMAGE --}}
                                                        @if($type === 'image')

                                                            @php
                                                                $photo = pathinfo($student->photo, PATHINFO_DIRNAME) . '/' .
                                                                             pathinfo($student->photo, PATHINFO_FILENAME) . '.jpg';
                                                               
                                                                if (pathinfo($student->photo, PATHINFO_DIRNAME) === '.') {
                                                                    $photo = pathinfo($student->photo, PATHINFO_FILENAME) . '.jpg';
                                                                }


                                                                $photoPath = collect([
                                                                    $student->capturephoto,
                                                                    $photo
                                                                ])->first(function ($path) {

                                                                    return $path &&
                                                                        Storage::disk('public')
                                                                            ->exists($path);

                                                                });


                                                                $isStudentPhoto =
                                                                    in_array(
                                                                        $fieldType,
                                                                        ['photo', 'student_photo']
                                                                    )
                                                                    ||
                                                                    str_contains(
                                                                        $fieldType,
                                                                        'photo'
                                                                    )
                                                                    ||
                                                                    str_contains(
                                                                        $fieldLabel,
                                                                        'photo'
                                                                    );


                                                                $imagePath = $isStudentPhoto
                                                                    ? $photoPath
                                                                    : ($field['src'] ?? null);


                                                                $imageUrl = null;

                                                                if ($imagePath) {

                                                                    $imageUrl = preg_match(
                                                                        '/^https?:\/\//',
                                                                        $imagePath
                                                                    )
                                                                        ? $imagePath
                                                                        : asset(
                                                                            'storage/' . $imagePath
                                                                        );

                                                                }

                                                            @endphp


                                                            @if($imageUrl)

                                                                <img
                                                                    src=" {{ $imageUrl }}" alt="{{ $fieldType }}"
                                                        style="{{ $style }} object-fit:contain;">

                                                        @endif


                                                        {{-- SHAPE --}}
                                                        @elseif($type === 'shape')

                                                        <div style="
                                                                {{ $style }}
                                                                background-color:{{ $field['backgroundColor'] ?? 'transparent' }};
                                                                opacity:{{ $field['opacity'] ?? 1 }};
                                                                border-radius:{{ (int)($field['borderRadius'] ?? 0) }}px;
                                                                box-sizing:border-box;
                                                            "></div>


                                                        {{-- TEXT --}}
                                                        @else
                                                        <div style="
                                                                {{ $style }}
                                                                font-size:{{ (int)($field['fontSize'] ?? 14) }}px;
                                                                color:{{ $field['color'] ?? '#111' }};
                                                                font-weight:{{ $field['fontWeight'] ?? 'normal' }};
                                                            ">
                                                            {{ $value }}
                                                        </div>
                                                        @endif
                                                        @endforeach

                                                        @if($hasTableData)
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
                                                @elseif($verticalSample)
                                                <div class="text-center">

                                                    <img src="{{ asset('storage/' . $verticalSample->file_path) }}"
                                                        alt="Vertical ID Card" style="
                                                        width:204px;
                                                        height:317px;
                                                        object-fit:fill;
                                                    " class="img-thumbnail">
                                                </div>
                                                @else
                                                <div class="alert alert-warning">
                                                    No vertical ID card selected.
                                                </div>
                                                @endif
                                        </div>
                                        <div class="tab-pane fade {{ $defaultOrientation === 'horizontal' ? 'show active' : '' }}" id="student-horizontal-card" role="tabpanel">
                                            @if($horizontalDesign &&
                                            is_array($horizontalDesign->layout))
                                            @php
                                            $layout = $horizontalDesign->layout;
                                            $cardWidth = (int) (
                                            $horizontalDesign->card_width
                                            ?? ($layout['cardWidth'] ?? 317)
                                            );
                                            $cardHeight = (int) (
                                            $horizontalDesign->card_height
                                            ?? ($layout['cardHeight'] ?? 204)
                                            );
                                            $previewWidth = 317;
                                            $previewHeight = 204;
                                            $scale = min(
                                            $previewWidth / max($cardWidth, 1),
                                            $previewHeight / max($cardHeight, 1)
                                            );
                                            $background = $horizontalDesign->background
                                            ?: ($horizontalSample->file_path ?? null);
                                            $backgroundUrl = $background
                                            ? (preg_match('/^https?:\\/\\//', $background)
                                                ? $background
                                                : asset('storage/' . $background))
                                            : null;
                                            $fields = $layout['fields'] ?? [];

                                            $tabledataHtmlRaw = $layout['tabledata'] ?? '';
                                            $tablePos = $layout['tablePosition'] ?? [];

                                            $tableLeft = isset($tablePos['left']) ? (float) $tablePos['left'] : 30;
                                            $tableTop = isset($tablePos['top']) ? (float) $tablePos['top'] : 120;
                                            $tableWidth = isset($tablePos['width']) ? (float) $tablePos['width'] : max(120, $cardWidth - 60);
                                            $tableHeight = isset($tablePos['height']) ? (float) $tablePos['height'] : 0;

                                            $hasTableData = is_string($tabledataHtmlRaw) && trim(strip_tags($tabledataHtmlRaw)) !== '';

                                            $tablePlaceholderValues = [
                                                'student_name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                                                'first_name' => $student->first_name ?? '',
                                                'last_name' => $student->last_name ?? '',
                                                'father_name' => $student->father_name ?? '-',
                                                'mother_name' => $student->mother_name ?? '-',
                                                'class' => optional($student->studentClass)->name ?? '-',
                                                'section' => optional($student->section)->name ?? '-',
                                                'admission_no' => $student->admission_no ?? '-',
                                                'date_of_birth' => $student->date_of_birth ? $student->date_of_birth->format('d-M-Y') : '-',
                                                'phone' => $student->phone ?? '-',
                                                'blood_group' => $student->blood_group ?? '-',
                                                'address' => $school->address ?? '-',
                                                'school_address' => $school->address ?? '',
                                                'student_address' => $student->address ?? '-',
                                                'school_name' => $school->school_name ?? 'School Name',
                                                'school_phone' => $school->phone ?? '',
                                                'session' => $school->session ?? '2026-27',
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
                                                    'address' => 'school_address',
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

                                            uksort($fields, function ($first, $second) use ($fields)
                                            {
                                            $firstShape =
                                            ($fields[$first]['type'] ?? '') === 'shape';
                                            $secondShape =
                                            ($fields[$second]['type'] ?? '') === 'shape';
                                            return $secondShape <=> $firstShape;
                                                });
                                                @endphp
                                                <div class="id-card-preview" style=" width:317px; height:204px; position:relative;
                                                   margin:auto;
                                                ">

                                                <div style="
                                                position:absolute;
                                                left:50%;
                                                top:50%;
                                                width:{{ $cardWidth }}px;
                                                height:{{ $cardHeight }}px;
                                                transform:
                                                    translate(-50%, -50%)
                                                    scale({{ $scale }});
                                                transform-origin:center;
                                                background:
                                                    {{ $backgroundUrl
                                                        ? " url('" . $backgroundUrl . "')" : '#fff' }} center / 100%
                                                        100% no-repeat; overflow:hidden; ">
                                                @foreach($fields as $key => $field)
                                                        @php
                                                            $type = $field['type'] ?? 'text';
                                                            $fieldType = strtolower(
                                                                str_replace(
                                                                    [' ', '-'],
                                                                    '_',
                                                                    $field['fieldType']
                                                                    ?? $field['field_type']
                                                                    ?? $key
                                                                )
                                                            );
                                                            $fieldLabel = strtolower(
                                                                $field['label']
                                                                ?? $field['text']
                                                                ?? ''
                                                            );

                                                            if (
                                                                str_contains($fieldType, 'father') ||
                                                                str_contains($fieldLabel, 'father')
                                                            ) {
                                                                $fieldType = 'father_name';
                                                            }

                                                            if (
                                                                $fieldType === 'adm' ||
                                                                str_contains($fieldType, 'admission') ||
                                                                str_contains($fieldLabel, 'admission') ||
                                                                str_contains($fieldLabel, 'adm no') ||
                                                                str_contains($fieldLabel, 'roll no')
                                                            ) {
                                                                $fieldType = 'admission_no';
                                                            }


                                                            $value = match ($fieldType) {

                                                                'student_name',
                                                                'name'
                                                                    => trim(
                                                                        ($student->first_name ?? '') .
                                                                        ' ' .
                                                                        ($student->last_name ?? '')
                                                                    ),

                                                                'first_name'
                                                                    => $student->first_name ?? '',

                                                                'last_name'
                                                                    => $student->last_name ?? '',

                                                                'father_name'
                                                                    => $student->father_name ?? '',

                                                                'admission_no'
                                                                    => $student->admission_no ?? '',

                                                            'class' => 'Class ' .trim(
                                                                    ($student->studentClass->name ?? '') .
                                                                    ' - ' .
                                                                    ($student->section ?? '')
                                                                ),

                                                                'dob',
                                                                'date_of_birth'
                                                                    => $student->date_of_birth
                                                                        ? $student->date_of_birth->format('d-M-Y')
                                                                        : '',

                                                                'gender'
                                                                    => $student->gender ?? '',

                                                                'blood_group'
                                                                    => $student->blood_group ?? '',

                                                                'phone'
                                                                    => $student->phone ?? '',

                                                                'school_name'
                                                                    => $school->school_name ?? '',

                                                                'principal_name'
                                                                    => $school->principal_name ?? '',

                                                                default
                                                                    => $field['text'] ?? '',
                                                            };


                                                            $left = (int)($field['x'] ?? 0);
                                                            $top = (int)($field['y'] ?? 0);

                                                            $width = isset($field['width'])
                                                                ? 'width:' . (int)$field['width'] . 'px;'
                                                                : '';

                                                            $height = isset($field['height'])
                                                                ? 'height:' . (int)$field['height'] . 'px;'
                                                                : '';

                                                            $zIndex = $type === 'shape'
                                                                ? 1
                                                                : 10;

                                                            $style = " position:absolute; left:{$left}px; top:{$top}px;
                                                            z-index:{$zIndex}; {$width} {$height} ";

                                                            if (!($field['visible'] ?? true)) {
                                                                $style .= 'display:none;';
                                                            }
                                                        @endphp


                                                    @if($type === 'image')

                                                        @php
                                                             $photo = pathinfo($student->photo, PATHINFO_DIRNAME) . '/' .
                                                                             pathinfo($student->photo, PATHINFO_FILENAME) . '.jpg';
                                                               
                                                                if (pathinfo($student->photo, PATHINFO_DIRNAME) === '.') {
                                                                    $photo = pathinfo($student->photo, PATHINFO_FILENAME) . '.jpg';
                                                                }

                                                            $photoPath = collect([
                                                                $student->capturephoto,
                                                                $photo
                                                            ])->first(function ($path) {

                                                                return $path &&
                                                                    Storage::disk('public')
                                                                        ->exists($path);

                                                            });


                                                            $isStudentPhoto =
                                                                in_array(
                                                                    $fieldType,
                                                                    ['photo', 'student_photo']
                                                                )
                                                                ||
                                                                str_contains(
                                                                    $fieldType,
                                                                    'photo'
                                                                )
                                                                ||
                                                                str_contains(
                                                                    $fieldLabel,
                                                                    'photo'
                                                                );


                                                            $imagePath = $isStudentPhoto
                                                                ? $photoPath
                                                                : ($field['src'] ?? null);


                                                            $imageUrl = null;

                                                            if ($imagePath) {

                                                                $imageUrl = preg_match(
                                                                    '/^https?:\/\//',
                                                                    $imagePath
                                                                )
                                                                    ? $imagePath
                                                                    : asset(
                                                                        'storage/' . $imagePath
                                                                    );

                                                            }

                                                        @endphp


                                                        @if($imageUrl)

                                                            <img
                                                                src=" {{ $imageUrl }}" alt="{{ $fieldType }}"
                                                        style="{{ $style }}">

                                                        @endif


                                                        @elseif($type === 'shape')

                                                        <div style="
                                                            {{ $style }}
                                                            background-color:{{ $field['backgroundColor'] ?? 'transparent' }};
                                                            opacity:{{ $field['opacity'] ?? 1 }};
                                                            border-radius:{{ (int)($field['borderRadius'] ?? 0) }}px;
                                                            box-sizing:border-box;
                                                        "></div>


                                                        @else

                                                        <div style="
                                                            {{ $style }}
                                                            font-size:{{ (int)($field['fontSize'] ?? 14) }}px;
                                                            color:{{ $field['color'] ?? '#111' }};
                                                            font-weight:{{ $field['fontWeight'] ?? 'normal' }};
                                                        ">
                                                            {{ $value }}
                                                        </div>

                                                        @endif

                                                @endforeach

                                                @if($hasTableData)
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


                                                @elseif($horizontalSample)

                                                <div class="text-center">

                                                    <img src="{{ asset('storage/' . $horizontalSample->file_path) }}"
                                                        alt="Horizontal ID Card" style="
                                                                                                    width:317px;
                                                                                                    height:204px;
                                                                                                    object-fit:fill;
                                                                                                "
                                                        class="img-thumbnail">

                                                </div>

                                                @else

                                                <div class="alert alert-warning">
                                                    No horizontal ID card selected.
                                                </div>

                                                @endif

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-md-right mt-3 mt-md-0">
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit mr-1"></i>
                            </a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="">
                                    <i class="fas fa-trash mr-1"></i>
                                </button>
                            </form>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm px-3">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-user mr-2 text-primary"></i>
                                Personal Information
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <tr>
                                    <th style="width:40%;">
                                        <i class="fas fa-id-card text-muted mr-2"></i>
                                        Admission No
                                    </th>
                                    <td>
                                        {{ $student->admission_no ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-user text-muted mr-2"></i>
                                        Full Name
                                    </th>
                                    <td>
                                        {{ $student->first_name }}
                                        {{ $student->last_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-male text-muted mr-2"></i>
                                        Father Name
                                    </th>
                                    <td>
                                        {{ $student->father_name ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-venus-mars text-muted mr-2"></i>
                                        Gender
                                    </th>
                                    <td>
                                        {{ $student->gender ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-birthday-cake text-muted mr-2"></i>
                                        Date of Birth
                                    </th>
                                    <td>
                                        @if($student->date_of_birth)
                                        {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-tint text-muted mr-2"></i>
                                        Blood Group
                                    </th>
                                    <td>
                                        {{ $student->blood_group ?? 'N/A' }}
                                    </td>
                                </tr>

                            </table>

                        </div>

                    </div>

                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-graduation-cap mr-2 text-primary"></i>
                                Academic & Contact
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <tr>
                                    <th style="width:40%;">
                                        <i class="fas fa-school text-muted mr-2"></i>
                                        Class
                                    </th>
                                    <td>
                                        {{ $student->studentClass->name ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-layer-group text-muted mr-2"></i>
                                        Section
                                    </th>
                                    <td>
                                        {{ $student->section ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-phone text-muted mr-2"></i>
                                        Phone
                                    </th>
                                    <td>
                                        {{ $student->phone ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-id-badge text-muted mr-2"></i>
                                        ID Card
                                    </th>
                                    <td>
                                        @if($student->idcardprinted == 'yes')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i>
                                            Printed
                                        </span>
                                        @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times mr-1"></i>
                                            Not Printed
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-palette text-muted mr-2"></i>
                                        Photo Background
                                    </th>
                                    <td>
                                        {{ $student->capture_background ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-calendar text-muted mr-2"></i>
                                        Added On
                                    </th>
                                    <td>
                                        {{ $student->created_at
                                        ? $student->created_at->format('d/m/Y')
                                        : 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="card shadow-sm">
                <div class="card-body text-right">
                   
                </div>
            </div> -->
        </div>
    </section>
</div>
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Capture Student Photo
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="modalPhotoContent">
                    @include('frontend.studentpartials.commoncapture')
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewPhotoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Student Photo
                </h5>

                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">

                <img id="viewStudentPhoto" src="{{ $studentPhotoUrl ?? '' }}" alt="Student Photo" class="img-fluid"
                    style="max-height: 600px;">

            </div>

        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    let selectedStudentId = null;
    $(document).on('click', '.capture-student-btn', function () {
        selectedStudentId = $(this).attr('data-student-id');
        console.log('Selected Student ID:', selectedStudentId);
    });

    $('#save-capture-photo').on('click', function () {
        console.log('Student ID:', selectedStudentId);
        if (!selectedStudentId) {
            alert('Student ID missing');
            return;
        }
        let photoData = $('#photo_data').val();
        let background = $('#camera-bg').val();
        if (!photoData) {
            alert('Please capture a photo first');
            return;
        }
        let url = "{{ route('student.capture-photo', ':student') }}";
        url = url.replace(':student', selectedStudentId);
        console.log('POST URL:', url);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                photo_data: photoData,
                capture_background: background
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    $('#photoModal').modal('hide');
                    toastr.success(data.message, 'Success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    alert(data.message || 'Photo could not be saved');
                }

            })
            .catch(error => {
                console.error(error);
                alert('Error while saving photo.');
            });
    });
</script>
@endsection