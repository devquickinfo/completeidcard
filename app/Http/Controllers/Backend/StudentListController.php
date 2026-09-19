<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Section;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentListController extends Controller
{
    public function index(Request $request)
    {
        // Allow superadmin to view students for a specific school when
        // 'viewing_school' is set in session (set when superadmin views a school)
        $schoolId = Auth::user()->school_id ?? session('viewing_school') ?? session('vendor_viewing');
        $classes = StudentClass::orderBy('id', 'ASC')->get();
        $sections = Section::orderBy('id', 'ASC')->get();
        $studentsQuery = Student::with([
            'studentClass',
            'section'
        ])
        ->where('IsDeleted', '0')
        ->where('school_id', $schoolId);
        
        if ($request->filled('class')) {
            $studentsQuery->where(
                'class_id',
                $request->class
            );
        }
        if ($request->filled('section')) {
            $studentsQuery->where(
                'section_id',
                $request->section
            );
        }
        if ($request->filled('idcardprinted')) {
            $studentsQuery->where('idcardprinted', $request->idcardprinted);
        }
        if ($request->student_photo === 'with_photo') {
            $studentsQuery->where(function ($query) {
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
        if ($request->student_photo === 'without_photo') {
            $studentsQuery->where(function ($query) {
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
        if($request->per_page){
            $perPage = $request->per_page;
        }else{
            $perPage = 20;
        }
        $students = $studentsQuery
            ->orderBy('first_name')
            ->paginate($perPage)
            ->appends($request->query());

        return view(
            'frontend.studentlist',
            compact(
                'students',
                'classes',
                'sections'
            )
        );
    }
    public function deletedStudents(Request $request)
    {
        $students = Student::with([
            'studentClass',
            'section'
        ])
        ->where('IsDeleted', '1')
        ->when($request->school, function ($query) use ($request) {
            $query->where('school_id', $request->school);
        })
        ->when($request->class, function ($query) use ($request) {
            $query->where('class_id', $request->class);
        })
        ->when($request->section, function ($query) use ($request) {
            $query->where('section_id', $request->section);
        })
        ->orderBy('first_name')
        ->paginate(20)
        ->withQueryString();
        $classes = StudentClass::orderBy('id')->get();
        $sections = Section::orderBy('id')->get();
        $school = School::orderBy('id')->get();
        return view(
            'admin.deletedstudentlist',
            compact(
                'students',
                'classes',
                'sections',
                'school'
            )
        );
    }
    public function restoreStudent(Student $student)
    {
        $student->IsDeleted = '0';
        $student->save();
        return redirect()->route('student.deleted')
            ->with('success', 'Student restored successfully.');
    }
}

