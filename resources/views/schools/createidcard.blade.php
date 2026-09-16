@extends('frontend.layout.applayout')
@section('title', 'Create ID Card')
@section('content')
<style>
    .pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    }

    .pagination-count {
        color: #6c757d;
        font-size: 13px;
        white-space: nowrap;
    }

    .pagination-links {
        display: flex;
        align-items: center;
    }

    .pagination-links .pagination {
        margin: 0;
    }

    .pagination-links .page-link {
        padding: 4px 9px;
        font-size: 13px;
        line-height: 1.4;
    }


    /* Mobile */
    @media (max-width: 767.98px) {

        .pagination-wrapper {
            flex-direction: column;
            gap: 8px;
        }

        .pagination-count {
            width: 100%;
            text-align: center;
        }

        .pagination-links {
            width: 100%;
            justify-content: center;
            overflow-x: auto;
        }

        .pagination-links .page-link {
            padding: 3px 7px;
            font-size: 12px;
        }
    }
</style>
<div class="content-wrapper">
<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- <div class="col-sm-6">
                <h1>Create ID Card</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Create ID Card
                    </li>
                </ol>
            </div> -->
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">

        <!-- Filter Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-id-card mr-2"></i>
                    Generate ID Card Filters
                </h3>
            </div>

            <div class="card-body">
                <form action="{{ route('idcard.create') }}" method="GET" id="idCardFilterForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class_id">Applicable</label>
                                <select name="applicableuser"
                                        id="applicableuser"
                                        class="form-control" onchange="this.form.submit()">
                                    @foreach($applicableusers as $applicableuser)
                                        <option value="{{ $applicableuser->id }}"
                                            {{ request('applicableuser') == $applicableuser->id ? 'selected' : '' }}>
                                            {{ $applicableuser->type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class_id">Class</label>
                                <select name="class_id"
                                        id="class_id"
                                        class="form-control" onchange="this.form.submit()">
                                    <option value="">All Classes</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="section_id">Section</label>
                                <select name="section_id"
                                        id="section_id"
                                        class="form-control" onchange="this.form.submit()">
                                    <option value="">All Sections</option>

                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Card Orientation -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="orientation">
                                    Card Orientation
                                </label>

                                <select name="orientation"
                                        id="orientation"
                                        class="form-control" onchange="this.form.submit()">

                                   <!--  <option value="">All Orientations</option> -->

                                    <option value="vertical"
                                        {{ request('orientation') == 'vertical' ? 'selected' : '' }}>
                                        Vertical (54mm x 84mm)
                                    </option>

                                    <option value="horizontal"
                                        {{ request('orientation') == 'horizontal' ? 'selected' : '' }}>
                                        Horizontal
                                    </option>

                                </select>
                            </div>
                        </div>

                        <!-- Photo -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="photo">
                                    Photo
                                </label>

                                <select name="photo"
                                        id="photo"
                                        class="form-control" onchange="this.form.submit()">

                                    <option value="">All Students</option>

                                    <option value="available"
                                        {{ request('photo') == 'available' ? 'selected' : '' }}>
                                        Photo Available
                                    </option>

                                    <option value="not_available"
                                        {{ request('photo') == 'not_available' ? 'selected' : '' }}>
                                        No Photo
                                    </option>

                                </select>
                            </div>
                        </div>

                        <!-- ID Card Printed -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="printed">
                                    ID Card Printed
                                </label>

                                <select name="printed"
                                        id="printed"
                                        class="form-control" onchange="this.form.submit()">

                                    <option value="">All</option>

                                    <option value="yes"
                                        {{ request('printed') == 'yes' ? 'selected' : '' }}>
                                        Yes
                                    </option>

                                    <option value="no"
                                        {{ request('printed') == 'no' ? 'selected' : '' }}>
                                        No
                                    </option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class_id">Paper</label>
                                <select name="papersize"
                                        id="papersize"
                                        class="form-control" onchange="this.form.submit()">
                                    @foreach($papersizes as $papersize)
                                        <option value="{{ $papersize->id }}"
                                            {{ request('papersize') == $papersize->id ? 'selected' : '' }}>
                                            {{ $papersize->size }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
            
                       
                        <div class="col-md-4"> 
                          <div class="form-group"> 
                            <label for="student_search">Search Student</label> 
                            <input type="text" name="student_search" id="student_search" class="form-control" placeholder="Name or Admission No" autocomplete="off" value="{{ request('student_search') ?? request('search') }}"> 
                            <small class="text-muted"> Type at least 3 characters </small> 
                           </div> 
                        </div> 
                         <div class="col-md-4 mt-4">
                            <div class="form-group mt-1">
                               <a href="{{ route('idcard.print-filtered') }}?{{ request()->getQueryString() }}" 
                                  class="btn btn-info btn-block" target="_blank">
                                   <i class="fas fa-print mr-1"></i> Print ID Cards
                               </a>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-12">

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i>
                                Search
                            </button>

                            <a href="{{ route('idcard.create') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-sync-alt mr-1"></i>
                                Reset
                            </a>

                        </div>
                    </div>

                <!-- </form> -->

            </div>
        </div>


        <!-- Student List -->
     {{---  <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-2"></i>
                   <span class="badge badge-info">
                        {{ $students->total() ?? $students->count() }} 
                    Students  </span> 
                </h3>
                <div class="card-tools card-tools d-flex align-items-center">
                
                        <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                            <option value="40" {{ request('per_page') == 40 ? 'selected' : '' }}>40</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                </div>
            </div>
            </form>
            <div class="card-body p-0">
                <form action="{{ route('idcard.generate') }}"  method="POST" id="generateIdCardForm" target="_blank">
                    @csrf
                    <input type="hidden"
                           name="background_template"
                           id="backgroundTemplateInput"
                           value="{{ request('background_template') }}">
                    <input type="hidden"
                           name="orientation"
                           id="orientationInput"
                           value="{{ request('orientation') }}">
                    <div id="selectedStudentIdsContainer"></div>
                    <div class="table-responsive p-0">
                        <table class="table table-bordered table-hover mb-0 responsive-table">
                            <thead>
                                <tr>
                                    <th width="40">
                                        <div class="icheck-primary">
                                            <input type="checkbox"
                                                   id="selectAll">
                                            <label for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th>Photo</th>
                                    <th>Admission No</th>
                                    <th>Student Name</th>
                                    <th>Father Name</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>ID Card Printed</th>
                                </tr>
                            </thead>

                            <tbody id="studentTableBody">
                                @include('schools.partials.student_rows', ['students' => $students])
                            </tbody>
                     
                        </table>
                           <div class="pagination-wrapper mt-3">
                                <div class="pagination-count ml-2">
                                    Showing {{ $students->firstItem() ?? 0 }}
                                    to {{ $students->lastItem() ?? 0 }}
                                    of {{ $students->total() }} results
                                </div>
                                <div class="pagination-links mr-2">
                                    {{ $students->withQueryString()->onEachSide(1)->links() }}
                                </div>
                            </div>
                    </div>
                    @if(optional($students)->count() > 0)

                        <div class="card-footer">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <span id="selectedCount">
                                        0 students selected
                                    </span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="{{ route('idcard.print-filtered') }}?{{ request()->getQueryString() }}" 
                                     class="btn btn-info" target="_blank">
                                      <i class="fas fa-print mr-1"></i> Print ID Cards
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
            </div>
       </div>---}}
</section>


</div>
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                   Capture Student Photo
                </h5>
                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="modalPhotoContent">
                    @include('frontend.studentpartials.commoncapture')
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
let selectedStudentId = null;
$(document).on('click', '.capture-student-btn', function () {
    selectedStudentId = $(this).attr('data-student-id');
    console.log('Selected Student ID:', selectedStudentId);
});

$('#save-capture-photo').on('click', function () {
    console.log('Student ID:', selectedStudentId);
    if (!selectedStudentId) {
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
    url = url.replace(':student', selectedStudentId);
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
@endsection

@push('scripts')

<script>

$(document).ready(function () {

    let searchTimer;

    // Select / deselect all students
    $('#selectAll').on('change', function () {

        $('.student-checkbox').prop(
            'checked',
            $(this).is(':checked')
        );

        updateSelectedCount();
    });

    // Individual checkbox
    $(document).on('change', '.student-checkbox', function () {

        let total = $('.student-checkbox').length;
        let checked = $('.student-checkbox:checked').length;

        $('#selectAll').prop(
            'checked',
            total > 0 && total === checked
        );

        updateSelectedCount();
    });

    function updateSelectedCount() {

        let selected = $('.student-checkbox:checked').length;

        $('#selectedCount').text(
            selected + ' student' + (selected !== 1 ? 's' : '') + ' selected'
        );

        $('#generateButton').prop(
            'disabled',
            selected === 0
        );
    }

    function syncGenerationInputs() {
        $('#backgroundTemplateInput').val($('#background_template').val());
        $('#orientationInput').val($('#orientation').val());

        let selectedIds = [];
        $('.student-checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });

        $('#selectedStudentIdsContainer').empty();
        selectedIds.forEach(function (id) {
            $('#selectedStudentIdsContainer').append(
                $('<input>', {
                    type: 'hidden',
                    name: 'student_ids[]',
                    value: id
                })
            );
        });
    }

    function loadStudents() {
        let formData = $('#idCardFilterForm').serialize();
        let searchTerm = $('#student_search').val().trim();

        $('#studentTableBody').html(`
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-muted mb-2"></i>
                    <p class="mb-0 text-muted">Searching students...</p>
                </td>
            </tr>
        `);

        $.ajax({
            url: '{{ route('idcard.search.students') }}',
            type: 'GET',
            data: formData + '&search=' + encodeURIComponent(searchTerm),
            success: function (response) {
                $('#studentTableBody').html(response);
                updateSelectedCount();
            },
            error: function () {
                $('#studentTableBody').html(`
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                            <p class="mb-0 text-muted">Unable to load students right now.</p>
                        </td>
                    </tr>
                `);
            }
        });
    }

    $('#student_search').on('keyup', function () {
        clearTimeout(searchTimer);

        let searchTerm = $(this).val().trim();

        if (searchTerm.length > 0 && searchTerm.length < 3) {
            return;
        }

        searchTimer = setTimeout(loadStudents, 300);
    });

    $('#background_template, #orientation').on('change', function () {
        syncGenerationInputs();
    });

    // $('#generateIdCardForm').on('submit', function (e) {
    //     syncGenerationInputs();

    //     if ($('.student-checkbox:checked').length === 0) {
    //         e.preventDefault();
    //         alert('Please select at least one student.');
    //         return false;
    //     }

    //     return true;
    // });

    $('#idCardFilterForm').on('submit', function (e) {
        e.preventDefault();
        loadStudents();
    });

    $('#class_id, #section_id, #photo, #printed').on('change', function () {
        loadStudents();
    });

});

</script>
@endpush
