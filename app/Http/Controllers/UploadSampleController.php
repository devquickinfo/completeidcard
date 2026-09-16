<?php

namespace App\Http\Controllers;

use App\Models\UploadSample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\SelectedSample;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentClass;
use App\Models\ApplicableUser;
use App\Models\House;
use App\Models\Mainidcard;


class UploadSampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedSamples = collect();
        $alls = collect();
        $defaultSamples = collect();
        $ownSamples = collect();

        if (session('role') === 'school' || session('viewing_school')) {

            $schoolId = Auth::user()->school_id ?? session('viewing_school');

            // Get selected vertical + horizontal samples
            $selectedSamples = SelectedSample::where('school_id', $schoolId)
                ->pluck('sample_id', 'orientation');

            // Admin/default samples
            $defaultSamples = UploadSample::whereNull('school_id')
                ->get();

            // School uploaded samples
            $ownSamples = UploadSample::where('school_id', $schoolId)
                ->get();

        } else {

            // Admin sees all
            $alls = UploadSample::all();
        }

        return view(
            'schools.uploadsample',
            compact(
                'alls',
                'defaultSamples',
                'ownSamples',
                'selectedSamples'
            )
        );
    }
   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes=StudentClass::all();
        $applicables=ApplicableUser::all();
        $houses=House::all();
        return view('schools.createuploadsample',compact('classes','applicables','houses'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'upload_samples' => 'required|image|max:40960',
            'image_name'     => 'required|string|max:255',
            'caption'        => 'nullable|string|max:255',
            'orientation'    => 'required|in:horizontal,vertical',
        ]);

        if (session('role') === 'school' || session('viewing_school')) {
            $schoolId = Auth::user()->school_id ?? session('viewing_school');
            $path = $request->file('upload_samples')
                ->store('samples/' . $schoolId, 'public');
        } else {
            $schoolId = null;
            $path = $request->file('upload_samples')
                ->store('samples', 'public');
        }
        UploadSample::create([
            'school_id'   => $schoolId,
            'name'        => $request->input('image_name'),
            'file_path'   => $path,
            'caption'     => $request->input('caption'),
            'orientation' => $request->input('orientation'),
        ]);
        return response()->json([
            'success'  => true,
            'message'  => 'Sample uploaded successfully.',
            'redirect' => route('upload-samples.index'),
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(UploadSample $uploadSample)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $uploadSample)
    {
         $classes = StudentClass::all();
         $applicables = ApplicableUser::all();
         $houses = House::all();
         $singleSample=UploadSample::find($uploadSample);
         return view('schools.createuploadsample',compact('singleSample', 'classes', 'applicables', 'houses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UploadSample $uploadSample)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UploadSample $uploadSample)
    {
        $schoolId = session('role') === 'school'
            ? Auth::user()->school_id
            : session('viewing_school');
        if (is_null($uploadSample->school_id)) {
            if (session('role') === 'school') {
                return redirect()
                    ->route('upload-samples.index')
                    ->with('error', 'You cannot delete default samples.');
            }

        } else {
            if ($uploadSample->school_id != $schoolId) {

                return redirect()
                    ->route('upload-samples.index')
                    ->with('error', 'You are not authorized to delete this sample.');
            }
        }
        if ($uploadSample->file_path) {
            Storage::disk('public')->delete($uploadSample->file_path);
        }
        $uploadSample->delete();
        return redirect()
            ->route('upload-samples.index')
            ->with('success', 'Sample deleted successfully.');
    }
    public function destroyAll()
    {
        if (session('role') === 'school' || session('viewing_school')) {
            $schoolId = session('role') === 'school'
                ? Auth::user()->school_id
                : session('viewing_school');
            $samples = UploadSample::where('school_id', $schoolId)->get();
        } else {
            $samples = UploadSample::whereNull('school_id')->get();
        }
        foreach ($samples as $sample) {
            if ($sample->file_path) {
                Storage::disk('public')->delete($sample->file_path);
            }
            $sample->delete();
        }
        return redirect()
            ->route('upload-samples.index')
            ->with('success', 'All samples deleted successfully.');
    }
    public function singleDelete($id)
    {
        $sample = UploadSample::findOrFail($id);

        if ($sample->file_path && Storage::disk('public')->exists($sample->file_path)) {
            Storage::disk('public')->delete($sample->file_path);
        }

        $sample->delete();

        return redirect()->back()->with('success', 'Sample deleted successfully.');
    }
    public function singleStore(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'applicable_id' => 'required',
            'orientation'  => 'required|in:horizontal,vertical',
            'sampleupload' => $request->filled('id')
            ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:40960'
            : 'required|image|mimes:jpg,jpeg,png,webp|max:40960',
        ]);

        // Get school ID
        if (session('role') === 'school') {
            $schoolId = Auth::user()->school_id;
        } elseif (session('viewing_school')) {
            $schoolId = session('viewing_school');
        } else {
            $schoolId = null;
        }

        // Insert or update
        $id = $request->input('id');

        if ($id) {
            $singleSample = UploadSample::findOrFail($id);
        } else {
            $singleSample = new UploadSample();
        }

        // Upload image
        if ($request->hasFile('sampleupload')) {

            // Delete old image when updating
            if (
                $singleSample->exists &&
                $singleSample->file_path &&
                Storage::disk('public')->exists($singleSample->file_path)
            ) {
                Storage::disk('public')->delete($singleSample->file_path);
            }

            // School folder or superadmin samples folder
            $folder = $schoolId
                ? 'samples/' . $schoolId
                : 'samples';

            $path = $request->file('sampleupload')
                ->store($folder, 'public');

            $singleSample->file_path = $path;
        }

        // Save fields
        $singleSample->school_id     = $schoolId;
        $singleSample->name          = $request->name;
        $singleSample->applicable_id = $request->applicable_id;
        $singleSample->class_id      = $request->class_id;
        $singleSample->orientation   = $request->orientation;
        $singleSample->house_id      = $request->house_id;
        $singleSample->height        = $request->height;
        $singleSample->width         = $request->width;

        $singleSample->save();

        return redirect()
            ->back()
            ->with(
                'success',
                $id
                    ? 'Template updated successfully.'
                    : 'Template uploaded successfully.'
            );
    }

    public function singleDefault($id)
    {
        $schoolId = session('role') === 'school'
            ? Auth::user()->school_id
            : session('viewing_school');

        // Get the actual Mainidcard record
        $sample = Mainidcard::where('sample_id', $id)
            ->where(function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId)
                      ->orWhereNull('school_id');
            })
            ->first();

        // Sample not found
        if (!$sample) {
            return redirect()
                ->back()
                ->with('error', 'ID card sample not found.');
        }

        // Authorization check
        if (
            !is_null($sample->school_id) &&
            $sample->school_id != $schoolId
        ) {
            return redirect()
                ->back()
                ->with('error', 'You are not authorized to set this sample as default.');
        }

        // Remove default from all cards for this school
        Mainidcard::where('school_id', $schoolId)
            ->update([
                'is_default' => 0
            ]);

        // If this is a global/admin sample (school_id NULL),
        // don't change its school_id; only make it default.
        $sample->is_default = 1;
        $sample->save();

        return redirect()
            ->back()
            ->with('success', 'Sample set as default successfully.');
    }
}
