@extends('frontend.layout.applayout')

@section('title', 'Event List')

@section('content')

<style>
    @media (max-width: 767.98px) {
        .pagination {
            flex-wrap: wrap;
            justify-content: flex-start !important;
            margin-bottom: 0;
        }

        .pagination .page-item {
            margin-bottom: 4px;
        }

        .pagination .page-link {
            padding: 5px 9px;
            font-size: 13px;
        }
    }
</style>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
               
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title">
                        Event List
                    </h3>
                    <a href="{{ route('manage-events.create') }}"
                       class="btn btn-sm btn-primary ml-auto">
                        <i class="fas fa-plus"></i>
                        Add Event
                    </a>
                    <a href="{{ route('event-id-cards.index') }}"
                       class="btn btn-sm btn-primary ml-1">
                        <i class="fas fa-id-card"></i>
                        Create ID Card
                    </a>

                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('manage-events.index') }}" class="row align-items-end mb-3">
                        <div class="col-md-2">
                            <label class="form-label">Start Date From</label>
                            <input type="date"
                                   name="start_date"
                                   value="{{ request('start_date') }}"
                                   class="form-control"
                                   onchange="this.form.submit()">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Date To</label>
                            <input type="date"
                                   name="end_date"
                                   value="{{ request('end_date') }}"
                                   class="form-control"
                                   onchange="this.form.submit()">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Records Per Page</label>
                            <select name="per_page"
                                    class="form-control"
                                    onchange="this.form.submit()">
                                @foreach([10, 20, 30, 40, 50, 100] as $number)
                                    <option value="{{ $number }}"
                                        {{ request('per_page', 10) == $number ? 'selected' : '' }}>
                                        {{ $number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text"
                                       name="search"
                                       id="event-search"
                                       value="{{ request('search') }}"
                                       class="form-control"
                                       placeholder="Search Event, Contact Person, Organizer or Code">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('manage-events.index') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-sync"></i>
                            </a>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="60">
                                        #
                                    </th>
                                    <th>
                                        Logo
                                    </th>

                                    <th>
                                        Event Name
                                    </th>

                                    <th>
                                        Start Date
                                    </th>

                                    <th>
                                        End Date
                                    </th>

                                    <th>
                                        Address
                                    </th>

                                    <th>
                                        Contact Person
                                    </th>

                                    <th>
                                        Organizer
                                    </th>

                                   <!--  <th>
                                        Unique Code
                                    </th> -->

                                    <th width="150">
                                        Actions
                                    </th>

                                </tr>

                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td>
                                            {{ $events->firstItem() + $loop->index }}
                                        </td>
                                        <td>
                                             <img src="{{ $event->logo
                                                ? asset('storage/' . $event->logo)
                                                : asset('storage/1.webp') }}"
                                                alt="Event Logo"
                                                style="max-width: 150px; max-height: 150px;" class="img-thumbnail">
                                        </td>
                                        <td>
                                            <strong>
                                                {{ $event->event_name }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('d-m-Y') }}
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($event->end_date)->format('d-m-Y') }}
                                        </td>

                                        <td>
                                            {{ $event->address ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $event->contact_person1 ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $event->organizer_name ?? '-' }}
                                        </td>

                                       <!--  <td>
                                            <span class="badge badge-info">
                                                {{ $event->unique_code }}
                                            </span>
                                        </td> -->

                                        <td style="white-space: nowrap;">

                                            {{-- Edit --}}
                                            <a href="{{ route('manage-events.edit', $event->id) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>

                                            {{-- View --}}
                                            <a href="{{ route('manage-events.show', $event->id) }}"
                                               class="btn btn-sm btn-info"
                                               title="View">

                                                <i class="fas fa-eye"></i>

                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('manage-events.destroy', $event->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('manage-event.people', $event->unique_code) }}"
                                               class="btn btn-sm btn-info"
                                               title="People">

                                                <i class="fas fa-user"></i>
                                            </a>
                                            <a href="{{ route('manage-event.settings', $event->id) }}"
                                               class="btn btn-sm btn-primary"
                                               title="Settings">
                                                <i class="fas fa-cog"></i>
                                            </a>  

                                             <a href="{{ route('manage-event.print.idcard', $event->id) }}"
                                               class="btn btn-sm btn-primary"
                                               title="Print ID Card">
                                                <i class="fas fa-print"></i>
                                            </a>                                         
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10"
                                            class="text-center">
                                            No events found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row align-items-center mt-3">
                        <div class="col-12 col-md-5 mb-2 mb-md-0">
                            <small class="text-muted">
                                Showing
                                <strong>
                                    {{ $events->firstItem() ?? 0 }}
                                </strong>
                                to
                                <strong>
                                    {{ $events->lastItem() ?? 0 }}
                                </strong>
                                of
                                <strong>
                                    {{ $events->total() }}
                                </strong>
                                events
                            </small>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="d-flex justify-content-md-end justify-content-start">
                                {{ $events->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
document.getElementById('event-search').addEventListener('input', function () {
    let value = this.value.trim();

    if (value.length >= 3 || value.length === 0) {
        this.form.submit();
    }
});
</script>
@endsection