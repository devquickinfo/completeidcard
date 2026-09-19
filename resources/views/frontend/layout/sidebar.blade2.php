 <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <!-- Brand -->
        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('frontend/dist/img/schoolid.jpg') }}"
                alt="School ID Logo"
                class="brand-image img-circle elevation-3"
                style="opacity: .8; width: 33px; height: 33px; object-fit: cover;">

            <span class="brand-text font-weight-light">
                School ID Card
            </span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false">

                    <!-- Dashboard -->
                    @if(session('role') === 'superadmin' && !session('viewing_school') || session('role') === 'vendor')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    @endif

                
                    <!-- Deleted Students -->
                   

                    <!-- School -->
                    @if(session('role') !== 'school' && !session('viewing_school') && session('role') !== 'vendor')
                    <li class="nav-item">
                        <a href="{{ Auth::user()->role === 'school'
                            ? route('schools.show', Auth::user()->school_id)
                            : route('schools.index') }}"
                        class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-school"></i>
                            <p>School</p>
                            
                        </a>
                    </li>
                    @endif
                    @if(session('viewing_school') || session('role') === 'school' || session('vendor_viewing'))
                    <li class="nav-item">
                        <a href="{{ route('schools.show', $schoolID) }}"
                        class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-school"></i>
                            <p>School</p>
                            
                        </a>
                    </li>
                    @endif

                    <!-- School Navigation -->
                    @if(session('role') === 'school' || session('viewing_school') || session('vendor_viewing'))

                        <!-- Students -->
                        <li class="nav-item">
                            <a href="{{ route('student.list') }}"
                            class="nav-link {{ request()->routeIs('student.list') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>Students</p>
                            </a>
                        </li>

                        <!-- Create ID Card -->
                        <li class="nav-item">
                            <a href="{{ route('idcard.create') }}"
                            class="nav-link {{ request()->routeIs('idcard.create') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-id-card"></i>
                                <p>Create Student ID Card</p>
                            </a>
                        </li>

                        <!-- Import Students -->
                        <li class="nav-item">
                            <a href="{{ route('student.import') }}"
                            class="nav-link {{ request()->routeIs('students.import.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-import"></i>
                                <p>Import Students</p>
                            </a>
                        </li>

                        <!-- Teachers -->
                        <li class="nav-item">
                            <a href="{{ route('teacher.list') }}"
                            class="nav-link {{ request()->routeIs('teacher.list') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>Teachers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a href="{{ route('upload-samples.index') }}"
                        class="nav-link {{ request()->routeIs('upload-samples.*') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-id-card"></i>

                            <p>
                               ID Card Templates
                            </p>
                        </a>
                        </li>
                        <li class="nav-item">
                        <a href="{{ route('school.profile')}}"
                        class="nav-link {{ request()->routeIs('idcard.editor.*') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-user"></i>
                            <p>Profile</p>
                        </a>
                        </li>

                    @endif
                    @if(session('role') === 'vendor' && !session('vendor_viewing') && $permission->event === 1)
                    <li class="nav-item">
                        <a href="{{ route('manage-events.index') }}"
                        class="nav-link {{ request()->routeIs('manage-events.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-flag"></i>
                            <p>
                                Events
                            </p>
                        </a>
                    </li>
                    @endif
                    @if(session('role') === 'superadmin' && !session('viewing_school'))
                    <li class="nav-item">
                        <a href="{{ route('manage-events.index') }}"
                        class="nav-link {{ request()->routeIs('manage-events.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-flag"></i>
                            <p>
                                Events
                            </p>
                        </a>
                    </li>
                    @php
                        $administrationOpen =
                            request()->routeIs('student.deleted') ||
                            request()->routeIs('upload-samples.*') || request()->routeIs('user.account');
                    @endphp
                    <li class="nav-item {{ $administrationOpen ? 'menu-open' : '' }}">
                        <a href="#"
                           class="nav-link {{ $administrationOpen ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Administration
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('student.deleted') }}"
                                class="nav-link {{ request()->routeIs('student.deleted') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-trash"></i>
                                    <p>Deleted Students</p>
                                </a>                  
        				            </li>
                            <li class="nav-item">
                                <a href="{{ route('upload-samples.index') }}"
                                class="nav-link {{ request()->routeIs('upload-samples.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-id-card"></i>
                                    <p>
                                        Upload Sample
                                    </p>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a href="{{ route('user.account') }}"
                                class="nav-link {{ request()->routeIs('user.account') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>
                                        Account
                                    </p>
                                </a>
                            </li>
                         </ul>
                    </li>
                    @endif
                </ul>
            </nav>

        </div>
    </aside>