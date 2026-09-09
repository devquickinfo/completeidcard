<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\School;
use App\Models\StudentClass;
use App\Models\Section;
use App\Helpers\ImageHelper;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id ?? session('viewing_school');
        $query = Teacher::where('school_id', $schoolId)
        ->where('IsDeleted', 0);
        if ($request->filled('idcardprinted')) {
            $query->where('idcardprinted', $request->idcardprinted);
        }
        if ($request->filled('teacher_photo')) {
            if ($request->teacher_photo === 'with_photo') {
                $query->whereNotNull('photo')
                      ->where('photo', '!=', '');
            }

            if ($request->teacher_photo === 'without_photo') {
                $query->where(function ($q) {
                    $q->whereNull('photo')
                      ->orWhere('photo', '');
                });
            }
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (strlen($search) >= 3) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', '%' . $search . '%')
                      ->orWhere('phone', 'LIKE', '%' . $search . '%');
                });
            }
        }
        $teachers = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('frontend.teacherlist', compact('teachers'));
    }

    public function create()
    {
    
        return view('frontend.addteacher');
    }

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_code' => 'nullable|string|max:255',
            'first_name'    => 'required|string|max:255',
            'father_name'   => 'nullable|string|max:255',
            'gender'        => 'nullable|string|max:50',
            'dob'           => 'nullable|date',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

       
        $school_id = Auth::user()->school_id
            ?? session('viewing_school');

        if (!$school_id) {
            return redirect()
                ->back()
                ->with('error', 'School ID not found.')
                ->withInput();
        }
        $photo = null;
        $capturePhoto = null;
        $capturedByCamera = false;
        if ($request->filled('photo_data')) {
            try {
                $capturePhoto = ImageHelper::saveImageAsJpg(
                    $request->input('photo_data'),
                    "teachers/{$school_id}",
                    "teacher_" . time()
                );
                $capturedByCamera = true;
            } catch (\Exception $e) {

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Unable to save camera image: ' . $e->getMessage()
                    )
                    ->withInput();
            }
        }
        elseif ($request->hasFile('photo')) {
            try {
                $uploadedFile = $request->file('photo');
                $imageName = time() . '_' . uniqid() . '.' .
                    $uploadedFile->getClientOriginalExtension();
                $uploadedFile->storeAs(
                    "teachers/{$school_id}",
                    $imageName,
                    'public'
                );
                $photo = "teachers/{$school_id}/{$imageName}";
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Unable to upload photo: ' . $e->getMessage()
                    )
                    ->withInput();
            }
        }
        Teacher::create([
            'school_id'          => $school_id,
            'employee_code'      => $request->employee_code,
            'first_name'         => $request->first_name,
            'father_husband'     => $request->father_name,
            'address'            => $request->address,
            'gender'             => $request->gender,
            'dob'                => $request->dob,
            'phone'              => $request->phone,
            'photo'              => $photo,
          
        ]);
        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
    }



    public function edit(string $id)
    {
        $schooleditid=Teacher::where('id',$id)->value('school_id');
        $newid= Auth::user()->school_id ?? session('viewing_school');
        if($schooleditid !== $newid){
            return view('404');
        }
        $teacher = Teacher::findOrFail($id);
        return view('frontend.addteacher', compact('teacher'));
    }

    public function show($id)
    {
        $schooleditid=Teacher::where('id',$id)->value('school_id');
        $newid= Auth::user()->school_id ?? session('viewing_school');
        if($schooleditid !== $newid){
            return view('404');
        }
        $teacher = Teacher::findOrFail($id);
        return view('frontend.teachershow', compact('teacher'));

    }
    public function update(Request $request, string $id)
    {

        //echo '<pre>'; print_r($request->all());die;
        $teacher = Teacher::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'first_name'    => 'required',
            'dob' => 'nullable|date',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $school_id = $teacher->school_id
            ?? Auth::user()->school_id
            ?? session('viewing_school');
        if (!$school_id) {
            return redirect()
                ->back()
                ->with('error', 'School ID not found.')
                ->withInput();
        }
        $photo = $teacher->photo;
        if ($request->filled('photo_data')) {
            try {
                if (
                    $teacher->photo &&
                    Storage::disk('public')->exists($teacher->photo)
                ) {
                    Storage::disk('public')->delete($teacher->photo);
                }
                $photo = ImageHelper::saveImageAsJpg(
                    $request->input('photo_data'),
                    "teachers/{$school_id}",
                    "teacher_{$teacher->id}"
                );

            } catch (\Exception $e) {

                return redirect()
                    ->back()
                    ->with('error', 'Unable to save camera image: ' . $e->getMessage())
                    ->withInput();
            }
        }
        elseif ($request->hasFile('photo')) {
            try {
                if (
                    $teacher->photo &&
                    Storage::disk('public')->exists($teacher->photo)
                ) {
                    Storage::disk('public')->delete($teacher->photo);
                }

                $uploadedFile = $request->file('photo');

                $imageName = time() . '_' . uniqid() . '.' .
                    $uploadedFile->getClientOriginalExtension();

                $imagePath = "teachers/{$school_id}/{$imageName}";

                $uploadedFile->storeAs(
                    "teachers/{$school_id}",
                    $imageName,
                    'public'
                );

                $photo = $imagePath;
            } catch (\Exception $e) {

                return redirect()
                    ->back()
                    ->with('error', 'Unable to upload photo: ' . $e->getMessage())
                    ->withInput();
            }
        }
        $teacher->update([
            'employee_code'    => $request->employee_code,
            'first_name'    => $request->first_name,
            'father_husband'=> $request->father_name,
            'address'       => $request->address,
            'gender'        => $request->gender,
            'dob'           => $request->dob,
            'phone'         => $request->phone,
            'photo'         => $photo,
        ]);
        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(string $id)
    {
        $updated = Teacher::where('id', $id)
            ->update([
                'IsDeleted' => 1,
            ]);

        if ($updated) {
            return redirect()
                ->route('teachers.index')
                ->with('success', 'Teacher deleted successfully.');
        }

        return redirect()
            ->route('teachers.index')
            ->with('error', 'Teacher could not be deleted.');
        }
}
