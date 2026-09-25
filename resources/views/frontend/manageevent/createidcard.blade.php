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


    /* Mobile */
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
        <div class="row mb-2">
        </div>
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
                <form action="{{ route('idcard.create') }}" method="GET" id="idCardFilterForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="photo">
                                    Photo
                                </label>

                                <select name="photo"
                                        id="photo"
                                        class="form-control" onchange="this.form.submit()">

                                    <option value="">All User</option>

                                    <option value="available"
                                        {{ request('photo') == 'available' ? 'selected' : '' }}>
                                        Photo Available
                                    </option>

                                    <option value="not_available"
                                        {{ request('photo') == 'not_available' ? 'selected' : '' }}>
                                        No Photo
                                    </option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="printed">
                                    ID Card Printed
                                </label>

                                <select name="printed"
                                        id="printed"
                                        class="form-control" onchange="this.form.submit()">

                                    <option value="">All</option>

                                    <option value="yes"
                                        {{ request('printed') == 'yes' ? 'selected' : '' }}>
                                        Yes
                                    </option>

                                    <option value="no"
                                        {{ request('printed') == 'no' ? 'selected' : '' }}>
                                        No
                                    </option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class_id">Paper</label>
                                <select name="papersize"
                                        id="papersize"
                                        class="form-control" onchange="this.form.submit()">
                                  
                                </select>
                            </div>
                        </div>
            
                       
                         <div class="col-md-4 mt-4">
                            <div class="form-group mt-1">
                               <a href="{{ route('idcard.print-filtered') }}?{{ request()->getQueryString() }}" 
                                  class="btn btn-info btn-block" target="_blank">
                                   <i class="fas fa-print mr-1"></i> Print ID Cards
                               </a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-12">

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i>
                                Search
                            </button>

                            <a href="{{ route('idcard.create') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-sync-alt mr-1"></i>
                                Reset
                            </a>

                        </div>
                    </div> -->
                </form>
            </div>
        </div>
</section>
</div>
@endsection


