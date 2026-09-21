@extends('frontend.layout.applayout')

@section('title', 'Event Custom Fields')

@section('content')

<div class="content-wrapper">

    <section class="content">

        <div class="container-fluid">

            {{-- =========================================================
                 PAGE HEADER
            ========================================================== --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="event-card-header">

                    <div class="event-header-title">

                        <h1 class="m-0 font-weight-bold">
                            Custom Registration Fields
                        </h1>

                        <small class="text-muted">
                            Manage additional registration fields for this event
                        </small>

                    </div>


                    <div class="event-header-actions">

                        <a href="{{ route('manage-events.show', $event->id) }}"
                           class="btn btn-default">

                            <i class="fas fa-arrow-left mr-1"></i>

                            Back to Event

                        </a>

                    </div>

                </div>


                {{-- =====================================================
                     EVENT SUMMARY
                ====================================================== --}}
                <div class="card-body p-4">

                    <div class="row align-items-center">

                        {{-- Event Icon --}}
                        <div class="col-md-2 text-center mb-3 mb-md-0">

                            <div class="event-logo-placeholder">

                                <i class="fas fa-calendar-alt"></i>

                            </div>

                        </div>


                        {{-- Event Information --}}
                        <div class="col-md-10">

                            <div class="mb-2">

                                <span class="badge badge-primary px-3 py-2">

                                    <i class="fas fa-calendar-check mr-1"></i>

                                    Event

                                </span>

                            </div>


                            <h2 class="font-weight-bold mb-2">

                                {{ $event->event_name ?? '-' }}

                            </h2>


                            <div class="text-muted">

                                <i class="fas fa-calendar-day mr-1"></i>

                                @if($event->start_date)

                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}

                                @else

                                    -

                                @endif


                                <span class="mx-2">
                                    to
                                </span>


                                <i class="fas fa-calendar-day mr-1"></i>

                                @if($event->end_date)

                                    {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </div>


                            @if(!empty($event->unique_code))

                                <div class="mt-3">

                                    <span class="text-muted mr-2">
                                        Event Code:
                                    </span>

                                    <span class="unique-code">

                                        {{ $event->unique_code }}

                                    </span>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                 ADD CUSTOM FIELD
            ========================================================== --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header border-bottom">

                    <h3 class="card-title font-weight-bold">

                        <i class="fas fa-plus-circle text-primary mr-2"></i>

                        Add Custom Field

                    </h3>

                </div>


                <form method="POST"
                      action="{{ route('manage-event.custom-fields.store', $event->id) }}">

                    @csrf


                    <div class="card-body">

                        <div class="row">

                            {{-- Label --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>
                                        Label
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="label"
                                           value="{{ old('label') }}"
                                           class="form-control"
                                           placeholder="Example: Designation"
                                           required>

                                    @error('label')
                                        <span class="text-danger small">
                                            {{ $message }}
                                        </span>
                                    @enderror

                                </div>

                            </div>


                            {{-- Field Name --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>
                                        Field Name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="field_name"
                                           value="{{ old('field_name') }}"
                                           class="form-control"
                                           placeholder="designation"
                                           required>

                                    <small class="text-muted">
                                        Example: designation, gender, city
                                    </small>

                                    @error('field_name')
                                        <span class="text-danger small">
                                            {{ $message }}
                                        </span>
                                    @enderror

                                </div>

                            </div>


                            {{-- Input Type --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>
                                        Input Type
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="input_type"
                                            id="input_type"
                                            class="form-control"
                                            required>

                                        <option value="text"
                                            {{ old('input_type', 'text') == 'text' ? 'selected' : '' }}>
                                            Text
                                        </option>

                                        <option value="textarea"
                                            {{ old('input_type') == 'textarea' ? 'selected' : '' }}>
                                            Textarea
                                        </option>

                                        <option value="email"
                                            {{ old('input_type') == 'email' ? 'selected' : '' }}>
                                            Email
                                        </option>

                                        <option value="number"
                                            {{ old('input_type') == 'number' ? 'selected' : '' }}>
                                            Number
                                        </option>

                                        <option value="date"
                                            {{ old('input_type') == 'date' ? 'selected' : '' }}>
                                            Date
                                        </option>

                                        <option value="time"
                                            {{ old('input_type') == 'time' ? 'selected' : '' }}>
                                            Time
                                        </option>

                                        <option value="dropdown"
                                            {{ old('input_type') == 'dropdown' ? 'selected' : '' }}>
                                            Dropdown
                                        </option>

                                        <option value="checkbox"
                                            {{ old('input_type') == 'checkbox' ? 'selected' : '' }}>
                                            Checkbox
                                        </option>

                                        <option value="radio"
                                            {{ old('input_type') == 'radio' ? 'selected' : '' }}>
                                            Radio
                                        </option>

                                    </select>

                                    @error('input_type')
                                        <span class="text-danger small">
                                            {{ $message }}
                                        </span>
                                    @enderror

                                </div>

                            </div>


                            {{-- HTML ID --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>
                                        HTML ID
                                    </label>

                                    <input type="text"
                                           name="html_id"
                                           value="{{ old('html_id') }}"
                                           class="form-control"
                                           placeholder="designation">

                                    <small class="text-muted">
                                        Optional HTML id attribute
                                    </small>

                                </div>

                            </div>


                            {{-- HTML Class --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>
                                        HTML Class
                                    </label>

                                    <input type="text"
                                           name="html_class"
                                           value="{{ old('html_class') }}"
                                           class="form-control"
                                           placeholder="form-control custom-field">

                                    <small class="text-muted">
                                        Optional CSS classes
                                    </small>

                                </div>

                            </div>


                            {{-- Sort Order --}}
                            <div class="col-md-2">

                                <div class="form-group">

                                    <label>
                                        Sort Order
                                    </label>

                                    <input type="number"
                                           name="sort_order"
                                           value="{{ old('sort_order', 0) }}"
                                           class="form-control">

                                </div>

                            </div>


                            {{-- Required --}}
                            <div class="col-md-2">

                                <div class="form-group">

                                    <label>
                                        Required
                                    </label>

                                    <div class="custom-control custom-switch mt-1">

                                        <input type="checkbox"
                                               name="is_required"
                                               value="1"
                                               class="custom-control-input"
                                               id="is_required"
                                               {{ old('is_required') ? 'checked' : '' }}>

                                        <label class="custom-control-label"
                                               for="is_required">

                                            Yes

                                        </label>

                                    </div>

                                </div>

                            </div>


                            {{-- Options --}}
                            <div class="col-md-12"
                                 id="options-wrapper"
                                 style="display:none;">

                                <div class="form-group">

                                    <label>
                                        Options
                                    </label>

                                    <textarea name="options"
                                              class="form-control"
                                              rows="5"
                                              placeholder="Male&#10;Female&#10;Other">{{ old('options') }}</textarea>

                                    <small class="text-muted">

                                        Enter one option per line.
                                        This is required for Dropdown, Checkbox and Radio fields.

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-footer bg-white">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-plus mr-1"></i>

                            Add Field

                        </button>

                    </div>

                </form>

            </div>


            {{-- =========================================================
                 EXISTING CUSTOM FIELDS
            ========================================================== --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header border-bottom">

                    <h3 class="card-title font-weight-bold">

                        <i class="fas fa-list text-primary mr-2"></i>

                        Custom Registration Fields

                    </h3>

                    <div class="card-tools">

                        <span class="badge badge-primary">

                            {{ $customFields->count() }}

                            {{ $customFields->count() == 1 ? 'Field' : 'Fields' }}

                        </span>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover mb-0">

                            <thead>

                                <tr>

                                    <th width="60">
                                        #
                                    </th>

                                    <th>
                                        Label
                                    </th>

                                    <th>
                                        Field Name
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        HTML ID
                                    </th>

                                    <th>
                                        HTML Class
                                    </th>

                                    <th>
                                        Required
                                    </th>

                                    <th>
                                        Sort
                                    </th>

                                    <th width="90">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($customFields as $field)

                                    <tr>

                                        <td>

                                            {{ $loop->iteration }}

                                        </td>


                                        <td>

                                            <strong>
                                                {{ $field->label }}
                                            </strong>

                                        </td>


                                        <td>

                                            <code>
                                                {{ $field->field_name }}
                                            </code>

                                        </td>


                                        <td>

                                            @php

                                                $typeClass = match($field->input_type) {

                                                    'dropdown' => 'badge-warning',

                                                    'checkbox' => 'badge-success',

                                                    'radio' => 'badge-info',

                                                    'textarea' => 'badge-secondary',

                                                    default => 'badge-primary',

                                                };

                                            @endphp

                                            <span class="badge {{ $typeClass }}">

                                                {{ ucfirst($field->input_type) }}

                                            </span>

                                        </td>


                                        <td>

                                            @if($field->html_id)

                                                <code>
                                                    #{{ $field->html_id }}
                                                </code>

                                            @else

                                                <span class="text-muted">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if($field->html_class)

                                                <small class="text-muted">

                                                    {{ $field->html_class }}

                                                </small>

                                            @else

                                                <span class="text-muted">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if($field->is_required)

                                                <span class="badge badge-danger">

                                                    Required

                                                </span>

                                            @else

                                                <span class="badge badge-secondary">

                                                    Optional

                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            {{ $field->sort_order }}

                                        </td>


                                        <td>

                                            <form method="POST"
                                                  action="{{ route('manage-event.custom-fields.delete', [$event->id, $field->id]) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this field?')">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete Field">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="9"
                                            class="text-center text-muted py-5">

                                            <div class="empty-state">

                                                <i class="fas fa-sliders-h"></i>

                                                <h5 class="mt-3">
                                                    No Custom Fields
                                                </h5>

                                                <p class="mb-0">
                                                    Add your first custom registration field above.
                                                </p>

                                            </div>

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


{{-- =========================================================
     PAGE CSS
========================================================== --}}
<style>

    /* =========================================================
       HEADER
    ========================================================== */

    .event-card-header {

        display: flex;

        justify-content: space-between;

        align-items: center;

        width: 100%;

        padding: 20px 24px;

        border-bottom: 1px solid #e9ecef;

    }


    .event-header-title {

        min-width: 0;

    }


    .event-header-title h1 {

        font-size: 24px;

        line-height: 1.3;

    }


    .event-header-title small {

        display: block;

        margin-top: 4px;

    }


    .event-header-actions {

        display: flex;

        align-items: center;

        justify-content: flex-end;

        gap: 8px;

        flex-shrink: 0;

    }


    .event-header-actions .btn {

        white-space: nowrap;

    }


    /* =========================================================
       EVENT ICON
    ========================================================== */

    .event-logo-placeholder {

        width: 120px;

        height: 120px;

        margin: auto;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 12px;

        background: #f4f6f9;

        color: #adb5bd;

        font-size: 45px;

        border: 1px solid #e5e7eb;

    }


    /* =========================================================
       UNIQUE CODE
    ========================================================== */

    .unique-code {

        display: inline-block;

        max-width: 100%;

        padding: 6px 10px;

        background: #e8f7fb;

        color: #117a8b;

        border-radius: 6px;

        font-family: monospace;

        font-size: 12px;

        line-height: 1.5;

        word-break: break-all;

        overflow-wrap: anywhere;

    }


    /* =========================================================
       CARDS
    ========================================================== */

    .card {

        border-radius: 10px;

    }


    .card-header {

        padding: 15px 20px;

    }


    .card-body {

        padding: 20px;

    }


    .card-footer {

        padding: 15px 20px;

    }


    /* =========================================================
       FORM
    ========================================================== */

    .form-group label {

        font-weight: 600;

        color: #343a40;

    }


    .form-control {

        border-radius: 6px;

    }


    .form-control:focus {

        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.10);

    }


    /* =========================================================
       TABLE
    ========================================================== */

    .table th {

        background: #f8f9fa;

        font-size: 13px;

        font-weight: 600;

        white-space: nowrap;

        vertical-align: middle;

    }


    .table td {

        vertical-align: middle;

        font-size: 14px;

    }


    .table code {

        font-size: 12px;

        word-break: break-all;

    }


    .table .badge {

        font-size: 11px;

        padding: 5px 8px;

    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-state {

        padding: 15px;

    }


    .empty-state > i {

        font-size: 42px;

        color: #adb5bd;

    }


    .empty-state h5 {

        font-weight: 600;

        color: #495057;

    }


    /* =========================================================
       OPTIONS
    ========================================================== */

    #options-wrapper {

        transition: all .2s ease;

    }


    /* =========================================================
       MOBILE
    ========================================================== */

    @media (max-width: 767px) {

        .event-card-header {

            flex-direction: column;

            align-items: flex-start;

            gap: 15px;

            padding: 18px;

        }


        .event-header-title {

            width: 100%;

        }


        .event-header-title h1 {

            font-size: 21px;

        }


        .event-header-actions {

            width: 100%;

            justify-content: flex-start;

        }


        .event-header-actions .btn {

            flex: 1;

            text-align: center;

        }


        .event-logo-placeholder {

            width: 100px;

            height: 100px;

            font-size: 38px;

        }


        .table {

            min-width: 1000px;

        }


        .card-body.p-4 {

            padding: 18px !important;

        }

    }

</style>


{{-- =========================================================
     JAVASCRIPT
========================================================== --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const inputType = document.getElementById('input_type');

    const optionsWrapper = document.getElementById('options-wrapper');


    function toggleOptions() {

        const selectedType = inputType.value;


        if (
            selectedType === 'dropdown' ||
            selectedType === 'checkbox' ||
            selectedType === 'radio'
        ) {

            optionsWrapper.style.display = 'block';

        } else {

            optionsWrapper.style.display = 'none';

        }

    }


    inputType.addEventListener('change', toggleOptions);


    // Restore correct state after validation error
    toggleOptions();

});

</script>

@endsection