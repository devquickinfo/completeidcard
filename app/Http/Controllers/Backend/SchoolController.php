<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\School;

use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\SelectedSample;
use App\Models\UploadSample;
use App\Models\Mainidcard;
use Illuminate\Support\Facades\DB;


class SchoolController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = Auth::user();

    //     // clear any previously set viewing_school when listing schools
    //     if (session()->has('viewing_school')) {
    //         session()->forget('viewing_school');
    //     }

    //     if ($user && $user->role === 'school' && $user->school_id) {
    //         $school = School::findOrFail($user->school_id);

    //         return redirect()->route('schools.show', $school);
    //     }

        
    //     //$schools = School::where('IsDeleted', 0)->orderBy('school_name', 'asc')->paginate(10);
    //      $search = $request->input('search');

    //      $schools = School::where('IsDeleted', 0)
    //         ->when(strlen($search) >= 3, function ($query) use ($search) {
    //             $query->where('school_name', 'like', '%' . $search . '%');
    //         })
    //         ->orderBy('school_name', 'asc')
    //         ->paginate(10)
    //         ->withQueryString();

    //     return view(
    //         'schools.index',
    //         compact('schools','search')
    //     );
    // }
    public function index(Request $request)
    {
        $user = Auth::user();
        if (session()->has('viewing_school')) {
            session()->forget('viewing_school');
        }
        if (session()->has('vendor_viewing')) {
            session()->forget('vendor_viewing');
        }
        if ($user && $user->role === 'school' && $user->school_id) {
            $school = School::findOrFail($user->school_id);

            return redirect()->route('schools.show', $school);
        }
        $search = $request->input('search');
        $schools = School::where('IsDeleted', 0)->whereNull('vendor_id')
            //->when($user && $user->role === 'vendor', function ($query) use ($user) {
                //$query->where('vendor_id', $user->id);
           // })
            ->when(strlen($search) >= 3, function ($query) use ($search) {
                $query->where('school_name', 'like', '%' . $search . '%');
            })
            ->orderBy('school_name', 'asc')
            ->paginate(10)
            ->withQueryString();
        return view(
            'schools.index',
            compact('schools', 'search')
        );
    }



    public function create()
    {
        return view('schools.create');
    }



    
    // public function store(Request $request)
    // {

    //    // echo '<pre>';print_r($request->all()); die;
    //     $validator = Validator::make($request->all(), [
    //         'school_name' => 'required',
    //         //'school_code' => 'required|unique:schools',
    //         'email' => 'nullable|email',
    //         'phone' =>'required',

    //         'school_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
    //         'username' => 'required|string|max:255|unique:users,username',
    //         'password' => 'required|string|min:4',
            
    //     ], [
    //         'username.required' => 'Username is required.',
    //         'username.unique' => 'This username is already taken.',
    //         'password.required' => 'Password is required.',
    //         'password.min' => 'Password must be at least 6 characters.',
           
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()
    //             ->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     $data = $request->only([
    //         'school_name',
    //         'school_code',
    //         'email',
    //         'phone',
    //         'address',
    //         'city',
    //         'state',
    //         'pincode',
    //         'status',
    //     ]);

    //     if (session('role') === 'vendor') {
    //        $data['vendor_id'] = Auth::id();
    //        $data['student_limit'] = $request->student_limit;
    //     }

    //     if ($request->hasFile('school_logo')) {
    //         $data['logo'] = $request->file('school_logo')
    //             ->store('schools', 'public');
    //     }

    //     // Create school
    //     $school = School::create($data);

    //     // Create school user
    //     $user= User::create([
    //         'name' => $school->school_name,
    //         'username' => $request->username,
    //         'email' => $school->email,
    //         'password' => Hash::make($request->password),
    //         'role' => 'school',
    //         'school_id' => $school->id,
    //     ]);

    //     $userId = $user->id;

    //     $school->update([
    //         'user_id' => $userId,
    //     ]);

    //     return redirect()
    //         ->route('schools.show', ['school' => $school->id])
    //         ->with('success', 'School Added Successfully');
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required',

            'school_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:4',

            'student_limit' => session('role') === 'vendor'
                ? 'required'
                : 'nullable',

        ], [
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 4 characters.',
            'student_limit.required' => 'Student limit is required.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {

            DB::transaction(function () use ($request, &$school) {

                $data = $request->only([
                    'school_name',
                    'school_code',
                    'email',
                    'phone',
                    'address',
                    'city',
                    'state',
                    'pincode',
                    'status',
                ]);

                if (session('role') === 'vendor') {
                    $data['vendor_id'] = Auth::id();
                    $data['student_limit'] = $request->student_limit;
                }

                if ($request->hasFile('school_logo')) {
                    $data['logo'] = $request->file('school_logo')
                        ->store('schools', 'public');
                }

                // Create school
                $school = School::create($data);

                // Create school user
                $user = User::create([
                    'name' => $school->school_name,
                    'username' => $request->username,
                    'email' => $school->email,
                    'password' => Hash::make($request->password),
                    'role' => 'school',
                    'school_id' => $school->id,
                ]);

                // Update school with user ID
                $school->update([
                    'user_id' => $user->id,
                ]);
            });

            return redirect()
                ->route('schools.show', ['school' => $school->id])
                ->with('success', 'School Added Successfully');

        }catch (\Throwable $e) {
             dd($e->getMessage());
        }
    }



    public function show(School $school)
    {
        $user = Auth::user();

        if (
            $user &&
            $user->role === 'school' &&
            $user->school_id &&
            $user->school_id !== $school->id
        ) {
            abort(403);
        }
        $classes = StudentClass::with('sections')
             ->whereHas('students', function ($query) use ($school) {
                $query->where('school_id', $school->id)->where('IsDeleted', '0');
             })
            ->withCount([
                'students as students_count' => function ($query) use ($school) {
                    $query->where('school_id', $school->id)->where('IsDeleted', '0');
                },
                'students as without_photo_count' => function ($query) use ($school) {
                    $query->where('school_id', $school->id)->where('IsDeleted', '0')
                        ->whereNull('photo');
                },
            ])
            ->get();

        // If a superadmin is viewing a school's page, set a session key
        // so UI and controllers can render school-specific navigation/data.
        if ($user && $user->role === 'superadmin') {
            session(['viewing_school' => $school->id]);
        }

        if ($user && $user->role === 'vendor') {
            session(['vendor_viewing' => $school->id]);
        }
        $school_id = Auth::user()->school_id ?? session('viewing_school') ?? session('vendor_viewing');
        // $idcardsample = null;
        // $selectedSample = SelectedSample::where('school_id', $school_id)->first();
        // if ($selectedSample) {
        //     $idcardsample = UploadSample::where('id', $selectedSample->sample_id)->first();
        // }
        // Vertical selected sample
        $verticalSelectedSample = SelectedSample::where('school_id', $school_id)
            ->where('orientation', 'vertical')
            ->first();

        $verticalSample = null;

        if ($verticalSelectedSample) {
            $verticalSample = UploadSample::where(
                'id',
                $verticalSelectedSample->sample_id
            )->first();
        }


        // Horizontal selected sample
        $horizontalSelectedSample = SelectedSample::where('school_id', $school_id)
            ->where('orientation', 'horizontal')
            ->first();

        $horizontalSample = null;

        if ($horizontalSelectedSample) {
            $horizontalSample = UploadSample::where(
                'id',
                $horizontalSelectedSample->sample_id
            )->first();
        }

        //$mainidcard = Mainidcard::where('school_id', $school->id)->first();
        $verticalDesign = Mainidcard::where('school_id', $school->id)
            ->where('orientation', 'vertical')->where('is_default',1)
            ->first();

        $horizontalDesign = Mainidcard::where('school_id', $school->id)
            ->where('orientation', 'horizontal')->where('is_default',1)
            ->first();

        return view('schools.show', compact('school', 'classes','verticalSample','horizontalSample','verticalDesign','horizontalDesign'));
    }

    public function edit(School $school)
    {
        $user = Auth::user();

        if (
            $user &&
            $user->role === 'school' &&
            $user->school_id &&
            $user->school_id !== $school->id
        ) {
            abort(403);
        }

        return view(
            'schools.edit',
            compact('school')
        );
    }

    // public function profile()
    // {
    //     $user = Auth::user();

    //     if (! $user || $user->role !== 'school' || ! $user->school_id) {
    //         abort(403);
    //     }
         
    //     $school = $user->school;
    //     $schoolUser = User::where('role', 'school')->where('school_id', $school->id)->first();

    //     return view('schools.profile', compact('school', 'schoolUser'));
    // }

    public function profile()
    {
        $user = Auth::user();

        if (
            !session('viewing_school') &&
            (!$user || $user->role !== 'school' || !$user->school_id)
        ) {
            abort(403);
        }

        $schoolId = $user?->school_id ?? session('viewing_school');

        $school = School::findOrFail($schoolId);

        $schoolUser = User::where('school_id', $schoolId)
            ->where('role', 'school')
            ->first();

        return view('schools.profile', compact('school', 'schoolUser'));
    }
   
   
    // public function updateProfile(Request $request)
    // {
    //     $user = Auth::user();
    //     if (! $user || $user->role !== 'school' || ! $user->school_id) {
    //         abort(403);
    //     }

    //     $school = $user->school;

    //     $schoolUser = User::where('role', 'school')
    //         ->where('school_id', $school->id)
    //         ->first();

    //     $request->validate([
    //         'school_name' => 'required',
    //         'school_code' => 'required|unique:schools,school_code,' . $school->id,
    //         'email' => 'required|email',
    //         'principal_name' => 'required|string',
    //         'phone' => 'required|string',
    //         'address' => 'required|string',

    //         'username' => 'required|string|unique:users,email,' . ($schoolUser?->id ?? 'NULL'),

    //         'password' => 'nullable|min:6|confirmed',

    //         'school_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

    //         'principal_signature' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
    //     ]);
    //     $data = [];
    //     if ($request->hasFile('school_logo')) {
    //         if (
    //             $school->logo &&
    //             Storage::disk('public')->exists($school->logo)
    //         ) {
    //             Storage::disk('public')->delete($school->logo);
    //         }

    //         $data['logo'] = $request->file('school_logo')
    //             ->store('schools', 'public');
    //     }
    //     if ($request->hasFile('principal_signature')) {

    //         if (
    //             $school->principal_signature &&
    //             Storage::disk('public')->exists($school->principal_signature)
    //         ) {
    //             Storage::disk('public')->delete($school->principal_signature);
    //         }

    //         $data['principal_signature'] = $request->file('principal_signature')
    //             ->store('schools/signatures', 'public');
    //     }
    //     $school->update([
    //         'school_name' => $request->school_name,
    //         'school_code' => $request->school_code,
    //         'email' => $request->email,
    //         'principal_name' => $request->principal_name,
    //         'phone' => $request->phone,
    //         'address' => $request->address,
    //         'status' => $school->status,

    //         'logo' => $data['logo'] ?? $school->logo,

    //         'principal_signature' =>
    //             $data['principal_signature'] ?? $school->principal_signature,
    //     ]);

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Update School User
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($schoolUser) {

    //         $schoolUser->update([
    //             'name' => $school->school_name,
    //             'email' => $request->username,
    //         ]);

    //         if ($request->filled('password')) {
    //             $schoolUser->update([
    //                 'password' => Hash::make($request->password),
    //             ]);
    //         }
    //     }

    //     return redirect()
    //         ->route('schools.show', [
    //             'school' => Auth::user()->school_id
    //         ])
    //         ->with('success', 'School Updated Successfully');
    // }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (
            !session('viewing_school') &&
            (!$user || $user->role !== 'school' || !$user->school_id)
        ) {
            abort(403);
        }
        $schoolId = session('viewing_school') ?? $user?->school_id;
        $school = School::findOrFail($schoolId);
        $schoolUser = User::where('role', 'school')
            ->where('school_id', $schoolId)
            ->first();
        $request->validate([
            'school_name' => 'required',
            'school_code' => 'nullable|unique:schools,school_code,' . $school->id,
            'email' => 'nullable|email',
            'principal_name' => 'nullable|string',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'username' => 'required|string|unique:users,username,' . ($schoolUser?->id ?? 'NULL'),
            'password' => 'nullable|min:6|confirmed',
            'school_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'principal_signature' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
        ]);

        $data = [];

        // School Logo
        if ($request->hasFile('school_logo')) {

            if (
                $school->logo &&
                Storage::disk('public')->exists($school->logo)
            ) {
                Storage::disk('public')->delete($school->logo);
            }

            $data['logo'] = $request->file('school_logo')
                ->store('schools', 'public');
        }

        // Principal Signature
        if ($request->hasFile('principal_signature')) {

            if (
                $school->principal_signature &&
                Storage::disk('public')->exists($school->principal_signature)
            ) {
                Storage::disk('public')->delete($school->principal_signature);
            }

            $data['principal_signature'] = $request->file('principal_signature')
                ->store('schools/signatures', 'public');
        }

        // Update School
        $school->update([
            'school_name' => $request->school_name,
            'school_code' => $request->school_code,
            'email' => $request->email,
            'principal_name' => $request->principal_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $school->status,

            'logo' => $data['logo'] ?? $school->logo,

            'principal_signature' =>
                $data['principal_signature'] ?? $school->principal_signature,
        ]);

        // Update School User
        if ($schoolUser) {

            $schoolUser->update([
                'name' => $school->school_name,
                'username' => $request->username,
                'email' => $school->email,
            ]);

            if ($request->filled('password')) {
                $schoolUser->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        }

        return redirect()
            ->route('schools.show', [
                'school' => $school->id
            ])
            ->with('success', 'School Updated Successfully');
    }


    public function update(Request $request, School $school)
    {
        $user = Auth::user();
        if ($user && $user->role === 'school' && $user->school_id && $user->school_id !== $school->id) {
            abort(403);
        }
       $validator = Validator::make($request->all(), [
            'school_name' => 'required',
            'school_code' => 'required|unique:schools,school_code,' . $school->id,
            'email' => 'nullable|email',
            'school_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

       if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        

        $schoolLogoFile = $request->file('school_logo');
        
        $data = $request->only([
            'school_name',
            'school_code',
            'email',
            'phone',
            'address',
            'city',
            'state',
            'pincode',
            'status',
        ]);

        $data['status'] = (bool) $request->input('status', $school->status);
    

        if ($schoolLogoFile instanceof \Illuminate\Http\UploadedFile && $schoolLogoFile->isValid()) {
            if ($school->logo && Storage::disk('public')->exists($school->logo)) {
                Storage::disk('public')->delete($school->logo);
            }

            $data['logo'] = $schoolLogoFile->store('schools', 'public');
        } else {
            $data['logo'] = $school->logo;
        }

        $school->update($data);

        $schoolUser = User::where('role', 'school')
            ->where('school_id', $school->id)
            ->first();

        if ($schoolUser) {
            $schoolUser->update([
                'name' => $school->school_name,
                'email' => $school->email ?: $schoolUser->email,
            ]);
        }

        return redirect()
            ->route('schools.show', $school)
            ->with('success','School Updated Successfully');

    }
  
      
    public function destroy($id)
    {
        
        $school = School::findOrFail($id);
        $school->update([
            'IsDeleted' => 1,
        ]);
        $message =  'School Deleted successfully.';
        return redirect()
            ->route('schools.index')
            ->with('success', $message);
    }


    public function updateStatus($id)
    {
        $school = School::findOrFail($id);

        $school->update([
            'status' => $school->status == 1 ? 0 : 1,
        ]);

        return redirect()
            ->back()
            ->with('success', 'School status updated successfully.');
    }
      
    
    // public function saveSample(Request $request)
    // {
    //     $schoolId = Auth::user()->school_id ?? session('viewing_school') ?? session('vendor_viewing');
    //     $verticalSampleId = $request->input('vertical_sample_id');
    //     $horizontalSampleId = $request->input('horizontal_sample_id');
    //     if (!$schoolId) {
    //         return redirect()->back()
    //             ->with('error', 'Invalid school selection.');
    //     }
    //     if (!$verticalSampleId && !$horizontalSampleId) {
    //         return redirect()->back()
    //             ->with('error', 'Please select at least one sample.');
    //     }
    //     if ($verticalSampleId) {
    //         $filepath =UploadSample::where('id',$verticalSampleId)->first();
    //        UploadSample::create([
    //             'school_id' => $schoolId,
    //             'name' => $filepath->name ?? null,
    //             'orientation' => 'vertical',
    //             'file_path' => $filepath->file_path ?? null,
    //         ]);
    //     }
    //     if ($horizontalSampleId) {
    //          $filepath =UploadSample::where('id',$horizontalSampleId)->first();
    //         UploadSample::create([
    //             'school_id' => $schoolId,
    //             'name' => $filepath->name ?? null,
    //             'orientation' => 'horizontal',
    //             'file_path' => $filepath->file_path ?? null,
    //         ]);
    //     }
        

    //     return redirect()->back()
    //         ->with('success', 'Samples selected successfully.');
    // }
    public function saveSample(Request $request)
    {
        $schoolId = Auth::user()->school_id
            ?? session('viewing_school')
            ?? session('vendor_viewing');

        $verticalSampleId = $request->input('vertical_sample_id');
        $horizontalSampleId = $request->input('horizontal_sample_id');

        if (!$schoolId) {
            return redirect()->back()
                ->with('error', 'Invalid school selection.');
        }

        if (!$verticalSampleId && !$horizontalSampleId) {
            return redirect()->back()
                ->with('error', 'Please select at least one sample.');
        }

        // Add vendor_id only for vendor role
        $vendorId = Auth::user()->role === 'vendor' ? Auth::id() : null;
        if ($verticalSampleId) {
            $filepath = UploadSample::find($verticalSampleId);

            if ($filepath) {
                UploadSample::create([
                    'school_id'  => $schoolId,
                    'vendor_id'  => $vendorId,
                    'name'       => $filepath->name,
                    'orientation' => 'vertical',
                    'file_path'  => $filepath->file_path,
                ]);
            }
        }

        if ($horizontalSampleId) {
            $filepath = UploadSample::find($horizontalSampleId);

            if ($filepath) {
                UploadSample::create([
                    'school_id'  => $schoolId,
                    'vendor_id'  => $vendorId,
                    'name'       => $filepath->name,
                    'orientation' => 'horizontal',
                    'file_path'  => $filepath->file_path,
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Samples selected successfully.');
    }

    
    public function profileAdmin()
    {
        $user = Auth::user();
        if (! $user || ($user->role !== 'school' && $user->role !== 'superadmin')) {
            abort(403);
        }
        $admin=User::where('id',$user->id)->first();
        return view('admin.profile', compact('admin'));
    }
    public function updateProfileAdmin(Request $request)
    {
        $user = Auth::user();
        if (! $user || ($user->role !== 'school' && $user->role !== 'superadmin')) {
            abort(403);
        }
        $admin=User::where('id',$user->id)->first();
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|min:6|confirmed',
            'profilepicture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        if ($request->hasFile('profilepicture')) {
            if ($admin->profilepicture && Storage::disk('public')->exists($admin->profilepicture)) {
                Storage::disk('public')->delete($admin->profilepicture);
            }
            $data['profilepicture'] = $request->file('profilepicture')->store('profiles', 'public');
        }
        $admin->update([
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $admin->password,
            'profilepicture' => $data['profilepicture'] ?? $admin->profilepicture,
        ]);
        return redirect()->route('dashboard')
         ->with('success', 'Profile updated successfully.');
    }
}
