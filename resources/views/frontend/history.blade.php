@extends('frontend.layout.applayout')

@section('title', 'Student History')

@section('content')

<style>
    .history-description {
        white-space: pre-line;
        line-height: 1.8;
    }

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

                    <div>
                        <h3 class="card-title mb-0 mr-3">
                            Student History
                        </h3>

                        <span class="text-muted">
                            {{ $student->first_name }}
                            {{ $student->last_name }}
                        </span>
                    </div>

                    <a
                        href="{{ route('students.index') }}"
                        class="btn btn-sm btn-primary ml-auto"
                    >
                        Back
                    </a>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped">

                            <thead>

                                <tr>
                                    <th style="width: 70px;">#</th>
                                    <th style="width: 180px;">Date & Time</th>
                                    <th style="width: 150px;">Change</th>
                                    <th>Details</th>
                                </tr>

                            </thead>


                            <tbody>

                                @forelse($histories as $history)

                                    <tr>

                                        <td>
                                            {{ $histories->firstItem() + $loop->index }}
                                        </td>

                                        <td>
                                            {{ $history->created_at?->format('d-m-Y H:i:s') }}
                                        </td>

                                        <td>
                                            <span class="badge badge-info">
                                                {{ $history->change }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="history-description">
                                                {{ $history->description }}
                                            </div>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="text-center text-muted"
                                        >
                                            No history found.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>


                        @if($histories->hasPages())

                            <div class="row align-items-center mt-3">

                                <div class="col-12 col-md-5 mb-2 mb-md-0">

                                    <small class="text-muted">

                                        Showing

                                        <strong>
                                            {{ $histories->firstItem() }}
                                        </strong>

                                        to

                                        <strong>
                                            {{ $histories->lastItem() }}
                                        </strong>

                                        of

                                        <strong>
                                            {{ $histories->total() }}
                                        </strong>

                                        history records

                                    </small>

                                </div>


                                <div class="col-12 col-md-7">

                                    <div class="d-flex justify-content-md-end justify-content-start">

                                        {{ $histories->onEachSide(1)->links() }}

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection