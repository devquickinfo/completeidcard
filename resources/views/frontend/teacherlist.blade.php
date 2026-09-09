@extends('frontend.layout.applayout')
@section('title', 'Teacher List')
@section('content')
@php
 //echo '<pre>'; print_r($teachers); die;
@endphp
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <!-- <div class="col-sm-6">
                    <h1>Teachers</h1>
                </div> -->
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title">Teacher List</h3>
                    <a href="{{route('teachers.create')}}" class="btn btn-sm btn-primary ml-auto">
                        Add Teacher
                    </a>
                </div>
                
                <div class="card-body">
                   <form method="GET" action="{{ route('teachers.index') }}" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label class="form-label">ID Card Created</label>

                            <select name="idcardprinted"
                                    class="form-control"
                                    onchange="this.form.submit()">

                                <option value=""
                                    {{ request('idcardprinted') === null || request('idcardprinted') === '' ? 'selected' : '' }}>
                                    All Teachers
                                </option>

                                <option value="1"
                                    {{ request('idcardprinted') === '1' ? 'selected' : '' }}>
                                    Created
                                </option>

                                <option value="0"
                                    {{ request('idcardprinted') === '0' ? 'selected' : '' }}>
                                    Not Created
                                </option>

                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teacher Photo</label>

                            <select name="teacher_photo"
                                    class="form-control"
                                    onchange="this.form.submit()">

                                <option value=""
                                    {{ request('teacher_photo') === '' ? 'selected' : '' }}>
                                    All Teachers
                                </option>

                                <option value="with_photo"
                                    {{ request('teacher_photo') === 'with_photo' ? 'selected' : '' }}>
                                    With Photo
                                </option>

                                <option value="without_photo"
                                    {{ request('teacher_photo') === 'without_photo' ? 'selected' : '' }}>
                                    Without Photo
                                </option>

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>

                            <input type="text"
                                   name="search"
                                   id="teacherSearch"
                                   class="form-control"
                                   placeholder="Search by Name or Phone"
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('teachers.index') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-sync"></i>
                            </a>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped" id="teacherTable">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr>
                                    <td>
                                        @if(!empty($teacher->photo))
                                            <img src="{{ asset('storage/' . $teacher->photo) }}" width="50" height="50" style="object-fit: cover;">
                                        @else
                                            <span class="text-muted">No Photo</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $teacher->first_name }} {{ $teacher->last_name }}
                                    </td>

                                    <td>
                                        {{ $teacher->phone ?? '-' }}
                                    </td>

                                    <td style="white-space: nowrap;">
                                       <a href="{{route('teachers.show', $teacher->id)}}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                       <a href="{{route('teachers.edit', $teacher->id)}}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                      <form action="{{ route('teachers.destroy', $teacher->id) }}"
                                              method="POST"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No teachers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="mt-3">
                            {{ $teachers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    let searchTimer;
    document.getElementById('teacherSearch').addEventListener('input', function () {
        let value = this.value.trim();
        clearTimeout(searchTimer);
        if (value.length >= 3 || value.length === 0) {

            searchTimer = setTimeout(function () {
                document.getElementById('teacherSearch').form.submit();
            }, 500);
        }
    });
</script>
@endsection
