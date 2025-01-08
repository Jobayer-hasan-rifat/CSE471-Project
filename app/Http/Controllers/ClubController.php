<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClubMembersExport;
use App\Imports\ClubMembersImport;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ClubController extends Controller
{
    /**
     * Display a listing of clubs.
     */
    public function index()
    {
        $clubs = Club::with('user')->paginate(10);
        return view('clubs.index', compact('clubs'));
    }

    /**
     * Display club members
     */
    public function members()
    {
        $club = Club::where('user_id', Auth::id())->first();
        
        if (!$club) {
            abort(403, 'You are not associated with any club.');
        }
        
        $members = $club->members()->paginate(10);
        
        return view('club.members.index', compact('members', 'club'));
    }

    /**
     * Add a new club member
     */
    public function addMember(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'student_id' => 'required|string|unique:users,student_id',
            'role' => 'required|string|in:member,executive'
        ]);

        $club = Club::where('user_id', Auth::id())->first();
        
        if (!$club) {
            abort(403, 'You are not associated with any club.');
        }
        
        $member = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'password' => Hash::make('default123'), // Set a default password
            'role' => 'club_member'
        ]);

        $club->members()->attach($member->id, ['role' => $request->role]);

        return back()->with('success', 'Member added successfully.');
    }

    /**
     * Remove a club member
     */
    public function removeMember(User $member)
    {
        $club = Club::where('user_id', Auth::id())->first();
        
        if (!$club) {
            abort(403, 'You are not associated with any club.');
        }
        
        $club->members()->detach($member->id);
        
        return back()->with('success', 'Member removed successfully.');
    }

    /**
     * Import club members from Excel/CSV
     */
    public function importMembers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $club = Club::where('user_id', Auth::id())->first();
        
        if (!$club) {
            abort(403, 'You are not associated with any club.');
        }
        
        Excel::import(new ClubMembersImport($club), $request->file('file'));

        return back()->with('success', 'Members imported successfully.');
    }

    /**
     * Export club members to Excel
     */
    public function exportMembers()
    {
        $club = Club::where('user_id', Auth::id())->first();
        
        if (!$club) {
            abort(403, 'You are not associated with any club.');
        }
        
        return Excel::download(new ClubMembersExport($club), 'club-members.xlsx');
    }

    /**
     * Display club activity log
     */
    public function activityLog()
    {
        $club = Club::where('user_id', Auth::id())->first();
        
        if (!$club) {
            abort(403, 'You are not associated with any club.');
        }
        
        $activities = Event::where('club_id', $club->id)
            ->with('venue')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $stats = [
            'total_events' => $club->events()->count(),
            'approved_events' => $club->events()->where('status', 'approved')->count(),
            'pending_events' => $club->events()->where('status', 'pending')->count(),
            'rejected_events' => $club->events()->where('status', 'rejected')->count(),
        ];
        
        return view('club.activity-log', compact('activities', 'stats', 'club'));
    }

    /**
     * Generate club activity reports
     */
    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonth());
        $endDate = $request->get('end_date', Carbon::now());
        
        $clubStats = Club::withCount(['events' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])->get();
        
        $eventStatusByClub = Event::whereBetween('created_at', [$startDate, $endDate])
            ->with('club')
            ->get()
            ->groupBy('club.name')
            ->map(function ($events) {
                return [
                    'total' => $events->count(),
                    'approved' => $events->where('status', 'approved')->count(),
                    'rejected' => $events->where('status', 'rejected')->count(),
                    'pending' => $events->where('status', 'pending')->count(),
                ];
            });
            
        $monthlyActivity = Event::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('reports.clubs', compact('clubStats', 'eventStatusByClub', 'monthlyActivity'));
    }

    /**
     * Display club information
     */
    public function info()
    {
        $club = Club::where('user_id', Auth::id())->first();
        
        if (!$club) {
            abort(403, 'You are not associated with any club.');
        }

        $advisors = $this->getAdvisors();
        $executives = $this->getExecutives();

        return view('club.info.index', compact('club', 'advisors', 'executives'));
    }

    /**
     * Update club information
     */
    public function updateInfo(Request $request)
    {
        $club = Club::where('user_id', Auth::id())->first();
        
        if (!$club) {
            return response()->json(['error' => 'You are not associated with any club.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255'
        ]);

        $club->update($request->all());

        return response()->json(['message' => 'Club information updated successfully']);
    }

    /**
     * Display club information
     */
    public function infoClub()
    {
        $clubs = Club::withCount('events')
            ->orderBy('name')
            ->get();

        if (request()->ajax()) {
            return response()->json(['html' => view('oca.club-info', compact('clubs'))->render()]);
        }

        return view('oca.club-info', compact('clubs'));
    }

    /**
     * Update club position and photo
     */
    public function updatePosition(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|string',
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048'
        ]);

        $folder = str_contains(strtolower($request->position), 'advisor') ? 'faculty' : 'executives';
        
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs("public/{$folder}", $filename);
            
            // Update the corresponding data in the controller
            if ($folder === 'faculty') {
                $advisors = collect($this->getAdvisors());
                $advisor = $advisors->where('position', $request->position)->first();
                if ($advisor) {
                    $advisor['name'] = $request->name;
                    $advisor['photo'] = Storage::url($path);
                }
            } else {
                $executives = collect($this->getExecutives());
                $executive = $executives->where('position', $request->position)->first();
                if ($executive) {
                    $executive['name'] = $request->name;
                    $executive['photo'] = Storage::url($path);
                }
            }
        }

        return redirect()->route('club.info.index')
            ->with('success', 'Position updated successfully.');
    }

    private function getAdvisors()
    {
        return [
            [
                'name' => 'Dr. Md. Ashraful Alam',
                'position' => 'Faculty Advisor',
                'photo' => 'storage/faculty/ashraful.jpg'
            ],
            [
                'name' => 'Dr. Amina Rahman',
                'position' => 'Co-Advisor',
                'photo' => 'storage/faculty/amina.jpg'
            ],
            [
                'name' => 'Dr. Kamal Hossain',
                'position' => 'Technical Advisor',
                'photo' => 'storage/faculty/kamal.jpg'
            ]
        ];
    }

    private function getExecutives()
    {
        return [
            [
                'name' => 'Sakib Ahmed',
                'position' => 'President',
                'photo' => 'storage/executives/president.jpg'
            ],
            [
                'name' => 'Nusrat Jahan',
                'position' => 'Vice President',
                'photo' => 'storage/executives/vice-president.jpg'
            ],
            [
                'name' => 'Rahul Das',
                'position' => 'General Secretary',
                'photo' => 'storage/executives/secretary.jpg'
            ],
            [
                'name' => 'Fahmida Khan',
                'position' => 'Treasurer',
                'photo' => 'storage/executives/treasurer.jpg'
            ]
        ];
    }

    /**
     * Update club logo
     */
    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();
        $club = Club::where('user_id', $user->id)->first();
        
        if (!$club) {
            return redirect()->back()->with('error', 'You are not associated with any club.');
        }

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = strtolower($user->getRoleNames()->first()) . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $filename);
            $club->update(['logo' => 'images/' . $filename]);
        }

        return redirect()->back()->with('success', 'Club logo updated successfully');
    }

    /**
     * Display a listing of clubs for OCA.
     */
    public function ocaIndex()
    {
        $clubs = Club::with(['user', 'events'])
            ->withCount('events')
            ->orderBy('name')
            ->get();

        // Update clubs with sample data if fields are empty
        foreach ($clubs as $club) {
            if (!$club->president_name) {
                $club->update([
                    'president_name' => 'Club President',
                    'phone' => '017' . rand(10000000, 99999999),
                    'is_active' => true,
                    'status' => 'active'
                ]);
            }
        }

        $clubs = Club::with(['user', 'events'])
            ->withCount('events')
            ->orderBy('name')
            ->paginate(10);

        return view('oca.club-info', compact('clubs'));
    }

    public function ocaShow(Club $club)
    {
        $stats = [
            'total_events' => $club->events()->count(),
            'approved_events' => $club->events()->where('status', 'approved')->count(),
            'pending_events' => $club->events()->where('status', 'pending')->count(),
            'rejected_events' => $club->events()->where('status', 'rejected')->count(),
        ];

        $positions = $club->positions()->orderBy('order')->get();

        return view('oca.clubs.show', compact('club', 'stats', 'positions'));
    }

        /**
         * Show the form for creating a new club.
         */
        public function create()
        {
            // Get the default admin user
            $defaultAdmin = User::where('email', 'admin@bracu.ac.bd')->first();
            
            if (!$defaultAdmin) {
                // If admin doesn't exist, create one
                $defaultAdmin = User::create([
                    'name' => 'Admin',
                    'email' => 'admin@bracu.ac.bd',
                    'password' => Hash::make('admin@123'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]);
                $defaultAdmin->assignRole('admin');
            }
            
            $users = User::whereDoesntHave('club')
                ->whereHas('roles', function($query) {
                    $query->whereIn('name', ['bucc', 'buac', 'robu', 'bizbee']);
                })
                ->get();
                
            return view('clubs.create', compact('users', 'defaultAdmin'));
        }

    /**
     * Store a newly created club in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clubs',
            'email' => 'required|email|unique:clubs',
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'president_name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except('logo');
        
        // Set the short_name from the name field
        $data['short_name'] = strtoupper($request->name);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = strtolower($request->name) . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $filename);
            $data['logo'] = 'images/' . $filename;
        }

        $club = Club::create($data);

        return redirect()->route('admin.clubs.index')
            ->with('success', 'Club created successfully.');
    }

    /**
     * Display club information for the authenticated club user
     */
    public function clubInformation()
    {
        $user = auth()->user();
        
        \Log::info('Debugging club access:', [
            'User ID' => $user->id,
            'User Email' => $user->email,
            'User Roles' => $user->getRoleNames()->toArray()
        ]);

        // Check if user has club role
        if (!$user->hasAnyRole(['club', 'bucc', 'robu', 'buac', 'bizbee'])) {
            \Log::warning('User does not have required roles');
            return redirect()->back()
                ->with('error', 'You do not have permission to access club information.');
        }

        try {
            // First try to find club by email
            $club = Club::where('email', $user->email)->first();
            
            if (!$club) {
                // If not found by email, try user_id
                $club = Club::where('user_id', $user->id)->first();
            }

            if (!$club) {
                \Log::error('No club found for user', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                throw new \Exception('No club found for user');
            }

            // Load relationships
            $club->load(['events']);
            $club->loadCount('events');

            \Log::info('Club found:', [
                'Club ID' => $club->id,
                'Club Name' => $club->name,
                'Club Email' => $club->email
            ]);

            return view('club.information', compact('club'));
        } catch (\Exception $e) {
            \Log::error('Club information error: ' . $e->getMessage());
            \Log::error('User ID: ' . $user->id);
            \Log::error('User Email: ' . $user->email);
            \Log::error('User Roles: ' . implode(', ', $user->getRoleNames()->toArray()));
            
            return redirect()->back()
                ->with('error', 'You are not associated with any club. Please contact the administrator.');
        }
    }
}
