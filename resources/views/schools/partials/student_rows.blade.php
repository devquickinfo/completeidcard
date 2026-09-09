@forelse($students as $student)
    <tr>
        <td>
            <div class="icheck-primary">
                <input type="checkbox"
                       class="student-checkbox"
                       name="student_ids[]"
                       value="{{ $student->id }}"
                       id="student_{{ $student->id }}">

                <label for="student_{{ $student->id }}"></label>
            </div>
        </td>

        <td class="text-center">
            @if($student->photo)
                  @php
                    $photo = pathinfo($student->photo, PATHINFO_DIRNAME) . '/' .
                                 pathinfo($student->photo, PATHINFO_FILENAME) . '.jpg';
                   
                    if (pathinfo($student->photo, PATHINFO_DIRNAME) === '.') {
                        $photo = pathinfo($student->photo, PATHINFO_FILENAME) . '.jpg';
                    }
                   @endphp
                <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                    <img src="{{ asset('storage/' . $photo) }}"
                        alt="Student Photo"
                        style="width: 50px; height: 50px; object-fit: cover;" class="img-thumbnail rounded-circle">
                </a>
            @elseif($student->capturephoto)
                <a href="{{ asset('storage/' . $student->capturephoto) }}" target="_blank">
                    <img src="{{ asset('storage/' . $student->capturephoto) }}"
                        alt="Student Photo"
                        style="width: 50px; height: 50px; object-fit: cover;" class="img-thumbnail">
                </a>
            @else
                <button type="button"
                        class="btn btn-sm btn-link text-white capture-student-btn"
                        data-toggle="modal"
                        data-target="#photoModal"
                        data-student-id="{{ $student->id }}">
                    Add Photo
                </button>
            @endif
        </td>

        <td>{{ $student->admission_no }}</td>
        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
        <td>{{ $student->father_name }}</td>
        <td>{{ $student->studentClass->name ?? '-' }}</td>
        <td>{{ $student->getRelation('section')?->name ?? '-' }}</td>
        <td>
           {{--- @if($student->idcardprinted === 'yes')
                <button type="button"
                        class="btn btn-sm btn-success"
                        data-toggle="modal"
                        data-target="#photoModal"
                        data-student-id="{{ $student->id }}"
                        data-photo="">
                    Yes
                </button>
            @else
               <button type="button"
                        class="btn btn-sm btn-danger"
                        data-toggle="modal"
                        data-target="#photoModal"
                        data-student-id="{{ $student->id }}"
                        data-photo="{{ asset('storage/' . $student->capturephoto) }}">
                   No
                </button>
            @endif----}}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-4">
            <i class="fas fa-users-slash fa-2x text-muted mb-2"></i>
            <p class="mb-0 text-muted">No students found.</p>
        </td>
    </tr>
@endforelse
