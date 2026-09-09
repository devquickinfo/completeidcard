@extends('frontend.layout.applayout')
@section('title', 'IDCard Grid')
@section('content')
@php
 //echo '<pre>'; print_r($selectedSamples); die;
@endphp

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-1">
               
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title">Templates List</h3>
                    <a href="{{ route('students.create') }}" class="btn btn-sm btn-primary ml-auto">
                       <i class="fas fa-plus"></i> Add 
                    </a>
                </div>

                <div class="card-body">
                    {{--<form method="GET" action="" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Class</label>
                            <select name="class" class="form-control" onchange="this.form.submit()">
                                <option value="">All Classes</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class')==$class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </form>--}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="studentTable">
                            <thead>
                                <tr>

                                    <th>Template Image</th>
                                    <th>Class</th>
                                    <th>Orientation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedSamples as $selectedSample)
                                <tr>
                                     <td><a href="{{'storage/' . $selectedSample->uploadSample->file_path}}" target="_blank"><img src="{{'storage/' . $selectedSample->uploadSample->file_path}}" class="img-thumbnail" alt="Image" style="max-width: 150px; max-height: 150px;"></a></td>
                                     <td>{{'Global'}}</td>
                                     <td>{{$selectedSample->uploadSample->orientation}}</td>
                                     <td style="white-space: nowrap;">
                                       <a href="" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                      <a href="{{ route('card.template.edit', [
										    'schoolId' => $selectedSample->school_id,
										    'orientation' => $selectedSample->orientation
										]) }}"
										   class="btn btn-sm btn-warning">
										    <i class="fas fa-edit"></i>
									   </a>
                                       <a href="" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                     </td>                                 
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row align-items-center mt-3">
                            <div class="col-12 col-md-5 mb-2 mb-md-0">
                                <small class="text-muted">
                                    Showing
                                    <strong></strong>
                                    to
                                    <strong></strong>
                                    of
                                    <strong></strong>
                                   
                                </small>
                            </div>
                            <div class="col-12 col-md-7">
                                <div class="d-flex justify-content-md-end justify-content-start">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
