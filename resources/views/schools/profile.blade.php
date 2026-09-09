@extends('frontend.layout.applayout')
@section('title', 'School Profile')
@section('content')
<style>
  .school-readonly {
        background-color: #495057 !important;
        color: #fff !important;
        cursor: not-allowed;
    }

    .school-readonly:hover {
        background-color: #495057 !important;
    }
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
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">
                        Update your school and account details
                    </h3>

                    <a href="{{ url()->previous() }}"
                    class="btn btn-secondary btn-sm ml-auto">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('school.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>School Name <span class="text-danger"> * </span></label>
                                    <input type="text" name="school_name" class="form-control {{ Auth::user()?->role === 'school' ? 'school-readonly' : '' }}" value="{{ old('school_name', $school->school_name) }}" @if(Auth::user()?->role === 'school') readonly @endif>
                                    @error('school_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>School Code</label>
                                    <input type="text" name="school_code" class="form-control {{ Auth::user()?->role === 'school' ? 'school-readonly' : '' }}" value="{{ old('school_code', $school->school_code) }}" @if(Auth::user()?->role === 'school') readonly @endif>
                                    @error('school_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Principal Name </label>
                                    <input type="text" name="principal_name" class="form-control" value="{{ old('principal_name', $schoolUser?->name ?? '') }}">
                                    @error('principal_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Phone <span class="text-danger"> * </span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $school->phone) }}">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $school->email) }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Username <span class="text-danger"> * </span></label>
                                    <input type="text" name="username" class="form-control" value="{{ old('username', $schoolUser?->username ?? '') }}">
                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" autocomplete="new-password">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{---<div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                                    @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>--}}

                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea name="address" class="form-control" rows="1">{{ old('address', $school->address) }}</textarea>
                                </div>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                           
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Logo</label>
                                    <input type="file" name="school_logo" class="form-control">
                                </div>
                                @error('school_logo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <img src="{{ $school->logo ? asset('storage/' . $school->logo) : asset('images/default-logo.png') }}" alt="School Logo" class="img-fluid mt-2" style="max-height: 100px;">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Principal Signature Upload</label>
                                    <input type="file"
                                        name="principal_signature"
                                        id="signatureInput"
                                        class="form-control"
                                        accept="image/*">

                                    @error('principal_signature')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div class="mt-2">
                                        <img
                                            id="signaturePreview"
                                            src="{{ $school->principal_signature
                                                ? asset('storage/' . $school->principal_signature)
                                                : asset('images/default-signature.png') }}"
                                            alt="Principal Signature"
                                            class="img-fluid"
                                            style="max-height: 100px;"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Save Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="signatureCropModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Crop Principal Signature
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">

                <div style="max-height: 500px;">
                    <img id="signatureCropImage"
                         src=""
                         style="max-width: 100%;">
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Cancel
                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="cropSignatureBtn">
                    <i class="fas fa-crop-alt mr-1"></i>
                    Crop & Use Signature
                </button>

            </div>

        </div>
    </div>
</div>
<script>
    
    let signatureCropper = null;
    document.getElementById('signatureInput').addEventListener('change', function (event) {

        const file = event.target.files[0];

        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            this.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            const image = document.getElementById('signatureCropImage');

            image.src = e.target.result;

            $('#signatureCropModal').modal('show');

            $('#signatureCropModal').off('shown.bs.modal').on('shown.bs.modal', function () {

                if (signatureCropper) {
                    signatureCropper.destroy();
                }

                signatureCropper = new Cropper(image, {
                    aspectRatio: 3 / 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.9,
                    responsive: true,
                    background: false,
                    movable: true,
                    zoomable: true,
                    rotatable: false,
                    scalable: false
                });

            });
        };

        reader.readAsDataURL(file);
    });


    document.getElementById('cropSignatureBtn').addEventListener('click', function () {

        if (!signatureCropper) {
            return;
        }

        const canvas = signatureCropper.getCroppedCanvas({
            width: 600,
            height: 200,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        canvas.toBlob(function (blob) {

            const file = new File(
                [blob],
                'principal_signature.png',
                {
                    type: 'image/png'
                }
            );

            /*
             * Put cropped image into the file input
             */
            const dataTransfer = new DataTransfer();

            dataTransfer.items.add(file);

            document.getElementById('signatureInput').files =
                dataTransfer.files;

            /*
             * Preview cropped signature
             */
            document.getElementById('signaturePreview').src =
                canvas.toDataURL('image/png');

            /*
             * Close modal
             */
            $('#signatureCropModal').modal('hide');

            /*
             * Destroy cropper
             */
            signatureCropper.destroy();
            signatureCropper = null;

        }, 'image/png');

    });
</script>
@endsection
