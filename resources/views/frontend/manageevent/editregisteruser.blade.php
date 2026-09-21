@extends('frontend.layout.applayout')

@section('title', !empty($manageEvent->id) ? 'Edit Event' : 'Add Event')

@section('content')

<div class="content-wrapper">


<section class="content-header">
    <div class="container-fluid">
    </div>
</section>

<section class="content">

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-12">

                <div class="card card-primary">

                    <div class="card-header">

                        <h3 class="card-title">
                            {{ !empty($manageEvent->id) ? 'Edit Register User' : 'Add Event' }}
                        </h3>

                        <a href="{{ route('manage-events.index') }}"
                           class="btn btn-primary float-right">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>

                    </div>

                    <form action="{{ route('manage-events.people.update', $manageEvent->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="edit-user-form">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="register-content">
                                            <div class="form-section-title">
                                                Your Information
                                            </div>
                                            <input type="hidden" name="unique_code" value="{{$manageEvent->user_unique_code}}">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Name <span class="required">*</span>
                                                </label>
                                                <div class="input-wrapper">
                                                    <i class="fas fa-user input-icon"></i>
                                                    <input type="text"
                                                           name="name"
                                                           class="form-control"
                                                           value="{{ old('name', $manageEvent->name ?? '') }}"
                                                           placeholder="Enter your name"
                                                           autocomplete="name"
                                                           required>
                                                </div>
                                                @error('name')

                                                    <span class="field-error">
                                                        {{ $message }}
                                                    </span>

                                                @enderror

                                            </div>


                                            {{-- Email --}}

                                            <div class="form-group">

                                                <label class="form-label">
                                                    Email
                                                </label>

                                                <div class="input-wrapper">

                                                    <i class="fas fa-envelope input-icon"></i>

                                                    <input type="email"
                                                           name="email"
                                                           class="form-control"
                                                           value="{{ old('email', $manageEvent->email ?? '') }}"
                                                           placeholder="Enter your email address"
                                                           autocomplete="email">

                                                </div>

                                                @error('email')

                                                    <span class="field-error">
                                                        {{ $message }}
                                                    </span>

                                                @enderror

                                            </div>


                                            {{-- Mobile --}}

                                            <div class="form-group">

                                                <label class="form-label">
                                                    Mobile <span class="required">*</span>
                                                </label>

                                                <div class="input-wrapper">

                                                    <i class="fas fa-mobile-alt input-icon"></i>

                                                    <input type="tel"
                                                           name="mobile"
                                                           class="form-control"
                                                           value="{{ old('mobile', $manageEvent->mobile ?? '') }}"
                                                           placeholder="Enter your mobile number"
                                                           autocomplete="tel"
                                                           required>

                                                </div>

                                                @error('mobile')

                                                    <span class="field-error">
                                                        {{ $message }}
                                                    </span>

                                                @enderror

                                            </div>


                                            {{-- Organization --}}

                                            <div class="form-group">

                                                <label class="form-label">
                                                    Organization
                                                </label>

                                                <div class="input-wrapper">

                                                    <i class="fas fa-building input-icon"></i>

                                                    <input type="text"
                                                           name="organization"
                                                           class="form-control"
                                                           value="{{ old('organization', $manageEvent->organization ?? '') }}"
                                                           placeholder="Enter organization name"
                                                           autocomplete="organization">

                                                </div>

                                                @error('organization')

                                                    <span class="field-error">
                                                        {{ $message }}
                                                    </span>

                                                @enderror

                                            </div>

                                          

                                            <div class="form-group">
                                                <label class="form-label">
                                                    Upload Photo
                                                </label>
                                                <div class="input-wrapper">
                                                    <i class="fas fa-image input-icon"></i>
                                                    <input type="file" name="photo" class="form-control">
                                                </div>
                                                @error('photo')
                                                    <span class="field-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-0">

                                                <label class="form-label">
                                                    Address <span class="required">*</span>
                                                </label>

                                                <div class="input-wrapper textarea-wrapper">

                                                    <i class="fas fa-map-marker-alt input-icon"></i>

                                                    <textarea name="address"
                                                              class="form-control"
                                                              rows="3"
                                                              placeholder="Enter your address"
                                                              autocomplete="street-address">{{ old('address', $manageEvent->address ?? '') }}</textarea>

                                                </div>
                                                @error('address')
                                                    <span class="field-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                     <div class="form-group">
                                    <label class="form-label">
                                        Photo
                                    </label>
                                    <input type="hidden" name="photo_data" id="photo_data">           
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
                                                    <h6 class="card-title">Captured Photo</h6>
                                                </div>
                                                <div class="card-body text-center">
                                                    <div id="camera-preview" style="width:200px;
                                                        height:258px;
                                                        margin:auto;
                                                        width:90%;
                                                        border:1px solid #ccc;
                                                        border-radius:8px;
                                                        display:flex;
                                                        align-items:center;
                                                        justify-content:center;
                                                        overflow:hidden;
                                                        background:#fff;">
                                                        @if(isset($manageEvent) && $manageEvent->photo)
                                                        <img src="{{ asset('storage/' . $manageEvent->photo) }}"
                                                            style="width:100%;height:100%;object-fit:cover;">
                                                        @else
                                                        No Capture
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                       


                                        </div>
                                    </div>
                                   

                                    @error('photo')
                                        <span class="field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                </div>
                            </div>
                                 
                        </div>
                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-primary">

                                Update

                            </button>

                            <a href="{{ route('manage-events.index') }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>


</div>

<script>

document.getElementById('logo').addEventListener('change', function (event) {

    const file = event.target.files[0];

    const preview = document.getElementById('logoPreview');

    const container = document.getElementById('logoPreviewContainer');

    if (file) {

        if (!file.type.startsWith('image/')) {

            preview.src = '';
            container.style.display = 'none';

            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            preview.src = e.target.result;
            container.style.display = 'block';

        };

        reader.readAsDataURL(file);

    }

});

</script>

@endsection
