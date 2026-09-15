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
                                        <a href="javascript:void(0)"
                                                 data-toggle="modal" data-target="#photoModal"
                                                data-student-id="{{ $teacher->id }}">
                                            <img src="{{ asset('storage/' . $teacher->photo) }}" width="80" height="80" class="img-thumbnail">
                                        </a>
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
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Capture Teacher Photo
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="modalPhotoContent">
                    @include('frontend.studentpartials.commoncaptureteacher')
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $('#save-capture-photo').on('click', function () {
    let studentId = $('#photoModal #student_id').val();
    console.log('Student ID:', studentId);
    if (!studentId) {
        alert('Student ID missing');
        return;
    }
    let photoData = $('#photo_data').val();
    let background = $('#camera-bg').val();

    if (!photoData) {
        alert('Please capture a photo first');
        return;
    }
    let url = "{{ route('student.capture-photo', ':student') }}";
    url = url.replace(':student', studentId);
    console.log('POST URL:', url);
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            photo_data: photoData,
            capture_background: background
        })
    })
    .then(response => response.json())
    .then(data => {

        console.log(data);

        if (data.success) {

            $('#photoModal').modal('hide');

            toastr.success(data.message, 'Success');

            setTimeout(function () {
                location.reload();
            }, 1000);

        } else {

            alert(data.message || 'Photo could not be saved');

        }

    })
    .catch(error => {

        console.error(error);
        alert('Error while saving photo.');

    });
    });
</script>

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
