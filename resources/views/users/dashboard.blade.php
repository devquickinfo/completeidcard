@extends('frontend.layout.applayout')
@section('title', 'Dashboard')
@section('content')
<style>
.school-logo {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 50%;
    border: 1px solid #ddd;
    padding: 5px;
    background: #fff;
}

.school-logo-placeholder {
    width: 80px;
    height: 80px;
    margin: auto;
    border-radius: 50%;
    background: #f4f6f9;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: #6c757d;
}

.card.h-100 {
    transition: all 0.2s ease;
}

.card.h-100:hover {
    transform: translateY(-3px);
}
.dark-mode .bg-white, .dark-mode .bg-white>a {
     color: #fff !important; 
}
</style>
@php
 use App\Helpers\ImageHelper;
 $permission=ImageHelper::getVendorPermission();
 $text = '';
 if($permission->school===0){
   //$text="You Have No Permission to Access  school  Plz Contact Admin";
 }
@endphp
<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
        </div>
      </div>
    </div>
   
    @if($permission->school===1)
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-school"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Schools</span>
                  <span class="info-box-number">{{ @$schoolsCount ?? 0 }}</span>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">School Limit</span>
                  <span class="info-box-number">{{ @$users->schoolcount ?? 0 }}</span>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-layer-group"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"></span>
                  <span class="info-box-number"></span>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Students</span>
                  <span class="info-box-number">{{ @$studentsCount ?? 0 }}</span>
                </div>
              </div>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Schools</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <div class="btn-group">
                   
                  </div>
                  <a href="{{route('schools.create')}}" class="btn btn-primary">
                    <i class="fas fa-plus"> Add</i>
                  </a>
                </div>
              </div>
              <!-- /.card-header -->
            <div class="card-body">
			    <div class="row">

			        @forelse($schools as $school)
			            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
			                <div class="card h-100 shadow-sm border-0">

			                    <div class="card-body text-center">

			                        {{-- School Logo --}}
			                        <a href="{{ route('schools.show', $school) }}">
			                        <div class="mb-3">
			                            @if($school->logo)
			                                <img
			                                    src="{{ asset('storage/' . $school->logo) }}"
			                                    alt="{{ $school->school_name }}"
			                                    class="school-logo"
			                                >
			                            @else
			                                <div class="school-logo-placeholder">
			                                    <i class="fas fa-school"></i>
			                                </div>
			                            @endif
			                        </div>

			                        {{-- School Name --}}
			                        <h5 class="font-weight-bold mb-2">
			                            {{ $school->school_name }}
			                        </h5>

			                        {{-- School Code --}}
			                        @if($school->school_code)
			                            <small class="text-muted">
			                                {{ $school->school_code }}
			                            </small>
			                        @endif

			                    </div>
                                </a>
			                    <div class="card-footer bg-white border-0 text-center">
			                      <a href="{{ route('schools.show', $school) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
		                          <a href="{{ route('schools.edit', $school) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
		                          <a href="{{ route('schools.status', $school->id) }}"
		                             class="btn btn-sm {{ $school->status ? 'btn-success' : 'btn-secondary' }}"
		                             title="{{ $school->status ? 'Deactivate' : 'Activate' }}">
		                              <i class="fas {{ $school->status ? 'fa-check-circle' : 'fa-ban' }}"></i>
		                          </a>
		                          <form action="{{ route('schools.destroy', $school->id) }}" method="POST" class="d-inline">
		                            @csrf
		                            @method('DELETE')
		                            <button type="submit" class="btn btn-sm btn-danger" onclick="">
		                              <i class="fas fa-trash"></i>
		                            </button>
		                          </form>

			                    </div>

			                </div>
			            </div>
			        @empty

			            <div class="col-12">
			                <div class="alert alert-info text-center">
			                    No schools found.
			                </div>
			            </div>

			        @endforelse

			    </div>
			</div>
            </div>
          </div>
        </div>
     
      </div>
    </section>
    @else
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-school"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Schools</span>
                  <span class="info-box-number">{{ 0 }}</span>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">School Limit</span>
                  <span class="info-box-number">{{  0 }}</span>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-layer-group"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"></span>
                  <span class="info-box-number"></span>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Students</span>
                  <span class="info-box-number">{{  0 }}</span>
                </div>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Schools</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <div class="btn-group">
                  </div>
                </div>
              </div>
            <div class="card-body">
              <div class="row">
                  <div class="col-12">
                      <div class="alert alert-danger text-center">
                          You Have no Permission to access school please contact admin.
                      </div>
                  </div>
             </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif
  </div>


@endsection