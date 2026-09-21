<?php

namespace App\Http\Controllers;

use App\Models\ManageEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\EventRegistration;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ManageEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ManageEvent::query();

        if (Auth::user()->role === 'vendor') {
          $query->where('vendor_id', Auth::id());
        }else{
            $query->where('vendor_id', null);
        }

        // Event Name filter
        if ($request->filled('event_name')) {
            $query->where('event_name', $request->event_name);
        }

        // Start Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        // End Date filter
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', '%' . $search . '%')
                    ->orWhere('contact_person1', 'like', '%' . $search . '%')
                    ->orWhere('organizer_name', 'like', '%' . $search . '%')
                    ->orWhere('unique_code', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 10);

        $events = $query
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        // Used for Event Name dropdown
        $eventNames = ManageEvent::query()
            ->select('event_name')
            ->whereNotNull('event_name')
            ->where('event_name', '!=', '')
            ->distinct()
            ->orderBy('event_name')
            ->get();

        return view('frontend.manageevent.index', compact(
            'events',
            'eventNames'
        ));
    }

    public function create()
    {
        return view('frontend.manageevent.form', [
            'manageEvent' => null
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name'       => 'required|string|max:255',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'address'          => 'nullable|string',
            'contact_person1'  => 'nullable|string|max:255',
            'organizer_name'   => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            //'unique_code'      => 'required|string|max:255|unique:manage_events,unique_code',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['unique_code'] = Str::random(60);

        if (Auth::user()->role === 'vendor') {
            $validated['vendor_id'] = Auth::id();
        } else {
            $validated['vendor_id'] = null;
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('events', 'public');
        }

        ManageEvent::create($validated);

        return redirect()
            ->route('manage-events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(ManageEvent $manageEvent)
    {
        return view('frontend.manageevent.show', compact('manageEvent'));
    }

    public function edit(ManageEvent $manageEvent)
    {
        return view('frontend.manageevent.form', compact('manageEvent'));
    }

    public function update(Request $request, ManageEvent $manageEvent)
    {

        if (Auth::user()->role === 'vendor' &&
            $manageEvent->vendor_id != Auth::id()) {

            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'event_name'       => 'required|string|max:255',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'address'          => 'nullable|string',
            'contact_person1'  => 'nullable|string|max:255',
            'organizer_name'   => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('logo')) {

            // Delete old logo
            if (!empty($manageEvent->logo)) {
                \Storage::disk('public')->delete($manageEvent->logo);
            }

            $validated['logo'] = $request->file('logo')->store('events', 'public');
        }

        // unique_code is NOT changed
        $manageEvent->update($validated);

        return redirect()
            ->route('manage-events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(ManageEvent $manageEvent)
    {
        return redirect()
            ->route('manage-events.index')
            ->with('success', 'Event deleted successfully.');
    }
    public function publicEvent($unique_code)
    {
        $manageEvent = ManageEvent::where('unique_code', $unique_code)
            ->firstOrFail();

        return view('frontend.manageevent.public', compact('manageEvent'));
    }
    public function register($unique_code)
    {
        $manageEvent = ManageEvent::where('unique_code', $unique_code)
            ->firstOrFail();

        return view('frontend.manageevent.register', compact('manageEvent'));
    }
   
    // public function storeRegistration(Request $request)
    // {
    //     $manageEvent = ManageEvent::where('unique_code', $request->unique_code)
    //         ->firstOrFail();

    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',

    //         'email' => 'nullable|email|max:255',

    //         'mobile' => [
    //             'required',
    //             'string',
    //             'max:10',
    //             Rule::unique('event_registrations', 'mobile')
    //                 ->where(function ($query) use ($manageEvent) {
    //                     return $query->where('event_id', $manageEvent->id);
    //                 }),
    //         ],

    //         'organization' => 'nullable|string|max:255',

    //         'address' => 'required|string|max:1000',

    //         'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    //     ]);

    //     $validated['event_id'] = $manageEvent->id;
    //     $validated['event_code'] = $manageEvent->unique_code;
    //     $validated['ip_address'] = $request->ip();
    //     $validated['device_name'] = $request->userAgent();

    //     /*
    //      * Compress photo to maximum 500 KB
    //      */
    //     if ($request->hasFile('photo')) {

    //         $photo = $request->file('photo');

    //         $manager = new ImageManager(new Driver());

    //         $image = $manager->read($photo->getRealPath());

    //         $maxSize = 500 * 1024; // 500 KB
    //         $quality = 90;

    //         do {

    //             $encoded = $image->toJpeg($quality);

    //             $size = strlen($encoded);

    //             if ($size <= $maxSize) {
    //                 break;
    //             }

    //             $quality -= 5;

    //         } while ($quality >= 30);

    //         $photoName = time() . '_' . uniqid() . '.jpg';

    //         Storage::disk('public')->put(
    //             'event-registrations/' . $photoName,
    //             $encoded
    //         );

    //         $validated['photo'] = 'event-registrations/' . $photoName;
    //     }

    //     EventRegistration::create($validated);

    //     return redirect()
    //         ->route('events.home')
    //         ->with('success', 'Registration completed successfully.');
    // }


    public function storeRegistration(Request $request)
    {
        $manageEvent = ManageEvent::where(
            'unique_code',
            $request->unique_code
        )->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'nullable|email|max:255',

            'mobile' => [
                'required',
                'string',
                'max:10',
                Rule::unique('event_registrations', 'mobile')
                    ->where(function ($query) use ($manageEvent) {
                        return $query->where('event_id', $manageEvent->id);
                    }),
            ],

            'organization' => 'nullable|string|max:255',

            'address' => 'required|string|max:1000',

            // Normal file upload
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            // Camera captured image
            'photo_data' => [
                'nullable',
                'string',
            ],
        ]);
        
        $validated['user_unique_code'] = Str::random(60);
        $validated['event_id'] = $manageEvent->id;
        $validated['event_code'] = $manageEvent->unique_code;
        $validated['ip_address'] = $request->ip();
        $validated['device_name'] = $request->userAgent();
        if (!$request->hasFile('photo') && !$request->filled('photo_data')) {
            return back()
                ->withInput()
                ->withErrors([
                    'photo' => 'Please capture a photo or upload a photo.',
                ]);
        }

        $manager = new ImageManager(new Driver());
        $encoded = null;
        if ($request->filled('photo_data')) {
            $photoData = $request->input('photo_data');
            if (preg_match(
                '/^data:image\/(jpeg|jpg|png|webp);base64,(.+)$/',
                $photoData,
                $matches
            )) {
                $base64Image = $matches[2];
                $base64Image = str_replace(' ', '+', $base64Image);
                $imageBinary = base64_decode($base64Image, true);

                if ($imageBinary === false) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'photo' => 'Invalid captured photo.',
                        ]);
                }
                try {

                    $image = $manager->read($imageBinary);
                } catch (\Exception $e) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'photo' => 'Unable to process captured photo.',
                        ]);
                }
                $maxSize = 500 * 1024;
                $quality = 90;
                do {
                    $encoded = $image->toJpeg($quality);
                    $size = strlen($encoded);
                    if ($size <= $maxSize) {
                        break;
                    }
                    $quality -= 5;
                } while ($quality >= 30);
            } else {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' => 'Invalid captured photo format.',
                    ]);
            }
        }
        elseif ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            try {
                $image = $manager->read($photo->getRealPath());
            } catch (\Exception $e) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' => 'Unable to process uploaded photo.',
                    ]);
            }

            $maxSize = 500 * 1024;
            $quality = 90;
            do {
                $encoded = $image->toJpeg($quality);
                $size = strlen($encoded);
                if ($size <= $maxSize) {
                    break;
                }
                $quality -= 5;
            } while ($quality >= 30);
        }
        if ($encoded !== null) {
            $photoName = time() . '_' . Str::random(20) . '.jpg';
            $photoPath = 'event-registrations/' . $photoName;
            Storage::disk('public')->put(
                $photoPath,
                $encoded
            );
            $validated['photo'] = $photoPath;
        }
        unset($validated['photo_data']);
        EventRegistration::create($validated);
        return redirect()
            ->route('events.home')
            ->with(
                'success',
                'Registration completed successfully.'
            );
    }

    public function eventPeople(Request $request, $id)
    {
        $search = $request->get('search');

        $events = EventRegistration::where('event_code', $id)
            ->where('is_deleted', 0)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('mobile', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('frontend.manageevent.people', compact('events','id'));
    }
    public function home(){

        return view('frontend.manageevent.homepage');
    }
    public function settings(){

        return view('frontend.manageevent.settings');
    }

    public function showRegisterUser($id){
         
         $manageEvent=EventRegistration::where('id',$id)->first();
         return view('frontend.manageevent.showuser',compact('manageEvent'));
    }
    public function showRegisterUserMobile($id){

      $manageEvent = EventRegistration::where(
        'user_unique_code',
        $id
        )->firstOrFail();

       return view('frontend.manageevent.showregisterusermobile', compact('manageEvent'));


    }
    public function editRegisteredUser($id)
    {
        $manageEvent = EventRegistration::findOrFail($id);

        return view(
            'frontend.manageevent.editregisteruser',
            compact('manageEvent')
        );
    }
    public function updateRegisteredUser(Request $request, $id)
    {
        $manageEvent = EventRegistration::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'mobile' => [
                'required',
                'string',
                'max:10',

                Rule::unique('event_registrations', 'mobile')
                    ->where(function ($query) use ($manageEvent) {
                        return $query->where(
                            'event_id',
                            $manageEvent->event_id
                        );
                    })
                    ->ignore($manageEvent->id),
            ],

            'organization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'photo_data' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Photo
        |--------------------------------------------------------------------------
        */

        $hasUploadedPhoto = $request->hasFile('photo');
        $hasCapturedPhoto = $request->filled('photo_data');

        $newPhotoPath = null;

        /*
        |--------------------------------------------------------------------------
        | Camera Photo
        |--------------------------------------------------------------------------
        */

        if ($hasCapturedPhoto) {

            $photoData = $request->input('photo_data');

            if (!preg_match(
                '/^data:image\/(jpeg|jpg|png|webp);base64,(.+)$/',
                $photoData,
                $matches
            )) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' => 'Invalid captured photo format.'
                    ]);
            }

            $base64Image = str_replace(
                ' ',
                '+',
                $matches[2]
            );

            $imageBinary = base64_decode(
                $base64Image,
                true
            );

            if ($imageBinary === false) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' => 'Invalid captured photo.'
                    ]);
            }

            try {

                $manager = new ImageManager(
                    new Driver()
                );

                $image = $manager->read(
                    $imageBinary
                );

            } catch (\Exception $e) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' => 'Unable to process captured photo.'
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Compress Image
            |--------------------------------------------------------------------------
            */

            $maxSize = 500 * 1024;
            $quality = 90;

            do {

                $encoded = $image->toJpeg($quality);

                $size = strlen($encoded);

                if ($size <= $maxSize) {
                    break;
                }

                $quality -= 5;

            } while ($quality >= 30);


            $photoName =
                time() .
                '_' .
                Str::random(20) .
                '.jpg';

            $newPhotoPath =
                'event-registrations/' .
                $photoName;

            Storage::disk('public')->put(
                $newPhotoPath,
                $encoded
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Uploaded Photo
        |--------------------------------------------------------------------------
        */

        elseif ($hasUploadedPhoto) {

            try {

                $manager = new ImageManager(
                    new Driver()
                );

                $image = $manager->read(
                    $request->file('photo')->getRealPath()
                );

            } catch (\Exception $e) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' => 'Unable to process uploaded photo.'
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Compress Image
            |--------------------------------------------------------------------------
            */

            $maxSize = 500 * 1024;
            $quality = 90;

            do {

                $encoded = $image->toJpeg($quality);

                $size = strlen($encoded);

                if ($size <= $maxSize) {
                    break;
                }

                $quality -= 5;

            } while ($quality >= 30);


            $photoName =
                time() .
                '_' .
                Str::random(20) .
                '.jpg';

            $newPhotoPath =
                'event-registrations/' .
                $photoName;

            Storage::disk('public')->put(
                $newPhotoPath,
                $encoded
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Basic Details
        |--------------------------------------------------------------------------
        */

        $manageEvent->name =
            $validated['name'];

        $manageEvent->email =
            $validated['email'] ?? null;

        $manageEvent->mobile =
            $validated['mobile'];

        $manageEvent->organization =
            $validated['organization'] ?? null;

        $manageEvent->address =
            $validated['address'];


        /*
        |--------------------------------------------------------------------------
        | Replace Old Photo
        |--------------------------------------------------------------------------
        */

        if ($newPhotoPath) {

            $oldPhoto = $manageEvent->photo;

            $manageEvent->photo =
                $newPhotoPath;

            $manageEvent->save();


            /*
            |--------------------------------------------------------------------------
            | Delete Old Photo
            |--------------------------------------------------------------------------
            */

            if (
                !empty($oldPhoto) &&
                Storage::disk('public')->exists($oldPhoto)
            ) {

                Storage::disk('public')->delete(
                    $oldPhoto
                );
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | No New Photo
            | Keep Existing Photo
            |--------------------------------------------------------------------------
            */

            $manageEvent->save();
        }


        return redirect()
            ->route(
                'manage-events.people.edit',
                $manageEvent->id
            )
            ->with(
                'success',
                'Registered user updated successfully.'
            );
    }
    public function deleteRegisteredUser($id)
    {
        $manageEvent = EventRegistration::findOrFail($id);

        $manageEvent->is_deleted = 1;
        $manageEvent->save();

        return redirect()
            ->back()
            ->with('success', 'Registered user deleted successfully.');
    }
}
