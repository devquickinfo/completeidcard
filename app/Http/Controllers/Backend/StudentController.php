<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\SelectedSample;
use App\Models\UploadSample;
use App\Models\Mainidcard;
use App\Helpers\ImageHelper;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['studentClass', 'section'])
                    ->latest()
                    ->paginate(10);
        $classes = StudentClass::orderBy('id', 'ASC')->get();
        $sections = Section::orderBy('id', 'ASC')->get();  

            $verticalSample='';
            $horizontalSample='';
            $verticalDesign='';
            $horizontalDesign='';
            $defaultOrientation='';

        return view('frontend.addstudent', compact('students', 'classes', 'sections', 'verticalSample',
            'horizontalSample',
            'verticalDesign',
            'horizontalDesign',
            'defaultOrientation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = StudentClass::all();
        $sections = Section::select('id', 'name', 'class_id')
            ->orderBy('id', 'ASC')
            ->get();
        $students = Student::with(['studentClass', 'section'])->latest()->paginate(10);
        $school_id = Auth::user()->school_id ?? session('viewing_school');
        $school = School::where('id', $school_id)->first();
        $mainidcard = Mainidcard::where('school_id', $school_id)->first();
        $selectedSample = SelectedSample::where('school_id', $school_id)->first();
        $idcardsample = null;
        if ($selectedSample) {
            $idcardsample = UploadSample::where('id', $selectedSample->sample_id)->first();
        }
        $defaultOrientation = SelectedSample::where('school_id', $school_id)
            ->latest('id')
            ->value('orientation') ?? 'vertical';

        $student = new Student([
            'admission_no' => old('admission_no'),
            'first_name'   => old('first_name'),
            'last_name'    => old('last_name'),
            'father_name'  => old('father_name'),
            'mother_name'  => old('mother_name'),
            'address'      => old('address'),
            'gender'       => old('gender'),
            'blood_group'  => old('blood_group'),
            'phone'        => old('phone'),
            'class_id'     => old('class_id'),
            'section_id'   => old('section_id'),
            'school_id'    => $school_id,
        ]);

        if (old('date_of_birth')) {
            $student->date_of_birth = \Illuminate\Support\Carbon::parse(old('date_of_birth'));
        }

        if (old('class_id')) {
            $selectedClass = StudentClass::find(old('class_id'));
            if ($selectedClass) {
                $student->setRelation('studentClass', $selectedClass);
            }
        }

        if (old('section_id')) {
            $selectedSection = Section::find(old('section_id'));
            if ($selectedSection) {
                $student->section = $selectedSection->name;
            }
        }

        return view('frontend.addstudent', compact(
            'students',
            'classes',
            'sections',
            'idcardsample',
            'mainidcard',
            'school',
            'student',
            'defaultOrientation'
        ));
    }

    
    public function store(Request $request)
    {
        $school_id = Auth::user()?->school_id ?? session('viewing_school');
        $validator = Validator::make($request->all(), [
            'first_name'    => 'required',
            'father_name'   => 'required',
            'date_of_birth' => 'required|date',
            'class_id'      => 'required',
            'section_id'    => 'required',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $className=StudentClass::where('id',$request->class_id)->value('name');
        $SectionName=Section::where('id',$request->section_id)->value('name');
        $student = Student::create([
            'school_id'     => $school_id,
            'admission_no'  => $request->admission_no,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'father_name'   => $request->father_name,
            'address'       => $request->address,
            'gender'        => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'blood_group'   => $request->blood_group,
            'phone'         => $request->phone,
            'class_id'      => $request->class_id,
            'section_id'    => $request->section_id,
            'mother_name'   => $request->mother_name,
            'class_name'    => $className,
            'section'       => $SectionName,
        ]);
        $photo = null;
        if ($request->filled('photo_data')) {
            try {
                $photo = ImageHelper::saveImageAsJpg(
                    $request->input('photo_data'),
                    "students/{$school_id}",
                    "student_{$student->id}"
                );

            } catch (\Exception $e) {
                $student->delete();
                return redirect()
                    ->back()
                    ->with('error', 'Unable to save camera image: ' . $e->getMessage())
                    ->withInput();
            }
        }
        elseif ($request->hasFile('photo')) {
            $imageName = time() . '_' . uniqid() . '.' .
                $request->file('photo')->getClientOriginalExtension();
            $imagePath = "students/$school_id/$imageName";
            $request->file('photo')->storeAs(
                "students/$school_id",
                $imageName,
                'public'
            );

            $photo = $imagePath;
        }
        if ($photo) {
            $student->update([
                'photo' => $photo,
            ]);
        }
        return redirect()->route('schools.classes.students', ['school' => $school_id,'class'  => $request->class_id,])
        ->with('success', 'Student added successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schooleditid=Student::where('id',$id)->value('school_id');
        $newid= Auth::user()->school_id ?? session('viewing_school');
        if($schooleditid !== $newid){
            //abort(404);
            return view('404');
        }
        $student = Student::with(['studentClass', 'section'])->findOrFail($id);
        $verticalDesign = Mainidcard::where('school_id', $newid)
            ->where('orientation', 'vertical')
            ->first();
            $verticalSelectedSample = SelectedSample::where('school_id', $newid)
            ->where('orientation', 'vertical')
            ->first();
             $verticalSample = null;

        if ($verticalSelectedSample) {
            $verticalSample = UploadSample::find(
                $verticalSelectedSample->sample_id
            );
        }
          $horizontalDesign = Mainidcard::where('school_id', $newid)
            ->where('orientation', 'horizontal')
            ->first();
             $horizontalSelectedSample = SelectedSample::where('school_id', $newid)
            ->where('orientation', 'horizontal')
            ->first();
        $horizontalSample = null;
        if ($horizontalSelectedSample) {
            $horizontalSample = UploadSample::find(
                $horizontalSelectedSample->sample_id
            );
        }
        $defaultOrientation = SelectedSample::where('school_id', $newid)
            ->latest('id')
            ->value('orientation') ?? 'vertical';

        return view('frontend.studentshow', compact(
            'student',
            'verticalDesign',
            'verticalSample',
            'horizontalDesign',
            'horizontalSample',
            'defaultOrientation'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
 
    public function edit(string $id)
    {
        $schooleditid=Student::where('id',$id)->value('school_id');
        $newid= Auth::user()->school_id ?? session('viewing_school');
        if($schooleditid !== $newid){
            //abort(404);
            return view('404');
        }
        $student = Student::with(['studentClass', 'section'])
            ->findOrFail($id);

        $classes = StudentClass::all();
        $sections = Section::selectRaw('MIN(id) as id, name')
            ->groupBy('name')
            ->orderBy('id', 'ASC')
            ->get();
        $students = Student::with(['studentClass', 'section'])
            ->latest()
            ->paginate(10);
        $schoolId = Auth::user()->school_id ?? session('viewing_school');
        $school = School::where('id', $schoolId)->first();
        $verticalSelectedSample = SelectedSample::where('school_id', $schoolId)
            ->where('orientation', 'vertical')
            ->first();
        $verticalSample = null;

        if ($verticalSelectedSample) {
            $verticalSample = UploadSample::find(
                $verticalSelectedSample->sample_id
            );
        }
        $horizontalSelectedSample = SelectedSample::where('school_id', $schoolId)
            ->where('orientation', 'horizontal')
            ->first();
        $horizontalSample = null;
        if ($horizontalSelectedSample) {
            $horizontalSample = UploadSample::find(
                $horizontalSelectedSample->sample_id
            );
        }
        $verticalDesign = Mainidcard::where('school_id', $schoolId)
            ->where('orientation', 'vertical')
            ->first();
        $horizontalDesign = Mainidcard::where('school_id', $schoolId)
            ->where('orientation', 'horizontal')
            ->first();
        $defaultOrientation = SelectedSample::where('school_id', $schoolId)
            ->latest('id')
            ->value('orientation') ?? 'vertical';

        return view('frontend.addstudent', compact(
            'student',
            'students',
            'classes',
            'sections',
            'verticalSample',
            'horizontalSample',
            'verticalDesign',
            'horizontalDesign',
            'school',
            'defaultOrientation'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);
        $validator = Validator::make($request->all(), [
            //'admission_no'  => 'required',
            'first_name'    => 'required',
            'father_name'   => 'required',
            'date_of_birth' => 'required|date',
            //'gender'        => 'required',
            'class_id'      => 'required',
            'section_id'    => 'required',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $school_id = $student->school_id ?? Auth::user()->school_id ?? session('viewing_school');
        if (!$school_id) {
            return redirect()->back()->with('error', 'School ID not found.')->withInput();
        }
        $photo = $student->photo;
        if ($request->filled('photo_data')) {
        if (
            $photo &&
            Storage::disk('public')->exists($photo)
        ) {
            Storage::disk('public')->delete($photo);
        }

            try {
                $photo = ImageHelper::saveImageAsJpg(
                    $request->input('photo_data'),
                    "students/{$school_id}",
                    "student_{$student->id}"
                );
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with('error', 'Invalid camera image.')
                    ->withInput();
            }
        }
        elseif ($request->hasFile('photo')) {
            if (
                $photo &&
                Storage::disk('public')->exists($photo)
            ) {
                Storage::disk('public')->delete($photo);
            }
            $uploadedFile = $request->file('photo');
            $imageName = time() . '_' . uniqid() . '.' .
                $uploadedFile->getClientOriginalExtension();
            $imagePath = "students/$school_id/$imageName";
            $uploadedFile->storeAs(
                "students/$school_id",
                $imageName,
                'public'
            );
            $photo = $imagePath;
        }
        $className=StudentClass::where('id',$request->class_id)->value('name');
        $SectionName=Section::where('id',$request->section_id)->value('name');
        $student->update([
            'admission_no'  => $request->admission_no,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'father_name'   => $request->father_name,
            'address'       => $request->address,
            'gender'        => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'blood_group'   => $request->blood_group,
            'phone'         => $request->phone,
            'class_id'      => $request->class_id,
            'section_id'    => $request->section_id,
            'photo'         => $photo,
            'mother_name'   => $request->mother_name,
            'class_name'    => $className,
            'section'       => $SectionName,
        ]);
        return redirect()->route('schools.classes.students', [
                   'school' => $student->school_id
                    ?? Auth::user()->school_id
                    ?? session('viewing_school'),
                'class' => $student->class_id,
        ])->with('success', 'Student updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schooleditid=Student::where('id',$id)->value('school_id');
        $newid= Auth::user()->school_id ?? session('viewing_school');
        if($schooleditid !== $newid){
           // abort(404);
           return view('404');
        }

        $student = Student::findOrFail($id);
        //$student->delete();
        $student->IsDeleted = '1';
        $student->save();
        $referer = request()->header('referer');
        if ($referer && preg_match('#/students/\d+$#', parse_url($referer, PHP_URL_PATH))) {
            return redirect('/student/list')
                ->with('success', 'Student Deleted Successfully');
        }
        return redirect()->back()
            ->with('success', 'Student Deleted Successfully');
    }
    public function getSections($classId)
    {
        $sections = Section::orderBy('id', 'ASC')->get();
        return response()->json($sections);
    }

    public function schoolClasses()
    {
        $user = Auth::user();
        $classes = StudentClass::orderBy('id', 'ASC')->get();
        $sections = Section::orderBy('id', 'ASC')->get();
        return view('frontend.school_classes', compact('classes', 'sections'));
    }

    public function classSectionStudents($classId, $sectionId)
    {
        $class = StudentClass::findOrFail($classId);
        $section = Section::findOrFail($sectionId);
        $students = Student::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->orderBy('first_name')
            ->get();

        return view('frontend.class_section_students', compact('class', 'section', 'students'));
    }

    public function classStudents(Request $request, $schoolId, $classId)
    {
        $school = School::findOrFail($schoolId);
        $selectedClassId = $request->filled('class')
            ? $request->class
            : $classId;

            
        $class = StudentClass::findOrFail($selectedClassId);
        //dd( $class);
        $classes = StudentClass::all();
        $studentsQuery = Student::with([
                'studentClass',
                'section'
            ])
            ->where('school_id', $school->id)
            ->where('class_id', $class->id)
            ->where('IsDeleted', '0')
            ->orderBy('first_name');
        if ($request->filled('section')) {
            $studentsQuery->where(
                'section_id',
                $request->section
            );
        }
        if ($request->filled('photo_filter')) {
            if ($request->photo_filter === 'with_photo') {
                $studentsQuery->where(function ($query) {
                    $query->whereNotNull('capturephoto')
                        ->where('capturephoto', '!=', '');
                });
            } elseif ($request->photo_filter === 'without_photo') {
                $studentsQuery->where(function ($query) {
                    $query->whereNull('capturephoto')
                        ->orWhere('capturephoto', '');
                });
            }
        }
        if ($request->filled('student_photo')) {
            if ($request->student_photo === 'with_photo') {
                $studentsQuery->where(function ($query) {
                    $query->whereNotNull('photo')
                        ->where('photo', '!=', '');
                });
            } elseif ($request->student_photo === 'without_photo') {

                $studentsQuery->where(function ($query) {
                    $query->whereNull('photo')
                        ->orWhere('photo', '');
                });
            }
        }
        $sections = Section::orderBy('id','asc')->get();
        if($request->per_page){
            $perPage = $request->per_page;
        }else{
            $perPage = 10;
        }
        $students = $studentsQuery
            ->paginate($perPage)
            ->appends($request->query());
        return view(
            'frontend.class_students',
            compact(
                'school',
                'class',
                'students',
                'sections',
                'classes'
            )
        );
    }
   
    // public function capturePhoto(Request $request, string $id)
    // {
    //     $student = Student::findOrFail($id);
    //     $request->validate([
    //         'photo_data' => 'required|string',
    //     ]);
    //     $school_id = $student->school_id
    //         ?? Auth::user()->school_id
    //         ?? session('viewing_school');
    //     if (!$school_id) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'School ID not found.'
    //         ], 422);
    //     }
    //     if (
    //         $student->photo &&
    //         Storage::disk('public')->exists($student->photo)
    //     ) {
    //         Storage::disk('public')->delete($student->photo);
    //     }
    //     $image = $request->input('photo_data');
    //     $image = preg_replace(
    //         '/^data:image\/\w+;base64,/',
    //         '',
    //         $image
    //     );
    //     $image = str_replace(' ', '+', $image);
    //     $imageData = base64_decode($image, true);
    //     if ($imageData === false) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid camera image.'
    //         ], 422);
    //     }
    //     $imageName = time() . '_' . $student->id . '_' . uniqid() . '.png';
    //     $imagePath = "students/$school_id/$imageName";
    //     Storage::disk('public')->put(
    //         $imagePath,
    //         $imageData
    //     );
    //     $student->update([
    //         'photo' => $imagePath,
    //     ]);
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Photo captured successfully.',
    //         'photo'   => asset('storage/' . $imagePath),
    //     ]);
    // }
    public function capturePhoto(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'photo_data' => 'required|string',
        ]);

        $school_id = $student->school_id
            ?? Auth::user()->school_id
            ?? session('viewing_school');

        if (!$school_id) {
            return response()->json([
                'success' => false,
                'message' => 'School ID not found.'
            ], 422);
        }

        try {

            // Save as JPG and <= 1 MB
            $imagePath =  ImageHelper::saveImageAsJpg(
                $request->input('photo_data'),
                "students/{$school_id}",
                "student_{$student->id}"
            );

            // Delete old photo
            if (
                $student->photo &&
                Storage::disk('public')->exists($student->photo)
            ) {
                Storage::disk('public')->delete($student->photo);
            }

            // Update student
            $student->update([
                'photo' => $imagePath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Photo captured successfully.',
                'photo' => asset('storage/' . $imagePath),
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
    public function cardStatus(string $id)
    {
        $schooleditid=Student::where('id',$id)->value('school_id');
        $newid= Auth::user()->school_id ?? session('viewing_school');
        if($schooleditid !== $newid){
            //abort(404);
            return view('404');
        }
        $student = Student::findOrFail($id);
        if ($student->idcardprinted === 'no') {
            $student->idcardprinted = 'yes';
        } else {
            $student->idcardprinted = 'no';
        }
        $student->save();
        return redirect()->back()
            ->with('success', 'Student ID card status updated successfully.');
    }

    public function bulkAction(Request $request)
    {
        $studentIds = $request->input('deleteAll', []);
        $schooleditids = Student::whereIn('id', $studentIds)
            ->pluck('school_id')
            ->unique();
        $newid = Auth::user()->school_id ?? session('viewing_school');
        if (
            $schooleditids->count() !== 1 ||
            (int) $schooleditids->first() !== (int) $newid
        ) {
           // abort(404);
           return view('404');
        }
        //dd($request->all());
        $action = $request->input('action');

        if (!isset($action)) {

            $studentIds = $request->input('deleteAll', []);

            if (empty($studentIds)) {
                return back()->with('error', 'Please select students to delete.');
            }

            Student::whereIn('id', $studentIds)
                ->update([
                    'IsDeleted' => 1,
                ]);

            return back()->with('success', 'Selected students deleted successfully.');
        }

       if ($action === 'print') {

        $studentIds = $request->input('printedAll', []);

        if (empty($studentIds)) {
            return back()->with('error', 'Please select students to print/unprint.');
        }

        $students = Student::whereIn('id', $studentIds)->get();

        foreach ($students as $student) {

            if ($student->idcardprinted === 'yes') {
                $student->idcardprinted = 'no';
            } else {
                $student->idcardprinted = 'yes';
            }

            $student->save();
        }

        return back()->with('success', 'Selected students print status updated.');
    }
        return back();
    }

    public function getPhoto($id)
    {
        $student = Student::findOrFail($id);

        $photo = null;

        if ($student->photo) {
            $photo = pathinfo($student->photo, PATHINFO_DIRNAME) . '/' .
                     pathinfo($student->photo, PATHINFO_FILENAME) . '.jpg';

            if (pathinfo($student->photo, PATHINFO_DIRNAME) === '.') {
                $photo = pathinfo($student->photo, PATHINFO_FILENAME) . '.jpg';
            }
        }

        if (!$photo && $student->capturephoto) {
            $photo = $student->capturephoto;
        }

        return response()->json([
            'photo' => $photo ? asset('storage/' . $photo) : null
        ]);
    }

    public function studentCardPreview(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $schoolId = Auth::user()->school_id ?? session('viewing_school');
        $orientation = $request->input('orientation')
            ?? SelectedSample::where('school_id', $schoolId)
                ->latest('id')
                ->value('orientation')
            ?? 'vertical';

        if (!in_array($orientation, ['vertical', 'horizontal'], true)) {
            $orientation = 'vertical';
        }

        return view('frontend.studentpartials.id-card-preview', compact('student', 'orientation'));
    }


}
