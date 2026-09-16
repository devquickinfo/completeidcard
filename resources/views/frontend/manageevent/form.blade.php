@extends('frontend.layout.applayout')

@section('title', !empty($manageEvent->id) ? 'Edit Event' : 'Add Event')

@section('content')

<div class="content-wrapper">


<section class="content-header">
    <div class="container-fluid">
    </div>
</section>

<section class="content">

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-12">

                <div class="card card-primary">

                    <div class="card-header">

                        <h3 class="card-title">
                            {{ !empty($manageEvent->id) ? 'Edit Event' : 'Add Event' }}
                        </h3>

                        <a href="{{ route('manage-events.index') }}"
                           class="btn btn-primary float-right">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>

                    </div>

                    <form id="eventForm"
                          action="{{ !empty($manageEvent->id)
                              ? route('manage-events.update', $manageEvent->id)
                              : route('manage-events.store') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        @if(!empty($manageEvent->id))
                            @method('PUT')
                        @endif

                        <div class="card-body">

                            <div class="row">

                                {{-- Event Name --}}
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="event_name">
                                            Event Name
                                        </label>

                                        <input type="text"
                                               name="event_name"
                                               id="event_name"
                                               class="form-control"
                                               placeholder="Enter Event Name"
                                               value="{{ old('event_name', $manageEvent->event_name ?? '') }}"
                                               required>

                                        @error('event_name')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>
                                </div>


                                {{-- Unique Code --}}
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="unique_code">
                                            Unique Code
                                        </label>

                                        <input type="text"
                                               name="unique_code"
                                               id="unique_code"
                                               class="form-control"
                                               placeholder="Enter Unique Code"
                                               value="{{ old('unique_code', $manageEvent->unique_code ?? '') }}"
                                               disabled>

                                        @error('unique_code')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>
                                </div>


                                {{-- Start Date --}}
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="start_date">
                                            Start Date
                                        </label>

                                        <input type="date"
                                               name="start_date"
                                               id="start_date"
                                               class="form-control"
                                               value="{{ old('start_date', !empty($manageEvent->start_date) ? \Carbon\Carbon::parse($manageEvent->start_date)->format('Y-m-d') : '') }}"
                                               required>

                                        @error('start_date')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>
                                </div>


                                {{-- End Date --}}
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="end_date">
                                            End Date
                                        </label>

                                        <input type="date"
                                               name="end_date"
                                               id="end_date"
                                               class="form-control"
                                               value="{{ old('end_date', !empty($manageEvent->end_date) ? \Carbon\Carbon::parse($manageEvent->end_date)->format('Y-m-d') : '') }}"
                                               required>

                                        @error('end_date')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>
                                </div>


                                {{-- Contact Person --}}
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="contact_person1">
                                            Contact Person 1
                                        </label>

                                        <input type="text"
                                               name="contact_person1"
                                               id="contact_person1"
                                               class="form-control"
                                               placeholder="Enter Contact Person"
                                               value="{{ old('contact_person1', $manageEvent->contact_person1 ?? '') }}">

                                        @error('contact_person1')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>
                                </div>


                                {{-- Organizer Name --}}
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="organizer_name">
                                            Organizer Name
                                        </label>

                                        <input type="text"
                                               name="organizer_name"
                                               id="organizer_name"
                                               class="form-control"
                                               placeholder="Enter Organizer Name"
                                               value="{{ old('organizer_name', $manageEvent->organizer_name ?? '') }}">

                                        @error('organizer_name')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>
                                </div>


                                {{-- Address --}}
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <label for="address">
                                            Address
                                        </label>

                                        <textarea name="address"
                                                  id="address"
                                                  class="form-control"
                                                  rows="3"
                                                  placeholder="Enter Event Address">{{ old('address', $manageEvent->address ?? '') }}</textarea>

                                        @error('address')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>
                                </div>


                                {{-- Description --}}
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <label for="description">
                                            Description
                                        </label>

                                        <textarea name="description"
                                                  id="description"
                                                  class="form-control"
                                                  rows="5"
                                                  placeholder="Enter Event Description">{{ old('description', $manageEvent->description ?? '') }}</textarea>

                                        @error('description')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>
                                </div>


                                {{-- Logo --}}
                                <div class="col-md-6">

                                    <div class="form-group">

                                        <label for="logo">
                                            Event Logo
                                        </label>

                                        <input type="file"
                                               name="logo"
                                               id="logo"
                                               class="form-control"
                                               accept="image/jpeg,image/png,image/jpg,image/webp">

                                        <small class="text-muted">
                                            JPG, JPEG, PNG or WEBP
                                        </small>

                                        @error('logo')
                                            <span class="text-danger d-block">
                                                {{ $message }}
                                            </span>
                                        @enderror


                                        {{-- Logo Preview --}}
                                        <div id="logoPreviewContainer"
                                             style="
                                                margin-top:10px;
                                                {{ !empty($manageEvent->logo) ? '' : 'display:none;' }}
                                             ">

                                            <img id="logoPreview"
                                                 src="{{ !empty($manageEvent->logo)
                                                     ? asset('storage/' . $manageEvent->logo)
                                                     : '' }}"
                                                 alt="Event Logo"
                                                 style="
                                                    max-width:200px;
                                                    max-height:200px;
                                                    border:1px solid #ddd;
                                                    padding:5px;
                                                    border-radius:5px;
                                                 ">

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="card-footer">

                            <button type="submit"
                                    class="btn btn-primary">

                                {{ !empty($manageEvent->id) ? 'Update' : 'Save' }}

                            </button>

                            <a href="{{ route('manage-events.index') }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>


</div>

<script>

document.getElementById('logo').addEventListener('change', function (event) {

    const file = event.target.files[0];

    const preview = document.getElementById('logoPreview');

    const container = document.getElementById('logoPreviewContainer');

    if (file) {

        if (!file.type.startsWith('image/')) {

            preview.src = '';
            container.style.display = 'none';

            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            preview.src = e.target.result;
            container.style.display = 'block';

        };

        reader.readAsDataURL(file);

    }

});

</script>

@endsection
