@php
   // $defaultOrientation = \App\Models\Mainidcard::where('school_id',auth()->user()->school_id ?? session('viewing_school')
   // )->latest('id')->value('orientation') ?? 'vertical';
    $defaultOrientation = \App\Models\Mainidcard::where(
    'school_id',
    auth()->user()->school_id ?? session('viewing_school')
    )
    ->whereNull('class_id')
    ->whereNull('applicable_id')
    ->where('is_default', 1)
    ->value('orientation') ?? 'vertical';
@endphp
<div class="col-md-12">
   <div id="captureForm">
        <input type="hidden" name="student_id" id="student_id" class="form-control" readonly>
        <input type="hidden" name="photo_data" id="photo_data">
        <h3>Capture Photo (Laptop/Mobile)</h3>
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
        <div class="row mt-3">
            <div class="col-md-3">
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
                        <div id="camera-stage" style="background:#dbeafe;padding:8px;border-radius:8px;">
                            <div id="camera" style="position:relative;aspect-ratio:3/4;background:#fff;border-radius:8px;overflow:hidden;">
                                <div id="camera-feed" style="position:absolute;inset:0;"></div>
                                <div id="capture-frame" style="position:absolute;
                                                            left:50%;
                                                            top:50%;
                                                            width:62%;
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
            <div class="col-md-3">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Captured Photo</h3>
                    </div>
                    <div class="card-body text-center">
                        <div id="camera-preview" style="width:180px;
                                                    height:255px;
                                                    margin:auto;
                                                    left:50%;
                                                    top:50%;
                                                    border:1px solid #ccc;
                                                    border-radius:8px;
                                                    display:flex;
                                                    align-items:center;
                                                    justify-content:center;
                                                    overflow:hidden;
                                                    background:#fff;">
                            <img id="existing-student-photo" src="" style="width:100%;  height:100%; object-fit:cover; display:none;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-info">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">Live ID Card Preview</h3>
                        </div>
                        <div class="card-body p-2" style="overflow-x:auto;">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ $defaultOrientation === 'vertical' ? 'active' : '' }} get-student-card" id="student-vertical-tab" data-toggle="tab" href="#student-vertical-card"
                                        role="tab" data-card-orientation="vertical">
                                        <i class="fas fa-mobile-alt mr-1"></i>
                                        Vertical
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $defaultOrientation === 'horizontal' ? 'active' : '' }} get-student-card" id="student-horizontal-tab" data-toggle="tab" href="#student-horizontal-card"
                                        role="tab" data-card-orientation="horizontal">

                                        <i class="fas fa-mobile-alt fa-rotate-90 mr-1"></i>
                                        Horizontal

                                    </a>
                                </li>

                            </ul>
                            <div id="id-card-preview-container"></div>
                            {{-- @include('frontend.studentpartials.id-card-preview') --}}
                        </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
           <button type="button" id="save-capture-photo"  class="btn btn-primary">
                <i class="fas fa-save"></i>
                Save Photo
            </button>
        </div>
    </div>
</div>
