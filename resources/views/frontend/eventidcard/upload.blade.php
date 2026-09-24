@extends('frontend.layout.applayout')

@section('title', 'ID Card Samples')

@section('content')

<style>
    .sample-image-wrapper {
        width: 100%;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f4f6f9;
        border-radius: 4px;
    }

    .sample-image {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
    }
</style>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline mt-4">
                <div class="card-header">
                    <div class="d-flex align-items-center w-100">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-id-card mr-2"></i>
                            ID Card Samples
                        </h3>

                        <a href="{{ route('event-id-cards.create') }}" class="ml-auto btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Add
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th >#</th>
                                        <th >Image</th>
                                        <th>Template Name</th>
                                        <th >Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($idcards as $idcard)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                @if($idcard->file_path)
                                                    <img src="{{ asset('storage/' . $idcard->file_path) }}"
                                                         alt="{{ $idcard->name }}"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>

                                            <td>
                                                {{ $idcard->name }}
                                            </td>

                                            <td>
                                                <a href="{{ route('event-id-cards.edit', $idcard->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <a href=""
                                                   class="btn btn-sm btn-danger"
                                                   title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>

                                                 <a href="{{route('edit.event.id.card', $idcard->id)}}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">
                                                    <i class="fas fa-Edit">Edit ID Card</i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                No templates found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
