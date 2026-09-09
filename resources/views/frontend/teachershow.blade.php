@extends('frontend.layout.applayout')
@section('title', 'Student Details')
@section('content')

  
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-body box-profile">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                           @if(isset($teacher) && $teacher->photo != '')
                            <div class="student-photo-wrapper">
                                <img src="{{ asset('storage/' . $teacher->photo) }}" alt="Student Photo"
                                    class="profile-user-img img-fluid img-circle student-photo"
                                    style="width: 180px; height: 180px; object-fit: cover;">
                                <div class="photo-hover-options mt-2">
                                    <a href="{{ asset('storage/' . $teacher->photo) }}" class="btn btn-sm btn-light" data-toggle="modal"
                                        data-target="#viewPhotoModal">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Image
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary capture-student-btn"
                                        data-toggle="modal" data-target="#photoModal"
                                        data-student-id="{{ $teacher->id }}">
                                        <i class="fas fa-camera mr-1"></i>
                                        Change Image
                                    </a>
                                </div>
                            </div>
                            @else
                            <a href="javascript:void(0);" class="capture-student-btn d-inline-flex align-items-center justify-content-center
                                            bg-light border rounded-circle text-decoration-none" data-toggle="modal"
                                data-target="#photoModal" data-student-id="{{ $teacher->id }}"
                                style="width:180px;height:180px;">
                                <i class="fas fa-user fa-4x text-muted"></i>
                            </a>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <h2 class="font-weight-bold mb-1">
                                {{ $teacher->first_name }}
                                {{ $teacher->last_name }}
                            </h2>
                            <p class="text-muted mb-2">
                                <i class="fas fa-id-card mr-1"></i>
                                Employee Code:
                                <strong>
                                    {{ $teacher->employee_code ?? 'N/A' }}
                                </strong>
                            </p>
                            <p class="mb-2">
                                <span class="badge badge-primary px-3 py-2">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    {{ $teacher->studentClass->name ?? 'N/A' }}
                                </span>

                                <span class="badge badge-info px-3 py-2 ml-1">
                                    Section:
                                    {{ $teacher->section ?? 'N/A' }}
                                </span>
                            </p>
                            @if($teacher->idcardprinted == '1')
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check-circle mr-1"></i>
                                ID Card Printed
                            </span>
                            @else
                            <span class="badge badge-danger px-3 py-2">
                                <i class="fas fa-times-circle mr-1"></i>
                                ID Card Not Printed
                            </span>

                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="card card-info">
                                <div class="card-header d-flex align-items-center">
                                    <h3 class="card-title mb-0">Live ID Card Preview</h3>

                                </div>
                                <div class="card-body p-2" style="overflow-x:auto;">
                                    <ul class="nav nav-tabs mb-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link {{ @$defaultOrientation === 'vertical' ? 'active' : '' }}" id="student-vertical-tab" data-toggle="tab"
                                                href="#student-vertical-card" role="tab">
                                                <i class="fas fa-mobile-alt mr-1"></i>
                                                Vertical
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ @$defaultOrientation === 'horizontal' ? 'active' : '' }}" id="student-horizontal-tab" data-toggle="tab"
                                                href="#student-horizontal-card" role="tab">
                                                <i class="fas fa-mobile-alt fa-rotate-90 mr-1"></i>
                                                Horizontal
                                            </a>
                                        </li>
                                    </ul>
                                  

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-md-right mt-3 mt-md-0">
                            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit mr-1"></i>
                            </a>
                            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="">
                                    <i class="fas fa-trash mr-1"></i>
                                </button>
                            </form>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm px-3">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-user mr-2 text-primary"></i>
                                Personal Information
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <tr>
                                    <th style="width:40%;">
                                        <i class="fas fa-id-card text-muted mr-2"></i>
                                        Employee Code
                                    </th>
                                    <td>
                                        {{ $teacher->employee_code ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-user text-muted mr-2"></i>
                                        Full Name
                                    </th>
                                    <td>
                                        {{ $teacher->first_name }}
                                        {{ $teacher->last_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-male text-muted mr-2"></i>
                                        Father Name
                                    </th>
                                    <td>
                                        {{ $teacher->father_husband ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-venus-mars text-muted mr-2"></i>
                                        Gender
                                    </th>
                                    <td>
                                        {{ $teacher->gender ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-birthday-cake text-muted mr-2"></i>
                                        Date of Birth
                                    </th>
                                    <td>
                                        @if($teacher->dob)
                                        {{ \Carbon\Carbon::parse($teacher->dob)->format('d/m/Y') }}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-home text-muted mr-2"></i>
                                        Address
                                    </th>
                                    <td>
                                        {{ $teacher->address ?? 'N/A' }}
                                    </td>
                                </tr>

                            </table>

                        </div>

                    </div>

                </div>
               {{---- <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-graduation-cap mr-2 text-primary"></i>
                                Academic & Contact
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <tr>
                                    <th style="width:40%;">
                                        <i class="fas fa-school text-muted mr-2"></i>
                                        Class
                                    </th>
                                    <td>
                                        {{ $student->studentClass->name ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-layer-group text-muted mr-2"></i>
                                        Section
                                    </th>
                                    <td>
                                        {{ $student->section ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-phone text-muted mr-2"></i>
                                        Phone
                                    </th>
                                    <td>
                                        {{ $student->phone ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-id-badge text-muted mr-2"></i>
                                        ID Card
                                    </th>
                                    <td>
                                        @if($student->idcardprinted == 'yes')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i>
                                            Printed
                                        </span>
                                        @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times mr-1"></i>
                                            Not Printed
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-palette text-muted mr-2"></i>
                                        Photo Background
                                    </th>
                                    <td>
                                        {{ $student->capture_background ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-calendar text-muted mr-2"></i>
                                        Added On
                                    </th>
                                    <td>
                                        {{ $student->created_at
                                        ? $student->created_at->format('d/m/Y')
                                        : 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>--}}
            </div>
            <!-- <div class="card shadow-sm">
                <div class="card-body text-right">
                   
                </div>
            </div> -->
        </div>
    </section>
</div>
<div class="modal fade" id="viewPhotoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Teacher Photo
                </h5>

                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">

                <img id="viewStudentPhoto" src="{{ asset('storage/' . $teacher->photo) ?? '' }}" alt="Teacher Photo" class="img-fluid"
                    style="max-height: 600px;">

            </div>

        </div>
    </div>
</div>
@endsection