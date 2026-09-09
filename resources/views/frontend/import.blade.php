@extends('frontend.layout.applayout')

@section('title', 'Import Students')

@section('content')

<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <!-- <div class="col-sm-6">
                    <h1>Import Students</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Import Students
                        </li>
                    </ol>
                </div> -->
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">

                <!-- Import Form -->
                <div class="col-md-7">

                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title">
                                Import Students
                            </h3>
                        </div>

                        <form action="{{ route('student.import.store') }}"
                              method="POST"
                              enctype="multipart/form-data">

                            @csrf
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ session('success') }}

                                    <button type="button"
                                            class="close"
                                            data-dismiss="alert"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="card-body">

                                <div class="form-group">
                                    <label>Assign Class (Optional)</label>

                                    <select name="class_id" class="form-control">
                                        <option value="">
                                            Use class_id from Excel
                                        </option>

                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">
                                                {{ $class->name }} (ID: {{ $class->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Assign Section (Optional)</label>

                                    <select name="section_id" class="form-control">
                                        <option value="">
                                            Use section_id from Excel
                                        </option>

                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}">
                                                {{ $section->name }} (ID: {{ $section->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Upload Excel File</label>

                                    <input type="file"
                                           name="file"
                                           class="form-control">

                                    @error('file')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i>
                                    Import Students
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

                <!-- Information Card -->
                <div class="col-md-5">

                    <div class="card card-success">

                        <div class="card-header">
                            <h3 class="card-title">
                                Master Data
                            </h3>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered">

                                <tr>
                                    <th width="50%">Total Classes</th>
                                    <td>{{ $classes->count() }}</td>
                                </tr>

                                <tr>
                                    <th>Total Sections</th>
                                    <td>{{ $sections->count() }}</td>
                                </tr>

                                <tr>
                                    <th>Static Template</th>
                                    <td>
                                        <a href="{{ route('student.sample') }}"
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-download"></i>
                                            Download
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Dynamic Template</th>
                                    <td>
                                        <a href="{{ route('student.dynamic.sample') }}"
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-download"></i>
                                            Download
                                        </a>
                                    </td>
                                </tr>

                            </table>

                            <hr>

                            <h5>Required Columns</h5>

                            <ul class="mb-0">
                                <li>Full Name (Required)</li>
                                <!-- <li>last_name</li> -->
                                <li>Father Name (Required)</li>
                                <li>gender (optional)</li>
                                <li>date_of_birth (optional)</li>
                                <li>admission_no (optional)</li>
                                <li>phone (Required)</li>
                                <li>class_id (optional)</li>
                                <li>section_id (optional)</li>
                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

</div>

@endsection