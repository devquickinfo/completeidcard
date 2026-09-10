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
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Card Sample</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Card Sample</li>
            </ol>
          </div>
        </div>
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
              <form id="quickForm" action="{{ route('upload-samples.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                        <label>Upload Samples</label>
                        <div id="sample-dropzone" class="dropzone">
                            <div class="dz-message">
                                <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                <br>
                                Drop images here or click to upload
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="button" id="upload-samples-btn" class="btn btn-primary"> Upload Samples</button>
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

@endsection