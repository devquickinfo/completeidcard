<?php

namespace App\Http\Controllers;

use App\Models\ManageEvent;
use App\Models\EventCustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\EventRegistration;
use App\Models\EventRegistrationFieldValue;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\EventIDCardLayout;


class ManageEventController extends Controller
{
   
    
    public function index(Request $request)
    {
        $query = ManageEvent::query();

        $vendorId = null;

        if (Auth::user()->role === 'vendor') {

            $vendorId = Auth::id();

        } elseif (
            Auth::user()->role === 'superadmin' &&
            session('vendor_viewing')
        ) {

            $vendorId = session('vendor_viewing');
        }

        $query->where('vendor_id', $vendorId);

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
        $manageEvent = ManageEvent::where(
            'unique_code',
            $unique_code
        )->firstOrFail();

        $customFields = EventCustomField::where(
            'event_id',
            $manageEvent->id
        )
        ->where('is_deleted', false)
        ->orderBy('sort_order', 'asc')
        ->orderBy('id', 'asc')
        ->get();

        return view(
            'frontend.manageevent.register',
            compact(
                'manageEvent',
                'customFields'
            )
        );
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


    // public function storeRegistration(Request $request)
    // {
    //     $manageEvent = ManageEvent::where(
    //         'unique_code',
    //         $request->unique_code
    //     )->firstOrFail();

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

    //         // Normal file upload
    //         'photo' => [
    //             'nullable',
    //             'image',
    //             'mimes:jpg,jpeg,png,webp',
    //             'max:5120',
    //         ],

    //         // Camera captured image
    //         'photo_data' => [
    //             'nullable',
    //             'string',
    //         ],
    //     ]);
        
    //     $validated['user_unique_code'] = Str::random(60);
    //     $validated['event_id'] = $manageEvent->id;
    //     $validated['event_code'] = $manageEvent->unique_code;
    //     $validated['ip_address'] = $request->ip();
    //     $validated['device_name'] = $request->userAgent();
    //     if (!$request->hasFile('photo') && !$request->filled('photo_data')) {
    //         return back()
    //             ->withInput()
    //             ->withErrors([
    //                 'photo' => 'Please capture a photo or upload a photo.',
    //             ]);
    //     }

    //     $manager = new ImageManager(new Driver());
    //     $encoded = null;
    //     if ($request->filled('photo_data')) {
    //         $photoData = $request->input('photo_data');
    //         if (preg_match(
    //             '/^data:image\/(jpeg|jpg|png|webp);base64,(.+)$/',
    //             $photoData,
    //             $matches
    //         )) {
    //             $base64Image = $matches[2];
    //             $base64Image = str_replace(' ', '+', $base64Image);
    //             $imageBinary = base64_decode($base64Image, true);

    //             if ($imageBinary === false) {
    //                 return back()
    //                     ->withInput()
    //                     ->withErrors([
    //                         'photo' => 'Invalid captured photo.',
    //                     ]);
    //             }
    //             try {

    //                 $image = $manager->read($imageBinary);
    //             } catch (\Exception $e) {

    //                 return back()
    //                     ->withInput()
    //                     ->withErrors([
    //                         'photo' => 'Unable to process captured photo.',
    //                     ]);
    //             }
    //             $maxSize = 500 * 1024;
    //             $quality = 90;
    //             do {
    //                 $encoded = $image->toJpeg($quality);
    //                 $size = strlen($encoded);
    //                 if ($size <= $maxSize) {
    //                     break;
    //                 }
    //                 $quality -= 5;
    //             } while ($quality >= 30);
    //         } else {

    //             return back()
    //                 ->withInput()
    //                 ->withErrors([
    //                     'photo' => 'Invalid captured photo format.',
    //                 ]);
    //         }
    //     }
    //     elseif ($request->hasFile('photo')) {
    //         $photo = $request->file('photo');
    //         try {
    //             $image = $manager->read($photo->getRealPath());
    //         } catch (\Exception $e) {

    //             return back()
    //                 ->withInput()
    //                 ->withErrors([
    //                     'photo' => 'Unable to process uploaded photo.',
    //                 ]);
    //         }

    //         $maxSize = 500 * 1024;
    //         $quality = 90;
    //         do {
    //             $encoded = $image->toJpeg($quality);
    //             $size = strlen($encoded);
    //             if ($size <= $maxSize) {
    //                 break;
    //             }
    //             $quality -= 5;
    //         } while ($quality >= 30);
    //     }
    //     if ($encoded !== null) {
    //         $photoName = time() . '_' . Str::random(20) . '.jpg';
    //         $photoPath = 'event-registrations/' . $photoName;
    //         Storage::disk('public')->put(
    //             $photoPath,
    //             $encoded
    //         );
    //         $validated['photo'] = $photoPath;
    //     }
    //     unset($validated['photo_data']);
    //     EventRegistration::create($validated);
    //     return redirect()
    //         ->route('events.home')
    //         ->with(
    //             'success',
    //             'Registration completed successfully.'
    //         );
    // }
    public function storeRegistration(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Find Event
        |--------------------------------------------------------------------------
        */

        $manageEvent = ManageEvent::where(
            'unique_code',
            $request->unique_code
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Get Dynamic Fields
        |--------------------------------------------------------------------------
        */

        $customFields = EventCustomField::where(
            'event_id',
            $manageEvent->id
        )
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Base Validation Rules
        |--------------------------------------------------------------------------
        */

        $rules = [

            'unique_code' => 'required|string',

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
                Rule::unique(
                    'event_registrations',
                    'mobile'
                )->where(function ($query) use ($manageEvent) {

                    return $query->where(
                        'event_id',
                        $manageEvent->id
                    );

                }),
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
        ];


        /*
        |--------------------------------------------------------------------------
        | Dynamic Field Validation
        |--------------------------------------------------------------------------
        */

        foreach ($customFields as $field) {

            $fieldName = 'custom_fields.' . $field->id;

            $fieldRules = [];


            /*
            |--------------------------------------------------------------------------
            | Required / Nullable
            |--------------------------------------------------------------------------
            */

            if ($field->is_required) {

                $fieldRules[] = 'required';

            } else {

                $fieldRules[] = 'nullable';

            }


            /*
            |--------------------------------------------------------------------------
            | Input Type Validation
            |--------------------------------------------------------------------------
            */

            switch ($field->input_type) {

                case 'email':

                    $fieldRules[] = 'email';
                    $fieldRules[] = 'max:255';

                    break;


                case 'number':

                    $fieldRules[] = 'numeric';

                    break;


                case 'date':

                    $fieldRules[] = 'date';

                    break;


                case 'time':

                    $fieldRules[] = 'date_format:H:i';

                    break;


                case 'textarea':

                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:5000';

                    break;


                case 'dropdown':

                case 'radio':

                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:255';

                    /*
                    |----------------------------------------------------------
                    | Validate Against Configured Options
                    |----------------------------------------------------------
                    */

                    $options = $this->getCustomFieldOptions(
                        $field->options
                    );

                    if (!empty($options)) {

                        $fieldRules[] = Rule::in($options);

                    }

                    break;


                case 'checkbox':

                    $fieldRules[] = 'array';

                    $options = $this->getCustomFieldOptions(
                        $field->options
                    );

                    if (!empty($options)) {

                        $fieldRules[] = 'array';

                    }

                    break;


                case 'text':

                default:

                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:255';

                    break;
            }


            $rules[$fieldName] = $fieldRules;


            /*
            |--------------------------------------------------------------------------
            | Checkbox Individual Values
            |--------------------------------------------------------------------------
            */

            if (
                $field->input_type === 'checkbox'
                && !empty($options)
            ) {

                $rules[$fieldName . '.*'] = [
                    Rule::in($options)
                ];

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate($rules);


        /*
        |--------------------------------------------------------------------------
        | Photo Required
        |--------------------------------------------------------------------------
        */

        if (
            !$request->hasFile('photo')
            && !$request->filled('photo_data')
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'photo' =>
                        'Please capture a photo or upload a photo.',
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Registration Data
        |--------------------------------------------------------------------------
        */

        $registrationData = [

            'user_unique_code' => Str::random(60),

            'event_id' => $manageEvent->id,

            'event_code' => $manageEvent->unique_code,

            'name' => $validated['name'],

            'email' => $validated['email'] ?? null,

            'mobile' => $validated['mobile'],

            'organization' =>
                $validated['organization'] ?? null,

            'address' => $validated['address'],

            'ip_address' => $request->ip(),

            'device_name' => $request->userAgent(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Photo Processing
        |--------------------------------------------------------------------------
        */

        $manager = new ImageManager(
            new Driver()
        );

        $encoded = null;


        /*
        |--------------------------------------------------------------------------
        | Camera Photo
        |--------------------------------------------------------------------------
        */

        if ($request->filled('photo_data')) {

            $photoData = $request->input('photo_data');


            if (
                preg_match(
                    '/^data:image\/(jpeg|jpg|png|webp);base64,(.+)$/',
                    $photoData,
                    $matches
                )
            ) {

                $base64Image = $matches[2];

                $base64Image = str_replace(
                    ' ',
                    '+',
                    $base64Image
                );


                $imageBinary = base64_decode(
                    $base64Image,
                    true
                );


                if ($imageBinary === false) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'photo' =>
                                'Invalid captured photo.',
                        ]);

                }


                try {

                    $image = $manager->read(
                        $imageBinary
                    );

                } catch (\Exception $e) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'photo' =>
                                'Unable to process captured photo.',
                        ]);

                }


                $maxSize = 500 * 1024;

                $quality = 90;


                do {

                    $encoded = $image->toJpeg(
                        $quality
                    );

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
                        'photo' =>
                            'Invalid captured photo format.',
                    ]);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Uploaded Photo
        |--------------------------------------------------------------------------
        */

        elseif ($request->hasFile('photo')) {

            $photo = $request->file('photo');


            try {

                $image = $manager->read(
                    $photo->getRealPath()
                );

            } catch (\Exception $e) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' =>
                            'Unable to process uploaded photo.',
                    ]);

            }


            $maxSize = 500 * 1024;

            $quality = 90;


            do {

                $encoded = $image->toJpeg(
                    $quality
                );

                $size = strlen($encoded);


                if ($size <= $maxSize) {
                    break;
                }


                $quality -= 5;

            } while ($quality >= 30);

        }


        /*
        |--------------------------------------------------------------------------
        | Save Photo
        |--------------------------------------------------------------------------
        */

        if ($encoded !== null) {

            $photoName =
                time()
                . '_'
                . Str::random(20)
                . '.jpg';


            $photoPath =
                'event-registrations/'
                . $photoName;


            Storage::disk('public')->put(
                $photoPath,
                $encoded
            );


            $registrationData['photo'] =
                $photoPath;

        }


        /*
        |--------------------------------------------------------------------------
        | Create Registration
        |--------------------------------------------------------------------------
        */

        $registration = EventRegistration::create(
            $registrationData
        );


        /*
        |--------------------------------------------------------------------------
        | Save Dynamic Field Values
        |--------------------------------------------------------------------------
        */

        foreach ($customFields as $field) {

            $fieldId = $field->id;


            $value = $request->input(
                'custom_fields.' . $fieldId
            );


            /*
            |--------------------------------------------------------------
            | Checkbox
            |--------------------------------------------------------------
            */

            if ($field->input_type === 'checkbox') {

                if (is_array($value)) {

                    $value = json_encode(
                        $value,
                        JSON_UNESCAPED_UNICODE
                    );

                } else {

                    $value = null;

                }

            }


            /*
            |--------------------------------------------------------------
            | Save Value
            |--------------------------------------------------------------
            */

            if (
                $value !== null
                && $value !== ''
            ) {

               EventRegistrationFieldValue::create([

                    'event_registration_id' => $registration->id,

                    'event_custom_field_id' => $field->id,

                    'value' => $value,

                ]);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('events.home')
            ->with(
                'success',
                'Registration completed successfully.'
            );
    }


    private function getCustomFieldOptions($options)
    {
        if (empty($options)) {
            return [];
        }

        // JSON options
        if (is_string($options)) {

            $decoded = json_decode($options, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return collect($decoded)
                    ->map(fn ($option) => trim((string) $option))
                    ->filter()
                    ->values()
                    ->toArray();
            }
        }

        // New-line options
        return collect(
            preg_split('/\r\n|\r|\n/', $options)
        )
            ->map(fn ($option) => trim($option))
            ->filter()
            ->values()
            ->toArray();
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
    public function settings($id)
    {
        $event = ManageEvent::findOrFail($id);

        $customFields = EventCustomField::where('event_id', $event->id)
            ->where('is_deleted', false)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view(
            'frontend.manageevent.settings',
            compact('event', 'customFields')
        );
    }

    public function showRegisterUser($id)
    {

         $manageEvent=EventRegistration::where('id',$id)->first();
         $layout= EventIDCardLayout::where('event_id',$manageEvent->event_id)->first();
        
         return view('frontend.manageevent.showuser',compact('manageEvent','layout'));
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

        /*
        |--------------------------------------------------------------------------
        | Get Custom Fields For This Event
        |--------------------------------------------------------------------------
        */

        $customFields = EventCustomField::where(
            'event_id',
            $manageEvent->event_id
        )
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Get Saved Custom Field Values
        |--------------------------------------------------------------------------
        */

        $customValues = EventRegistrationFieldValue::where(
            'event_registration_id',
            $manageEvent->id
        )
            ->get()
            ->keyBy('event_custom_field_id');


        return view(
            'frontend.manageevent.editregisteruser',
            compact(
                'manageEvent',
                'customFields',
                'customValues'
            )
        );
    }
    public function updateRegisteredUser(Request $request, $id)
    {
        $manageEvent = EventRegistration::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Get Custom Fields
        |--------------------------------------------------------------------------
        */

        $customFields = EventCustomField::where(
            'event_id',
            $manageEvent->event_id
        )
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Base Validation
        |--------------------------------------------------------------------------
        */

        $rules = [

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

                Rule::unique(
                    'event_registrations',
                    'mobile'
                )
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
        ];


        /*
        |--------------------------------------------------------------------------
        | Dynamic Field Validation
        |--------------------------------------------------------------------------
        */

        foreach ($customFields as $field) {

            $fieldName =
                'custom_fields.' . $field->id;

            $fieldRules = [];


            /*
            |--------------------------------------------------------------------------
            | Required
            |--------------------------------------------------------------------------
            */

            if ($field->is_required) {

                $fieldRules[] = 'required';

            } else {

                $fieldRules[] = 'nullable';
            }


            /*
            |--------------------------------------------------------------------------
            | Input Type
            |--------------------------------------------------------------------------
            */

            switch ($field->input_type) {

                case 'email':

                    $fieldRules[] = 'email';
                    $fieldRules[] = 'max:255';

                    break;


                case 'number':

                    $fieldRules[] = 'numeric';

                    break;


                case 'date':

                    $fieldRules[] = 'date';

                    break;


                case 'time':

                    $fieldRules[] = 'date_format:H:i';

                    break;


                case 'textarea':

                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:5000';

                    break;


                case 'checkbox':

                    $fieldRules[] = 'array';

                    break;


                case 'dropdown':
                case 'radio':
                case 'text':
                default:

                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:255';

                    break;
            }


            $rules[$fieldName] = $fieldRules;


            /*
            |--------------------------------------------------------------------------
            | Checkbox Individual Validation
            |--------------------------------------------------------------------------
            */

            if ($field->input_type === 'checkbox') {

                $rules[$fieldName . '.*'] = [
                    'string',
                    'max:255',
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate($rules);


        /*
        |--------------------------------------------------------------------------
        | Photo
        |--------------------------------------------------------------------------
        */

        $hasUploadedPhoto =
            $request->hasFile('photo');

        $hasCapturedPhoto =
            $request->filled('photo_data');

        $newPhotoPath = null;


        /*
        |--------------------------------------------------------------------------
        | Camera Photo
        |--------------------------------------------------------------------------
        */

        if ($hasCapturedPhoto) {

            $photoData =
                $request->input('photo_data');


            if (!preg_match(
                '/^data:image\/(jpeg|jpg|png|webp);base64,(.+)$/',
                $photoData,
                $matches
            )) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' =>
                            'Invalid captured photo format.'
                    ]);
            }


            $base64Image =
                str_replace(
                    ' ',
                    '+',
                    $matches[2]
                );


            $imageBinary =
                base64_decode(
                    $base64Image,
                    true
                );


            if ($imageBinary === false) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' =>
                            'Invalid captured photo.'
                    ]);
            }


            try {

                $manager =
                    new ImageManager(
                        new Driver()
                    );

                $image =
                    $manager->read(
                        $imageBinary
                    );

            } catch (\Exception $e) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' =>
                            'Unable to process captured photo.'
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

                $encoded =
                    $image->toJpeg(
                        $quality
                    );

                $size =
                    strlen($encoded);


                if ($size <= $maxSize) {
                    break;
                }


                $quality -= 5;

            } while ($quality >= 30);


            $photoName =
                time()
                . '_'
                . Str::random(20)
                . '.jpg';


            $newPhotoPath =
                'event-registrations/'
                . $photoName;


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

                $manager =
                    new ImageManager(
                        new Driver()
                    );

                $image =
                    $manager->read(
                        $request
                            ->file('photo')
                            ->getRealPath()
                    );

            } catch (\Exception $e) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'photo' =>
                            'Unable to process uploaded photo.'
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

                $encoded =
                    $image->toJpeg(
                        $quality
                    );

                $size =
                    strlen($encoded);


                if ($size <= $maxSize) {
                    break;
                }


                $quality -= 5;

            } while ($quality >= 30);


            $photoName =
                time()
                . '_'
                . Str::random(20)
                . '.jpg';


            $newPhotoPath =
                'event-registrations/'
                . $photoName;


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

            $oldPhoto =
                $manageEvent->photo;


            $manageEvent->photo =
                $newPhotoPath;


            $manageEvent->save();


            /*
            |--------------------------------------------------------------------------
            | Delete Old Photo
            |--------------------------------------------------------------------------
            */

            if (
                !empty($oldPhoto)
                &&
                Storage::disk('public')->exists(
                    $oldPhoto
                )
            ) {

                Storage::disk('public')->delete(
                    $oldPhoto
                );
            }

        } else {

            $manageEvent->save();
        }


        /*
        |--------------------------------------------------------------------------
        | Update Custom Fields
        |--------------------------------------------------------------------------
        */

        foreach ($customFields as $field) {

            $value = $request->input(
                'custom_fields.' . $field->id
            );


            /*
            |--------------------------------------------------------------------------
            | Checkbox
            |--------------------------------------------------------------------------
            */

            if (
                $field->input_type === 'checkbox'
            ) {

                if (is_array($value)) {

                    $value = json_encode(
                        $value,
                        JSON_UNESCAPED_UNICODE
                    );

                } else {

                    $value = null;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Empty Optional Value
            |--------------------------------------------------------------------------
            */

            if (
                $value === null
                ||
                $value === ''
            ) {

                EventRegistrationFieldValue::where(
                    'event_registration_id',
                    $manageEvent->id
                )
                ->where(
                    'event_custom_field_id',
                    $field->id
                )
                ->delete();

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Create / Update
            |--------------------------------------------------------------------------
            */

            EventRegistrationFieldValue::updateOrCreate(

                [
                    'event_registration_id' =>
                        $manageEvent->id,

                    'event_custom_field_id' =>
                        $field->id,
                ],

                [
                    'value' =>
                        $value,
                ]

            );
        }


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

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
    public function storeCustomField(Request $request, $eventId)
    {
        $event = ManageEvent::findOrFail($eventId);

        $validated = $request->validate([
            'label' => 'required|string|max:255',

            'field_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z][a-zA-Z0-9_]*$/',
            ],

            'input_type' => [
                'required',
                'in:text,textarea,email,number,date,time,dropdown,checkbox,radio'
            ],

            'html_id' => 'nullable|string|max:100',
            'html_class' => 'nullable|string|max:255',

            'options' => 'nullable|string',

            'is_required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $options = null;

        if (in_array($validated['input_type'], [
            'dropdown',
            'checkbox',
            'radio'
        ])) {
            $optionsArray = collect(
                preg_split('/\r\n|\r|\n/', $request->options ?? '')
            )
            ->map(fn ($value) => trim($value))
            ->filter()
            ->values()
            ->toArray();

            $options = json_encode($optionsArray);
        }

        EventCustomField::create([
            'event_id' => $event->id,
            'label' => $validated['label'],
            'field_name' => $validated['field_name'],
            'input_type' => $validated['input_type'],
            'html_id' => $validated['html_id'] ?? null,
            'html_class' => $validated['html_class'] ?? null,
            'options' => $options,
            'is_required' => $request->boolean('is_required'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return back()->with(
            'success',
            'Custom field added successfully.'
        );
    }
    public function updateCustomField(Request $request, $eventId, $fieldId)
    {
        $event = ManageEvent::findOrFail($eventId);

        $field = EventCustomField::where('event_id', $event->id)
            ->where('id', $fieldId)
            ->firstOrFail();

        $validated = $request->validate([
            'label' => 'required|string|max:255',

            'field_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z][a-zA-Z0-9_]*$/',
            ],

            'input_type' => [
                'required',
                'in:text,textarea,email,number,date,time,dropdown,checkbox,radio'
            ],

            'html_id' => 'nullable|string|max:100',
            'html_class' => 'nullable|string|max:255',
            'options' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $options = null;

        if (in_array($validated['input_type'], [
            'dropdown',
            'checkbox',
            'radio'
        ])) {
            $optionsArray = collect(
                preg_split('/\r\n|\r|\n/', $request->options ?? '')
            )
            ->map(fn ($value) => trim($value))
            ->filter()
            ->values()
            ->toArray();

            $options = json_encode($optionsArray);
        }

        $field->update([
            'label' => $validated['label'],
            'field_name' => $validated['field_name'],
            'input_type' => $validated['input_type'],
            'html_id' => $validated['html_id'] ?? null,
            'html_class' => $validated['html_class'] ?? null,
            'options' => $options,
            'is_required' => $request->boolean('is_required'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return back()->with(
            'success',
            'Custom field updated successfully.'
        );
    }
    public function deleteCustomField($eventId, $fieldId)
    {
        $event = ManageEvent::findOrFail($eventId);

        $field = EventCustomField::where('event_id', $event->id)
            ->where('id', $fieldId)
            ->firstOrFail();

        $field->update([
            'is_deleted' => true
        ]);

        return back()->with(
            'success',
            'Custom field deleted successfully.'
        );
    }
}
