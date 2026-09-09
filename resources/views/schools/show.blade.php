@extends('frontend.layout.applayout')
@section('title', 'School Details')
@section('content')
<style>
    .id-card-template {
        width: 350px;
        height: 220px;
        object-fit: contain;
        display: block;
    }

    .id-card-section {
        width: 100%;
    }

    .id-card-preview {
        width: 100%;
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .id-card-template {
        width: auto;
        max-width: 100%;
        /* height: auto; */
        display: block;
        max-height: 300px;

    }
</style>
@php
    $defaultOrientation = \App\Models\SelectedSample::where(
        'school_id',
        auth()->user()->school_id ?? session('viewing_school')
    )->latest('id')->value('orientation') ?? 'vertical';
@endphp
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <!-- <div class="col-sm-6">
                    <h1>School Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">School</li>
                    </ol>
                </div> -->
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm school-profile-card">
                <div class="card-header bg-white border-0 px-4 py-3">
                    <div class="d-flex align-items-center">
                        <div>
                            <h4 class="mb-1 font-weight-bold text-dark">
                                <i class="fas fa-school text-primary mr-2"></i>
                                {{ ucwords($school->school_name) }}
                            </h4>

                            <small class="text-muted">
                                <i class="fas fa-id-card mr-1"></i>
                                School Code: {{ $school->school_code }}
                            </small>
                        </div>
                        <div class="ml-auto d-flex align-items-center">
                            @if($school->status)
                            <span class="badge badge-success px-3 py-2 mr-2">
                                <i class="fas fa-check-circle mr-1"></i> Active
                            </span>
                            @else
                            <span class="badge badge-danger px-3 py-2 mr-2">
                                <i class="fas fa-times-circle mr-1"></i> Inactive
                            </span>
                            @endif
                            @if(Auth::user()?->role === 'superadmin')
                            <a href="{{ route('schools.edit', $school) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 pt-2 pb-4">
                    <div class="principal-box mb-4">
                        <div class="d-flex align-items-center">
                            <div class="principal-icon flex-shrink-0">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="ml-3">
                                <small class="text-muted d-block">
                                    PRINCIPAL
                                </small>
                                <h5 class="mb-0 font-weight-bold">
                                    {{ $school->principal_name ?: 'Not Available' }}
                                </h5>
                            </div>
                            @if(Auth::user()?->role === 'superadmin')
                            <a href="{{ route('schools.index') }}" class="btn btn-info btn-sm ml-auto flex-shrink-0">
                                <i class="fas fa-arrow-left"></i>
                                <span class="d-none d-sm-inline ml-1">Back</span>
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-lg-3 col-md-4 mb-3 mb-md-0">
                            <div class="school-logo-box">

                                @if($school->logo)
                                <img src="{{ Storage::disk('public')->url($school->logo) }}" alt="School Logo"
                                    class="school-logo img-thumbnail">
                                @else
                                <div class="no-logo">
                                    <i class="fas fa-school"></i>
                                    <span>No Logo</span>
                                </div>
                                @endif

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 mb-3 mb-md-0">

                            <h6 class="section-title">
                                <i class="fas fa-address-book mr-2"></i>
                                Contact Information
                            </h6>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <small>Email</small>
                                    <strong>{{ $school->email ?: 'Not Available' }}</strong>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <small>Phone</small>
                                    <strong>{{ $school->phone ?: 'Not Available' }}</strong>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <small>Address</small>
                                    <strong>{{ $school->address ?: 'Not Available' }}</strong>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-5 col-md-4">
                            <div class="id-card-section">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="section-title mb-0">
                                        <i class="fas fa-id-card mr-2"></i>
                                        Live ID Card Preview Click Card to Edit
                                    </h6>
                                    <!-- <a href="{{ route('idcard.editor', ['orientation' => 'vertical']) }}"
                                        id="editIdCardBtn" class="btn btn-sm btn-warning ml-auto" target="_blank">
                                        <i class="fas fa-edit"></i> Edit
                                    </a> -->
                                </div>
                                <ul class="nav nav-tabs mb-3" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link {{ $defaultOrientation === 'vertical' ? 'active' : '' }}" id="vertical-tab" data-toggle="tab"
                                            href="#vertical-card" role="tab">

                                            <i class="fas fa-mobile-alt mr-1"></i>
                                            Vertical

                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ $defaultOrientation === 'horizontal' ? 'active' : '' }}" id="horizontal-tab" data-toggle="tab"
                                            href="#horizontal-card" role="tab">

                                            <i class="fas fa-mobile-alt fa-rotate-90 mr-1"></i>
                                            Horizontal

                                        </a>
                                    </li>

                                </ul>


                                <div class="tab-content">

                                    {{-- ===================================================== --}}
                                    {{-- VERTICAL --}}
                                    {{-- ===================================================== --}}

                                    <div class="tab-pane fade show {{ $defaultOrientation === 'vertical' ? 'show active' : '' }}" id="vertical-card" role="tabpanel">

                                        @if($verticalDesign && is_array($verticalDesign->layout))

                                        @php
                                        $layout = $verticalDesign->layout;

                                        $cardW = $verticalDesign->card_width
                                        ?? ($layout['cardWidth'] ?? 204);

                                        $cardH = $verticalDesign->card_height
                                        ?? ($layout['cardHeight'] ?? 317);

                                        $previewW = 204;
                                        $previewH = 317;

                                        $scale = min(
                                        $previewW / max($cardW, 1),
                                        $previewH / max($cardH, 1),
                                        1
                                        );

                                        $fields = $layout['fields'] ?? [];

                                        if (!empty($verticalDesign->background)) {
                                        $bgUrl = asset(
                                        'storage/' . $verticalDesign->background
                                        );
                                        } elseif ($verticalSample) {
                                        $bgUrl = asset(
                                        'storage/' . $verticalSample->file_path
                                        );
                                        } else {
                                        $bgUrl = '';
                                        }
                                        @endphp


                                        <div class="id-card-preview text-center">

                                            <a href="{{ route('idcard.editor', ['orientation' => 'vertical']) }}"
                                                target="_blank" title="Edit Vertical ID Card"
                                                style="display:inline-block;">

                                                <div style="
                        width:{{ $previewW }}px;
                        height:{{ $previewH }}px;
                        overflow:hidden;
                        position:relative;
                        margin:auto;
                        cursor:pointer;
                    ">

                                                    <div style="
                            width:{{ $cardW }}px;
                            height:{{ $cardH }}px;
                            position:relative;
                            transform:scale({{ $scale }});
                            transform-origin:top left;
                            background-image:url('{{ $bgUrl }}');
                            background-size:100% 100%;
                            background-repeat:no-repeat;
                            overflow:hidden;
                            box-shadow:0 8px 24px rgba(0,0,0,.25);
                            border-radius:6px;
                        ">

                                                        @foreach($fields as $key => $field)

                                                        @php
                                                        $type = $field['type'] ?? 'text';

                                                        $left = (int)($field['x'] ?? 0);
                                                        $top = (int)($field['y'] ?? 0);

                                                        $width = isset($field['width'])
                                                        ? (int)$field['width']
                                                        : null;

                                                        $height = isset($field['height'])
                                                        ? (int)$field['height']
                                                        : null;

                                                        $visible = $field['visible'] ?? true;

                                                        $zIndex = $type === 'shape'
                                                        ? 1
                                                        : 10;

                                                        $style = "
                                                        position:absolute;
                                                        left:{$left}px;
                                                        top:{$top}px;
                                                        z-index:{$zIndex};
                                                        ";

                                                        if ($width) {
                                                        $style .= "width:{$width}px;";
                                                        }

                                                        if ($height) {
                                                        $style .= "height:{$height}px;";
                                                        }

                                                        if (!$visible) {
                                                        $style .= "display:none;";
                                                        }
                                                        @endphp


                                                        {{-- IMAGE --}}
                                                        @if($type === 'image')

                                                        @php
                                                        $src = '';

                                                        if (!empty($field['src'])) {

                                                        if (preg_match(
                                                        '/^https?:\/\//',
                                                        $field['src']
                                                        )) {
                                                        $src = $field['src'];
                                                        } else {
                                                        $src = Storage::disk('public')
                                                        ->url($field['src']);
                                                        }
                                                        }
                                                        @endphp

                                                        @if($src)
                                                        <img src="{{ $src }}" alt="{{ $key }}"
                                                            style="{{ $style }}object-fit:contain;" class="img-thumbnail">
                                                        @endif


                                                        {{-- SHAPE --}}
                                                        @elseif($type === 'shape')

                                                        @php
                                                        $backgroundColor =
                                                        $field['backgroundColor']
                                                        ?? 'transparent';

                                                        $opacity =
                                                        isset($field['opacity'])
                                                        ? (float)$field['opacity']
                                                        : 1;

                                                        $borderRadius =
                                                        isset($field['borderRadius'])
                                                        ? (int)$field['borderRadius']
                                                        : 0;

                                                        $style .= "
                                                        background-color:{$backgroundColor};
                                                        opacity:{$opacity};
                                                        border-radius:{$borderRadius}px;
                                                        box-sizing:border-box;
                                                        ";
                                                        @endphp

                                                        <div style="{{ $style }}"></div>


                                                        {{-- TEXT --}}
                                                        @else

                                                        @php
                                                        $text = $field['text'] ?? '';

                                                        if (isset($field['fontSize'])) {
                                                        $style .=
                                                        'font-size:' .
                                                        (int)$field['fontSize'] .
                                                        'px;';
                                                        }

                                                        if (!empty($field['color'])) {
                                                        $style .=
                                                        'color:' .
                                                        $field['color'] .
                                                        ';';
                                                        }

                                                        if (!empty($field['fontWeight'])) {
                                                        $style .=
                                                        'font-weight:' .
                                                        $field['fontWeight'] .
                                                        ';';
                                                        }
                                                        @endphp

                                                        <div style="{{ $style }}">
                                                            {!! e($text) !!}
                                                        </div>

                                                        @endif

                                                        @endforeach

                                                    </div>

                                                </div>

                                            </a>

                                        </div>


                                        @elseif($verticalSample)

                                        {{-- No saved design --}}
                                        <div class="id-card-preview text-center">

                                            <a href="{{ route('idcard.editor', ['orientation' => 'vertical']) }}"
                                                target="_blank">

                                                <img src="{{ asset('storage/' . $verticalSample->file_path) }}"
                                                    alt="Vertical ID Card" class="img-thumbnail" style="
                            width:204px;
                            height:317px;
                            object-fit:fill;
                            cursor:pointer;
                        ">

                                            </a>

                                        </div>

                                        @else

                                        <div class="alert alert-warning">
                                            No vertical ID card sample selected.
                                        </div>

                                        @endif

                                    </div>



                                    {{-- ===================================================== --}}
                                    {{-- HORIZONTAL --}}
                                    {{-- ===================================================== --}}

                                    <div class="tab-pane fade {{ $defaultOrientation === 'horizontal' ? 'show active' : '' }}" id="horizontal-card" role="tabpanel">

                                        @if($horizontalDesign && is_array($horizontalDesign->layout))

                                        @php
                                        $layout = $horizontalDesign->layout;

                                        $cardW = $horizontalDesign->card_width
                                        ?? ($layout['cardWidth'] ?? 317);

                                        $cardH = $horizontalDesign->card_height
                                        ?? ($layout['cardHeight'] ?? 204);

                                        $previewW = 317;
                                        $previewH = 204;

                                        $scale = min(
                                        $previewW / max($cardW, 1),
                                        $previewH / max($cardH, 1),
                                        1
                                        );
                                        $fields = $layout['fields'] ?? [];
                                        if (!empty($horizontalDesign->background)) {
                                        $bgUrl = asset(
                                        'storage/' . $horizontalDesign->background
                                        );
                                        } elseif ($horizontalSample) {
                                        $bgUrl = asset(
                                        'storage/' . $horizontalSample->file_path
                                        );
                                        } else {
                                        $bgUrl = '';
                                        }
                                        @endphp
                                        <div class="id-card-preview text-center">
                                            <a href="{{ route('idcard.editor', ['orientation' => 'horizontal']) }}"
                                                target="_blank" title="Edit Horizontal ID Card"
                                                style="display:inline-block;">

                                                <div style="
                                                    width:{{ $previewW }}px;
                                                    height:{{ $previewH }}px;
                                                    overflow:hidden;
                                                    position:relative;
                                                    margin:auto;
                                                    cursor:pointer;
                                                ">
                                                <div style="
                                                        width:{{ $cardW }}px;
                                                        height:{{ $cardH }}px;
                                                        position:relative;
                                                        transform:scale({{ $scale }});
                                                        transform-origin:top left;
                                                        background-image:url('{{ $bgUrl }}');
                                                        background-size:100% 100%;
                                                        background-repeat:no-repeat;
                                                        overflow:hidden;
                                                        box-shadow:0 8px 24px rgba(0,0,0,.25);
                                                        border-radius:6px;
                                                ">
                                                        @foreach($fields as $key => $field)
                                                        @php
                                                        $type = $field['type'] ?? 'text';
                                                        $left = (int)($field['x'] ?? 0);
                                                        $top = (int)($field['y'] ?? 0);
                                                        $width = isset($field['width'])
                                                        ? (int)$field['width']
                                                        : null;
                                                        $height = isset($field['height'])
                                                        ? (int)$field['height']
                                                        : null;
                                                        $visible = $field['visible'] ?? true;
                                                        $zIndex = $type === 'shape'
                                                        ? 1
                                                        : 10;
                                                        $style = "
                                                        position:absolute;
                                                        left:{$left}px;
                                                        top:{$top}px;
                                                        z-index:{$zIndex};
                                                        ";
                                                        if ($width) {
                                                        $style .= "width:{$width}px;";
                                                        }
                                                        if ($height) {
                                                        $style .= "height:{$height}px;";
                                                        }
                                                        if (!$visible) {
                                                        $style .= "display:none;";
                                                        }
                                                        @endphp
                                                        @if($type === 'image')
                                                        @php
                                                        $src = '';
                                                        if (!empty($field['src'])) {
                                                        if (preg_match(
                                                        '/^https?:\/\//',
                                                        $field['src']
                                                        )) {
                                                        $src = $field['src'];
                                                        } else {
                                                        $src = Storage::disk('public')
                                                        ->url($field['src']);
                                                        }
                                                        }
                                                        @endphp
                                                        @if($src)
                                                        <img src="{{ $src }}" alt="{{ $key }}"
                                                            style="{{ $style }}object-fit:contain;" class="img-thumbnail">
                                                        @endif
                                                        @elseif($type === 'shape')
                                                        @php
                                                        $backgroundColor =
                                                        $field['backgroundColor']
                                                        ?? 'transparent';
                                                        $opacity =
                                                        isset($field['opacity'])
                                                        ? (float)$field['opacity']
                                                        : 1;

                                                        $borderRadius =
                                                        isset($field['borderRadius'])
                                                        ? (int)$field['borderRadius']
                                                        : 0;

                                                        $style .= "
                                                        background-color:{$backgroundColor};
                                                        opacity:{$opacity};
                                                        border-radius:{$borderRadius}px;
                                                        box-sizing:border-box;
                                                        ";
                                                        @endphp
                                                        <div style="{{ $style }}"></div>
                                                        @else
                                                        @php
                                                        $text = $field['text'] ?? '';
                                                        if (isset($field['fontSize'])) {
                                                        $style .=
                                                        'font-size:' .
                                                        (int)$field['fontSize'] .
                                                        'px;';
                                                        }

                                                        if (!empty($field['color'])) {
                                                        $style .=
                                                        'color:' .
                                                        $field['color'] .
                                                        ';';
                                                        }

                                                        if (!empty($field['fontWeight'])) {
                                                        $style .=
                                                        'font-weight:' .
                                                        $field['fontWeight'] .
                                                        ';';
                                                        }
                                                        @endphp
                                                        <div style="{{ $style }}">
                                                            {!! e($text) !!}
                                                        </div>
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @elseif($horizontalSample)
                                        <div class="id-card-preview text-center">
                                            <a href="{{ route('idcard.editor', ['orientation' => 'horizontal']) }}"
                                                target="_blank">

                                                <img src="{{ asset('storage/' . $horizontalSample->file_path) }}"
                                                    alt="Horizontal ID Card" class="img-thumbnail" style="
                                                    width:317px;
                                                    height:204px;
                                                    object-fit:fill;
                                                    cursor:pointer;
                                                ">
                                            </a>
                                        </div>
                                        @else
                                        <div class="alert alert-warning">
                                            No horizontal ID card sample selected.
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Classes & Sections</h3>
                </div>
                <div class="card-body">
                    @if($classes->isEmpty())
                    <p class="text-muted">No classes or sections found for this school.</p>
                    @else
                    <div class="row">
                        @foreach($classes as $class)
                       {{---- <div class="col-md-4 mb-4">
                            <a href="{{ route('schools.classes.students', ['school' => $school, 'class' => $class]) }}"
                                class="text-decoration-none text-dark">
                                <div class="card h-100 cursor-pointer">
                                    <div class="card-header bg-primary text-white d-flex align-items-center w-100">
                                        <h3 class="card-title mb-0 flex-grow-1">{{ $class->name }}</h3>
                                        <span class="badge bg-light text-dark ms-3" style="font-size: 0.9rem;">
                                            {{ App\Models\Student::where('class_id', $class->id)->where('school_id',
                                            $school->id)->where('IsDeleted', '0')->count() }} Students
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0 text-muted">Click to view students</p>
                                        <p class="mb-0 mt-2 text-success" style="font-size: 1rem;">
                                            {{ App\Models\Student::where('class_id', $class->id)->where('school_id',
                                            $school->id)->whereNull('photo')->where('IsDeleted', '0')->count() }} students without capture photo
                                        </p>
                                        <p class="mb-0 mt-2 text-danger" style="font-size: 1rem;">
                                            {{ App\Models\Student::where('class_id', $class->id)->where('school_id',
                                            $school->id)->where('idcardprinted', 'no')->where('IsDeleted', '0')->count() }} students without
                                            printed ID cards
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>----}}
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 cursor-pointer">
                                <a href="{{ route('schools.classes.students', [
                                    'school' => $school->id,
                                    'class' => $class->id
                                ]) }}"
                                class="text-decoration-none">
                                    <div class="card-header bg-primary text-white d-flex align-items-center w-100">
                                        <h3 class="card-title mb-0 flex-grow-1">
                                            {{ $class->name }}
                                        </h3>
                                        <span class="badge bg-light text-dark ms-3" style="font-size: 0.9rem;">
                                            {{ App\Models\Student::where('class_id', $class->id)
                                                ->where('school_id', $school->id)->where('IsDeleted', '0')
                                                ->count() }}
                                            Students
                                        </span>
                                    </div>
                                </a>
                                <div class="card-body">
                                    <a href="{{ route('schools.classes.students', [
                                        'school' => $school->id,
                                        'class' => $class->id
                                    ]) }}"
                                    class="text-decoration-none">

                                        <p class="mb-0 text-muted">
                                            Click to view students
                                        </p>

                                    </a>
                                    @php
                                        $withoutPhoto = App\Models\Student::where('class_id', $class->id)
                                            ->where('school_id', $school->id)
                                            ->where(function ($query) {
                                                $query->whereNull('photo')->where('IsDeleted', '0')
                                                    ->orWhere('photo', '');
                                            })
                                            ->count();
                                    @endphp

                                    <a href="{{ route('schools.classes.students', [
                                        'school' => $school->id,
                                        'class' => $class->id,
                                        'student_photo' => 'without_photo'
                                    ]) }}"
                                    class="text-decoration-none">

                                        <p class="mb-0 mt-2 text-success" style="font-size: 1rem;">
                                            {{ $withoutPhoto }} students without capture photo
                                        </p>

                                    </a>


                                    {{-- WITHOUT PRINTED ID CARD --}}
                                    @php
                                        $withoutPrinted = App\Models\Student::where('class_id', $class->id)
                                            ->where('school_id', $school->id)->where('IsDeleted', '0')
                                            ->where('idcardprinted', 'no')
                                            ->count();
                                    @endphp

                                    <a href="{{ route('schools.classes.students', [
                                        'school' => $school->id,
                                        'class' => $class->id,
                                        'idcard_filter' => 'not_printed'
                                    ]) }}"
                                    class="text-decoration-none">

                                        <p class="mb-0 mt-2 text-danger" style="font-size: 1rem;">
                                            {{ $withoutPrinted }} students without printed ID cards
                                        </p>

                                    </a>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection