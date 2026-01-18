@extends('layouts.app')

@section('title', 'User Attendances - ' . $user->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-person-badge"></i> {{ $user->name }}'s Attendance</h2>
                <p class="text-muted">View attendance history for {{ $user->email }}</p>
            </div>
            <div>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
    </div>
</div>

<!-- User Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        <div class="avatar-circle bg-primary text-white mx-auto" style="width: 80px; height: 80px; font-size: 32px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted mb-1"><i class="bi bi-envelope"></i> {{ $user->email }}</p>
                        <p class="text-muted mb-1"><i class="bi bi-calendar"></i> Member since {{ $user->created_at->format('M d, Y') }}</p>
                        <p class="text-muted mb-0"><i class="bi bi-list"></i> Total Records: <span class="badge bg-info">{{ $attendances->total() }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Records -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Attendance Records</h5>
            </div>
            <div class="card-body">
                @if($attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Hours Worked</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td>
                                            <strong>{{ $attendance->date->format('M d, Y') }}</strong><br>
                                            <small class="text-muted">{{ $attendance->date->format('l') }}</small>
                                        </td>
                                        <td>
                                            @if($attendance->clock_in)
                                                <span class="badge bg-success">
                                                    {{ $attendance->clock_in->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Not clocked in</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->clock_out)
                                                <span class="badge bg-danger">
                                                    {{ $attendance->clock_out->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Not clocked out</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->hours_worked)
                                                <strong>{{ round($attendance->hours_worked, 2) }} hrs</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->clock_in && $attendance->clock_out)
                                                <span class="badge bg-success">Complete</span>
                                            @elseif($attendance->clock_in)
                                                <span class="badge bg-warning text-dark">In Progress</span>
                                            @else
                                                <span class="badge bg-secondary">Incomplete</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.attendance.detail', $attendance->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Details
                                            </a>
                                        </td>
                                    </tr>

                                    @if($attendance->notes)
                                        <tr>
                                            <td colspan="6" class="border-0 pt-0">
                                                <small class="text-muted">
                                                    <i class="bi bi-sticky"></i> <strong>Notes:</strong> {{ $attendance->notes }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $attendances->links() }}

                    <!-- Summary Statistics -->
                    @php
                        $totalHours = $attendances->sum('hours_worked');
                        $completedDays = $attendances->where('clock_out', '!=', null)->count();
                        $totalDays = $attendances->count();
                        $completionRate = $totalDays > 0 ? ($completedDays / $totalDays) * 100 : 0;
                        $avgHours = $totalDays > 0 ? $totalHours / $totalDays : 0;
                    @endphp

                    <div class="row mt-4">
                        <div class="col-md-3 mb-2">
                            <div class="card border-info">
                                <div class="card-body text-center p-3">
                                    <h5 class="text-info mb-1">{{ $totalDays }}</h5>
                                    <small class="text-muted">Total Days</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="card border-success">
                                <div class="card-body text-center p-3">
                                    <h5 class="text-success mb-1">{{ number_format($totalHours, 1) }}</h5>
                                    <small class="text-muted">Total Hours</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="card border-warning">
                                <div class="card-body text-center p-3">
                                    <h5 class="text-warning mb-1">{{ number_format($avgHours, 1) }}</h5>
                                    <small class="text-muted">Avg Hours/Day</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="card border-primary">
                                <div class="card-body text-center p-3">
                                    <h5 class="text-primary mb-1">{{ number_format($completionRate, 0) }}%</h5>
                                    <small class="text-muted">Completion Rate</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <h4 class="text-muted">No attendance records</h4>
                        <p class="text-muted">{{ $user->name }} has not recorded any attendance yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.avatar-circle {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
</style>
@endsection