<?php

namespace App\Http\Controllers;

use App\Models\ManageEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\EventRegistration;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ManageEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ManageEvent::query();

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
        // if (!empty($manageEvent->logo)) {
        //     Storage::disk('public')->delete($manageEvent->logo);
        // }
        // $manageEvent->delete();
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
    public function storeRegistration(Request $request, $unique_code)
    {
        $manageEvent = ManageEvent::where('unique_code', $unique_code)
            ->firstOrFail();
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'mobile'       => [
                'required',
                'string',
                'max:10',
                Rule::unique('event_registrations', 'mobile')->ignore($id),
            ],
            'organization' => 'nullable|string|max:255',
            'address'      => 'required|string',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        $validated['event_id'] = $manageEvent->id;
        $validated['ip_address'] = $request->ip();
        $validated['device_name'] = $request->userAgent();
        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');

            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();

            $photo->storeAs(
                'event-registrations',
                $photoName,
                'public'
            );

            $validated['photo'] = 'event-registrations/' . $photoName;
        }

        EventRegistration::create($validated);

        return redirect()
            ->route('events.public', $manageEvent->unique_code)
            ->with('success', 'Registration completed successfully.');
    }

    public function eventPeople(Request $request, $id)
    {
        $search = $request->get('search');

        $events = EventRegistration::where('event_code', $id)
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
}
