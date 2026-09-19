<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
             'email' => 'required',
             'password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        if (Auth::attempt([
            'username' => $request->email,
            'password' => $request->password,
        ], $request->remember)) {
            $user = Auth::user();

            if ($user?->role === 'school' && $user->school && ! $user->school->status) {
                Auth::logout();

                return back()
                    ->withErrors([
                        'email' => 'Your account is not active. Please contact admin.',
                    ])
                    ->withInput();
            }
            /// Added on 19-09-2026
            if ($user?->role === 'vendor' && ! $user->status) {
                Auth::logout();

                return back()
                    ->withErrors([
                        'email' => 'Your account is not active. Please contact admin.',
                    ])
                    ->withInput();
            }
            // end /////
            $request->session()->regenerate();
            session([
                'user_id' => Auth::id(),
                'role'    => $user->role,
                'school_id' => $user->school_id,
            ]);

            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors([
                'email' => 'Invalid email or password.',
            ])
            ->withInput();
    }

    public function dashboard()
    {
        if (session()->has('viewing_school')) {
            session()->forget('viewing_school');
        }
        if (session()->has('vendor_viewing')) {
            session()->forget('vendor_viewing');
        }
        $user = Auth::user();

        if ($user?->role === 'school') {
            $school = $user->school;

            // Redirect school users to their school details page
            return redirect()->route('schools.show', $school);
        }

        if ($user?->role === 'vendor') {
            $users = User::where('role', 'vendor')
            ->where('id', Auth::id())
            ->first();
            $schools = School::where('vendor_id', Auth::id())->get();
            $schoolsCount = School::where('vendor_id', Auth::id())->count();
            $studentsCount = Student::where('vendor_id', Auth::id())->count();
            return view('users.dashboard', compact('user', 'users','schoolsCount','schools','studentsCount'));
        }


        $schoolsCount = School::count();
        $classesCount = StudentClass::count();
        $sectionsCount = Section::count();
        $studentsCount = Student::count();

        return view('frontend.index', compact('user', 'schoolsCount', 'classesCount', 'sectionsCount', 'studentsCount'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
