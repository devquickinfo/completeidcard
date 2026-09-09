@extends('frontend.layout.applayout')
@section('title', 'Add Teacher')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <!-- <div class="col-sm-6">
            <h1>Add Teacher</h1>
          </div> -->
        </div>
      </div>
    </section>

     <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">{{ isset($teacher) ? 'EDIT TEACHER' : 'ADD TEACHER' }}</h3>
                            <a href="{{ route('teacher.list') }}"
                                class="btn btn-secondary btn-sm ml-auto flex-shrink-0">
                                <i class="fas fa-arrow-left"></i>
                                <span class="d-none d-sm-inline ml-1">Back</span>
                            </a>
                        </div>
                       <form id="quickForm" action="{{ isset($teacher) ? route('teachers.update', $teacher->id) : route('teachers.store') }}"                     method="POST"
                                            enctype="multipart/form-data">
                                          @csrf
                                          @if(isset($teacher))
                                              @method('PUT')
                                          @endif
                                <input type="hidden" name="photo_data" id="photo_data" value="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <h5 class="text-center">
                                                <span class="badge badge-info">Teacher Details</span>
                                            </h5>

                                            <div class="row">

                                                <!-- Employee No -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="employee_code">Employee No</label>

                                                        <input type="text"
                                                               name="employee_code"
                                                               class="form-control"
                                                               id="employee_code"
                                                               placeholder="Enter Employee No"
                                                               value="{{ old('employee_code', @$teacher->employee_code ?? '') }}">

                                                        @error('employee_code')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Full Name -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="full_name">
                                                            Full Name <span class="text-danger">*</span>
                                                        </label>

                                                        <input type="text"
                                                               name="first_name"
                                                               class="form-control"
                                                               id="full_name"
                                                               placeholder="Enter Full Name"
                                                               value="{{ old('first_name', trim((@$teacher->first_name ?? '') . ' ' . ($teachert->last_name ?? ''))) }}">

                                                        @error('first_name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Father Name -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="father_name">
                                                            Father/Husband Name <span class="text-danger">*</span>
                                                        </label>

                                                        <input type="text"
                                                               name="father_name"
                                                               class="form-control"
                                                               id="father_name"
                                                               placeholder="Enter Father Name"
                                                               value="{{ old('father_name', @$teacher->father_husband ?? '') }}">

                                                        @error('father_name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Gender -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="gender">Gender</label>

                                                        <select name="gender"
                                                                id="gender"
                                                                class="form-control">

                                                            <option value="">-- Select Gender --</option>

                                                            <option value="Male"
                                                                {{ old('gender', @$teacher->gender ?? '') == 'Male' ? 'selected' : '' }}>
                                                                Male
                                                            </option>

                                                            <option value="Female"
                                                                {{ old('gender', @$teacher->gender ?? '') == 'Female' ? 'selected' : '' }}>
                                                                Female
                                                            </option>

                                                            <option value="Other"
                                                                {{ old('gender', @$teacher->gender ?? '') == 'Other' ? 'selected' : '' }}>
                                                                Other
                                                            </option>

                                                        </select>

                                                        @error('gender')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Date of Birth -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="date_of_birth">Date of Birth</label>
                                                        <input type="date"
                                                               name="dob"
                                                               class="form-control"
                                                               id="dob"
                                                               value="{{ old('dob', @$teacher->dob ? \Carbon\Carbon::parse(@$teacher->dob)->format('Y-m-d') : '') }}">

                                                        @error('dob')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Phone -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="phone">Phone</label>

                                                        <input type="text"
                                                               name="phone"
                                                               class="form-control"
                                                               id="phone"
                                                               placeholder="Enter Phone"
                                                               value="{{ old('phone', @$teacher->phone ?? '') }}">
                                                    </div>
                                                </div>

                                                <!-- Photo Upload -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="photo">Photo Upload</label>

                                                        <input type="file"
                                                               name="photo"
                                                               class="form-control"
                                                               id="photo"
                                                               accept="image/jpeg,image/png,image/webp">
                                                    </div>
                                                </div>

                                                <!-- Address -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="address">Address</label>

                                                        <textarea name="address"
                                                                  class="form-control"
                                                                  rows="1"
                                                                  id="address"
                                                                  placeholder="Enter Address">{{ old('address', @$teacher->address ?? '') }}</textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- End nested row -->
                                        </div>
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
                                                                    @if(isset($teacher) && $teacher->photo)
                                                                    <img src="{{ asset('storage/' . $teacher->photo) }}"
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
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($teacher) ? 'Update' : 'Submit' }}
                                    </button>
                                </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


@endsection
