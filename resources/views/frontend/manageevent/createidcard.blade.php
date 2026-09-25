
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
    <section class="content-header">
        <div class="container-fluid">
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-id-card mr-2"></i>
                        Generate ID Card Filters
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('event.idcard.create') }}" method="GET"  id="idCardFilterForm" target="_blank">
                        <input type="hidden"
                               name="id"
                               value="{{ $events->id }}">
                        <div class="row">

                            {{-- Paper Size --}}
                            <div class="col-md-2">
                                <div class="form-group">

                                    <label for="papersize">
                                        Paper Size
                                    </label>

                                    <select name="paper_size" id="papersize" class="form-control">
                                        @foreach($papers as $paper)
                                          <option value="{{$paper->size}}">{{$paper->size}}</option>

                                        @endforeach

                                        {{--<option value="A4"
                                            {{ request('paper_size', 'A4') == 'A4' ? 'selected' : '' }}>
                                            A4
                                        </option>

                                        <option value="A3"
                                            {{ request('paper_size') == 'A3' ? 'selected' : '' }}>
                                            A3
                                        </option>

                                        <option value="A5"
                                            {{ request('paper_size') == 'A5' ? 'selected' : '' }}>
                                            A5
                                        </option>

                                        <option value="A6"
                                            {{ request('paper_size') == 'A6' ? 'selected' : '' }}>
                                            A6
                                        </option>--}}

                                    </select>

                                </div>
                            </div>


                            {{-- Print Per Page --}}
                            <div class="col-md-2">

                                <div class="form-group">

                                    <label for="cardperpage">
                                        Print Per Page
                                    </label>

                                    <input type="number"
                                           name="cardperpage"
                                           id="cardperpage"
                                           class="form-control"
                                           value="{{ request('cardperpage', 1) }}"
                                           min="1"
                                           step="1">

                                </div>

                            </div>


                            {{-- Buttons --}}
                            <div class="col-md-4 mt-4">

                                <div class="form-group mt-1">

                                    {{-- Print All --}}
                                    <button type="submit"
                                            name="print_type"
                                            value="all"
                                            class="btn btn-info">

                                        <i class="fas fa-print mr-1"></i>
                                        Print All ID Cards

                                    </button>


                                    {{-- Print Selected --}}
                                    <button type="submit"
                                            name="print_type"
                                            value="selected"
                                            id="printSelectedBtn"
                                            class="btn btn-success ml-2">

                                        <i class="fas fa-check-square mr-1"></i>
                                        Print Selected

                                    </button>

                                </div>

                            </div>
                        </div>
                        <div class="table-responsive mt-4">

                            <table class="table table-bordered table-striped">

                                <thead>

                                    <tr>

                                        <th width="40">
                                            <input type="checkbox"
                                                   id="selectAll">
                                        </th>

                                        <th>
                                            Photo
                                        </th>

                                        <th>
                                            Participant Name
                                        </th>

                                        <th>
                                            Email
                                        </th>

                                        <th>
                                            Phone
                                        </th>

                                        <th>
                                            Organization
                                        </th>
                                        <th>
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($eventusers as $user)

                                        <tr>

                                            <td>

                                                <input type="checkbox"
                                                       name="selected_users[]"
                                                       value="{{ $user->id }}"
                                                       class="user-checkbox">

                                            </td>

                                            <td>

                                                @if($user->photo)

                                                    <img src="{{ asset('storage/' . $user->photo) }}"
                                                         alt="Photo"
                                                         width="50"
                                                         height="50"
                                                         style="object-fit: cover;">

                                                @else

                                                    N/A

                                                @endif

                                            </td>

                                            <td>
                                                {{ $user->name }}
                                            </td>

                                            <td>
                                                {{ $user->email }}
                                            </td>

                                            <td>
                                                {{ $user->mobile }}
                                            </td>

                                            <td>
                                                {{ $user->organization }}
                                            </td>
                                            <td style="white-space: nowrap;">

                                            {{-- Edit --}}
                                            <a href="{{route('manage-events.people.edit',$user->id)}}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>

                                            {{-- View --}}
                                            <a href="{{route('show.register.user', $user->id)}}"
                                               class="btn btn-sm btn-info"
                                               title="View">

                                                <i class="fas fa-eye"></i>

                                            </a>
                                             <a href="{{route('manage-events.people.delete',$user->id)}}"
                                               class="btn btn-sm btn-danger"
                                               title="Delete">

                                                <i class="fas fa-trash"></i>

                                            </a>
                                           
                                        </td>


                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="6"
                                                class="text-center">

                                                No participants found.

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>
                        </div>
                        <div class="pagination-wrapper mt-3">

                            <div class="pagination-count">

                                Showing
                                {{ $eventusers->firstItem() }}
                                to
                                {{ $eventusers->lastItem() }}
                                of
                                {{ $eventusers->total() }}
                                entries

                            </div>

                            <div class="pagination-links">

                                {{ $eventusers->links() }}

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll(
        '.user-checkbox'
    );
    const printSelectedBtn = document.getElementById(
        'printSelectedBtn'
    );
    selectAll.addEventListener('change', function () {
        userCheckboxes.forEach(function (checkbox) {

            checkbox.checked = selectAll.checked;
        });
    });

    userCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const allChecked =
                userCheckboxes.length > 0 &&
                Array.from(userCheckboxes).every(function (checkbox) {
                    return checkbox.checked;
                });
            selectAll.checked = allChecked;
        });
    });
    printSelectedBtn.addEventListener('click', function (e) {
        const selected = document.querySelectorAll(
            '.user-checkbox:checked'
        );
        if (selected.length === 0) {
            e.preventDefault();
            //alert('Please select at least one participant to print.');
            Swal.fire({
            icon: 'warning',
            title: 'No Participant Selected',
            text: 'Please select at least one participant to print.',
            confirmButtonText: 'OK'
            });
            return false;
        }
    });
});
</script>

@endsection
