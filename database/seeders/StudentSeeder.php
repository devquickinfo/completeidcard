<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get schools
        $schools = School::query()->get();

        // Create demo school if no school exists
        if ($schools->isEmpty()) {
            $schools = collect([
                School::create([
                    'school_name' => 'Demo School',
                    'school_code' => 'DEMO001',
                    'email' => 'demo@example.com',
                    'phone' => '9876543210',
                    'address' => 'Main Street',
                    'city' => 'Delhi',
                    'state' => 'Delhi',
                    'pincode' => '110001',
                    'status' => true,
                ])
            ]);
        }

        $firstNames = [
            'Aarav',
            'Vihaan',
            'Ananya',
            'Ishaan',
            'Diya',
            'Rohan',
            'Kavya',
            'Arjun',
            'Neha',
            'Dev'
        ];

        $lastNames = [
            'Sharma',
            'Verma',
            'Patel',
            'Singh',
            'Mehta',
            'Kumar',
            'Gupta',
            'Reddy',
            'Chopra',
            'Joshi'
        ];

        foreach ($schools as $school) {

            /*
            |--------------------------------------------------------------------------
            | Get classes for this school (classes are global)
            |--------------------------------------------------------------------------
            */

            $classes = StudentClass::all();

            /*
            |--------------------------------------------------------------------------
            | Get sections (sections are global for all schools)
            |--------------------------------------------------------------------------
            */

            $sections = Section::all();

            /*
            |--------------------------------------------------------------------------
            | Create A, B, C, D if sections don't exist
            |--------------------------------------------------------------------------
            */

            if ($sections->isEmpty()) {

                foreach (['A', 'B', 'C', 'D'] as $sectionName) {

                    Section::create([
                        'name' => $sectionName,
                    ]);
                }

                // Get newly created sections
                $sections = Section::all();
            }

            /*
            |--------------------------------------------------------------------------
            | Create students
            |--------------------------------------------------------------------------
            */

            foreach ($classes as $class) {

                foreach ($sections as $section) {

                    // 5 students per class/section
                    for ($i = 1; $i <= 5; $i++) {

                        $firstName = $firstNames[array_rand($firstNames)];
                        $lastName = $lastNames[array_rand($lastNames)];

                        $admissionNo =
                            strtoupper(substr($school->school_code, 0, 3))
                            . '-'
                            . $class->id
                            . '-'
                            . $section->id
                            . '-'
                            . $i;

                        Student::updateOrCreate(
                            [
                                'admission_no' => $admissionNo,
                            ],
                            [
                                'school_id' => $school->id,

                                'first_name' => $firstName,

                                'last_name' => $lastName,

                                'father_name' => $firstName . ' Sr.',

                                'address' => 'Address ' . $i,

                                'gender' => $i % 2 === 0
                                    ? 'Female'
                                    : 'Male',

                                'date_of_birth' => now()
                                    ->subYears(6 + ($i % 3))
                                    ->subMonths($i)
                                    ->toDateString(),

                                'blood_group' => [
                                    'A+',
                                    'B+',
                                    'O+',
                                    'AB+',
                                    'A-'
                                ][($i - 1) % 5],

                                'phone' => '9' . str_pad(
                                    (string) (700000000 + $i),
                                    9,
                                    '0',
                                    STR_PAD_LEFT
                                ),

                                'class_id' => $class->id,

                                'section_id' => $section->id,
                            ]
                        );
                    }
                }
            }
        }
    }
}