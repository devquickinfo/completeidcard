
@extends('frontend.layout.applayout')

@section('title', 'Event Details')

@section('content')

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm border-0 mb-4">
                <div class="event-card-header">
                    <div class="event-header-title">
                        <h1 class="m-0 font-weight-bold">
                            Event Details
                        </h1>

                        <small class="text-muted">
                            View complete information about this event
                        </small>

                    </div>


                   
                    <div class="event-header-actions">

                        <a href="{{ route('manage-events.index') }}"
                           class="btn btn-default">

                            <i class="fas fa-arrow-left mr-1"></i>
                            Back

                        </a>


                        <a href="{{ route('manage-events.edit', $manageEvent->id) }}"
                           class="btn btn-warning">

                            <i class="fas fa-edit mr-1"></i>
                            Edit Event

                        </a>

                    </div>

                </div>


                {{-- =====================================================
                     EVENT HERO CONTENT
                ====================================================== --}}
                <div class="card-body p-4">

                    <div class="row align-items-center">

                        {{-- =================================================
                             LOGO
                        ================================================== --}}
                        <div class="col-md-2 text-center mb-3 mb-md-0">

                            @if(!empty($manageEvent->logo))

                                <div class="event-logo-wrapper">

                                    <img src="{{ asset('storage/' . $manageEvent->logo) }}"
                                         alt="{{ $manageEvent->event_name }}"
                                         class="event-logo">

                                </div>

                            @else

                                <div class="event-logo-placeholder">

                                    <i class="fas fa-calendar-alt"></i>

                                </div>

                            @endif

                        </div>


                        {{-- =================================================
                             EVENT TITLE
                        ================================================== --}}
                        <div class="col-md-7">

                            <div class="mb-2">

                                <span class="badge badge-primary px-3 py-2">

                                    <i class="fas fa-calendar-check mr-1"></i>

                                    Event

                                </span>

                            </div>


                            <h2 class="font-weight-bold mb-2">

                                {{ $manageEvent->event_name ?? '-' }}

                            </h2>


                            <div class="text-muted">

                                <i class="fas fa-calendar-day mr-1"></i>

                                @if($manageEvent->start_date)

                                    {{ \Carbon\Carbon::parse($manageEvent->start_date)->format('d M Y') }}

                                @else

                                    -

                                @endif


                                <span class="mx-2">
                                    to
                                </span>


                                <i class="fas fa-calendar-day mr-1"></i>

                                @if($manageEvent->end_date)

                                    {{ \Carbon\Carbon::parse($manageEvent->end_date)->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        {{-- =================================================
                             QR CODE
                        ================================================== --}}
                        <div class="col-md-3 text-center">

                            <div class="qr-box">

                                <div class="qr-title">

                                    <i class="fas fa-qrcode mr-1"></i>

                                    Event QR Code

                                </div>


                                <div class="qr-image">

                                    {!! QrCode::size(130)->generate(
                                        route('events.public', $manageEvent->unique_code)
                                    ) !!}

                                </div>


                                <small class="text-muted d-block mt-2">

                                    Scan to view event & register

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                 INFORMATION CARDS
            ========================================================== --}}
            <div class="row">

                {{-- =====================================================
                     EVENT INFORMATION
                ====================================================== --}}
                <div class="col-lg-8">

                    <div class="card shadow-sm border-0 h-100">

                        <div class="card-header border-bottom">

                            <h3 class="card-title font-weight-bold">

                                <i class="fas fa-info-circle text-primary mr-2"></i>

                                Event Information

                            </h3>

                        </div>


                        <div class="card-body">

                            <div class="row">

                                {{-- Start Date --}}
                                <div class="col-md-6 mb-4">

                                    <div class="info-item">

                                        <div class="info-icon bg-primary">

                                            <i class="fas fa-calendar-alt"></i>

                                        </div>


                                        <div>

                                            <div class="info-label">
                                                Start Date
                                            </div>


                                            <div class="info-value">

                                                @if($manageEvent->start_date)

                                                    {{ \Carbon\Carbon::parse($manageEvent->start_date)->format('d M Y') }}

                                                @else

                                                    -

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- End Date --}}
                                <div class="col-md-6 mb-4">

                                    <div class="info-item">

                                        <div class="info-icon bg-danger">

                                            <i class="fas fa-calendar-times"></i>

                                        </div>


                                        <div>

                                            <div class="info-label">
                                                End Date
                                            </div>


                                            <div class="info-value">

                                                @if($manageEvent->end_date)

                                                    {{ \Carbon\Carbon::parse($manageEvent->end_date)->format('d M Y') }}

                                                @else

                                                    -

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- Contact Person --}}
                                <div class="col-md-6 mb-4">

                                    <div class="info-item">

                                        <div class="info-icon bg-success">

                                            <i class="fas fa-user"></i>

                                        </div>


                                        <div>

                                            <div class="info-label">
                                                Contact Person
                                            </div>


                                            <div class="info-value">

                                                {{ $manageEvent->contact_person1 ?? '-' }}

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- Organizer --}}
                                <div class="col-md-6 mb-4">

                                    <div class="info-item">

                                        <div class="info-icon bg-warning">

                                            <i class="fas fa-users"></i>

                                        </div>


                                        <div>

                                            <div class="info-label">
                                                Organizer
                                            </div>


                                            <div class="info-value">

                                                {{ $manageEvent->organizer_name ?? '-' }}

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- Unique Code --}}
                                <div class="col-md-12">

                                    <div class="info-item">

                                        <div class="info-icon bg-info">

                                            <i class="fas fa-key"></i>

                                        </div>


                                        <div class="flex-grow-1">

                                            <div class="info-label">
                                                Unique Event Code
                                            </div>


                                            <div class="unique-code">

                                                {{ $manageEvent->unique_code ?? '-' }}

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     EVENT SUMMARY
                ====================================================== --}}
                <div class="col-lg-4 mt-3 mt-lg-0">

                    <div class="card shadow-sm border-0 h-100">

                        <div class="card-header border-bottom">

                            <h3 class="card-title font-weight-bold">

                                <i class="fas fa-chart-pie text-primary mr-2"></i>

                                Event Summary

                            </h3>

                        </div>


                        <div class="card-body">

                            {{-- Event Name --}}
                            <div class="summary-item">

                                <div class="summary-icon">

                                    <i class="fas fa-calendar"></i>

                                </div>


                                <div>

                                    <small class="text-muted">
                                        Event Name
                                    </small>


                                    <div class="font-weight-bold">

                                        {{ $manageEvent->event_name ?? '-' }}

                                    </div>

                                </div>

                            </div>


                            {{-- Location --}}
                            <div class="summary-item">

                                <div class="summary-icon">

                                    <i class="fas fa-map-marker-alt"></i>

                                </div>


                                <div>

                                    <small class="text-muted">
                                        Location
                                    </small>


                                    <div class="font-weight-bold">

                                        {{ $manageEvent->address ?? 'Not specified' }}

                                    </div>

                                </div>

                            </div>


                            {{-- Organizer --}}
                            <div class="summary-item">

                                <div class="summary-icon">

                                    <i class="fas fa-user-tie"></i>

                                </div>


                                <div>

                                    <small class="text-muted">
                                        Organizer
                                    </small>


                                    <div class="font-weight-bold">

                                        {{ $manageEvent->organizer_name ?? '-' }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                 ADDRESS
            ========================================================== --}}
            <div class="card shadow-sm border-0 mt-4">

                <div class="card-header border-bottom">

                    <h3 class="card-title font-weight-bold">

                        <i class="fas fa-map-marker-alt text-danger mr-2"></i>

                        Event Address

                    </h3>

                </div>


                <div class="card-body">

                    @if(!empty($manageEvent->address))

                        <div class="address-box">

                            <i class="fas fa-map-marker-alt text-danger mr-2"></i>

                            {{ $manageEvent->address }}

                        </div>

                    @else

                        <div class="text-muted">

                            <i class="fas fa-info-circle mr-1"></i>

                            No address information available.

                        </div>

                    @endif

                </div>

            </div>


            {{-- =========================================================
                 DESCRIPTION
            ========================================================== --}}
            <div class="card shadow-sm border-0 mt-4">

                <div class="card-header border-bottom">

                    <h3 class="card-title font-weight-bold">

                        <i class="fas fa-align-left text-primary mr-2"></i>

                        Event Description

                    </h3>

                </div>


                <div class="card-body">

                    @if(!empty($manageEvent->description))

                        <div class="description-box">

                            {!! nl2br(e($manageEvent->description)) !!}

                        </div>

                    @else

                        <div class="text-muted">

                            <i class="fas fa-info-circle mr-1"></i>

                            No description available.

                        </div>

                    @endif

                </div>

            </div>


            {{-- =========================================================
                 METADATA
            ========================================================== --}}
            <div class="card shadow-sm border-0 mt-4 mb-4">

                <div class="card-body">

                    <div class="row">

                        {{-- Created At --}}
                        <div class="col-md-6">

                            <div class="metadata">

                                <div class="metadata-icon">

                                    <i class="fas fa-plus-circle"></i>

                                </div>


                                <div>

                                    <small class="text-muted">
                                        Created At
                                    </small>


                                    <div class="font-weight-bold">

                                        @if($manageEvent->created_at)

                                            {{ $manageEvent->created_at->format('d M Y, h:i A') }}

                                        @else

                                            -

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Updated At --}}
                        <div class="col-md-6 mt-3 mt-md-0">

                            <div class="metadata">

                                <div class="metadata-icon">

                                    <i class="fas fa-sync-alt"></i>

                                </div>


                                <div>

                                    <small class="text-muted">
                                        Last Updated
                                    </small>


                                    <div class="font-weight-bold">

                                        @if($manageEvent->updated_at)

                                            {{ $manageEvent->updated_at->format('d M Y, h:i A') }}

                                        @else

                                            -

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

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
       EVENT CARD HEADER
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
       EVENT LOGO
    ========================================================== */

    .event-logo-wrapper {

        width: 150px;
        height: 150px;

        margin: auto;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #e5e7eb;

        border-radius: 12px;

        background: #f8f9fa;

        padding: 10px;
    }


    .event-logo {

        max-width: 100%;
        max-height: 100%;

        object-fit: contain;

        border-radius: 8px;
    }


    .event-logo-placeholder {

        width: 150px;
        height: 150px;

        margin: auto;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #f4f6f9;

        color: #adb5bd;

        font-size: 55px;
    }


    /* =========================================================
       QR CODE
    ========================================================== */

    .qr-box {

        background: #f8f9fa;

        border: 1px solid #e5e7eb;

        border-radius: 12px;

        padding: 15px;
    }


    .qr-title {

        font-weight: 600;

        margin-bottom: 10px;

        color: #343a40;
    }


    .qr-image {

        background: #fff;

        display: inline-flex;

        padding: 8px;

        border-radius: 8px;

        border: 1px solid #e5e7eb;
    }


    /* =========================================================
       INFORMATION ITEMS
    ========================================================== */

    .info-item {

        display: flex;

        align-items: center;

        gap: 14px;
    }


    .info-icon {

        min-width: 45px;

        height: 45px;

        border-radius: 10px;

        display: flex;

        align-items: center;

        justify-content: center;

        color: #fff;

        font-size: 17px;
    }


    .info-label {

        font-size: 12px;

        color: #6c757d;

        text-transform: uppercase;

        letter-spacing: .4px;

        margin-bottom: 3px;
    }


    .info-value {

        font-size: 15px;

        font-weight: 600;

        color: #fff;
    }


    /* =========================================================
       UNIQUE CODE
    ========================================================== */

    .unique-code {

        display: inline-block;

        max-width: 100%;

        padding: 7px 10px;

        background: #e8f7fb;

        color: #117a8b;

        border-radius: 6px;

        font-family: monospace;

        font-size: 13px;

        line-height: 1.6;

        word-break: break-all;

        overflow-wrap: anywhere;
    }


    /* =========================================================
       SUMMARY
    ========================================================== */

    .summary-item {

        display: flex;

        align-items: flex-start;

        gap: 14px;

        padding: 15px 0;

        border-bottom: 1px solid #eee;
    }


    .summary-item:first-child {

        padding-top: 0;
    }


    .summary-item:last-child {

        border-bottom: none;

        padding-bottom: 0;
    }


    .summary-icon {

        min-width: 40px;

        height: 40px;

        border-radius: 50%;

        background: #eef4ff;

        color: #007bff;

        display: flex;

        align-items: center;

        justify-content: center;
    }


    /* =========================================================
       ADDRESS
    ========================================================== */

    .address-box {

        padding: 15px 18px;

        border-radius: 8px;

        border-left: 4px solid #dc3545;

        font-size: 15px;

        line-height: 1.6;

        overflow-wrap: anywhere;
    }


    /* =========================================================
       DESCRIPTION
    ========================================================== */

    .description-box {

        border-radius: 8px;

        padding: 18px;

        line-height: 1.8;

        min-height: 100px;

        overflow-wrap: anywhere;
    }


    /* =========================================================
       METADATA
    ========================================================== */

    .metadata {

        display: flex;

        align-items: center;

        gap: 12px;
    }


    .metadata-icon {

        width: 42px;

        height: 42px;

        border-radius: 50%;

        background: #f1f3f5;

        color: #6c757d;

        display: flex;

        align-items: center;

        justify-content: center;
    }


    /* =========================================================
       GENERAL CARD
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


        .event-logo-wrapper,
        .event-logo-placeholder {

            width: 120px;

            height: 120px;
        }


        .qr-box {

            margin-top: 20px;
        }

    }

</style>

@endsection

