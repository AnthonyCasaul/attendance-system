<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
            
        $recentAttendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'icon' => 'house-fill']
        ];

        return view('attendance.dashboard', compact('todayAttendance', 'recentAttendances', 'breadcrumbs'));
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $today = Carbon::today();
        
        // Check if already clocked in today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->clock_in) {
            return redirect()->route('attendance.dashboard')
                ->with('error', 'You have already clocked in today!');
        }

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = 'clock_in_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('attendance_photos', $filename, 'public');
        }

        // Create or update attendance record
        if ($existingAttendance) {
            $existingAttendance->update([
                'clock_in' => now(),
                'clock_in_photo' => $photoPath,
                'notes' => $request->notes,
            ]);
        } else {
            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'clock_in' => now(),
                'clock_in_photo' => $photoPath,
                'notes' => $request->notes,
            ]);
        }

        return redirect()->route('attendance.dashboard')
            ->with('success', 'Clock in successful!');
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $today = Carbon::today();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->first();

        if (!$attendance) {
            return redirect()->route('attendance.dashboard')
                ->with('error', 'You need to clock in first or you have already clocked out today!');
        }

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = 'clock_out_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('attendance_photos', $filename, 'public');
        }

        $attendance->update([
            'clock_out' => now(),
            'clock_out_photo' => $photoPath,
            'notes' => $request->notes ? $attendance->notes . ' | Clock Out: ' . $request->notes : $attendance->notes,
        ]);

        return redirect()->route('attendance.dashboard')
            ->with('success', 'Clock out successful!');
    }

    public function history()
    {
        $user = Auth::user();
        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(15);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('attendance.dashboard'), 'icon' => 'house-fill'],
            ['label' => 'Attendance History', 'icon' => 'clock-history']
        ];

        return view('attendance.history', compact('attendances', 'breadcrumbs'));
    }
}
