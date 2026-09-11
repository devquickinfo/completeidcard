@extends('frontend.layout.applayout')
@section('title', 'Add Student')
@section('content')
@php
    $defaultOrientation = \App\Models\Mainidcard::where(
        'school_id',
        auth()->user()->school_id ?? session('viewing_school')
    )->latest('id')->value('orientation') ?? 'vertical';
@endphp
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">{{ isset($student) ? 'EDIT STUDENT' : 'ADD STUDENT' }}</h3>
                            <a href="{{ route('student.list') }}"
                                class="btn btn-secondary btn-sm ml-auto flex-shrink-0">
                                <i class="fas fa-arrow-left"></i>
                                <span class="d-none d-sm-inline ml-1">Back</span>
                            </a>
                        </div>
                        @if(session('success'))
                        <div class="alert alert-success m-3 mb-0">
                            {{ session('success') }}
                        </div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger m-3 mb-0">
                            {{ session('error') }}
                        </div>
                        @endif
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="quickForm"
                            action="{{ isset($student) ? route('students.update', $student->id) : route('students.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($student))
                            @method('PUT')
                            @endif
                            <input type="hidden" name="photo_data" id="photo_data" value="">
                            <div class="card-body">
                                <div class="row">
                                    <!---form fields------>

                                    <div class="col-md-6">
                                        <h5 class="text-center badge badge-info">Student Details</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label for="exampleInputEmail1">Admission No</label>
                                                    <input type="text" name="admission_no" class="form-control"
                                                        id="exampleInputEmail1" placeholder="Enter Admission No"
                                                        value="{{ old('admission_no', $student->admission_no ?? '') }}">
                                                    @error('admission_no')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Full Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="first_name" class="form-control"
                                                        id="exampleInputPassword1" placeholder="Enter First Name"
                                                        value="{{ old('first_name', trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''))) }}">
                                                    @error('first_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Last Name </label>
                                                    <input type="text" name="last_name" class="form-control"
                                                        id="exampleInputPassword1" placeholder="Enter Last Name"
                                                        value="{{ old('last_name', $student->last_name  ?? '') }}">
                                                    @error('first_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div> -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword2">Father Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="father_name" class="form-control"
                                                        id="exampleInputPassword2" placeholder="Enter Father Name"
                                                        value="{{ old('father_name', $student->father_name ?? '') }}">
                                                    @error('father_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword2">Mother Name </label>
                                                    <input type="text" name="mother_name" class="form-control"
                                                        id="exampleInputPassword2" placeholder="Enter Mother Name"
                                                        value="{{ old('mother_name', $student->mother_name ?? '') }}">
                                                    @error('father_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Gender</label>
                                                    <select name="gender" id="gender" class="form-control">
                                                        <option value="">-- Select Gender --</option>
                                                        <option value="Male" {{ old('gender', $student->gender ?? '') == 'Male'
                                                            ? 'selected' : ''
                                                            }}>Male</option>
                                                        <option value="Female" {{ old('gender', $student->gender ?? '') ==
                                                            'Female' ? 'selected' : ''
                                                            }}>Female</option>
                                                        <option value="Other" {{ old('gender', $student->gender ?? '') ==
                                                            'Other' ? 'selected' : ''
                                                            }}>Other</option>
                                                    </select>
                                                    @error('gender')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Date of Birth</label>
                                                    <input type="date" name="date_of_birth" class="form-control"
                                                        id="exampleInputPassword1" placeholder="Enter Date of Birth"
                                                        value="{{ old('date_of_birth', optional($student->date_of_birth ?? null)->format('Y-m-d') ?? '') }}">
                                                    @error('date_of_birth')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword2">Class</label>
                                                    <select name="class_id" id="class_id" class="form-control" required>
                                                        <option value="">Select Class</option>

                                                        @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" {{ old('class_id', $student->class_id
                                                            ?? '') == $class->id ?
                                                            'selected' : '' }}>
                                                            {{ $class->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('class_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword2">Section</label>
                                                    <select name="section_id" id="section_id" class="form-control" required>
                                                        <option value="">Select Section</option>
                                                        @foreach($sections as $section)
                                                        <option value="{{ $section->id }}"
                                                            data-class-id="{{ $section->class_id }}" {{ old('section_id',
                                                            $student->section_id ?? '') == $section->id ? 'selected' : '' }}>
                                                            {{ $section->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('section_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword2">Blood Group</label>
                                                    <input type="text" name="blood_group" class="form-control"
                                                        id="exampleInputPassword2" placeholder="Enter Blood Group"
                                                        value="{{ old('blood_group', $student->blood_group ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword2">Phone</label>
                                                    <input type="text" name="phone" class="form-control"
                                                        id="exampleInputPassword2" placeholder="Enter Phone"
                                                        value="{{ old('phone', $student->phone ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword2">Photo Upload</label>
                                                    <input type="file" name="photo" class="form-control"
                                                        id="exampleInputPassword2" placeholder="Upload Photo">
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword2">Address</label>
                                                    <textarea name="address" class="form-control" rows="1"
                                                        id="exampleInputPassword2"
                                                        placeholder="Enter Address">{{ old('address', $student->address ?? '') }}</textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
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

                                                                @if(@$verticalDesign &&
                                                                is_array(@$verticalDesign->layout))

                                                                @php

                                                                $layout = @$verticalDesign->layout;

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
                                                                ?: (@$verticalSample->file_path ?? null);

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

                                                                                $height = isset($field['height'])
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
                                                                            style="{{ $style }}">

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
                                                                    @elseif(@$verticalSample)
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
                                                                @if(@$horizontalDesign &&
                                                                is_array(@$horizontalDesign->layout))
                                                                @php
                                                                $layout = @$horizontalDesign->layout;
                                                                $cardWidth = (int) (
                                                                @$horizontalDesign->card_width
                                                                ?? ($layout['cardWidth'] ?? 317)
                                                                );
                                                                $cardHeight = (int) (
                                                                @$horizontalDesign->card_height
                                                                ?? ($layout['cardHeight'] ?? 204)
                                                                );
                                                                $previewWidth = 317;
                                                                $previewHeight = 204;
                                                                $scale = min(
                                                                $previewWidth / max($cardWidth, 1),
                                                                $previewHeight / max($cardHeight, 1)
                                                                );
                                                                $background = @$horizontalDesign->background
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
                                                                            style="{{ $style }}object-fit:contain;">

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
                                                                    @elseif(@$horizontalSample)

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
                                                        
                                                    </div>
                                            </div>
                                           
                                                        <!---form fields end--->
                                                        <!-----cmaera code----->
                                            <div class="col-md-6">
                                                <h5 class="text-center badge badge-info">Capture Photo (Laptop/Mobile)</h5>
                                                <div class="row">
                                                   <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label>Camera</label>
                                                            <select id="camera-facing-mode"
                                                                    class="form-control">

                                                                <option value="user">
                                                                    Front Camera
                                                                </option>
                                                                <option value="environment" selected>
                                                                    Back Camera
                                                                </option>
                                                            </select>
                                                        </div>
                                                   </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card card-primary">
                                                           <div class="card-header d-flex align-items-center">
                                                                <button type="button"
                                                                        id="start-camera"
                                                                        class="btn btn-info btn-xs">
                                                                    <i class="fas fa-video"></i>
                                                                    Start 
                                                                </button>

                                                                <button type="button"
                                                                        id="capture-photo"
                                                                        class="btn btn-success btn-xs ml-auto">
                                                                    <i class="fas fa-camera"></i>
                                                                    Capture 
                                                                </button>
                                                            </div>

                                                            <div class="card-body">
                                                                <div id="camera-stage"
                                                                     style="background:#dbeafe;padding:8px;border-radius:8px;">

                                                                    <div id="camera"
                                                                         style="position:relative;
                                                                                aspect-ratio:3/4;
                                                                                background:#fff;
                                                                                border-radius:8px;
                                                                                overflow:hidden;">

                                                                        <div id="camera-feed"
                                                                             style="position:absolute;inset:0;">
                                                                        </div>

                                                                        <div id="capture-frame"
                                                                             style="position:absolute;
                                                                                    left:50%;
                                                                                    top:50%;
                                                                                    width:90%;
                                                                                    height:90%;
                                                                                    aspect-ratio:35/45;
                                                                                    transform:translate(-50%,-50%);
                                                                                    border:3px solid #000;
                                                                                    border-radius:12px;
                                                                                    box-shadow:0 0 0 9999px rgba(0,0,0,.25);
                                                                                    pointer-events:none;">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card card-success">
                                                            <div class="card-header">
                                                                <h3 class="card-title">Captured Photo</h3>
                                                            </div>
                                                            <div class="card-body text-center">
                                                                <div id="camera-preview" style="width:200px;
                                                                    height:258px;
                                                                    margin:auto;
                                                                    width:100%;
                                                                    border:1px solid #ccc;
                                                                    border-radius:8px;
                                                                    display:flex;
                                                                    align-items:center;
                                                                    justify-content:center;
                                                                    overflow:hidden;
                                                                    background:#fff;">
                                                                    @if(isset($student) && $student->photo)
                                                                    <img src="{{ asset('storage/' . $student->photo) }}"
                                                                        style="width:100%;height:100%;object-fit:cover;">
                                                                    @else
                                                                    No Capture
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                   


                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{ isset($student) ? 'Update' : 'Submit'
                                    }}</button>
                            </div>
                           
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="idCardImageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ID Card Sample</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    @if(@$idcardsample)
                    <img src="{{ asset('storage/' . $idcardsample->file_path) }}" alt="ID Card Sample" class="img-fluid"
                        style="max-height: 80vh;">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@if(@$idcardsample)
<div class="modal fade" id="editSampleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Edit ID Card
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Preview ID Card
                                </h3>
                            </div>
                            <div class="card-body text-center">
                                <div id="id-card-preview"
                                    style="position: relative; display: inline-block; width: 380px; overflow: hidden; background: #fff;">
                                    <img src="{{ asset('storage/' . $idcardsample->file_path) }}"
                                        id="preview-background" class="img-fluid"
                                        style="width: 100%; height: auto; display: block;" alt="ID Card">
                                    <div class="preview-field" id="preview-name">Student Name</div>
                                    <div class="preview-field" id="preview-admission">Admission No: 83838</div>
                                    <div class="preview-field" id="preview-class">Class: Class 1 (A)</div>
                                    <div class="preview-field" id="preview-address">Address</div>
                                    <img id="preview-photo" src="{{ asset('images/default-photo.png') }}"
                                        style="display:none; position:absolute; object-fit:cover;">
                                    <img id="preview-logo" src=""
                                        style="display:none; position:absolute; object-fit:contain;">
                                    <img id="preview-signature" src=""
                                        style="display:none; position:absolute; object-fit:contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    ID Card Settings
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>ID CARD TITLE <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ $idcardsample->title ?? '' }}" id="card-title">
                                </div>
                                <div class="form-group">
                                    <label>LAYOUT</label>
                                    <select name="layout" id="card-layout" class="form-control">

                                        <option value="horizontal">
                                            Horizontal
                                        </option>

                                        <option value="vertical">
                                            Vertical
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>BACKGROUND IMAGE</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="background-image-name"
                                            placeholder="Background Image" readonly>
                                        <div class="input-group-append">
                                            <label class="btn btn-primary mb-0">
                                                BROWSE
                                                <input type="file" id="background-image" accept="image/*" hidden>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>APPLICABLE USER</label>
                                    <select name="applicable_user" class="form-control">
                                        <option value="student">
                                            Student
                                        </option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                PAGE LAYOUT WIDTH
                                                <small>(DEFAULT 57 MM)</small>
                                            </label>
                                            <input type="number" name="page_width" class="form-control" value="57"
                                                step="0.1">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                PAGE LAYOUT HEIGHT
                                                <small>(DEFAULT 89 MM)</small>
                                            </label>

                                            <input type="number" name="page_height" class="form-control" value="89"
                                                step="0.1">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>PROFILE IMAGE</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="profile-image-name"
                                            placeholder="Profile Image" readonly>
                                        <div class="input-group-append">
                                            <label class="btn btn-primary mb-0">
                                                BROWSE
                                                <input type="file" id="profile-image" accept="image/*" hidden>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>USER PHOTO STYLE</label>
                                    <select name="photo_style" class="form-control" id="photo-style">
                                        <option value="square">
                                            Square
                                        </option>
                                        <option value="circle">
                                            Circle
                                        </option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                USER PHOTO SIZE WIDTH
                                                <small>(DEFAULT 21 MM)</small>
                                            </label>
                                            <input type="number" name="photo_width" class="form-control" value="21"
                                                step="0.1">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                USER PHOTO SIZE HEIGHT
                                                <small>(DEFAULT 21 MM)</small>
                                            </label>

                                            <input type="number" name="photo_height" class="form-control" value="21"
                                                step="0.1">
                                        </div>
                                    </div>
                                </div>
                                <label class="mt-2">
                                    Layout Spacing
                                </label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                TOP SPACE
                                                <small>(DEFAULT 2.5 MM)</small>
                                            </label>

                                            <input type="number" name="top_space" class="form-control" value="2.5"
                                                step="0.1">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                BOTTOM SPACE
                                                <small>(DEFAULT 2.5 MM)</small>
                                            </label>

                                            <input type="number" name="bottom_space" class="form-control" value="2.5"
                                                step="0.1">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                LEFT SPACE
                                                <small>(DEFAULT 3 MM)</small>
                                            </label>

                                            <input type="number" name="left_space" class="form-control" value="3"
                                                step="0.1">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                RIGHT SPACE
                                                <small>(DEFAULT 3 MM)</small>
                                            </label>

                                            <input type="number" name="right_space" class="form-control" value="3"
                                                step="0.1">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>LOGO</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="logo-name" placeholder="Logo"
                                            readonly>
                                        <div class="input-group-append">
                                            <label class="btn btn-primary mb-0">
                                                BROWSE
                                                <input type="file" id="logo-image" accept="image/*" hidden>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        SIGNATURE
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="signature-name"
                                            placeholder="Signature" readonly>
                                        <div class="input-group-append">
                                            <label class="btn btn-primary mb-0">
                                                BROWSE

                                                <input type="file" id="signature-image" accept="image/*" hidden>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="id-card-fields">
                                    @php
                                    $fields = [
                                    'admission_no' => 'ADMISSION NO',
                                    'name' => 'NAME',
                                    'class' => 'CLASS',
                                    'address' => 'ADDRESS',
                                    'photo' => 'PHOTO',
                                    'signature' => 'SIGNATURE',
                                    ];
                                    @endphp
                                    @foreach($fields as $key => $label)
                                    <div class="row align-items-center mb-3">
                                        <div class="col-6">
                                            <label class="mb-0">
                                                {{ $label }}
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="{{ $key }}_yes"
                                                    name="{{ $key }}" value="1" checked>

                                                <label class="custom-control-label" for="{{ $key }}_yes">
                                                    Yes
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-3">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="{{ $key }}_no"
                                                    name="{{ $key }}" value="0">
                                                <label class="custom-control-label" for="{{ $key }}_no">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="save-id-card">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const classSelect = document.getElementById('class_id');
        const sectionSelect = document.getElementById('section_id');
        function filterSections() {
            const selectedClassId = classSelect.value;
            Array.from(sectionSelect.querySelectorAll('option[data-class-id]')).forEach(function (option) {
                const sectionClassId = option.getAttribute('data-class-id');
                const belongsToAnotherClass = selectedClassId && sectionClassId && sectionClassId !== selectedClassId;
                option.style.display = belongsToAnotherClass ? 'none' : '';
                if (belongsToAnotherClass) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
            if (!selectedClassId) {
                sectionSelect.value = '';
            }
        }
        if (classSelect && sectionSelect) {
            filterSections();
            classSelect.addEventListener('change', filterSections);
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const preview = document.getElementById('id-card-preview');
        const bg = document.getElementById('preview-background');

        // ---- px-per-mm scale, recalculated whenever page width changes ----
        const DISPLAY_WIDTH = 380; // fixed preview width in px
        function getScale() {
            const widthMm = parseFloat(document.querySelector('[name="page_width"]').value) || 57;
            return DISPLAY_WIDTH / widthMm;
        }

        function applyPageSize() {
            const widthMm = parseFloat(document.querySelector('[name="page_width"]').value) || 57;
            const heightMm = parseFloat(document.querySelector('[name="page_height"]').value) || 89;
            const scale = getScale();
            preview.style.width = DISPLAY_WIDTH + 'px';
            preview.style.height = (heightMm * scale) + 'px';
            applySpacing();
            applyPhotoSize();
        }

        function applySpacing() {
            const scale = getScale();
            const top = parseFloat(document.querySelector('[name="top_space"]').value) || 0;
            const bottom = parseFloat(document.querySelector('[name="bottom_space"]').value) || 0;
            const left = parseFloat(document.querySelector('[name="left_space"]').value) || 0;
            const right = parseFloat(document.querySelector('[name="right_space"]').value) || 0;

            preview.style.padding =
                (top * scale) + 'px ' +
                (right * scale) + 'px ' +
                (bottom * scale) + 'px ' +
                (left * scale) + 'px';
        }

        function applyPhotoSize() {
            const scale = getScale();
            const w = parseFloat(document.querySelector('[name="photo_width"]').value) || 21;
            const h = parseFloat(document.querySelector('[name="photo_height"]').value) || 21;
            const photo = document.getElementById('preview-photo');
            photo.style.width = (w * scale) + 'px';
            photo.style.height = (h * scale) + 'px';
        }

        // ---- LAYOUT (horizontal / vertical) ----
        document.getElementById('card-layout').addEventListener('change', function () {
            const widthInput = document.querySelector('[name="page_width"]');
            const heightInput = document.querySelector('[name="page_height"]');

            // swap width/height values when orientation changes
            const w = widthInput.value;
            const h = heightInput.value;
            widthInput.value = h;
            heightInput.value = w;

            applyPageSize();
        });

        // ---- PAGE SIZE / SPACING / PHOTO SIZE live inputs ----
        ['page_width', 'page_height'].forEach(name => {
            document.querySelector(`[name="${name}"]`).addEventListener('input', applyPageSize);
        });

        ['top_space', 'bottom_space', 'left_space', 'right_space'].forEach(name => {
            document.querySelector(`[name="${name}"]`).addEventListener('input', applySpacing);
        });

        ['photo_width', 'photo_height'].forEach(name => {
            document.querySelector(`[name="${name}"]`).addEventListener('input', applyPhotoSize);
        });

        // ---- PHOTO STYLE (square / circle) ----
        document.getElementById('photo-style').addEventListener('change', function () {
            const photo = document.getElementById('preview-photo');
            photo.style.borderRadius = this.value === 'circle' ? '50%' : '0';
        });

        // ---- IMAGE UPLOADS (background, profile, logo, signature) ----
        function bindImageUpload(inputId, nameFieldId, targetImgId, showOnUpload = true) {
            document.getElementById(inputId).addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                document.getElementById(nameFieldId).value = file.name;

                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.getElementById(targetImgId);
                    img.src = e.target.result;
                    if (showOnUpload) img.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });
        }

        bindImageUpload('background-image', 'background-image-name', 'preview-background');
        bindImageUpload('profile-image', 'profile-image-name', 'preview-photo');
        bindImageUpload('logo-image', 'logo-name', 'preview-logo');
        bindImageUpload('signature-image', 'signature-name', 'preview-signature');

        // ---- YES / NO FIELD TOGGLES ----
        const fieldMap = {
            admission_no: 'preview-admission',
            name: 'preview-name',
            class: 'preview-class',
            address: 'preview-address',
            photo: 'preview-photo',
            signature: 'preview-signature',
        };

        Object.keys(fieldMap).forEach(function (key) {
            document.querySelectorAll(`input[name="${key}"]`).forEach(function (radio) {
                radio.addEventListener('change', function () {
                    const targetEl = document.getElementById(fieldMap[key]);
                    const isYes = this.value === '1' && this.checked;

                    if (!targetEl) return;

                    if (key === 'photo' || key === 'signature') {
                        // only show if it's set to yes AND actually has an image loaded
                        targetEl.style.display = (isYes && targetEl.src) ? 'block' : 'none';
                    } else {
                        targetEl.style.display = isYes ? 'block' : 'none';
                    }
                });
            });
        });

        // ---- INIT on modal open ----
        applyPageSize();
    });
</script>
@endsection