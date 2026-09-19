<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Register - {{ $manageEvent->event_name }}</title>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f7f9fc;
        color: #263238;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
                     Roboto, Helvetica, Arial, sans-serif;
    }

    .register-page {
        max-width: 760px;
        margin: 0 auto;
        padding: 20px 15px 60px;
    }

    .register-card {
        background: #ffffff;
        border: 1px solid #e9edf3;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(31, 45, 61, 0.07);
    }

    /* Header */

    .register-header {
        text-align: center;
        padding: 30px 20px 25px;
        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #f8fbff 100%
        );
        border-bottom: 1px solid #edf0f4;
    }

    .header-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 15px;
        border-radius: 18px;
        background: #eaf3ff;
        color: #2878d4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
    }

    .register-title {
        margin: 0;
        font-size: 25px;
        line-height: 1.35;
        font-weight: 700;
        color: #17212b;
    }

    .register-subtitle {
        margin: 8px 0 0;
        color: #7d8995;
        font-size: 14px;
    }

    .event-name {
        margin-top: 12px;
        display: inline-block;
        padding: 6px 13px;
        border-radius: 20px;
        background: #eef5ff;
        color: #2878d4;
        font-size: 12px;
        font-weight: 600;
    }

    /* Form */

    .register-content {
        padding: 25px;
    }

    .form-section-title {
        font-size: 17px;
        font-weight: 700;
        color: #202b36;
        margin-bottom: 18px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #4c5965;
        font-size: 13px;
        font-weight: 600;
    }

    .required {
        color: #dc3545;
    }

    .form-control {
        height: 46px;
        border: 1px solid #dfe5eb;
        border-radius: 10px;
        background: #fbfcfd;
        color: #263238;
        font-size: 14px;
        padding: 10px 13px;
        box-shadow: none;
    }

    textarea.form-control {
        height: auto;
        min-height: 100px;
        resize: vertical;
    }

    .form-control:focus {
        background: #ffffff;
        border-color: #2878d4;
        box-shadow: 0 0 0 3px rgba(40, 120, 212, 0.08);
    }

    .form-control::placeholder {
        color: #aab3bc;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca8b3;
        font-size: 14px;
        pointer-events: none;
    }

    .input-wrapper .form-control {
        padding-left: 40px;
    }

    .input-wrapper.textarea-wrapper .input-icon {
        top: 17px;
        transform: none;
    }

    .field-error {
        display: block;
        margin-top: 5px;
        color: #dc3545;
        font-size: 12px;
    }

    /* Footer */

    .register-footer {
        padding: 18px 25px 23px;
        border-top: 1px solid #edf0f4;
        background: #ffffff;
    }

    .register-btn {
        width: 100%;
        border: 0;
        border-radius: 12px;
        padding: 14px 20px;
        background: #2878d4;
        color: #ffffff !important;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 15px rgba(40, 120, 212, 0.22);
        transition: all .2s ease;
    }

    .register-btn i {
        margin-right: 9px;
    }

    .register-btn:hover {
        background: #216bbf;
        transform: translateY(-1px);
    }

    .back-btn {
        width: 100%;
        margin-top: 10px;
        border: 1px solid #dfe5eb;
        border-radius: 12px;
        padding: 12px 20px;
        background: #ffffff;
        color: #596774 !important;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .back-btn i {
        margin-right: 8px;
    }

    .back-btn:hover {
        background: #f7f9fc;
    }

    .footer-note {
        text-align: center;
        color: #9aa4ae;
        font-size: 11px;
        margin: 12px 0 0;
    }

    /* Mobile */

    @media (max-width: 575.98px) {

        .register-page {
            padding: 10px 10px 40px;
        }

        .register-card {
            border-radius: 15px;
        }

        .register-header {
            padding: 25px 16px 22px;
        }

        .header-icon {
            width: 62px;
            height: 62px;
            border-radius: 16px;
            font-size: 24px;
        }

        .register-title {
            font-size: 22px;
        }

        .register-subtitle {
            font-size: 13px;
        }

        .register-content {
            padding: 20px 16px;
        }

        .register-footer {
            padding: 15px 16px 18px;
        }

        .form-group {
            margin-bottom: 17px;
        }

        .form-control {
            height: 45px;
        }

        textarea.form-control {
            min-height: 90px;
        }
    }


    .photo-buttons {
        display: flex;
        gap: 10px;
    }

    .photo-btn {
        flex: 1;
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 8px;
        padding: 12px 10px;
        font-size: 14px;
        color: #333;
        cursor: pointer;
    }

    .photo-btn i {
        margin-right: 6px;
        color: #007bff;
    }

    .photo-btn:active {
        transform: scale(0.98);
    }

    #previewImage {
        border: 2px solid #eee;
    }

    @media (min-width: 768px) {
        .photo-btn {
            max-width: 200px;
        }
    }
</style>

</head>
<body>

<div class="register-page">


<div class="register-card">

    {{-- Header --}}

    <div class="register-header">

        <div class="header-icon">

            <i class="fas fa-user-plus"></i>

        </div>

        <h1 class="register-title">
            Event Registration
        </h1>

        <p class="register-subtitle">
            Please enter your details to register
        </p>

        <span class="event-name">
            {{ $manageEvent->event_name }}
        </span>

    </div>
    <form method="POST" action="{{ route('events.register.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="register-content">
            <div class="form-section-title">
                Your Information
            </div>
            <input type="hidden" name="unique_code" value="{{$manageEvent->unique_code}}">
            <div class="form-group">
                <label class="form-label">
                    Name <span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}"
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
                           value="{{ old('email') }}"
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
                           value="{{ old('mobile') }}"
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
                           value="{{ old('organization') }}"
                           placeholder="Enter organization name"
                           autocomplete="organization">

                </div>

                @error('organization')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>



            {{--<div class="form-group">
                <label class="form-label">
                    Photo
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
            </div>--}}

            <div class="form-group">
                <label class="form-label">
                    Photo
                </label>

                <div class="photo-buttons">

                    <button type="button"
                            class="photo-btn"
                            onclick="document.getElementById('cameraInput').click()">
                        <i class="fas fa-camera"></i>
                        Take Photo
                    </button>

                    <button type="button"
                            class="photo-btn"
                            onclick="document.getElementById('galleryInput').click()">
                        <i class="fas fa-images"></i>
                        Gallery
                    </button>

                </div>

                <input type="file"
                       id="cameraInput"
                       name="photo"
                       accept="image/*"
                       capture="user"
                       style="display:none;">

                <input type="file"
                       id="galleryInput"
                       accept="image/*"
                       style="display:none;">

                <div id="photoPreview" class="mt-2" style="display:none;">
                    <img id="previewImage"
                         src=""
                         alt="Photo Preview"
                         style="width:120px;height:120px;object-fit:cover;border-radius:10px;">
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
                              autocomplete="street-address">{{ old('address') }}</textarea>

                </div>
                @error('address')
                    <span class="field-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
        <div class="register-footer">
            <button type="submit"
                    class="register-btn">

                <i class="fas fa-check-circle"></i>

                Complete Registration

            </button>


            <a href="{{ route('events.public', $manageEvent->unique_code) }}"
               class="back-btn">

                <i class="fas fa-arrow-left"></i>

                Back to Event

            </a>


            <p class="footer-note">
                Your information will be used for this event registration.
            </p>

        </div>

    </form>

</div>

</div>
<script>
    const cameraInput = document.getElementById('cameraInput');
    const galleryInput = document.getElementById('galleryInput');
    const previewBox = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');

    function showPreview(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Show preview
            previewImage.src = URL.createObjectURL(file);
            previewBox.style.display = 'block';

            // Put selected file into the actual form input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            cameraInput.files = dataTransfer.files;
        }
    }

    cameraInput.addEventListener('change', function () {
        showPreview(this);
    });

    galleryInput.addEventListener('change', function () {
        showPreview(this);
    });
</script>
</body>

</html>
