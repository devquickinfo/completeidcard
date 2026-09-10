@php
    use App\Helpers\ImageHelper;
    $orientation = $orientation
        ?? \App\Models\Mainidcard::where(
            'school_id',
            auth()->user()->school_id ?? session('viewing_school')
        )->latest('id')->value('orientation')
        ?? 'vertical';
    $idCardData = ImageHelper::getIdCard($student->id);
    $verticalSample = $idCardData['verticalSample'];
    $horizontalSample = $idCardData['horizontalSample'];
    $verticalDesign = $idCardData['verticalDesign'];
    $horizontalDesign = $idCardData['horizontalDesign'];
    $school = $idCardData['school'];
@endphp
<div class="tab-content">
    <div class="tab-pane fade {{ $orientation === 'vertical' ? 'show active' : '' }}" id="student-vertical-card" role="tabpanel">
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

                                                $style = " position:absolute; left:{$left}px; top:{$top}px;
                    z-index:{$zIndex}; {$width} {$height} ";

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
                    style="{{ $style }}object-fit:contain;">

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

                </div>

            </div>


            @elseif($verticalSample)

            <div class="text-center">

                <img src="{{ asset('storage/' . $verticalSample->file_path) }}" alt="Vertical ID Card"
                    style="
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


    {{-- =====================================================
    --}}
    {{-- HORIZONTAL CARD --}}
    {{-- =====================================================
    --}}

    <div class="tab-pane fade {{ $orientation === 'horizontal' ? 'show active' : '' }}" id="student-horizontal-card" role="tabpanel">

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
                                    width:317px;
                                    height:204px;
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

                </div>

            </div>


            @elseif($horizontalSample)

            <div class="text-center">

                <img src="{{ asset('storage/' . $horizontalSample->file_path) }}" alt="Horizontal ID Card"
                    style="
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

       
