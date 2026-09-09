@extends('frontend.layout.applayout')
@section('title', 'School')
@section('content')
<style>
   .school-link:hover {
        text-decoration: underline;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <!-- <div class="col-sm-6">
            <h1>Schools</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Schools</li>
            </ol>
          </div> -->
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                  <a href="{{ route('schools.create') }}" class="btn btn-primary">
                      <i class="fas fa-plus"></i> Add School
                  </a>

                  <div class="ml-auto">
                      <form method="GET" action="{{ route('schools.index') }}">
                          <div class="input-group" style="width:300px;">
                              <input type="text"
                                     name="search"
                                     id="school-search"
                                     class="form-control"
                                     placeholder="Search school name..."
                                     value="{{ $search ?? '' }}"
                                     autocomplete="off">

                              <div class="input-group-append">
                                  <button type="submit" class="btn btn-primary">
                                      <i class="fas fa-search"></i>
                                  </button>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
              <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>Logo</th>
                        <th>School Name</th>
                        <th>School Code</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                      @foreach($schools as $school)
                      <tr>
                        <td>
                            <img src="{{ $school->logo
                                ? asset('storage/' . $school->logo)
                                : asset('storage/1.webp') }}"
                                alt="School Logo"
                                style="max-width: 150px; max-height: 150px;" class="img-thumbnail">
                        </td>
                        <td><a href="{{ route('schools.show', $school) }}" class="school-link">{{ str_replace('"', '',  ucwords($school->school_name)) }}</a></td>
                        <td>{{ $school->school_code }}</td>
                        <td>{{ $school->email }}</td>
                        <td>{{ $school->phone }}</td>
                        <td>
                          @if($school->status)
                            <span class="badge badge-success">Active</span>
                          @else
                            <span class="badge badge-secondary">Inactive</span>
                          @endif
                        </td>
                        <td style="white-space: nowrap;">
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
                        </td>
                      </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
                <div class="mt-3">
                  {{ $schools->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
<script>
document.getElementById('school-search').addEventListener('input', function () {
    let value = this.value.trim();

    if (value.length >= 3 || value.length === 0) {
        this.form.submit();
    }
});
</script>
@endsection

