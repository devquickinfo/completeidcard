<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EventIDCard;
use App\Models\EventIDCardLayout;
use Illuminate\Support\Facades\Validator;
use App\Models\ManageEvent;
use Illuminate\Support\Facades\Storage;

class EventIDCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events= ManageEvent::where('vendor_id',Auth::id())->get();
        $idcards =EventIDCard::where('vendor_id',Auth::id())->get();
        return view('frontend.eventidcard.upload',compact('idcards','events'));
    }

    
    public function create()
    {
        return view('frontend.eventidcard.create');
    }

    // public function store(Request $request)
    // {
    //     $id = $request->input('id');
    //     if ($id) {
    //         $eventIDCard = EventIDCard::findOrFail($id);
    //         $validated = $request->validate([
    //             'name'       => 'required|string|max:255',
    //             'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    //             'width'      => 'required|numeric|min:1',
    //             'height'     => 'required|numeric|min:1',
    //             'paper_size' => 'required|in:A4,A3,A5,A6',
    //         ]);
    //         $eventIDCard->name = $validated['name'];
    //         $eventIDCard->width = $validated['width'];
    //         $eventIDCard->height = $validated['height'];
    //         $eventIDCard->paper_size = $validated['paper_size'];

    //         if ($request->hasFile('image')) {

    //             // Delete old image
    //             if (
    //                 $eventIDCard->file_path &&
    //                 \Storage::disk('public')->exists($eventIDCard->file_path)
    //             ) {
    //                 \Storage::disk('public')->delete($eventIDCard->file_path);
    //             }

    //             // Store new image
    //             $eventIDCard->file_path = $request
    //                 ->file('image')
    //                 ->store('event-id-cards', 'public');
    //         }


    //         $eventIDCard->save();

    //         return redirect()
    //             ->route('event-id-cards.index')
    //             ->with('success', 'ID Card Sample updated successfully.');
    //     }

    //     $validated = $request->validate([
    //         'name'       => 'required|string|max:255',
    //         'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
    //         'width'      => 'required|numeric|min:1',
    //         'height'     => 'required|numeric|min:1',
    //         'paper_size' => 'required|in:A4,A3,A5,A6',
    //     ]);
    //     $eventIDCard = new EventIDCard();

    //     $eventIDCard->vendor_id = Auth::id();
    //     $eventIDCard->name = $validated['name'];
    //     $eventIDCard->width = $validated['width'];
    //     $eventIDCard->height = $validated['height'];
    //     $eventIDCard->paper_size = $validated['paper_size'];


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Store Image
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->hasFile('image')) {

    //         $eventIDCard->file_path = $request
    //             ->file('image')
    //             ->store('event-id-cards', 'public');
    //     }


    //     $eventIDCard->save();


    //     return redirect()
    //         ->route('event-id-cards.index')
    //         ->with('success', 'ID Card Sample created successfully.');
    // }
    public function store(Request $request)
    {
        $id = $request->input('id');

        if ($id) {

            $eventIDCard = EventIDCard::findOrFail($id);

            $validated = $request->validate([
                'event_id'   => 'required|exists:manage_events,id',
                'name'       => 'required|string|max:255',
                'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'width'      => 'required|numeric|min:1',
                'height'     => 'required|numeric|min:1',
                'paper_size' => 'required|in:A4,A3,A5,A6',
            ]);

            $eventIDCard->event_id = $validated['event_id'];
            $eventIDCard->name = $validated['name'];
            $eventIDCard->width = $validated['width'];
            $eventIDCard->height = $validated['height'];
            $eventIDCard->paper_size = $validated['paper_size'];

            if ($request->hasFile('image')) {

                // Delete old image
                if (
                    $eventIDCard->file_path &&
                    Storage::disk('public')->exists($eventIDCard->file_path)
                ) {
                    Storage::disk('public')->delete($eventIDCard->file_path);
                }

                // Store new image
                $eventIDCard->file_path = $request
                    ->file('image')
                    ->store('event-id-cards', 'public');
            }

            $eventIDCard->save();

            return redirect()
                ->route('event-id-cards.index')
                ->with('success', 'ID Card Sample updated successfully.');
        }

        // CREATE
        $validated = $request->validate([
            'event_id'   => 'required|exists:manage_events,id',
            'name'       => 'required|string|max:255',
            'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'width'      => 'required|numeric|min:1',
            'height'     => 'required|numeric|min:1',
            'paper_size' => 'required|in:A4,A3,A5,A6',
        ]);

        $eventIDCard = new EventIDCard();

        $eventIDCard->vendor_id = Auth::id();
        $eventIDCard->event_id = $validated['event_id'];
        $eventIDCard->name = $validated['name'];
        $eventIDCard->width = $validated['width'];
        $eventIDCard->height = $validated['height'];
        $eventIDCard->paper_size = $validated['paper_size'];

        if ($request->hasFile('image')) {
            $eventIDCard->file_path = $request
                ->file('image')
                ->store('event-id-cards', 'public');
        }

        $eventIDCard->save();

        return redirect()
            ->route('event-id-cards.index')
            ->with('success', 'ID Card Sample created successfully.');
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $idCardData = EventIDCard::findOrFail($id);
        $events= ManageEvent::where('vendor_id',Auth::id())->get();
        return view('frontend.eventidcard.create', compact('idCardData','events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function editIDCard($id){
       // if(session->role==='vendor'){
       //   $idCardData =EventIDCard::where('vendor_id',Auth::id())->get();
       // }elseif(session->role==='superadmin'){
       //   $idCardData
       // }else{

       //   abort(404);
       // }
        $designcard=EventIDCardLayout::where('sample_id',$id)->first();
        $idCardData =EventIDCard::where('id',$id)->first();

       return view('frontend.eventidcard.editasidcard',compact('idCardData', 'designcard'));
    }

    public function storeEventIDCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'nullable|string|max:255',
            'event_id'        => 'nullable|integer',
            'sample_id'       => 'nullable|integer',
            'card_width'      => 'required|numeric',
            'card_height'     => 'required|numeric',
            'layout'          => 'required|string', // JSON string via FormData
            'background'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'background_path' => 'nullable|string',
            'is_default'      => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $layoutArray = json_decode($data['layout'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid layout JSON.',
            ], 422);
        }

        // Decide the background path:
        // 1. A freshly uploaded file this session wins.
        // 2. Otherwise fall back to the existing sample's file_path.
        $backgroundPath = null;

        if ($request->hasFile('background')) {
            $backgroundPath = $request->file('background')->store('id-card-backgrounds', 'public');
        } elseif (!empty($data['background_path'])) {
            $backgroundPath = $data['background_path'];
        }

        EventIDCardLayout::where('sample_id', $data['sample_id'])->delete();

        $idCardLayout = EventIDCardLayout::create([
            'vendor_id'  => Auth::id(),
            'event_id'   => $data['event_id'] ?? null,
            'sample_id'  => $data['sample_id'] ?? null,
            'name'       => $data['name'] ?? 'Default ID Card',
            'width'      => $data['card_width'],
            'height'     => $data['card_height'],
            'layout'     => $layoutArray,
            'background' => $backgroundPath,
            'is_default' => $data['is_default'] ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ID Card layout saved successfully.',
            'id'      => $idCardLayout->id,
        ]);
    }

    public function updateBackground(Request $request, $id)
    {
        $request->validate([
            'file_path' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $idCardData = EventIDCard::findOrFail($id);

        // Delete old image
        if (!empty($idCardData->file_path)) {
            Storage::disk('public')->delete($idCardData->file_path);
        }

        // Upload new image
        $path = $request->file('file_path')->store(
            'event-id-cards',
            'public'
        );

        // Save new image path
        $idCardData->file_path = $path;
        $idCardData->save();

        // Delete existing layout
        //EventIDCardLayout::where('sample_id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Background updated successfully.',
            'background_url' => Storage::disk('public')->url($path),
        ]);
    }
    public function updateSize(Request $request, $id)
    {
        $idCardData = EventIDCard::findOrFail($id);
        $idCardData->width = $request->width;
        $idCardData->height = $request->height;
        $idCardData->save();
        return response()->json([
            'success' => true
        ]);
    }
    public function uploadFieldImage(Request $request, $idCard)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
            'field' => 'required|in:photo,logo,sign',
        ]);

        $path = $request->file('image')->store('idcards', 'public');

        return response()->json([
            'success' => true,
            'path' => $path,                       // short — save this in layout
            'url'  => asset('storage/' . $path),    // full — for preview
        ]);
    }
}
