@extends('frontend.layout.applayout')
@section('title', 'ID Card')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline mt-4">
              
                   
                    
            <div class="card-header">
            <div class="d-flex align-items-center w-100">
                <h3 class="card-title mb-0">
                    <i class="fas fa-id-card mr-2"></i>
                    Create ID Card Sample
                </h3>
                <a href="{{ url()->previous() }}" class="ml-auto btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>
            <form action="{{ route('event-id-cards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ old('id', $idCardData->id ?? '') }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">
                                            Template Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="name"
                                               id="name"
                                               class="form-control"
                                               placeholder="Enter template name"
                                               value="{{ old('name', $idCardData->name ?? '') }}"
                                               required>

                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="event_id">
                                            Event
                                        </label>

                                        <select name="event_id"
                                                id="event_id"
                                                class="form-control">

                                            <option value="">Global</option>

                                            @foreach($events as $event)
                                                <option value="{{ $event->id }}"
                                                    {{ old('event_id', $idCardData->event_id ?? '') == $event->id ? 'selected' : '' }}>
                                                    {{ $event->event_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                        @error('event_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                {{-- Width --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width">
                                            Width in mm <span class="text-danger">*</span>
                                        </label>

                                        <input type="number"
                                               name="width"
                                               id="width"
                                               class="form-control"
                                               placeholder="Enter width"
                                               value="{{ old('width', $idCardData->width ?? '') }}"
                                               min="1"
                                               step="1"
                                               required>

                                        @error('width')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                {{-- Height --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="height">
                                            Height in mm <span class="text-danger">*</span>
                                        </label>

                                        <input type="number"
                                               name="height"
                                               id="height"
                                               class="form-control"
                                               placeholder="Enter height"
                                               value="{{ old('height', $idCardData->height ?? '') }}"
                                               min="1"
                                               step="1"
                                               required>

                                        @error('height')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>


                            {{-- ================= PAPER + CARD PER PAGE ================= --}}
                            <div class="row">

                                {{-- Paper Size --}}
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="paper_size">
                                            Paper Size <span class="text-danger">*</span>
                                        </label>

                                        <select name="paper_size"
                                                id="paper_size"
                                                class="form-control"
                                                required>

                                            <option value="">Select Paper Size</option>

                                            @foreach(['A4', 'A3', 'A5', 'A6'] as $size)
                                                <option value="{{ $size }}"
                                                    {{ old('paper_size', $idCardData->paper_size ?? '') == $size ? 'selected' : '' }}>
                                                    {{ $size }}
                                                </option>
                                            @endforeach

                                        </select>

                                        @error('paper_size')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>


                                {{-- Card Per Page --}}
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="cardperpage">
                                            Card Per Page <span class="text-danger">*</span>
                                        </label>

                                        <input type="number"
                                               name="cardperpage"
                                               id="cardperpage"
                                               class="form-control"
                                               placeholder="Enter Card Per Page"
                                               value="{{ old('cardperpage', $idCardData->cardperpage ?? '') }}"
                                               min="1"
                                               step="1"
                                               required>

                                        @error('cardperpage')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>

                            </div>


                            {{-- ================= BROWSE IMAGE ================= --}}
                            <div class="row">

                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label for="image">
                                            Template Image

                                            @if(empty($idCardData->id))
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>

                                        <input type="file"
                                               name="image"
                                               id="image"
                                               class="form-control-file"
                                               accept="image/jpeg,image/png,image/webp"
                                               {{ empty($idCardData->id) ? '' : '' }}>

                                        <small class="text-muted d-block mt-1">
                                            JPG, PNG or WEBP. Maximum 5MB.
                                        </small>

                                        @error('image')
                                            <span class="text-danger d-block mt-1">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>

                                </div>

                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Current Image</strong>
                                        </div>
                                        <div class="card-body text-center"
                                             style="
                                                min-height: 350px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                background: #f8f9fa;
                                             ">
                                            @if(!empty($idCardData->file_path))
                                                <img src="{{ asset('storage/' . $idCardData->file_path) }}"
                                                     alt="Current Template"
                                                     style="
                                                        max-width: 100%;
                                                        max-height: 320px;
                                                        object-fit: contain;
                                                     "
                                                     class="border rounded p-1">
                                            @else
                                                <div class="text-muted">
                                                    <i class="fas fa-image fa-3x mb-2"></i>
                                                    <p class="mb-0">
                                                        No current image
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Template Preview</strong>
                                        </div>

                                        <div class="card-body text-center"
                                             style="
                                                min-height: 350px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                background: #f8f9fa;
                                             ">

                                            {{-- Placeholder --}}
                                            <div id="imagePreviewPlaceholder"
                                                 class="text-muted"
                                                 style="{{ !empty($idCardData->file_path) ? 'display:none;' : '' }}">

                                                <i class="fas fa-image fa-3x mb-2"></i>

                                                <p class="mb-0">
                                                    Image preview will appear here
                                                </p>

                                            </div>


                                            {{-- Preview --}}
                                            <img id="imagePreview"
                                                 src="{{ !empty($idCardData->file_path) ? asset('storage/' . $idCardData->file_path) : '' }}"
                                                 alt="Template Preview"
                                                 style="
                                                    {{ !empty($idCardData->file_path) ? '' : 'display:none;' }}
                                                    max-width:100%;
                                                    max-height:320px;
                                                    object-fit:contain;
                                                 ">

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">

                    <a href="{{ route('event-id-cards.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">

                        <i class="fas fa-save mr-1"></i>

                        {{ !empty($idCardData->id) ? 'Update' : 'Create' }}

                    </button>

                </div>
            </form>
            </div>
            </div>
        </div>
    </section>
</div>
<script>
    document.getElementById('image').addEventListener('change', function (event) {

        const file = event.target.files[0];

        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imagePreviewPlaceholder');

        if (!file) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            preview.src = e.target.result;
            preview.style.display = 'block';

            placeholder.style.display = 'none';
        };

        reader.readAsDataURL(file);
    });

    'onkeypress' => 'return isNumberfloatKey(event)',
 
    function isNumberfloatKey(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if(charCode == 46)
                return true;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
     
            return true;
        }
</script>
@endsection