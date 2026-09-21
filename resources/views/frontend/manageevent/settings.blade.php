@extends('frontend.layout.applayout')

@section('title', 'Settings')

@section('content')


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         <!--  <div class="col-sm-6">
            <h1>Add School</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add School</li>
            </ol>
          </div> -->
        </div>
      </div>
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
                <h3 class="card-title">Settings</h3>
                <a href="{{ route('manage-events.index') }}" class="btn btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
              </div>
              <form id="quickForm" action="{{ route('schools.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="field1">Field 1 </label>
                        <input type="text" name="field1" class="form-control" id="field1" placeholder="" value="{{ old('field1') }}">
                      </div>
                       @error('school_code')
                            <span class="text-danger">{{ $message }}</span>
                       @enderror
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="field2">Field 2</label>
                        <input type="text" name="field2" class="form-control" id="field2" placeholder="" value="{{ old('field2') }}">
                      </div>
                       @error('field2')
                            <span class="text-danger">{{ $message }}</span>
                       @enderror
                    </div>
                </div>
              
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
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