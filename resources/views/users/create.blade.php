@extends('frontend.layout.applayout')
@section('title', 'Add User')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
        </div>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">

                    <div class="card-header">
                        <h3 class="card-title">
                            @if(isset($user))
                                Edit User
                            @else
                                Add User
                            @endif
                        </h3>

                        <a href="{{ route('user.account') }}"
                           class="btn btn-primary float-right">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <form id="quickForm" action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                          <input type="hidden" name="id" value="{{ @$user->id }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">
                                            Name <span class="text-danger">*</span>
                                        </label>

                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               id="name"
                                               placeholder="Enter User Name"
                                               value="{{ old('name', $user->name ?? '') }}" >
                                    </div>

                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="username">
                                            User Name <span class="text-danger">*</span>
                                        </label>

                                        <input type="text"
                                               name="username"
                                               class="form-control"
                                               id="username"
                                               placeholder="Enter username"
                                               value="{{ old('username', $user->username ?? '') }}" autocomplete="off">
                                    </div>

                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                {{-- Password --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="password">
                                            Password
                                            @if(!isset($user))
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>

                                        <input type="password"
                                               name="password"
                                               class="form-control"
                                               id="password" autocomplete="off"
                                               placeholder="{{ isset($user) ? 'Leave blank to keep current password' : 'Enter Password' }}">
                                    </div>

                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                {{-- Email --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="email">Email</label>

                                        <input type="email"
                                               name="email"
                                               class="form-control"
                                               id="email"
                                               placeholder="Enter Email"
                                               value="{{ old('email', $user->email ?? '') }}">
                                    </div>

                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                {{-- Phone --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="phone">
                                            Phone <span class="text-danger">*</span>
                                        </label>

                                        <input type="text"
                                               name="phone"
                                               class="form-control"
                                               id="phone"
                                               placeholder="Enter Phone"
                                               value="{{ old('phone', $user->phone ?? '') }}">
                                    </div>

                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="schoolcount">
                                            School Count <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="schoolcount"
                                               class="form-control"
                                               id="phone"
                                               placeholder="Enter School Limit"
                                               value="{{ old('schoolcount', $user->schoolcount ?? '') }}">
                                    </div>
                                    @error('schoolcount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>




                                {{-- Photo --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="user_logo">Photo</label>

                                        <input type="file"
                                               name="user_logo"
                                               class="form-control"
                                               id="user_logo">

                                        @if(isset($user) && $user->user_logo)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $user->user_logo) }}"
                                                     alt="User Photo"
                                                     width="80"
                                                     height="80"
                                                     style="object-fit: cover; border-radius: 5px;">
                                            </div>
                                        @endif
                                    </div>

                                    @error('user_logo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                {{-- Status --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status">Status</label>

                                        <select name="status"
                                                id="status"
                                                class="form-control">

                                            <option value="1"
                                                {{ old('status', $user->status ?? 1) == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>

                                            <option value="0"
                                                {{ old('status', $user->status ?? 1) == 0 ? 'selected' : '' }}>
                                                Inactive
                                            </option>

                                        </select>
                                    </div>

                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                 {{-- Permissions --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Permissions</label>

                                        <div class="custom-control custom-switch mb-2">
                                            <input type="hidden" name="school" value="0">

                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   id="school_permission"
                                                   name="school"
                                                   value="1"
                                                   {{ old('school', $permission->school ?? 0) == 1 ? 'checked' : '' }}>

                                            <label class="custom-control-label" for="school_permission">
                                                School Permission
                                            </label>
                                        </div>

                                        <div class="custom-control custom-switch">
                                            <input type="hidden" name="event" value="0">

                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   id="event_permission"
                                                   name="event"
                                                   value="1"
                                                   {{ old('event', $permission->event ?? 0) == 1 ? 'checked' : '' }}>

                                            <label class="custom-control-label" for="event_permission">
                                                Event Permission
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address">User Address</label>

                                        <textarea name="address"
                                                  class="form-control"
                                                  id="address"
                                                  rows="5"
                                                  placeholder="Enter User Address">{{ old('address', $user->address ?? '') }}</textarea>
                                    </div>

                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>


                        {{-- Footer --}}
                        <div class="card-footer">

                            <button type="submit" class="btn btn-primary">
                                @if(isset($user))
                                    Update
                                @else
                                    Submit
                                @endif
                            </button>

                            <a href="{{ route('user.account') }}"
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
@endsection