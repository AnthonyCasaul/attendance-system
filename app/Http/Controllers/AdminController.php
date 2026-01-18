<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $totalUsers = User::where('role', 'user')->count();
        $today = Carbon::today();
        
        $todayAttendances = Attendance::whereDate('date', $today)->count();
        $clockedInToday = Attendance::whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->count();
        $clockedOutToday = Attendance::whereDate('date', $today)
            ->whereNotNull('clock_out')
            ->count();

        $recentAttendances = Attendance::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'icon' => 'speedometer2']
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'todayAttendances', 
            'clockedInToday',
            'clockedOutToday',
            'recentAttendances',
            'breadcrumbs'
        ));
    }

    public function users()
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $users = User::where('role', 'user')
            ->withCount('attendances')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $breadcrumbs = [
            ['label' => 'Users', 'icon' => 'people']
        ];

        return view('admin.users', compact('users', 'breadcrumbs'));
    }

    public function userAttendances($userId)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $user = User::findOrFail($userId);
        $attendances = Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->paginate(15);

        $breadcrumbs = [
            ['label' => 'Users', 'url' => route('admin.users'), 'icon' => 'people'],
            ['label' => $user->name, 'icon' => 'person']
        ];

        return view('admin.user-attendances', compact('user', 'attendances', 'breadcrumbs'));
    }

    public function attendances(Request $request)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $breadcrumbs = [
            ['label' => 'Attendances', 'icon' => 'list']
        ];
        
        $query = Attendance::with('user');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('clock_in', 'desc')
            ->paginate(15);

        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.attendances', compact('attendances', 'users', 'breadcrumbs'));
    }

    public function attendanceDetail($id)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $attendance = Attendance::with('user')->findOrFail($id);
        
        $breadcrumbs = [
            ['label' => 'Attendances', 'url' => route('admin.attendances'), 'icon' => 'list'],
            ['label' => $attendance->user->name . ' - ' . $attendance->date->format('M d, Y'), 'icon' => 'calendar']
        ];
        
        return view('admin.attendance-detail', compact('attendance', 'breadcrumbs'));
    }

    public function createUser()
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $breadcrumbs = [
            ['label' => 'Users', 'url' => route('admin.users'), 'icon' => 'people'],
            ['label' => 'Create New', 'icon' => 'person-plus']
        ];
        
        return view('admin.create-user', compact('breadcrumbs'));
    }

    public function storeUser(Request $request)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully!');
    }

    public function reports()
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $breadcrumbs = [
            ['label' => 'Reports', 'icon' => 'bar-chart']
        ];
        
        $startDate = request('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $userId = request('user_id', null);

        $query = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate]);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $attendances = $query->orderBy('date', 'desc')->get();

        $totalHours = $attendances->sum('hours_worked');
        $avgHoursPerDay = $attendances->where('hours_worked', '>', 0)->avg('hours_worked');
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.reports', compact('attendances', 'startDate', 'endDate', 'totalHours', 'avgHoursPerDay', 'breadcrumbs', 'users', 'userId'));
    }

    public function editAttendance($id)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $attendance = Attendance::with('user')->findOrFail($id);
        
        $breadcrumbs = [
            ['label' => 'Attendances', 'url' => route('admin.attendances'), 'icon' => 'list'],
            ['label' => $attendance->user->name . ' - ' . $attendance->date->format('M d, Y'), 'url' => route('admin.attendance.detail', $attendance->id), 'icon' => 'calendar'],
            ['label' => 'Edit', 'icon' => 'pencil-square']
        ];
        
        return view('admin.edit-attendance', compact('attendance', 'breadcrumbs'));
    }

    public function updateAttendance(Request $request, $id)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $request->validate([
            'clock_in' => 'nullable|date_format:Y-m-d\TH:i',
            'clock_out' => 'nullable|date_format:Y-m-d\TH:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendance = Attendance::findOrFail($id);
        
        // Convert datetime-local format to proper datetime
        $clockIn = $request->filled('clock_in') ? \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->clock_in) : null;
        $clockOut = $request->filled('clock_out') ? \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->clock_out) : null;

        $attendance->update([
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.attendance.detail', $attendance->id)
            ->with('success', 'Attendance record updated successfully!');
    }
}
