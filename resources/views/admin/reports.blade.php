@extends('layouts.app')

@section('title', 'Attendance Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-bar-chart"></i> Attendance Reports</h2>
                <p class="text-muted">Generate and view attendance analytics</p>
            </div>
        </div>
    </div>
</div>

<!-- Date Range Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-calendar-range"></i> Report Period</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports') }}">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" 
                                   value="{{ $startDate }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" 
                                   value="{{ $endDate }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="user_id" class="form-label">Filter by User</label>
                            <select class="form-control" name="user_id">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Generate Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-people display-4 text-primary"></i>
                <h3 class="mt-2">{{ $attendances->groupBy('user_id')->count() }}</h3>
                <p class="text-muted">Active Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="bi bi-clock display-4 text-success"></i>
                <h3 class="mt-2">{{ number_format($totalHours, 1) }}</h3>
                <p class="text-muted">Total Hours</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-info">
            <div class="card-body text-center">
                <i class="bi bi-graph-up display-4 text-info"></i>
                <h3 class="mt-2">{{ $avgHoursPerDay ? number_format($avgHoursPerDay, 1) : '0' }}</h3>
                <p class="text-muted">Avg Hours/Day</p>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Report -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-table"></i> Detailed Report 
                    <small class="text-muted">({{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }})</small>
                </h5>
                <button onclick="printReport()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
            <div class="card-body">
                @if($attendances->count() > 0)
                    <!-- User Summary Table -->
                    <h6 class="mb-3"><i class="bi bi-person-lines-fill"></i> Summary by User</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Days Present</th>
                                    <th>Total Hours</th>
                                    <th>Avg Hours/Day</th>
                                    <th>Completion Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $userStats = $attendances->groupBy('user_id')->map(function($userAttendances) {
                                        $totalHours = $userAttendances->sum('hours_worked');
                                        $completedDays = $userAttendances->where('clock_out', '!=', null)->count();
                                        $totalDays = $userAttendances->count();
                                        return [
                                            'user' => $userAttendances->first()->user,
                                            'days_present' => $totalDays,
                                            'total_hours' => $totalHours,
                                            'avg_hours' => $totalDays > 0 ? $totalHours / $totalDays : 0,
                                            'completion_rate' => $totalDays > 0 ? ($completedDays / $totalDays) * 100 : 0
                                        ];
                                    });
                                @endphp
                                
                                @foreach($userStats as $stats)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-secondary text-white me-2">
                                                    {{ strtoupper(substr($stats['user']->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $stats['user']->name }}</strong><br>
                                                    <small class="text-muted">{{ $stats['user']->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $stats['days_present'] }} days</span>
                                        </td>
                                        <td>
                                            <strong>{{ number_format($stats['total_hours'], 1) }} hrs</strong>
                                        </td>
                                        <td>
                                            {{ number_format($stats['avg_hours'], 1) }} hrs
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar {{ $stats['completion_rate'] >= 90 ? 'bg-success' : ($stats['completion_rate'] >= 70 ? 'bg-warning' : 'bg-danger') }}" 
                                                     style="width: {{ $stats['completion_rate'] }}%">
                                                    {{ number_format($stats['completion_rate'], 0) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Detailed Daily Records -->
                    <h6 class="mb-3"><i class="bi bi-calendar-day"></i> Daily Records</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Hours Worked</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances->sortByDesc('date')->sortBy('user.name') as $attendance)
                                    <tr>
                                        <td>
                                            <strong>{{ $attendance->date->format('M d, Y') }}</strong><br>
                                            <small class="text-muted">{{ $attendance->date->format('l') }}</small>
                                        </td>
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>
                                            @if($attendance->clock_in)
                                                <span class="badge bg-success">{{ $attendance->clock_in->format('h:i A') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->clock_out)
                                                <span class="badge bg-danger">{{ $attendance->clock_out->format('h:i A') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->hours_worked)
                                                <strong>{{ number_format($attendance->hours_worked, 2) }}</strong>
                                            @else
                                                <span class="text-muted">0.00</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->clock_in && $attendance->clock_out)
                                                <span class="badge bg-success">Complete</span>
                                            @elseif($attendance->clock_in)
                                                <span class="badge bg-warning text-dark">Incomplete</span>
                                            @else
                                                <span class="badge bg-secondary">No Record</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <h4 class="text-muted">No Data Available</h4>
                        <p class="text-muted">No attendance records found for the selected period.</p>
                        <p class="text-muted">Try selecting a different date range.</p>
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
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}

@media print {
    .btn, .navbar, .card-header .btn {
        display: none !important;
    }
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function printReport() {
    window.print();
}
</script>
@endsection