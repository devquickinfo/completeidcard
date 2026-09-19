<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/dist/img/schoolid1.png') }}">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('frontend/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('frontend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('frontend/dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/app.css')}}">
  <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
      <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@mediapipe/selfie_segmentation/selfie_segmentation.js"></script>
  <style>

      /* School section */
      .school-navbar-info {
          display: flex;
          align-items: center;
          gap: 10px;
          margin-left: 15px;
          min-width: 0;
      }

      .school-name {
          color: #fff;
          text-decoration: none;
          font-weight: 500;

          max-width: 350px;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
      }

      .school-name:hover {
          color: #fff;
          text-decoration: underline;
      }

      .admin-menu-btn {
          white-space: nowrap;
      }


      /* Profile */
      .profile-image-link {
          display: flex;
          align-items: center;
      }

      .profile-image {
          width: 32px;
          height: 32px;
          object-fit: cover;
          display: block;
      }

      .profile-icon {
          font-size: 28px;
          line-height: 32px;
      }


      /* User */
      .user-dropdown-link {
          display: flex !important;
          align-items: center;
          white-space: nowrap;
      }

      .user-name {
          max-width: 150px;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
      }


      /* Mobile */
      @media (max-width: 767.98px) {

          .school-navbar-info {
              margin-left: 5px;
              gap: 5px;
              max-width: calc(100vw - 130px);
          }

          .school-name {
              max-width: 120px;
              font-size: 13px;
          }

          .admin-menu-btn {
              font-size: 11px;
              padding: 4px 7px;
          }

          .admin-menu-btn i {
              display: none;
          }

          .profile-image {
              width: 28px;
              height: 28px;
          }

          .profile-icon {
              font-size: 25px;
          }

          .user-name {
              display: none;
          }

          .user-dropdown-link {
              padding-left: 5px !important;
              padding-right: 5px !important;
          }

          .user-dropdown-link .fa-user {
              margin-right: 0 !important;
          }

          .user-dropdown-link .fa-caret-down {
              margin-left: 3px !important;
          }

      }


      /* Very small phones */
      @media (max-width: 400px) {

          .school-navbar-info {
              max-width: calc(100vw - 115px);
          }

          .school-name {
              max-width: 90px;
              font-size: 12px;
          }

          .admin-menu-btn {
              font-size: 10px;
              padding: 3px 5px;
          }

          .profile-image {
              width: 26px;
              height: 26px;
          }

      }
      #mobile-settings-toggle {
            display: none;
        }

        @media (max-width: 767.98px) {

            /* Floating cog button */
            #mobile-settings-toggle {
                display: flex !important;

                position: fixed !important;

                right: 15px !important;
                bottom: 15px !important;

                width: 50px !important;
                height: 50px !important;

                padding: 0 !important;

                align-items: center !important;
                justify-content: center !important;

                border-radius: 50% !important;

                z-index: 99999 !important;

                font-size: 20px !important;

                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
            }

            /* Floating settings panel */
            #camera-settings-card {
                position: fixed !important;

                right: 15px !important;
                bottom: 75px !important;

                width: 200px !important;

                margin: 0 !important;

                z-index: 99998 !important;

                border-radius: 8px !important;

                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3) !important;
            }

            /* Hide settings */
            #camera-settings-card.mobile-hidden {
                display: none !important;
            }

            /* Compact header */
            #camera-settings-card .card-header {
                padding: 8px 10px !important;
            }

            #camera-settings-card .card-title {
                font-size: 13px !important;
                margin: 0 !important;
            }

            /* Compact body */
            #camera-settings-card .card-body {
                padding: 10px !important;
            }

            #camera-settings-card label {
                font-size: 12px !important;
                margin-bottom: 3px !important;
            }

            #camera-settings-card .form-group {
                margin-bottom: 8px !important;
            }

            #camera-settings-card select {
                height: 32px !important;
                padding: 3px 6px !important;
                font-size: 12px !important;
            }

            #camera-settings-card .btn {
                font-size: 12px !important;
                padding: 6px !important;
            }

        }

        #camera-stage,
        #camera-feed {
            position: relative;
            width: 100%;
            aspect-ratio: 3 / 4;   /* match whatever ID-photo shape you want */
            overflow: hidden;
        }
        .modal-content{
               width: 1000px !important;
         }
         @media (max-width: 767px) {
           .modal-content {
                width: 100% !important;
                max-width: 100% !important;
            }

            .modal-dialog {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
            }

            .modal-body {
                overflow-x: hidden !important;
            }
        }

  </style>
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed text-sm">
<div class="wrapper">
  @php
        use App\Helpers\ImageHelper;
        $schoolID = session('school_id');
        $schoolname = '';
        if (session()->has('viewing_school')) {
            $schoolID = session('viewing_school');
            $schoolname = ImageHelper::getSchoolName($schoolID);
        }
        if (session()->has('vendor_viewing')) {
            $schoolID = session('vendor_viewing');
            $schoolname = ImageHelper::getSchoolName($schoolID);
        }
  @endphp
  
   @include('frontend.layout.navbar');
   @include('frontend.layout.sidebar');
   @yield('content')



  <footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="">IDCard</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</div>

<script src="{{asset('frontend/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('frontend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('frontend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<script src="{{asset('frontend/dist/js/adminlte.js')}}"></script>
<script src="{{asset('frontend/plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
<script src="{{asset('frontend/plugins/raphael/raphael.min.js')}}"></script>
<script src="{{asset('frontend/plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
<script src="{{asset('frontend/plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
<script src="{{asset('frontend/plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('frontend/dist/js/demo.js')}}"></script>
<script src="{{asset('frontend/dist/js/pages/dashboard2.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{asset('js/app.js')}}"></script>
<script>
  $(document).ready(function () {
      $('#class_id').on('change', function () {
          let classId = $(this).val();
          $('#section_id').html('<option value="">Select Section</option>');
          if (classId) {
              $.ajax({
                  url: '/sections/' + classId,
                  type: 'GET',
                  success: function (response) {

                      $.each(response, function (index, section) {
                          $('#section_id').append(
                              '<option value="' + section.id + '">' + section.name + '</option>'
                          );
                      });

                  }
              });
          }
      });
  });
</script>
<script>
  let stream = null;
  async function startCamera() {
      if (stream) {
          stream.getTracks().forEach(track => track.stop());
      }
      const facingModeInput = document.getElementById('camera-facing-mode');
      const cameraFeed = document.getElementById('camera-feed');

      if (!facingModeInput || !cameraFeed) {
          return;
      }

      const facingMode = facingModeInput.value;
      try {
          stream = await navigator.mediaDevices.getUserMedia({
              video: {
                  facingMode: { ideal: facingMode }
              },
              audio: false
          });
          const video = document.createElement('video');
          video.autoplay = true;
          video.playsInline = true;
          video.muted = true;
          video.srcObject = stream;
          video.style.width = "100%";
          video.style.height = "100%";
          video.style.objectFit = "cover";

          cameraFeed.innerHTML = "";
          cameraFeed.appendChild(video);

          await video.play();
          if (video.videoWidth && video.videoHeight) {
              video.style.width = "100%";
              video.style.height = "100%";
          }

      } catch (err) {
          console.error(err);
          console.log(err.name + "\n" + err.message);
      }
  }
  // Start button
  const startCameraButton = document.getElementById('start-camera');
  const cameraFacingMode = document.getElementById('camera-facing-mode');
  const capturePhotoButton = document.getElementById('capture-photo');

  if (startCameraButton) {
      startCameraButton.addEventListener('click', startCamera);
  }
  // Change camera (Front/Back)
  if (cameraFacingMode) {
      cameraFacingMode.addEventListener('change', startCamera);
  }
  // Capture
  if (capturePhotoButton) {
      capturePhotoButton.addEventListener('click', function () {
      const video = document.querySelector('#camera-feed video');
      if (!video) {
          alert("Please start the camera first.");
          return;
      }
      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth || 640;
      canvas.height = video.videoHeight || 480;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0);
      const image = canvas.toDataURL("image/png");
      const photoData = document.getElementById('photo_data');
      const cameraPreview = document.getElementById('camera-preview');
      const previewPhoto = document.getElementById('preview-photo');

      if (photoData) {
          photoData.value = image;
      }

      if (cameraPreview) {
          cameraPreview.innerHTML =
              '<img src="' + image + '" style="width:100%;height:100%;object-fit:cover;">';
      }

      if (previewPhoto) {
          previewPhoto.src = image;
          previewPhoto.style.display = 'block';
      }
      });
  }
    
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('studentSearch');
        var tableRows = Array.from(document.querySelectorAll('#studentTable tbody tr'));
        if (!searchInput || tableRows.length === 0) {
            return;
        }
        searchInput.addEventListener('keyup', function () {
            var value = this.value.trim().toLowerCase();
            tableRows.forEach(function (row) {
                var admissionNo = row.cells[0].textContent.toLowerCase();
                var name = row.cells[1].textContent.toLowerCase();
                var phone = row.cells[3].textContent.toLowerCase();
                if (value.length < 3) {
                    row.style.display = '';
                } else {
                    var matches = admissionNo.indexOf(value) > -1 || name.indexOf(value) > -1 || phone.indexOf(value) > -1;
                    row.style.display = matches ? '' : 'none';
                }
            });
        });
    });
</script>
<script>
  $(document).on('change', '#selectAll', function () {
      $('.student-checkbox').prop('checked', this.checked);
  });

  $(document).on('change', '.student-checkbox', function () {
      $('#selectAll').prop(
          'checked',
          $('.student-checkbox').length === $('.student-checkbox:checked').length
      );
  });

  $("#upload-samples-btn").on("click", function () {

      if (sampleDropzone.files.length === 0) {
          alert("Please select at least one image.");
          return;
      }
      sampleDropzone.processQueue();
  });
  Dropzone.autoDiscover = false;
  const sampleDropzone = new Dropzone("#sample-dropzone", {

      url: "{{ route('upload-samples.store') }}",

      paramName: "upload_samples",

      method: "POST",
      autoProcessQueue: false, 

      uploadMultiple: false,
      parallelUploads: 8,

      acceptedFiles: "image/*",
      maxFilesize: 40,

      addRemoveLinks: true,

      headers: {
          "X-CSRF-TOKEN": document
              .querySelector('meta[name="csrf-token"]')
              .getAttribute("content")
      },

      // Add fields to each Dropzone preview
      init: function () {

          this.on("addedfile", function (file) {

              let preview = $(file.previewElement);

              preview.append(`
                  <div class="sample-fields mt-2">

                      <input
                          type="text"
                          class="form-control form-control-sm sample-name mb-2"
                          placeholder="Image Name"
                          value="${file.name}"
                      >

                      <input
                          type="text"
                          class="form-control form-control-sm sample-caption mb-2"
                          placeholder="Caption"
                      >

                      <select
                          class="form-control form-control-sm sample-orientation"
                      >
                          <option value="horizontal">
                              Horizontal
                          </option>

                          <option value="vertical">
                              Vertical
                          </option>
                      </select>

                  </div>
              `);
          });

          this.on("sending", function (file, xhr, formData) {

              let preview = $(file.previewElement);

              let imageName = preview
                  .find(".sample-name")
                  .val() || file.name;

              let caption = preview
                  .find(".sample-caption")
                  .val() || "";

              let orientation = preview
                  .find(".sample-orientation")
                  .val() || "horizontal";

              formData.append("image_name", imageName);
              formData.append("caption", caption);
              formData.append("orientation", orientation);

              console.log("========== FORMDATA ==========");

              for (let pair of formData.entries()) {
                  console.log(
                      pair[0],
                      pair[1],
                      pair[1] instanceof File
                  );
              }
          });

          this.on("queuecomplete", function () {
              console.log("All files uploaded successfully.");
              window.location.href = "{{ route('upload-samples.index') }}";

          });

          this.on("error", function (file, error) {

              console.log("ERROR:", error);
          });
      }
  });
</script>
 <script>
    $(document).on('change', '.sample-radio', function () {
        $('#selected-sample-id').val($(this).val());
    });
</script>
<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}", 'Success');
    @endif
</script>
<script>
    $(document).on('click', '.btn-danger', function (e) {

        e.preventDefault();


        let button = this;
        let form = $(button).closest('form');
        let buttonText = $(button).text().trim();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to undo this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${buttonText} it!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (result.isConfirmed) {

                if (form.length) {
                    form.submit();
                } else if (button.tagName === 'A') {
                    window.location.href = button.href;
                }

            }

        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const admissionNo  = document.querySelector('[name="admission_no"]');
    const firstName    = document.querySelector('[name="first_name"]');
    const lastName     = document.querySelector('[name="last_name"]');
    const fatherName   = document.querySelector('[name="father_name"]');
    const section      = document.querySelector('[name="section_id"]');
    const dob          = document.querySelector('[name="date_of_birth"]');
    const classSelect  = document.querySelector('[name="class_id"]');
    const bloodGroup   = document.querySelector('[name="blood_group"]');
    const phone        = document.querySelector('[name="phone"]');


    function setText(id, value, defaultText) {

        const element = document.getElementById(id);

        if (element) {
            element.textContent =
                value && value.trim()
                    ? value
                    : defaultText;
        }
    }


    function updateCard() {

        // Student Name
        let first = firstName ? firstName.value.trim() : '';
        let last  = lastName ? lastName.value.trim() : '';

        let fullName = `${first} ${last}`.trim();

        setText(
            'cardStudentName',
            fullName,
            'Student Name'
        );


        // Father Name
        setText(
            'cardFatherName',
            fatherName ? fatherName.value : '',
            'Father Name'
        );


        // Admission Number
        setText(
            'cardAdmissionNo',
            admissionNo ? admissionNo.value : '',
            'Admission No'
        );


        // Section
        if (section) {

            const selectedOption =
                section.options[section.selectedIndex];

            if (selectedOption && selectedOption.value) {

                setText(
                    'cardSection',
                    selectedOption.text,
                    'Section'
                );

            } else {

                setText(
                    'cardSection',
                    '',
                    'Section'
                );
            }
        }


        // Blood Group
        setText(
            'cardBloodGroup',
            bloodGroup ? bloodGroup.value : '',
            'Blood Group'
        );


        // Phone
        setText(
            'cardPhone',
            phone ? phone.value : '',
            'Phone'
        );


        // Class
        if (classSelect) {

            const selectedOption =
                classSelect.options[classSelect.selectedIndex];

            if (selectedOption && selectedOption.value) {

                setText(
                    'cardClass',
                    selectedOption.text,
                    'Class'
                );

            } else {

                setText(
                    'cardClass',
                    '',
                    'Class'
                );
            }
        }


        // Date of Birth
        if (dob && dob.value) {

            const parts = dob.value.split('-');

            if (parts.length === 3) {

                const formattedDob =
                    `${parts[2]}-${parts[1]}-${parts[0]}`;

                setText(
                    'cardDob',
                    formattedDob,
                    'DOB'
                );
            }

        } else {

            setText(
                'cardDob',
                '',
                'DOB'
            );
        }
    }


    // Listen for changes

    if (admissionNo) {
        admissionNo.addEventListener('input', updateCard);
    }

    if (firstName) {
        firstName.addEventListener('input', updateCard);
    }

    if (lastName) {
        lastName.addEventListener('input', updateCard);
    }

    if (fatherName) {
        fatherName.addEventListener('input', updateCard);
    }

    if (section) {
        section.addEventListener('change', updateCard);
    }

    if (dob) {
        dob.addEventListener('change', updateCard);
    }

    if (classSelect) {
        classSelect.addEventListener('change', updateCard);
    }

    if (bloodGroup) {
        bloodGroup.addEventListener('input', updateCard);
    }

    if (phone) {
        phone.addEventListener('input', updateCard);
    }


    // Initial card render
    updateCard();

});
</script>
<script>
$(document).on('change', '.sample-radio', function () {

    var sampleId = $(this).val();
    var orientation = $(this).data('orientation');

    console.log('Sample:', sampleId);
    console.log('Orientation:', orientation);

    if (orientation === 'vertical') {

        $('#selected-vertical-sample-id').val(sampleId);

    }

    if (orientation === 'horizontal') {

        $('#selected-horizontal-sample-id').val(sampleId);

    }

});
</script>
<script>
  $(document).on('click', '[data-target="#photoModal"]', function () {
      let studentId = $(this).data('student-id');
      console.log('Clicked Student ID:', studentId);
      $('#photoModal #student_id').val(studentId);
      console.log(
          'Modal Student ID:',
          $('#photoModal #student_id').val()
      );
      $('#existing-student-photo')
          .hide()
          .attr('src', '');
      $('#preview-placeholder').show();
      $.ajax({
          url: "{{ url('/student') }}/" + studentId + "/photo",
          type: "GET",
          success: function(response) {
              console.log('Photo response:', response);
              if (response.photo) {
                  $('#existing-student-photo')
                      .attr('src', response.photo)
                      .show();
                  $('#preview-placeholder').hide();
              } else {
                  $('#existing-student-photo').hide();
                  $('#preview-placeholder').show();
              }
          },
          error: function(xhr) {
              console.log('Photo loading error:', xhr);
          }
      });
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const settingsCard = document.getElementById('camera-settings-card');
    const settingsButton = document.getElementById('mobile-settings-toggle');

    if (!settingsCard || !settingsButton) {
        return;
    }

    // Hide settings initially on mobile
    if (window.innerWidth <= 767) {
        settingsCard.classList.add('mobile-hidden');
    }

    settingsButton.addEventListener('click', function () {

        settingsCard.classList.toggle('mobile-hidden');

    });

});
</script>
<script>
    $(document).on('click', '.get-student-card', function (event) {
        event.preventDefault();

        let studentId = $('#student_id').val();
        let orientation = $(this).attr('data-card-orientation');
        let previewUrl = "{{ route('student.card.preview', ['id' => '__STUDENT_ID__']) }}";

        if (!studentId) {
            $('#id-card-preview-container').html(`
                <div class="alert alert-danger">
                    Student ID is missing.
                </div>
            `);
            return;
        }

        previewUrl = previewUrl.replace('__STUDENT_ID__', studentId);
        previewUrl += '?orientation=' + encodeURIComponent(orientation);
        $('#student_id').val(studentId);

        // Show loading
        $('#id-card-preview-container').html(`
            <div class="text-center p-4">
                <i class="fas fa-spinner fa-spin"></i>
                Loading ID Card...
            </div>
        `);

        $.ajax({
            url: previewUrl,
            type: "GET",

            success: function (html) {
                $('#id-card-preview-container').html(html);
                $('#student-' + orientation + '-tab').tab('show');
            },

            error: function (xhr) {
                console.log(xhr.responseText);

                $('#id-card-preview-container').html(`
                    <div class="alert alert-danger">
                        Unable to load ID card preview.
                    </div>
                `);
            }
        });

    });
</script>
<script>
    $(document).on('click', '[data-target="#photoModal"][data-student-id]', function () {
        $('#photoModal #student_id').val($(this).data('student-id'));
    });

    $('#photoModal').on('shown.bs.modal', function () {
        $(this).find('.get-student-card.active').first().trigger('click');
    });
</script>
@yield('scripts')

</body>
</html>
