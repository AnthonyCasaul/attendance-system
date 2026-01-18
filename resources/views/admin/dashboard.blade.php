@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
        <p class="text-muted">Manage and monitor attendance system</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-people display-4 text-primary"></i>
                <h3 class="mt-2">{{ $totalUsers }}</h3>
                <p class="text-muted">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-info">
            <div class="card-body text-center">
                <i class="bi bi-calendar-day display-4 text-info"></i>
                <h3 class="mt-2">{{ $todayAttendances }}</h3>
                <p class="text-muted">Today's Records</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="bi bi-play-circle display-4 text-success"></i>
                <h3 class="mt-2">{{ $clockedInToday }}</h3>
                <p class="text-muted">Clocked In Today</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-danger">
            <div class="card-body text-center">
                <i class="bi bi-stop-circle display-4 text-danger"></i>
                <h3 class="mt-2">{{ $clockedOutToday }}</h3>
                <p class="text-muted">Clocked Out Today</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.attendances') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-list"></i> View Attendances
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.reports') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-bar-chart"></i> Reports
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-warning w-100">
                            <i class="bi bi-person-plus"></i> Add User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Activities</h5>
            </div>
            <div class="card-body">
                @if($recentAttendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Hours</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendances as $attendance)
                                    <tr>
                                        <td>
                                            <strong>{{ $attendance->user->name }}</strong><br>
                                            <small class="text-muted">{{ $attendance->user->email }}</small>
                                        </td>
                                        <td>{{ $attendance->date->format('M d, Y') }}</td>
                                        <td>
                                            @if($attendance->clock_in)
                                                <span class="badge bg-success">
                                                    {{ $attendance->clock_in->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->clock_out)
                                                <span class="badge bg-danger">
                                                    {{ $attendance->clock_out->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $attendance->hours_worked ? round($attendance->hours_worked, 2) . ' hrs' : '-' }}
                                        </td>
                                        <td>
                                            @if($attendance->clock_in && $attendance->clock_out)
                                                <span class="badge bg-success">Complete</span>
                                            @elseif($attendance->clock_in)
                                                <span class="badge bg-warning">In Progress</span>
                                            @else
                                                <span class="badge bg-secondary">No Record</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.attendance.detail', $attendance->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.attendances') }}" class="btn btn-primary">
                            <i class="bi bi-list"></i> View All Attendances
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <h4 class="text-muted">No recent activities</h4>
                        <p class="text-muted">Attendance records will appear here as users clock in and out.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection