@extends('frontend.layout.applayout')

@section('title', 'Vendor Details')

@section('content')

<div class="content-wrapper">

    {{-- Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
               <!--  <div class="col-sm-6">
                    <h1>Vendor Details</h1>
                </div>

                <div class="col-sm-6 text-right">
                    <a href="{{ route('user.account') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div> -->
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            {{-- Vendor Information --}}
            <div class="card card-primary">

                <div class="card-header">
                    <h3 class="card-title">
                        {{ $vendor->name }}
                    </h3>

                    <div class="card-tools">
                        <a href="{{ route('user.account') }}"
                           class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">
                            <strong>Name</strong>
                            <p>{{ $vendor->name }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Phone</strong>
                            <p>{{ $vendor->phone ?? '-' }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Email</strong>
                            <p>{{ $vendor->email ?? '-' }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>School Limit</strong>
                            <p>{{ $vendor->schoolcount ?? 0 }}</p>
                        </div>

                    </div>

                </div>
            </div>


            {{-- Tabs --}}
            <div class="card">

                <div class="card-header p-0">

                    <ul class="nav nav-tabs" id="vendorTabs" role="tablist">

                        {{-- School Tab --}}
                        <li class="nav-item">
                            <a class="nav-link active"
                               id="school-tab"
                               data-toggle="tab"
                               href="#schools"
                               role="tab">

                                <i class="fas fa-school"></i>
                                Schools

                                <span class="badge badge-primary ml-1">
                                    {{ $schools->total() }}
                                </span>

                            </a>
                        </li>

                        {{-- Event Tab --}}
                        <li class="nav-item">
                            <a class="nav-link"
                               id="event-tab"
                               data-toggle="tab"
                               href="#events"
                               role="tab">

                                <i class="fas fa-calendar-alt"></i>
                                Events

                                <span class="badge badge-primary ml-1">
                                    {{ $events->total() }}
                                </span>

                            </a>
                        </li>

                    </ul>

                </div>


                <div class="card-body">

                    <div class="tab-content" id="vendorTabsContent">


                        {{-- ================= SCHOOLS TAB ================= --}}
                        <div class="tab-pane fade show active"
                             id="schools"
                             role="tabpanel">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <h5 class="mb-0">
                                    Vendor Schools
                                </h5>

                            </div>

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped">

                                    <thead>
                                        <tr>
                                            <th width="60">#</th>
                                            <th width="80">Logo</th>
                                            <th>School</th>
                                            <th>School Code</th>
                                            <th>Status</th>
                                            <th width="150">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse($schools as $school)

                                            <tr>

                                                <td>
                                                    {{ $schools->firstItem() + $loop->index }}
                                                </td>

                                                <td>
                                                    @if($school->logo)

                                                        <img
                                                            src="{{ asset('storage/' . $school->logo) }}"
                                                            alt="{{ $school->school_name }}"
                                                            style="width:50px;height:50px;object-fit:cover;border-radius:5px;"
                                                        >

                                                    @else

                                                        <div style="
                                                            width:50px;
                                                            height:50px;
                                                            display:flex;
                                                            align-items:center;
                                                            justify-content:center;
                                                            background:#f1f1f1;
                                                            border-radius:5px;
                                                        ">
                                                            <i class="fas fa-school text-muted"></i>
                                                        </div>

                                                    @endif
                                                </td>

                                                <td>
                                                    <strong>
                                                        {{ $school->school_name }}
                                                    </strong>
                                                </td>

                                                <td>
                                                    {{ $school->school_code ?? '-' }}
                                                </td>

                                                <td>
                                                    @if($school->status)

                                                        <span class="badge badge-success">
                                                            Active
                                                        </span>

                                                    @else

                                                        <span class="badge badge-secondary">
                                                            Inactive
                                                        </span>

                                                    @endif
                                                </td>

                                                <td style="white-space:nowrap;">

                                                    <a href="{{ route('schools.show', $school->id) }}"
                                                       class="btn btn-sm btn-info"
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('schools.edit', $school->id) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
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

                                        @empty

                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    No schools found for this vendor.
                                                </td>
                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                            @if($schools->hasPages())

                                <div class="mt-3">
                                    {{ $schools->appends(request()->except('school_page'))->links() }}
                                </div>

                            @endif

                        </div>


                        {{-- ================= EVENTS TAB ================= --}}
                        <div class="tab-pane fade"
                             id="events"
                             role="tabpanel">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <h5 class="mb-0">
                                    Vendor Events
                                </h5>

                            </div>

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped">

                                    <thead>
                                        <tr>
                                            <th width="60">#</th>
                                            <th width="80">Logo</th>
                                            <th>Event Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Address</th>
                                            <th>Contact Person</th>
                                            <th>Organizer</th>
                                            <th width="120">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse($events as $event)

                                            <tr>

                                                <td>
                                                    {{ $events->firstItem() + $loop->index }}
                                                </td>

                                                {{-- Logo --}}
                                                <td>
                                                    @if($event->logo)

                                                        <img
                                                            src="{{ asset('storage/' . $event->logo) }}"
                                                            alt="{{ $event->event_name }}"
                                                            style="
                                                                width:50px;
                                                                height:50px;
                                                                object-fit:cover;
                                                                border-radius:5px;
                                                            "
                                                        >

                                                    @else

                                                        <div style="
                                                            width:50px;
                                                            height:50px;
                                                            display:flex;
                                                            align-items:center;
                                                            justify-content:center;
                                                            background:#f1f1f1;
                                                            border-radius:5px;
                                                        ">
                                                            <i class="fas fa-calendar-alt text-muted"></i>
                                                        </div>

                                                    @endif
                                                </td>

                                                {{-- Event Name --}}
                                                <td>
                                                    <strong>
                                                        {{ $event->event_name }}
                                                    </strong>
                                                </td>

                                                {{-- Start Date --}}
                                                <td>
                                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d-m-Y') }}
                                                </td>

                                                {{-- End Date --}}
                                                <td>
                                                    {{ \Carbon\Carbon::parse($event->end_date)->format('d-m-Y') }}
                                                </td>

                                                {{-- Address --}}
                                                <td>
                                                    {{ $event->address ?? '-' }}
                                                </td>

                                                {{-- Contact Person --}}
                                                <td>
                                                    {{ $event->contact_person1 ?? '-' }}
                                                </td>

                                                {{-- Organizer --}}
                                                <td>
                                                    {{ $event->organizer_name ?? '-' }}
                                                </td>

                                                {{-- Actions --}}
                                                <td style="white-space:nowrap;">

                                                    <a href="{{ route('manage-events.show', $event->id) }}"
                                                       class="btn btn-sm btn-info"
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('manage-events.edit', $event->id) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>
                                                <td colspan="9" class="text-center">
                                                    No events found for this vendor.
                                                </td>
                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                            @if($events->hasPages())

                                <div class="mt-3">
                                    {{ $events->appends(request()->except('event_page'))->links() }}
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

</div>

@endsection