@extends('frontend.layout.applayout')

@section('title', 'User List')

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
                        User List
                    </h3>
                    <a href="{{route('user.create')}}"
                       class="btn btn-sm btn-primary ml-auto">
                        <i class="fas fa-plus"></i>
                        Add User
                    </a>

                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('user.account') }}" class="row g-2 align-items-end mb-3">
                        <div class="col-md-2">
                            <label class="form-label">
                                Records Per Page
                            </label>
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
                    </form>
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <form method="GET"action="{{ route('user.account') }}">
                                <input type="hidden"
                                       name="per_page"
                                       value="{{ request('per_page', 10) }}">
                                <div class="input-group">
                                    <input type="text"
                                           name="search"
                                           id="user-search"
                                           value="{{ request('search') }}"
                                           class="form-control"
                                           placeholder="Search User">
                                    <div class="input-group-append">
                                        <button type="submit"
                                                class="btn btn-primary">
                                            <i class="fas fa-search"></i>

                                        </button>

                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('user.account') }}"
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
                                    <th width="60">#</th> 
                                    <th>User Name</th> 
                                    <th>Mobile</th> 
                                    <th>Email</th> 
                                    <th>School Limit</th>
                                    <th>Address</th> 
                                    <th width="150">Actions</th> 
                                </tr> 
                            </thead> 
                            <tbody> @forelse($events as $event)
                                <tr> 
                                    <td> {{ $events->firstItem() + $loop->index }} </td> 
                                    <td> <strong> {{ $event->name }} </strong> </td> 
                                    <td> {{ $event->phone ?? '-' }} </td> 
                                    <td> {{ $event->email ?? '-' }} </td> 
                                    <td> {{ $event->schoolcount ?? '-' }} </td> 
                                    <td> {{ $event->address ?? '-' }} </td> 
                                    <td style="white-space: nowrap;"> <a href="{{route('user.edit',$event->id)}}" class="btn btn-sm btn-warning" title="Edit"> <i class="fas fa-edit"></i> </a> 
                                        <a href="{{ route('vendor.schools', $event->id) }}" class="btn btn-sm btn-info" title="View"> <i class="fas fa-eye"></i> </a> 
                                        <a href="" class="btn btn-sm btn-danger" title="Delete"> <i class="fas fa-trash"></i> </a> 
                                        <a href="{{ route('user.status', $event->id) }}"
                                           class="btn btn-sm {{ $event->status ? 'btn-success' : 'btn-secondary' }}"
                                           title="{{ $event->status ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $event->status ? 'fa-check-circle' : 'fa-ban' }}"></i>
                                        </a>
                                    </td> 
                                </tr> 
                                @empty 
                                <tr> 
                                    <td colspan="5" class="text-center"> No user found. </td> 
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
document.getElementById('user-search').addEventListener('input', function () {
    let value = this.value.trim();

    if (value.length >= 3 || value.length === 0) {
        this.form.submit();
    }
});
</script>
@endsection