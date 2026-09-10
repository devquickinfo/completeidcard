@extends('frontend.layout.applayout')

@section('title', 'ID Card Samples')

@section('content')

<style>
    .sample-image-wrapper {
        width: 100%;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f4f6f9;
        border-radius: 4px;
    }

    .sample-image {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
    }
</style>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid mt-1">
        </div>
    </section>

    <section class="content">

        <div class="container-fluid">

            <div class="card card-primary card-outline mt-4">

                {{-- ================= CARD HEADER ================= --}}
                <div class="card-header">

                    <div class="d-flex align-items-center">

                        <h3 class="card-title">
                            <i class="fas fa-id-card mr-2"></i>
                            ID Card Samples
                        </h3>

                        <div class="ml-auto d-flex align-items-center">

                            {{-- DELETE ALL --}}
                            <form action="{{ route('upload-sample.destroyAll') }}"
                                  method="POST"
                                  class="mr-2">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-danger">

                                    <i class="fas fa-trash mr-1"></i>
                                    All

                                </button>

                            </form>


                            @if(session('role') !== 'school' && !session('viewing_school'))

                                {{-- ADMIN --}}
                                <a href="{{ route('upload-samples.create') }}"
                                   class="btn btn-sm btn-primary">

                                    <i class="fas fa-plus mr-1"></i>
                                    Add Samples

                                </a>

                            @else

                                {{-- SCHOOL UPLOAD --}}
                                <a href="{{ route('upload-samples.create') }}"
                                   class="btn btn-sm btn-primary mr-2">

                                    <i class="fas fa-upload mr-1"></i>
                                    Upload Your Own Sample

                                </a>


                                {{-- SAVE --}}
                                <form action="{{ route('selected-samples.store') }}"
                                    method="POST"
                                    id="saveSamplesForm">

                                    @csrf

                                    {{-- Selected Vertical Sample --}}
                                    <input type="hidden"
                                        name="vertical_sample_id"
                                        id="selected-vertical-sample-id">

                                    <input type="hidden"
                                        name="vertical_orientation"
                                        value="vertical">

                                    {{-- Selected Horizontal Sample --}}
                                    <input type="hidden"
                                        name="horizontal_sample_id"
                                        id="selected-horizontal-sample-id">

                                    <input type="hidden"
                                        name="horizontal_orientation"
                                        value="horizontal">

                                    <button type="submit"
                                            class="btn btn-sm btn-success">

                                        <i class="fas fa-save mr-1"></i>
                                        Save Samples

                                    </button>

                                </form>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- ================= CARD BODY ================= --}}
                <div class="card-body">


                    @if(session('role') === 'school' || session('viewing_school'))

                        {{-- =================================================
                             PARENT TABS
                        ================================================== --}}

                        <ul class="nav nav-tabs mb-3"
                            id="sampleParentTabs"
                            role="tablist">

                            <li class="nav-item">

                                <a class="nav-link"
                                   id="default-tab"
                                   data-toggle="tab"
                                   href="#default-samples"
                                   role="tab">

                                    <i class="fas fa-layer-group mr-1"></i>
                                    Default Samples

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link active"
                                   id="my-uploaded-tab"
                                   data-toggle="tab"
                                   href="#my-uploaded-samples"
                                   role="tab">

                                    <i class="fas fa-user mr-1"></i>
                                    My Uploaded Samples

                                </a>

                            </li>

                        </ul>


                        {{-- =================================================
                             PARENT TAB CONTENT
                        ================================================== --}}

                        <div class="tab-content">


                            {{-- =================================================
                                 DEFAULT SAMPLES
                            ================================================== --}}

                            <div class="tab-pane fade"
                                 id="default-samples"
                                 role="tabpanel">


                                {{-- CHILD TABS --}}

                                <ul class="nav nav-tabs mb-3"
                                    id="defaultChildTabs"
                                    role="tablist">

                                    <li class="nav-item">

                                        <a class="nav-link active"
                                           data-toggle="tab"
                                           href="#default-vertical"
                                           role="tab">

                                            <i class="fas fa-mobile-alt mr-1"></i>
                                            Vertical

                                        </a>

                                    </li>

                                    <li class="nav-item">

                                        <a class="nav-link"
                                           data-toggle="tab"
                                           href="#default-horizontal"
                                           role="tab">

                                            <i class="fas fa-credit-card mr-1"></i>
                                            Horizontal

                                        </a>

                                    </li>

                                </ul>


                                {{-- CHILD CONTENT --}}

                                <div class="tab-content">


                                    {{-- DEFAULT VERTICAL --}}
                                    <div class="tab-pane fade show active"
                                         id="default-vertical"
                                         role="tabpanel">

                                        <div class="row">

                                            @forelse($defaultSamples->where('orientation', 'vertical') as $all)

                                                @include('schools.partials.sample-card', [
                                                    'all' => $all
                                                ])

                                            @empty

                                                <div class="col-12">

                                                    <div class="alert alert-info">

                                                        <i class="fas fa-info-circle mr-1"></i>

                                                        No default vertical samples available.

                                                    </div>

                                                </div>

                                            @endforelse

                                        </div>

                                    </div>


                                    {{-- DEFAULT HORIZONTAL --}}
                                    <div class="tab-pane fade"
                                         id="default-horizontal"
                                         role="tabpanel">

                                        <div class="row">

                                            @forelse($defaultSamples->where('orientation', 'horizontal') as $all)

                                                @include('schools.partials.sample-card', [
                                                    'all' => $all
                                                ])

                                            @empty

                                                <div class="col-12">

                                                    <div class="alert alert-info">

                                                        <i class="fas fa-info-circle mr-1"></i>

                                                        No default horizontal samples available.

                                                    </div>

                                                </div>

                                            @endforelse

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 MY UPLOADED SAMPLES
                            ================================================== --}}

                            <div class="tab-pane fade show active"
                                 id="my-uploaded-samples"
                                 role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Orientation</th>
                                                <th>Applicable</th>
                                                <th>House</th>
                                                <th>Apply</th>
                                             
                                                <th>Default</th>
                                                
                                                <th>Action</th>
                                                <th>Create Template</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse($ownSamples as $all)

                                                <tr>
                                                    <td>
                                                        <a href="{{ asset('storage/' . $all->file_path) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $all->file_path) }}"
                                                                 class="img-thumbnail"
                                                                 alt="{{ $all->name }}"
                                                                 style="max-width: 120px; max-height: 120px; object-fit: contain;">
                                                        </a>
                                                    </td>
                                                    <td>{{ $all->name }}</td>
                                                    <td>{{ ucfirst($all->orientation) }}</td>
                                                    @php
                                                      $applicable = \App\Helpers\ImageHelper::getApplicableUser($all->applicable_id);
                                                      $house = \App\Helpers\ImageHelper::getHouse($all->house_id);
                                                      $className = \App\Helpers\ImageHelper::getClassName($all->class_id);
                                                    @endphp
                                                    <td>{{ $applicable ?? '-' }}</td>
                                                    <td>{{ $house ?? '-' }}</td>
                                                    <td>{{ $className ?? 'Global' }}</td>
                                                    <td class="text-center">
                                                           
                                                    </td>
                                                    <td style="white-space: nowrap;">
                                                        <a href="" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                        <a href="{{ route('card.templatesingle.edit', $all->id ) }}"
                                                            class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit"></i>
                                                        </a>
                                                       
                                                        <a href="{{ route('singlesample.delete', $all->id) }}" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                                    </td> 
                                                    <td>
                                                         <a href="{{ route('card.template.edit', [
                                                                'schoolId' => $all->school_id,
                                                                'orientation' => $all->orientation
                                                            ]) }}"
                                                            class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit">Edit as Template</i>
                                                        </a>
                                                    </td>     
                                                </tr>

                                            @empty

                                                <tr>
                                                    <td colspan="{{ (session('role') === 'school' || session('viewing_school')) ? 9 : 9 }}" class="text-center">
                                                        <div class="alert alert-info mb-0">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            You have not uploaded any samples yet.
                                                        </div>
                                                    </td>
                                                </tr>

                                            @endforelse

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>


                    @else

                        {{-- =================================================
                             ADMIN VIEW
                        ================================================== --}}

                        <div class="row">

                            @forelse($alls as $all)

                                @include('schools.partials.sample-card', [
                                    'all' => $all
                                ])

                            @empty

                                <div class="col-12">

                                    <div class="alert alert-info">

                                        <i class="fas fa-info-circle mr-1"></i>

                                        No samples available.

                                    </div>

                                </div>

                            @endforelse

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </section>

</div>


{{-- ==========================================================
     SELECT SAMPLE
=========================================================== --}}


@endsection
