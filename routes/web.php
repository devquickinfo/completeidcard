<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\StudentListController;
use App\Http\Controllers\Backend\TeacherListController;
use App\Http\Controllers\Backend\TeacherController;
use App\Http\Controllers\Backend\IdCardController;
use App\Http\Controllers\Backend\LoginController;
use App\Http\Controllers\Backend\StudentController;
use App\Http\Controllers\Backend\SchoolController;
use App\Http\Controllers\Backend\StudentImportController;
use App\Http\Controllers\UploadSampleController;
use App\Http\Controllers\MainidcardController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ManageEventController;
use App\Http\Controllers\UserController;


Route::get('/check', function () {
    return view('check');
});
Route::get('/optimize', function () {

    Artisan::call('optimize');

    return 'Laravel optimized successfully.';
});

//event public route///
Route::get('/event/{unique_code}', [ManageEventController::class, 'publicEvent'])
    ->name('events.public');

Route::get('/event/{unique_code}/register', [ManageEventController::class, 'register'])
        ->name('events.register');

 Route::post('/event/store', [ManageEventController::class, 'storeRegistration'])
        ->name('events.register.store');


/// event public route end//////////////

Route::get('/', function () {
    return view('frontend.login');
})->name('login');
Route::middleware('guest')->group(function () {
Route::post('/', [LoginController::class, 'login'])->name('user.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('user.logout');
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
    Route::resource('students', StudentController::class);
    Route::get('/school/classes', [StudentController::class, 'schoolClasses'])->name('school.classes');
    Route::get('/school/classes/{classId}/sections/{sectionId}/students', [StudentController::class, 'classSectionStudents'])->name('school.class.section.students');
    Route::get('/schools/{school}/classes/{class}/students', [StudentController::class, 'classStudents'])->name('schools.classes.students');
    Route::get('/sections/{class}', [StudentController::class, 'getSections'])
    ->name('sections.byClass');

    Route::get('/school/profile', [SchoolController::class, 'profile'])->name('school.profile');
    Route::post('/school/profile', [SchoolController::class, 'updateProfile'])->name('school.profile.update');
    Route::get('/admin/profile', [SchoolController::class, 'profileAdmin'])->name('admin.profile');
    Route::post('/admin/profile', [SchoolController::class, 'updateProfileAdmin'])->name('admin.profile.update');
    Route::resource('schools',SchoolController::class);

    Route::get('/student/import',
        [StudentImportController::class,'index'])
        ->name('student.import');

    Route::post('/student/import',
        [StudentImportController::class,'store'])
        ->name('student.import.store');

    Route::get('/student/sample',
        [StudentImportController::class,'downloadSample'])
        ->name('student.sample');

    Route::get('/student/dynamic-sample',
        [StudentImportController::class,'downloadDynamicSample'])
        ->name('student.dynamic.sample');
    Route::get('/id-card/create', [IdCardController::class, 'index'])
    ->name('idcard.create');
    Route::get('/idcard/search-students', [IdCardController::class, 'searchStudents']) 
    ->name('idcard.search.students');
    Route::post('/idcard/generate', [IdCardController::class, 'generate'])
    ->name('idcard.generate');
    Route::get('/student/list', [StudentListController::class, 'index'])
    ->name('student.list');
    Route::get('/teacher/list', [TeacherController::class, 'index'])
    ->name('teacher.list');
    Route::resource('teachers', TeacherController::class);
    Route::resource('upload-samples', UploadSampleController::class);
    Route::delete('/upload-sample/all',[UploadSampleController::class, 'destroyAll'])->name('upload-sample.destroyAll');
    Route::post('/save-sample',[SchoolController::class, 'saveSample'])->name('selected-samples.store');
    Route::post('/student/{student}/capture-photo', [StudentController::class, 'capturePhoto'])
    ->name('student.capture-photo');
    Route::get('/student/{student}/cardstatus', [StudentController::class, 'cardStatus'])
    ->name('student.cardstatus');
    route::get('/student/deleted', [StudentListController::class, 'deletedStudents'])
    ->name('student.deleted');
    route::post('/student/{student}/restore', [StudentListController::class, 'restoreStudent'])
    ->name('student.restore');




    Route::get('idcard-editor', [IdCardController::class, 'editIDCard'])->name('idcard.editor');

    Route::post('/mainidcard/save',[MainidcardController::class, 'store'])->name('mainidcard.store');


    //user upload id card from editor
    Route::post('/id-card/upload-design', [IDCardController::class, 'uploadDesign'])
    ->name('id-card.upload-design');

    Route::delete('/student/bulk-action', [StudentController::class, 'bulkAction'])
    ->name('student.bulkAction');

    Route::get('/idcard/print-filtered', [IdCardController::class, 'printFiltered'])
    ->name('idcard.print-filtered');


    Route::get('/student/{id}/photo', [StudentController::class, 'getPhoto'])
    ->name('student.photo');
    Route::get('/student/card-preview/{id}', [StudentController::class, 'studentCardPreview'])
    ->name('student.card.preview');
    Route::get('/schools/{school}/status', [SchoolController::class, 'updateStatus'])
    ->name('schools.status');


    Route::get('idcard-grid', [IdCardController::class, 'IdCardGrid'])->name('idcard.grid');


    Route::get('/students/{id}/history',[StudentController::class, 'history'])->name('student.history');


    Route::get('/card/{schoolId}/{orientation}/edit', [MainidcardController::class, 'edit'])
    ->name('card.template.edit');
    Route::get('/cardsingle/{schoolId}/edit', [UploadSampleController::class, 'edit'])
    ->name('card.templatesingle.edit');
    Route::get('singlesample/{id}/delete', [UploadSampleController::class, 'singleDelete'])
    ->name('singlesample.delete');
    Route::post('upload-single', [UploadSampleController::class, 'singleStore'])
    ->name('upload-single.store');

    Route::get('singlesample/{id}/status', [UploadSampleController::class, 'singleDefault'])
    ->name('singlesample.status');


    Route::resource('manage-events', ManageEventController::class);

    Route::get('/events/{id}/people', [ManageEventController::class, 'eventPeople'])
    ->name('manage-event.people');

     Route::get('/events/home', [ManageEventController::class, 'home'])
    ->name('events.home');

    Route::get('/user-accounts', [UserController::class,'index'])->name('user.account');
    Route::get('/user-create', [UserController::class,'create'])->name('user.create');
    Route::post('/user-store', [UserController::class,'store'])->name('user.store');
    Route::get('/user-edit/{id}', [UserController::class,'edit'])->name('user.edit');
    Route::get('/vendors/{id}/schools', [UserController::class, 'vendorSchools'])
    ->name('vendor.schools');

    Route::get('/user-status/{id}', [UserController::class, 'status'])
    ->name('user.status');
    
   
});
