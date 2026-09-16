<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mainidcard;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\SelectedSample;
use App\Models\UploadSample;
use App\Models\School;


class MainidcardController extends Controller
{
   
    public function store(Request $request)
    {
        $schoolId = Auth::user()->school_id ?? session('viewing_school');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'orientation' => 'required|in:vertical,horizontal',
            'card_width' => 'required|integer',
            'card_height' => 'required|integer',
            'background' => 'nullable|string',
            'layout' => 'required|array',
            'class_id'      => 'nullable|integer',
            'applicable_id' => 'nullable|integer',
            'house_id'      => 'nullable|integer',
            'sample_id'     => 'nullable|integer',
        ]);


        $orientation = $validated['orientation'];
        $existing = Mainidcard::where('sample_id', $request->sample_id)
            ->where('orientation', $orientation)
            ->first();
        if ($existing) {
            if (
                !empty($existing->background) &&
                is_string($existing->background) &&
                strpos($existing->background, 'idcards/') === 0
            ) {
                if (Storage::disk('public')->exists($existing->background)) {
                    Storage::disk('public')->delete($existing->background);
                }
            }
            $existingLayout = $existing->layout ?? [];
            if (
                isset($existingLayout['fields']) &&
                is_array($existingLayout['fields'])
            ) {
                foreach ($existingLayout['fields'] as $fld) {

                    if (
                        isset($fld['type']) &&
                        $fld['type'] === 'image' &&
                        !empty($fld['src']) &&
                        is_string($fld['src']) &&
                        strpos($fld['src'], 'idcards/') === 0
                    ) {

                        if (Storage::disk('public')->exists($fld['src'])) {
                            Storage::disk('public')->delete($fld['src']);
                        }
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Delete ONLY this orientation's record
            |--------------------------------------------------------------------------
            */
            $existing->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | Process background
        |--------------------------------------------------------------------------
        */
        if (
            !empty($validated['background']) &&
            is_string($validated['background']) &&
            strpos($validated['background'], 'data:') === 0
        ) {

            if (
                preg_match(
                    '/^data:(image\/[a-zA-Z]+);base64,/',
                    $validated['background'],
                    $m
                )
            ) {

                $ext = explode('/', $m[1])[1] ?? 'png';

                $data = preg_replace(
                    '/^data:image\/[a-zA-Z]+;base64,/',
                    '',
                    $validated['background']
                );

                $data = base64_decode($data);

                $filename = 'idcards/' . Str::random(16) . '.' . $ext;

                Storage::disk('public')->put($filename, $data);

                $validated['background'] = $filename;
            }
        }

        // Store a disk-relative path even when the editor submits an asset URL.
        if (!empty($validated['background']) && is_string($validated['background'])) {
            $backgroundPath = parse_url($validated['background'], PHP_URL_PATH);
            if (is_string($backgroundPath) && str_contains($backgroundPath, '/storage/')) {
                $validated['background'] = ltrim(
                    Str::after($backgroundPath, '/storage/'),
                    '/'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Process layout image fields
        |--------------------------------------------------------------------------
        */
        $layout = $validated['layout'] ?? [];

        if (
            isset($layout['fields']) &&
            is_array($layout['fields'])
        ) {

            foreach ($layout['fields'] as $key => $field) {

                if (
                    isset($field['type']) &&
                    $field['type'] === 'image' &&
                    !empty($field['src']) &&
                    is_string($field['src']) &&
                    strpos($field['src'], 'data:') === 0
                ) {

                    if (
                        preg_match(
                            '/^data:(image\/[a-zA-Z]+);base64,/',
                            $field['src'],
                            $m2
                        )
                    ) {

                        $ext = explode('/', $m2[1])[1] ?? 'png';

                        $data = preg_replace(
                            '/^data:image\/[a-zA-Z]+;base64,/',
                            '',
                            $field['src']
                        );

                        $data = base64_decode($data);

                        $filename = 'idcards/' . Str::random(16) . '.' . $ext;

                        Storage::disk('public')->put(
                            $filename,
                            $data
                        );

                        $layout['fields'][$key]['src'] = $filename;
                    }
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Check if this is the first ID card for the school
        |--------------------------------------------------------------------------
        */
        $isFirst = !Mainidcard::where('school_id', $schoolId)->exists();


        /*
        |--------------------------------------------------------------------------
        | Create new card for this orientation
        |--------------------------------------------------------------------------
        */
        $mainidcard = Mainidcard::create([
            'school_id' => $schoolId,
            'name' => $validated['name'],
            'orientation' => $orientation,
            'card_width' => $validated['card_width'],
            'card_height' => $validated['card_height'],
            'background' => $validated['background'] ?? null,
            'layout' => $layout,
            'is_default' => $isFirst,

            'class_id'      => $validated['class_id'] ?? null,
            'applicable_id' => $validated['applicable_id'] ?? null,
            'house_id'      => $validated['house_id'] ?? null,
            'sample_id'     => $validated['sample_id'] ?? null,
        ]);


        return response()->json([
            'success' => true,
            'message' => ucfirst($orientation) . ' ID Card saved successfully.',
            'id' => $mainidcard->id,
            'orientation' => $orientation,
        ]);
    }



    
    public function edit($id, $orientation)
    {
        if (session('role') === 'school') {
            $schoolId = Auth::user()->school_id;
        } elseif (session('role') === 'superadmin') {
            $schoolId = session('viewing_school') ?? null;
        } else {
            $schoolId = null;
        }
        $school = School::find($schoolId);
        $idCardData = UploadSample::where('id', $id)->first();
        return response()->view('idcard.inlteeditor',compact('schoolId','idCardData','school','orientation','id'));
    }

   
}
