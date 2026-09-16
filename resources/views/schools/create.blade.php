@extends('frontend.layout.applayout')
@section('title', 'Add School')
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
                <h3 class="card-title">ADD SCHOOL</h3>
                <a href="{{ route('schools.index') }}" class="btn btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
              </div>
              <form id="quickForm" action="{{ route('schools.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="school_code">School Code </label>
                        <input type="text" name="school_code" class="form-control" id="school_code" placeholder="Enter School Code" value="{{ old('school_code') }}">
                      </div>
                       @error('school_code')
                            <span class="text-danger">{{ $message }}</span>
                       @enderror
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="school_name">School Name <span class="text-danger"> * </span></label>
                        <input type="text" name="school_name" class="form-control" id="school_name" placeholder="Enter School Name" value="{{ old('school_name') }}">
                      </div>
                       @error('school_name')
                            <span class="text-danger">{{ $message }}</span>
                       @enderror
                     </div>
                     <div class="col-md-3">
                      <div class="form-group">
                        <label for="username">User Name <span class="text-danger"> * </span></label>
                        <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" value="{{ old('username') }}">
                      </div>
                       @error('username')
                            <span class="text-danger">{{ $message }}</span>
                       @enderror
                     </div>
                     <div class="col-md-3">
                      <div class="form-group">
                        <label for="email">Password <span class="text-danger"> * </span></label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" value="">
                      </div>
                       @error('password')
                            <span class="text-danger">{{ $message }}</span>
                       @enderror
                     </div>
                     <div class="col-md-3">
                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" value="{{ old('email') }}">
                      </div>
                    </div>
                    <div class="col-md-3">                    
                      <div class="form-group">
                        <label for="phone">Phone <span class="text-danger"> * </span></label>
                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone" value="{{ old('phone') }}">
                      </div>
                       @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                       @enderror
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="school_logo">School Logo</label>
                        <input type="file" name="school_logo" class="form-control" id="school_logo" placeholder="Upload School Logo">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" name="city" class="form-control" id="city" placeholder="Enter City" value="{{ old('city') }}">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" name="state" class="form-control" id="state" placeholder="Enter State" value="{{ old('state') }}">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="pincode">Pincode</label>
                        <input type="text" name="pincode" class="form-control" id="pincode" placeholder="Enter Pincode" value="{{ old('pincode') }}">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="address">School Address</label>
                        <textarea name="address" class="form-control" id="address" rows="5" placeholder="Enter School Address">{{ old('address') }}</textarea>
                      </div>
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