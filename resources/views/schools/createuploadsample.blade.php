@extends('frontend.layout.applayout')
@section('title', 'Add Card Sample')
@section('content')
<style>
  #sample-dropzone {
    background-color: #fff;
    border: 2px dashed #999;
  }

  #sample-dropzone .dz-message {
      color: #333 !important;
  }

  #sample-dropzone .dz-message span {
      color: #333 !important;
  }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
       <!--  <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Card Sample</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Card Sample</li>
            </ol>
          </div>
        </div> -->
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Card Sample</h3>
                <a href="{{ route('upload-samples.index') }}" class="btn btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
              </div>
                  <form id="quickForm" action="{{ route('upload-single.store') }}"  method="POST"  enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $singleSample->id ?? '' }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="template_name">Template Name</label>
                                        <input type="text"
                                               name="name"
                                               id="template_name"
                                               class="form-control"
                                               placeholder="Template Name"
                                               value="{{ old('name', $singleSample->name ?? '') }}"
                                               required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="applicable_id">Applicable User</label>
                                        <select name="applicable_id" id="applicable_id"  class="form-control">
                                            @foreach($applicables as $applicable)
                                                <option value="{{ $applicable->id }}"
                                                    {{ old('applicable_id', $singleSample->applicable_id ?? '') == $applicable->id ? 'selected' : '' }}>
                                                    {{ ucwords($applicable->type) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('applicable_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_id">Apply</label>
                                        <select name="class_id" id="class_id"  class="form-control" >
                                            <option value="">Global</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ old('class_id', $singleSample->class_id ?? '') == $class->id ? 'selected' : 'Global' }}>
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
                                        <label for="orientation">Orientation</label>
                                        <select name="orientation"  id="orientation"  class="form-control" >
                                            <option value="horizontal"
                                                {{ old('orientation', $singleSample->orientation ?? '') == 'horizontal' ? 'selected' : '' }}>
                                                Horizontal
                                            </option>
                                            <option value="vertical"
                                                {{ old('orientation', $singleSample->orientation ?? '') == 'vertical' ? 'selected' : '' }}>
                                                Vertical
                                            </option>
                                        </select>
                                        @error('orientation')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="house_id">House</label>
                                        <select name="house_id" id="house_id" class="form-control">
                                            @foreach($houses as $house)
                                                <option value="{{ $house->id }}"
                                                    {{ old('house_id', $singleSample->house_id ?? '') == $house->id ? 'selected' : '' }}>
                                                    {{ ucwords($house->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('house_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                {{-- Sample Upload --}}
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="sampleupload">Sample Upload</label>

                                        <input type="file"
                                               class="form-control"
                                               name="sampleupload"
                                               id="sampleupload"
                                               accept="image/*">

                                        @error('sampleupload')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror


                                        {{-- Image Preview --}}
                                        <div id="samplePreviewContainer"
                                             style="margin-top:10px; {{ !empty($singleSample->file_path) ? '' : 'display:none;' }}">

                                            <img id="samplePreview"
                                                 src="{{ !empty($singleSample->file_path) ? asset('storage/' . $singleSample->file_path) : '' }}"
                                                 alt="Sample Preview"
                                                 style="max-width:100%; max-height:250px; border:1px solid #ddd; padding:5px; border-radius:5px;">

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-primary">
                                {{ !empty($singleSample->id) ? 'Update' : 'Save' }}
                            </button>
                            <a href="{{ route('upload-samples.index') }}"
                               class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                  </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<script>
document.getElementById('sampleupload').addEventListener('change', function (event) {

    const file = event.target.files[0];
    const preview = document.getElementById('samplePreview');
    const container = document.getElementById('samplePreviewContainer');

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

    } else {
        preview.src = '';
        container.style.display = 'none';
    }
});
</script>
@endsection