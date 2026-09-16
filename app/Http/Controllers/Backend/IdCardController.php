<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Section;
use App\Models\School;
use App\Models\SelectedSample;
use App\Models\UploadSample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Mainidcard;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\PaperSize;
use App\Models\ApplicableUser;
use App\Models\Teacher;

class IdCardController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id ?? session('viewing_school');
        $classes = StudentClass::
            orderBy('id', 'ASC')
            ->get();
        $sections = Section::selectRaw('MIN(id) as id, name')
            ->groupBy('name')
            ->orderBy('id', 'ASC')
            ->get();
        if($request->has('per_page') && is_numeric($request->input('per_page'))){
            $perPage = (int)$request->input('per_page');
        } else {
            $perPage = 20; 
        }
        $students = $this->buildStudentQuery($request, $schoolId)
            ->paginate($perPage);
        $papersizes=PaperSize::all();
        $applicableusers=ApplicableUser::all();

        return view('schools.createidcard', compact(
            'classes',
            'sections',
            'students',
            'perPage',
            'papersizes',
            'applicableusers'
        ));
    }

    public function searchStudents(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $students = $this->buildStudentQuery($request, $schoolId)
            ->get();

        return view('schools.partials.student_rows', compact('students'))->render();
    }

    
    public function generate(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $studentIds = $request->input('student_ids', []);

        if (empty($studentIds)) {
            return redirect()->back()->with('error', 'Please select at least one student.');
        }

        $students = Student::with(['studentClass', 'section'])
            ->where('school_id', $schoolId)
            ->whereIn('id', $studentIds)
            ->orderBy('first_name')
            ->get();

        $school = School::find($schoolId);

        // Whitelist so an unexpected value never produces a broken/blank card
        $allowedTemplates = ['sky_blue', 'blue', 'green', 'red', 'custom'];
        $template = $request->input('background_template', 'sky_blue');
        $template = in_array($template, $allowedTemplates) ? $template : 'sky_blue';

        $orientation = $request->input('orientation', 'horizontal');
        $orientation = in_array($orientation, ['horizontal', 'vertical']) ? $orientation : 'horizontal';

        // Cards per A4 page — tuned to each card shape so the grid fills exactly
        $cardsPerPage = $orientation === 'vertical' ? 12 : 10;

        $students->each(function ($student) {
            $student->idcardprinted = 'yes';
            $student->save();
        });

        return response()
        ->view('schools.generated_id_cards', [
            'students' => $students,
            'school' => $school,
            'template' => $template,
            'orientation' => $orientation,
            'cardsPerPage' => $cardsPerPage,
        ])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }
    public function IdCardGrid(Request $request){
       
      $schoolId = Auth::user()->school_id ?? session('viewing_school');
      $classes=StudentClass::all();
      $selectedSamples = SelectedSample::where('school_id', $schoolId)
      ->with('uploadSample')
      ->get();
      return view('IDCards.grideditor',compact('classes','selectedSamples','schoolId'));

    }
    public function editIDCard(Request $request)
    {
        //echo '<pre>';print_r($request->all()); die;
        $schoolId = Auth::user()->school_id ?? session('viewing_school');
        $orientation = $request->query('orientation', 'vertical');
        if (!in_array($orientation, ['vertical', 'horizontal'])) {
            $orientation = 'vertical';
        }
        $selectedSampleId = SelectedSample::where('school_id', $schoolId)
            ->where('orientation', $orientation)
            ->value('sample_id');
        $selectedSample = null;
        if ($selectedSampleId) {
            $selectedSample = UploadSample::find($selectedSampleId);
        }
        $verticalSample = UploadSample::find(
            SelectedSample::where('school_id', $schoolId)
                ->where('orientation', 'vertical')
                ->value('sample_id')
        );
        $horizontalSample = UploadSample::find(
            SelectedSample::where('school_id', $schoolId)
                ->where('orientation', 'horizontal')
                ->value('sample_id')
        );
        $school = School::find($schoolId);
        $idCardData = Mainidcard::where('school_id', $schoolId)
            ->where('orientation', $orientation)
            ->first();
        $designcard = $idCardData ?: null;
        return response()
            ->view(
                'IDCards.inlteeditor',
                compact(
                    'schoolId',
                    'selectedSample',
                    'verticalSample',
                    'horizontalSample',
                    'designcard',
                    'school',
                    'orientation'
                )
            )
            ->header(
                'Cache-Control',
                'no-store, no-cache, must-revalidate, max-age=0'
            )
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
   

    protected function buildStudentQuery(Request $request, $schoolId)
    {
        $search = trim($request->input('search', $request->input('student_search', '')));
        return Student::with(['studentClass', 'section'])
            ->where('school_id', $schoolId)
            ->when($request->filled('class_id'), function ($query) use ($request) {
                $query->where('class_id', $request->class_id);
            })
            ->when($request->filled('section_id'), function ($query) use ($request) {
                $query->where('section_id', $request->section_id);
            })
            ->when($request->filled('photo'), function ($query) use ($request) {
                if ($request->photo === 'available') {
                    $query->where(function ($query) {
                        $query->where(function ($q) {
                            $q->whereNotNull('photo')
                                ->where('photo', '!=', '');
                        })
                            ->orWhere(function ($q) {
                                $q->whereNotNull('capturephoto')
                                    ->where('capturephoto', '!=', '');
                            });
                    });
                }

                if ($request->photo === 'not_available') {
                    $query->where(function ($query) {
                        $query->where(function ($q) {
                            $q->whereNull('photo')
                                ->orWhere('photo', '');
                        })
                            ->where(function ($q) {
                                $q->whereNull('capturephoto')
                                    ->orWhere('capturephoto', '');
                            });
                    });
                }
            })
            ->when($request->filled('printed'), function ($query) use ($request) {
                if ($request->printed === 'yes') {
                    $query->where('idcardprinted', 'yes');
                } elseif ($request->printed === 'no') {
                    $query->where('idcardprinted', 'no');
                }
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('admission_no', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('first_name');
    }
    

    public function uploadDesign(Request $request)
    {
        $schoolId = Auth::user()?->school_id
            ?? session('viewing_school');

        if (!$schoolId) {

            return response()->json([
                'success' => false,
                'message' => 'School not found.'
            ], 400);
        }
        $request->validate([
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:20480'
            ],

            'orientation' => [
                'required',
                'in:horizontal,vertical'
            ],
        ]);
        $uploadedFile = $request->file('image');
        $orientation = $request->input('orientation');
        $originalName = $uploadedFile->getClientOriginalName();
        if ($orientation === 'horizontal') {

            $width = 317;
            $height = 204;

        } else {

            $width = 204;
            $height = 317;
        }
        $manager = new ImageManager(
            new Driver()
        );

        try {

            $image = $manager->read(
                $uploadedFile->getPathname()
            );

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Unable to read uploaded image.'
            ], 422);
        }
        $image->resize(
            width: $width,
            height: $height
        );
        $maxSize = 1024 * 1024; // 1 MB
        $quality = 90;
        do {
            $encoded = $image->toJpeg($quality);
            $size = strlen($encoded);
            $quality -= 5;
        } while (
            $size > $maxSize &&
            $quality >= 20
        );
        if ($size > $maxSize) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to reduce image below 1 MB.'
            ], 422);
        }
        $filename = uniqid('sample_') . '.jpg';
        $path = 'samples/' . $schoolId . '/' . $filename;
        try {

            Storage::disk('public')->put(
                $path,
                (string) $encoded
            );
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Unable to save image.'
            ], 500);
        }
        $sample = UploadSample::create([
            'school_id' => $schoolId,
            'name' => $originalName,
            'file_path' => $path,
            'orientation' => $orientation,
        ]);
        $selectedSample = SelectedSample::updateOrCreate(
            [
                'school_id' => $schoolId,
                'orientation' => $orientation,
            ],
            [
                'sample_id' => $sample->id,
            ]
        );
        $imageUrl = Storage::disk('public')->url(
            $path
        );
        return response()->json([
            'success' => true,
            'message' => 'Card design uploaded successfully.',
            'sample_id' => $sample->id,
            'name' => $sample->name,
            'path' => $sample->file_path,
            'url' => $imageUrl,
            'orientation' => $orientation,
            'width' => $width,
            'height' => $height,
            'size' => round($size / 1024, 2) . ' KB',
            'selected_sample_id' => $selectedSample->id,
        ]);
    }

    // public function printFiltered(Request $request)
    // {
        
    //     echo '<pre>'; print_r($request->all()); die;

    //     $schoolId = Auth::user()->school_id ?? session('viewing_school');
    //     $orientation = $request->input('orientation', 'vertical');
    //     $orientation = in_array($orientation, ['horizontal', 'vertical']) ? $orientation : 'vertical';
    //     $students = $this->buildStudentQuery($request, $schoolId)->get();
    //     $school = School::find($schoolId);
    //     $design = Mainidcard::where('school_id', $schoolId)
    //         ->where('orientation', $orientation)->where('is_default',1)
    //         ->first();
        
    //     $sample = null;
    //     $selectedSampleId = SelectedSample::where('school_id', $schoolId)
    //         ->where('orientation', $orientation)
    //         ->value('sample_id');
        
    //     if ($selectedSampleId) {
    //         $sample = UploadSample::find($selectedSampleId);
    //     }
        
    //     $classFilter = '';
    //     if ($request->filled('class_id')) {
    //         $class = StudentClass::find($request->class_id);
    //         $classFilter = $class ? $class->name : '';
    //     }
        
    //     $sectionFilter = '';
    //     if ($request->filled('section_id')) {
    //         $section = Section::find($request->section_id);
    //         $sectionFilter = $section ? $section->name : '';
    //     }
        
    //     $photoFilter = '';
    //     if ($request->filled('photo')) {
    //         $photoFilter = $request->photo === 'available' ? 'Photo Available' : 'No Photo';
    //     }
        
    //     // Prepare layout
    //     $layout = $design?->layout ?? [];
        
    //     return response()
    //         ->view('schools.print_filtered_idcards', [
    //             'students' => $students,
    //             'school' => $school,
    //             'design' => $design,
    //             'sample' => $sample,
    //             'layout' => $layout,
    //             'orientation' => $orientation,
    //             'classFilter' => $classFilter,
    //             'sectionFilter' => $sectionFilter,
    //             'photoFilter' => $photoFilter,
    //         ])
    //         ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
    //         ->header('Pragma', 'no-cache')
    //         ->header('Expires', '0');
    // }

    // public function printFiltered(Request $request){
    //     $schoolId = Auth::user()->school_id ?? session('viewing_school');
    //     $orientation = $request->input('orientation', 'vertical');
    //     $orientation = in_array($orientation, ['horizontal', 'vertical']) ? $orientation : 'vertical';
    //     $students = $this->buildStudentQuery($request, $schoolId)->get();
    //     $school = School::find($schoolId);
    //     $teachers='';
    //     if($request->applicableuser==2){

    //         $design = Mainidcard::where('school_id', $schoolId)
    //         ->where('applicable_id',2)
    //         ->first();
    //          $teachers=Teacher::where('school_id', $schoolId);
            

    //     }else{
    //         $design = Mainidcard::where('school_id', $schoolId)
    //         ->where('is_default',1)
    //         ->first();
    //     }
        
    //     $classFilter = '';
    //     if ($request->filled('class_id')) {
    //         $class = StudentClass::find($request->class_id);
    //         $classFilter = $class ? $class->name : '';
    //     }
        
    //     $sectionFilter = '';
    //     if ($request->filled('section_id')) {
    //         $section = Section::find($request->section_id);
    //         $sectionFilter = $section ? $section->name : '';
    //     }
        
    //     $photoFilter = '';
    //     if ($request->filled('photo')) {
    //         $photoFilter = $request->photo === 'available' ? 'Photo Available' : 'No Photo';
    //     }
    //     $orientation= $design->orientation;
    //     $layout = $design?->layout ?? [];

    //     return response()
    //         ->view('schools.print_filtered_idcards', [
    //             'students' => $students,
    //             'school' => $school,
    //             'design' => $design,
    //             'teachers' => $teachers,
    //             'layout' => $layout,
    //             'orientation' => $orientation,
    //             'classFilter' => $classFilter,
    //             'sectionFilter' => $sectionFilter,
    //             'photoFilter' => $photoFilter,
    //         ]);
    // }


    public function printFiltered(Request $request){
        $schoolId = Auth::user()->school_id ?? session('viewing_school');
        $school = School::find($schoolId);
        $isTeacher = $request->applicableuser == 2;

        if ($isTeacher) {
            $design = Mainidcard::where('school_id', $schoolId)
                ->where('applicable_id', 2)
                ->first();

            $records = $this->buildTeacherQuery($request, $schoolId)->get();
        } else {
            // $design = Mainidcard::where('school_id', $schoolId)
            //     ->where('is_default', 1)
            //     ->first();
             $design = Mainidcard::where('school_id', $schoolId)
                ->when($request->class_id, function ($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                }, function ($query) {
                    $query->where('is_default', 1);
                })
                ->first();

            $records = $this->buildStudentQuery($request, $schoolId)->get();
        }

        // Reuse the same filter-label vars, just mapped to different columns for teachers
        $classFilter = '';
        if ($isTeacher) {
            $classFilter = $request->filled('department') ? $request->department : '';
        } elseif ($request->filled('class_id')) {
            $class = StudentClass::find($request->class_id);
            $classFilter = $class ? $class->name : '';
        }

        $sectionFilter = '';
        if ($isTeacher) {
            $sectionFilter = $request->filled('designation') ? $request->designation : '';
        } elseif ($request->filled('section_id')) {
            $section = Section::find($request->section_id);
            $sectionFilter = $section ? $section->name : '';
        }

        $photoFilter = '';
        if ($request->filled('photo')) {
            $photoFilter = $request->photo === 'available' ? 'Photo Available' : 'No Photo';
        }

        $orientation = $design->orientation;
        $layout = $design?->layout ?? [];

        return response()->view('schools.print_filtered_idcards', [
            'students'     => $records,      // blade var name kept as-is
            'school'       => $school,
            'design'       => $design,
            'isTeacher'    => $isTeacher,    // new — drives all the branching in the blade
            'layout'       => $layout,
            'orientation'  => $orientation,
            'classFilter'  => $classFilter,
            'sectionFilter'=> $sectionFilter,
            'photoFilter'  => $photoFilter,
        ]);
    }

    protected function buildTeacherQuery(Request $request, $schoolId)
    {
        $query = Teacher::where('school_id', $schoolId)
            ->where('IsDeleted', 0);

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        if ($request->filled('photo')) {
            if ($request->photo === 'available') {
                $query->whereNotNull('photo')->where('photo', '!=', '');
            } else {
                $query->where(function ($q) {
                    $q->whereNull('photo')->orWhere('photo', '');
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}

