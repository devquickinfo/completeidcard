<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Permission;
use App\Models\School;
use App\Models\ManageEvent;

class UserController extends Controller
{
    public function index(Request $request) { 
    	$search = $request->input('search'); $perPage = $request->input('per_page', 10); 
        $allowedPerPage = [10, 20, 30, 40, 50, 100];
         if (!in_array((int) $perPage, $allowedPerPage)) {
          $perPage = 10; 
         } 
        $events = User::query()
	    ->where('role', 'vendor')
	    ->when($search, function ($query) use ($search) {
	        $query->where(function ($q) use ($search) {
	            $q->where('name', 'like', '%' . $search . '%')
	              ->orWhere('username', 'like', '%' . $search . '%')
	              ->orWhere('email', 'like', '%' . $search . '%');
	        });
	    })
	    ->latest()
	    ->paginate($perPage)
	    ->withQueryString();
        return view('users.index', compact('events', 'search', 'perPage')); 
    }
    public function create(){
    	return view('users.create');
    }



	// public function store(Request $request)
	// {
	//     $id = $request->input('id');
	//     $user = $id
	//         ? User::where('role', 'vendor')->findOrFail($id)
	//         : null;
	//     $validated = $request->validate([
	//         'name' => 'required|string|max:255',
	//         'username' => 'required|string|max:255|unique:users,username' .
	//             ($user ? ',' . $user->id : ''),
	//         'password' => $user
	//             ? 'nullable|string|min:6'
	//             : 'required|string|min:6',
	//         'email' => 'nullable|email|max:255',
	//         'phone' => 'required|string|max:20',
	//         'user_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
	//         'status' => 'nullable|boolean',
	//         'address' => 'nullable|string',
	//         'schoolcount' =>'required',
	//     ]);
	//     if ($request->hasFile('user_logo')) {
	//         if ($user && $user->user_logo) {
	//             Storage::disk('public')->delete($user->user_logo);
	//         }
	//         $validated['user_logo'] = $request->file('user_logo')
	//             ->store('users', 'public');
	//     }
	//     if (!empty($validated['password'])) {
	//         $validated['password'] = Hash::make($validated['password']);
	//     } else {
	//         unset($validated['password']);
	//     }

	//     $validated['status'] = $request->has('status')
	//         ? (int) $request->status
	//         : 1;
	//     $validated['role'] = 'vendor';
	//     if ($user) {
	//         $user->update($validated);
	//         $message = 'User updated successfully.';
	//     } else {
	//         User::create($validated);
	//         $message = 'User created successfully.';
	//     }
	//     return redirect()
	//         ->route('user.account')
	//         ->with('success', $message);
	// }

	public function store(Request $request)
	{
	    $id = $request->input('id');

	    $user = $id
	        ? User::where('role', 'vendor')->findOrFail($id)
	        : null;

	    $validated = $request->validate([
	        'name' => 'required|string|max:255',
	        'username' => 'required|string|max:255|unique:users,username' .
	            ($user ? ',' . $user->id : ''),
	        'password' => $user
	            ? 'nullable|string|min:6'
	            : 'required|string|min:6',
	        'email' => 'nullable|email|max:255',
	        'phone' => 'required|string|max:20',
	        'user_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
	        'status' => 'nullable|boolean',
	        'address' => 'nullable|string',
	        'schoolcount' => 'required',

	        // Permissions
	        'school' => 'nullable|boolean',
	        'event' => 'nullable|boolean',
	    ]);

	    /*
	    |--------------------------------------------------------------------------
	    | Upload Logo
	    |--------------------------------------------------------------------------
	    */
	    if ($request->hasFile('user_logo')) {

	        if ($user && $user->user_logo) {
	            Storage::disk('public')->delete($user->user_logo);
	        }

	        $validated['user_logo'] = $request->file('user_logo')
	            ->store('users', 'public');
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Password
	    |--------------------------------------------------------------------------
	    */
	    if (!empty($validated['password'])) {
	        $validated['password'] = Hash::make($validated['password']);
	    } else {
	        unset($validated['password']);
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Status
	    |--------------------------------------------------------------------------
	    */
	    $validated['status'] = $request->has('status')
	        ? (int) $request->status
	        : 1;

	    $validated['role'] = 'vendor';

	    /*
	    |--------------------------------------------------------------------------
	    | Create / Update User
	    |--------------------------------------------------------------------------
	    */
	    if ($user) {

	        $user->update($validated);

	        $message = 'User updated successfully.';

	    } else {

	        $user = User::create($validated);

	        $message = 'User created successfully.';
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Save Vendor Permissions
	    |--------------------------------------------------------------------------
	    */
	    Permission::updateOrCreate(
	        [
	            'vendor_id' => $user->id,
	        ],
	        [
	            'school' => $request->input('school', 0),
	            'event' => $request->input('event', 0),
	        ]
	    );

	    return redirect()
	        ->route('user.account')
	        ->with('success', $message);
	}


	public function edit($id)
	{
	    $user = User::where('role', 'vendor')->findOrFail($id);
	    $permission = Permission::where('vendor_id', $user->id)->first();
	    return view('users.create', compact('user','permission'));
	}
	
	public function show($id){

	}

	public function vendorSchools($id)
	{
	    
	    $vendor = User::where('role', 'vendor')->findOrFail($id);
	    $schools = School::where('vendor_id', $vendor->id)
	        ->where('IsDeleted', 0)
	        ->orderBy('school_name', 'asc')
	        ->paginate(10);
	    $events = ManageEvent::where('vendor_id', $vendor->id)
	        ->orderBy('id', 'desc')
	        ->paginate(10);

	    return view('users.vendor-schools', compact('vendor', 'schools','events'));
	}
	public function status($id)
	{
	    $user = User::findOrFail($id);
	    // if ($user->role !== 'superadmin') {
	    //     return redirect()
	    //         ->back()
	    //         ->with('error', 'Invalid user.');
	    // }
	    $user->status = $user->status ? 0 : 1;
	    $user->save();
	    return redirect()
	        ->back()
	        ->with(
	            'success',
	            $user->status
	                ? 'Vendor activated successfully.'
	                : 'Vendor deactivated successfully.'
	        );
	}
}
