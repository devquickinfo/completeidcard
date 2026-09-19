<nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link"
               data-widget="pushmenu"
               href="#"
               role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
    @if(session()->has('viewing_school'))
        <div class="school-navbar-info">
            <a href="{{ route('schools.show', session('viewing_school')) }}"
               class="school-name">
                {{ ucwords(trim($schoolname, '"')) }}
            </a>
            <a href="{{ route('dashboard') }}"
               class="btn btn-info btn-sm admin-menu-btn">
                <i class="fas fa-arrow-left mr-1"></i>
                Admin Menu
            </a>
        </div>
    @endif
    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-flex align-items-center mr-2">
            @if(Auth::user()->profilepicture)
                <a href="{{ asset('storage/' . Auth::user()->profilepicture) }}"
                   target="_blank"
                   class="profile-image-link">
                    <img src="{{ asset('storage/' . Auth::user()->profilepicture) }}"
                         alt="Profile"
                         class="img-circle elevation-2 profile-image">
                </a>
            @else
                <span class="profile-icon">
                    <i class="fas fa-user-circle"></i>
                </span>
            @endif
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link user-dropdown-link"
               data-toggle="dropdown"
               href="#">
                <i class="far fa-user mr-1"></i>
                <span class="user-name">
                    {{ ucwords(Auth::user()->name) }}
                </span>
                <i class="fas fa-caret-down ml-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                @if(Auth::user()?->role === 'school')
                    <a href="{{ route('school.profile') }}"
                       class="dropdown-item">

                        <i class="fas fa-school mr-2"></i>
                        School Profile

                    </a>
                @elseif(Auth::user()?->role === 'vendor')
                     <a href="{{ route('school.profile') }}"
                       class="dropdown-item">
                        <i class="fas fa-user mr-2"></i>
                       User Profile
                    </a>
                @else
                    <a href="{{ route('admin.profile') }}"
                       class="dropdown-item">
                        <i class="fas fa-user mr-2"></i>
                        Profile
                    </a>
                @endif
                <div class="dropdown-divider"></div>
                <a href="{{ route('user.logout') }}"
                   class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </a>
                <div class="dropdown-divider"></div>
            </div>
        </li>
    </ul>
  </nav>