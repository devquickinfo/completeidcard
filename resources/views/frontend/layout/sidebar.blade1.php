@php
    use App\Helpers\ImageHelper;
    use App\Models\Permission;

    /*
    |--------------------------------------------------------------------------
    | CURRENT ROLE / SESSION
    |--------------------------------------------------------------------------
    */

    $role = session('role');

    $isSuperAdmin = $role === 'superadmin';
    $isVendor     = $role === 'vendor';
    $isSchool     = $role === 'school';

    $viewingSchool = session('viewing_school');
    $vendorViewing = session('vendor_viewing');

    /*
    |--------------------------------------------------------------------------
    | CONTEXT
    |--------------------------------------------------------------------------
    */

    // Superadmin is currently viewing a vendor
    $isVendorViewing =
        $isSuperAdmin &&
        !empty($vendorViewing);

    // Superadmin is currently viewing a school
    $isSchoolViewing =
        $isSuperAdmin &&
        !empty($viewingSchool);

    // Vendor is currently viewing a school
    $isVendorSchoolViewing =
        $isVendor &&
        !empty($viewingSchool);


    /*
    |--------------------------------------------------------------------------
    | VENDOR PERMISSION
    |--------------------------------------------------------------------------
    */

    if ($isVendorViewing) {

        // Superadmin viewing selected vendor
        $vendorPermission = Permission::where(
            'vendor_id',
            $vendorViewing
        )->first();

    } elseif ($isVendor) {

        // Normal logged-in vendor
        $vendorPermission = ImageHelper::getVendorPermission();

    } else {

        $vendorPermission = null;
    }


    /*
    |--------------------------------------------------------------------------
    | VENDOR PERMISSION FLAGS
    |--------------------------------------------------------------------------
    */

    $vendorSchoolPermission =
        $vendorPermission?->school == 1;

    $vendorEventPermission =
        $vendorPermission?->event == 1;


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    |
    | Superadmin -> Dashboard
    | Vendor     -> Dashboard
    | School     -> No Dashboard
    |
    */

    $showDashboard =
        $isSuperAdmin ||
        $isVendor;


    /*
    |--------------------------------------------------------------------------
    | MAIN SCHOOL MENU
    |--------------------------------------------------------------------------
    |
    | This is the MAIN "Schools" listing.
    |
    | Superadmin:
    |   Show only in normal mode.
    |
    | Vendor:
    |   Show only when vendor is NOT inside a school.
    |
    */

    $showSchoolMenu = false;

    if ($isSuperAdmin) {

        $showSchoolMenu =
            !$isVendorViewing &&
            !$isSchoolViewing;

    } elseif ($isVendor) {

        $showSchoolMenu =
            !$isVendorSchoolViewing &&
            $vendorSchoolPermission;
    }


    /*
    |--------------------------------------------------------------------------
    | SELECTED SCHOOL NAVIGATION
    |--------------------------------------------------------------------------
    |
    | This section is for:
    |
    | - Normal school user
    | - Superadmin viewing school
    | - Vendor viewing school
    |
    */

    $showSchoolNavigation =
        $isSchool ||
        $isSchoolViewing ||
        $isVendorSchoolViewing;


    /*
    |--------------------------------------------------------------------------
    | VENDOR NAVIGATION
    |--------------------------------------------------------------------------
    |
    | Show vendor navigation:
    |
    | - Normal vendor
    | - Superadmin viewing vendor
    |
    | But if vendor is viewing a school, vendor navigation is hidden.
    |
    */

    $showVendorNavigation =
        (
            $isVendor ||
            $isVendorViewing
        )
        &&
        !$isVendorSchoolViewing;


    /*
    |--------------------------------------------------------------------------
    | VENDOR EVENTS
    |--------------------------------------------------------------------------
    */

    $showVendorEvents =
        $showVendorNavigation &&
        $vendorEventPermission;


    /*
    |--------------------------------------------------------------------------
    | SUPERADMIN EVENTS
    |--------------------------------------------------------------------------
    |
    | Show only in completely normal Superadmin mode.
    |
    */

    $showSuperAdminEvents =
        $isSuperAdmin &&
        !$isVendorViewing &&
        !$isSchoolViewing;


    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATION
    |--------------------------------------------------------------------------
    */

    $administrationOpen =
        request()->routeIs('student.deleted') ||
        request()->routeIs('upload-samples.*') ||
        request()->routeIs('user.account');

@endphp


<aside class="main-sidebar sidebar-dark-primary elevation-4">

    {{-- ========================================================= --}}
    {{-- BRAND --}}
    {{-- ========================================================= --}}

    <a href="{{ route('dashboard') }}" class="brand-link">

        <img
            src="{{ asset('frontend/dist/img/schoolid.jpg') }}"
            alt="School ID Logo"
            class="brand-image img-circle elevation-3"
            style="
                opacity:.8;
                width:33px;
                height:33px;
                object-fit:cover;
            "
        >

        <span class="brand-text font-weight-light">
            School ID Card
        </span>

    </a>


    <div class="sidebar">

        <nav class="mt-2">

            <ul
                class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
            >


                {{-- ================================================= --}}
                {{-- DASHBOARD --}}
                {{-- ================================================= --}}

                @if($showDashboard)

                    <li class="nav-item">

                        <a
                            href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-tachometer-alt"></i>

                            <p>
                                Dashboard
                            </p>

                        </a>

                    </li>

                @endif



                {{-- ================================================= --}}
                {{-- MAIN SCHOOL LIST --}}
                {{-- ================================================= --}}
                {{-- 
                    This is shown ONLY when:
                    - Normal Superadmin
                    - Normal Vendor with school permission
                --}}

                @if($showSchoolMenu)

                    <li class="nav-item">

                        <a
                            href="{{ route('schools.index') }}"
                            class="nav-link {{ request()->routeIs('schools.index') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-school"></i>

                            <p>
                                Schools
                            </p>

                        </a>

                    </li>

                @endif



                {{-- ================================================= --}}
                {{-- SELECTED / VIEWING SCHOOL --}}
                {{-- ================================================= --}}
                {{-- 
                    This is shown ONLY when a school is selected:
                    - School user
                    - Superadmin viewing school
                    - Vendor viewing school
                --}}

                @if($showSchoolNavigation)

                    @php
                        $schoolID = $viewingSchool ?? null;
                    @endphp

                    @if($schoolID || $isSchool)

                        <li class="nav-item">

                            <a
                                href="{{ $schoolID
                                    ? route('schools.show', $schoolID)
                                    : route('schools.show', Auth::user()->school_id)
                                }}"
                                class="nav-link {{ request()->routeIs('schools.show') ? 'active' : '' }}"
                            >

                                <i class="nav-icon fas fa-school"></i>

                                <p>
                                    School
                                </p>

                            </a>

                        </li>

                    @endif

                @endif



                {{-- ================================================= --}}
                {{-- VENDOR NAVIGATION --}}
                {{-- ================================================= --}}
                {{-- 
                    Normal Vendor
                    OR
                    Superadmin viewing Vendor
                --}}

                @if($showVendorNavigation)


                    {{-- ================================================= --}}
                    {{-- VENDOR SCHOOLS --}}
                    {{-- ================================================= --}}

                    @if($vendorSchoolPermission)

                        <li class="nav-item">

                            <a
                                href="{{ route('schools.index') }}"
                                class="nav-link {{ request()->routeIs('schools.index') ? 'active' : '' }}"
                            >

                                <i class="nav-icon fas fa-school"></i>

                                <p>
                                    Schools
                                </p>

                            </a>

                        </li>

                    @endif



                    {{-- ================================================= --}}
                    {{-- STUDENTS --}}
                    {{-- ================================================= --}}

                    @if($vendorSchoolPermission)

                        <li class="nav-item">

                            <a
                                href="{{ route('student.list') }}"
                                class="nav-link {{ request()->routeIs('student.list') ? 'active' : '' }}"
                            >

                                <i class="nav-icon fas fa-user-graduate"></i>

                                <p>
                                    Students
                                </p>

                            </a>

                        </li>

                    @endif



                    {{-- ================================================= --}}
                    {{-- CREATE STUDENT ID CARD --}}
                    {{-- ================================================= --}}

                    @if($vendorSchoolPermission)

                        <li class="nav-item">

                            <a
                                href="{{ route('idcard.create') }}"
                                class="nav-link {{ request()->routeIs('idcard.create') ? 'active' : '' }}"
                            >

                                <i class="nav-icon fas fa-id-card"></i>

                                <p>
                                    Create Student ID Card
                                </p>

                            </a>

                        </li>

                    @endif



                    {{-- ================================================= --}}
                    {{-- IMPORT STUDENTS --}}
                    {{-- ================================================= --}}

                    @if($vendorSchoolPermission)

                        <li class="nav-item">

                            <a
                                href="{{ route('student.import') }}"
                                class="nav-link {{ request()->routeIs('students.import.*') ? 'active' : '' }}"
                            >

                                <i class="nav-icon fas fa-file-import"></i>

                                <p>
                                    Import Students
                                </p>

                            </a>

                        </li>

                    @endif



                    {{-- ================================================= --}}
                    {{-- TEACHERS --}}
                    {{-- ================================================= --}}

                    @if($vendorSchoolPermission)

                        <li class="nav-item">

                            <a
                                href="{{ route('teacher.list') }}"
                                class="nav-link {{ request()->routeIs('teacher.list') ? 'active' : '' }}"
                            >

                                <i class="nav-icon fas fa-chalkboard-teacher"></i>

                                <p>
                                    Teachers
                                </p>

                            </a>

                        </li>

                    @endif



                    {{-- ================================================= --}}
                    {{-- ID CARD TEMPLATES --}}
                    {{-- ================================================= --}}

                    @if($vendorSchoolPermission)

                        <li class="nav-item">

                            <a
                                href="{{ route('upload-samples.index') }}"
                                class="nav-link {{ request()->routeIs('upload-samples.*') ? 'active' : '' }}"
                            >

                                <i class="nav-icon fas fa-id-card"></i>

                                <p>
                                    ID Card Templates
                                </p>

                            </a>

                        </li>

                    @endif



                    {{-- ================================================= --}}
                    {{-- EVENTS --}}
                    {{-- ================================================= --}}

                    @if($showVendorEvents)

                        <li class="nav-item">

                            <a
                                href="{{ route('manage-events.index') }}"
                                class="nav-link {{ request()->routeIs('manage-events.*') ? 'active' : '' }}"
                            >

                                <i class="nav-icon fas fa-flag"></i>

                                <p>
                                    Events
                                </p>

                            </a>

                        </li>

                    @endif


                @endif



                {{-- ================================================= --}}
                {{-- SCHOOL NAVIGATION --}}
                {{-- ================================================= --}}
                {{-- 
                    This is shown when:
                    - Normal school
                    - Superadmin viewing school
                    - Vendor viewing school
                --}}

                @if($showSchoolNavigation)


                    {{-- ================================================= --}}
                    {{-- STUDENTS --}}
                    {{-- ================================================= --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('student.list') }}"
                            class="nav-link {{ request()->routeIs('student.list') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-user-graduate"></i>

                            <p>
                                Students
                            </p>

                        </a>

                    </li>



                    {{-- ================================================= --}}
                    {{-- CREATE ID CARD --}}
                    {{-- ================================================= --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('idcard.create') }}"
                            class="nav-link {{ request()->routeIs('idcard.create') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-id-card"></i>

                            <p>
                                Create Student ID Card
                            </p>

                        </a>

                    </li>



                    {{-- ================================================= --}}
                    {{-- IMPORT STUDENTS --}}
                    {{-- ================================================= --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('student.import') }}"
                            class="nav-link {{ request()->routeIs('students.import.*') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-file-import"></i>

                            <p>
                                Import Students
                            </p>

                        </a>

                    </li>



                    {{-- ================================================= --}}
                    {{-- TEACHERS --}}
                    {{-- ================================================= --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('teacher.list') }}"
                            class="nav-link {{ request()->routeIs('teacher.list') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-chalkboard-teacher"></i>

                            <p>
                                Teachers
                            </p>

                        </a>

                    </li>



                    {{-- ================================================= --}}
                    {{-- ID CARD TEMPLATES --}}
                    {{-- ================================================= --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('upload-samples.index') }}"
                            class="nav-link {{ request()->routeIs('upload-samples.*') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-id-card"></i>

                            <p>
                                ID Card Templates
                            </p>

                        </a>

                    </li>


                @endif



                {{-- ================================================= --}}
                {{-- PROFILE --}}
                {{-- ================================================= --}}

                <li class="nav-item">

                    <a
                        href="{{ route('school.profile') }}"
                        class="nav-link {{ request()->routeIs('school.profile') ? 'active' : '' }}"
                    >

                        <i class="nav-icon fas fa-user"></i>

                        <p>
                            Profile
                        </p>

                    </a>

                </li>



                {{-- ================================================= --}}
                {{-- SUPERADMIN EVENTS --}}
                {{-- ================================================= --}}

                @if($showSuperAdminEvents)


                    <li class="nav-item">

                        <a
                            href="{{ route('manage-events.index') }}"
                            class="nav-link {{ request()->routeIs('manage-events.*') ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-flag"></i>

                            <p>
                                Events
                            </p>

                        </a>

                    </li>



                    {{-- ================================================= --}}
                    {{-- ADMINISTRATION --}}
                    {{-- ================================================= --}}

                    <li class="nav-item {{ $administrationOpen ? 'menu-open' : '' }}">

                        <a
                            href="#"
                            class="nav-link {{ $administrationOpen ? 'active' : '' }}"
                        >

                            <i class="nav-icon fas fa-cog"></i>

                            <p>

                                Administration

                                <i class="right fas fa-angle-left"></i>

                            </p>

                        </a>


                        <ul class="nav nav-treeview">


                            {{-- ================================================= --}}
                            {{-- DELETED STUDENTS --}}
                            {{-- ================================================= --}}

                            <li class="nav-item">

                                <a
                                    href="{{ route('student.deleted') }}"
                                    class="nav-link {{ request()->routeIs('student.deleted') ? 'active' : '' }}"
                                >

                                    <i class="nav-icon fas fa-trash"></i>

                                    <p>
                                        Deleted Students
                                    </p>

                                </a>

                            </li>



                            {{-- ================================================= --}}
                            {{-- UPLOAD SAMPLE --}}
                            {{-- ================================================= --}}

                            <li class="nav-item">

                                <a
                                    href="{{ route('upload-samples.index') }}"
                                    class="nav-link {{ request()->routeIs('upload-samples.*') ? 'active' : '' }}"
                                >

                                    <i class="nav-icon fas fa-id-card"></i>

                                    <p>
                                        Upload Sample
                                    </p>

                                </a>

                            </li>



                            {{-- ================================================= --}}
                            {{-- ACCOUNT --}}
                            {{-- ================================================= --}}

                            <li class="nav-item">

                                <a
                                    href="{{ route('user.account') }}"
                                    class="nav-link {{ request()->routeIs('user.account') ? 'active' : '' }}"
                                >

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