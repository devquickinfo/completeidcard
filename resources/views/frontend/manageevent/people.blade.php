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
                        People List
                    </h3>
                    <a href=""
                       class="btn btn-sm btn-primary ml-auto">
                        <i class="fas fa-plus"></i>
                        Add People
                    </a>

                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <form method="GET" action="{{ route('manage-event.people', $id) }}">
                                <div class="input-group">
                                    <input type="text"
                                           name="search"
                                           id="event-search"
                                           value="{{ request('search') }}"
                                           class="form-control"
                                           placeholder="Search Name, Mobile or Email">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-3">
                            <a href="{{ route('manage-event.people', $id) }}"
                               class="btn btn-secondary">
                                <i class="fas fa-sync"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="60">
                                        #
                                    </th>
                                    <th>
                                        Photo
                                    </th>

                                    <th>
                                         Name
                                    </th>
                                    <th>
                                        mobile
                                    </th>
                                    <th>
                                        Email
                                    </th>

                                    <th>
                                        Address
                                    </th>

                                    <th>
                                        Organization
                                    </th>


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
                                             <img src="{{ $event->photo
                                                ? asset('storage/' . $event->photo)
                                                : asset('storage/1.webp') }}"
                                                alt="Event Logo"
                                                style="max-width: 150px; max-height: 150px;" class="img-thumbnail">
                                        </td>
                                        <td>
                                            <strong>
                                                {{ $event->name }}
                                            </strong>
                                        </td>

                                      

                                        <td>
                                            {{ $event->mobile ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $event->email ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $event->address ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $event->organization ?? '-' }}
                                        </td>

                                        <td style="white-space: nowrap;">

                                            {{-- Edit --}}
                                            <a href=""
                                               class="btn btn-sm btn-warning"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>

                                            {{-- View --}}
                                            <a href=""
                                               class="btn btn-sm btn-info"
                                               title="View">

                                                <i class="fas fa-eye"></i>

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