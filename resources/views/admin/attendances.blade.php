@extends('layouts.app')

@section('title', 'All Attendances')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-list"></i> All Attendances</h2>
                <p class="text-muted">View and filter attendance records</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-funnel"></i> Filter Attendances</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.attendances') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" 
                                   value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" 
                                   value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="user_id" class="form-label">User</label>
                            <select class="form-select" name="user_id">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                @if($attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
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
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-secondary text-white me-2">
                                                    {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $attendance->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $attendance->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination with query parameters -->
                    {{ $attendances->appends(request()->query())->links() }}
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <h4 class="text-muted">No attendance records found</h4>
                        <p class="text-muted">Try adjusting your filters or check back later.</p>
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
</style>
@endsection