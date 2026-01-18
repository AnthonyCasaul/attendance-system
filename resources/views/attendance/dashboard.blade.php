@extends('layouts.app')

@section('title', 'Attendance Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2><i class="bi bi-house"></i> Welcome, {{ auth()->user()->name }}</h2>
                <p class="text-muted">Manage your attendance for {{ date('F d, Y') }}</p>
            </div>
            <div class="text-center">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" 
                         class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #007bff;">
                @else
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                @endif
                <p class="mt-2 mb-0">
                    <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil-square"></i> Edit Profile
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Today's Status -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Today's Attendance</h5>
            </div>
            <div class="card-body">
                @if($todayAttendance)
                    <div class="row">
                        <div class="col-md-6">
                            @if($todayAttendance->clock_in)
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle"></i> <strong>Clocked In:</strong> 
                                    {{ $todayAttendance->clock_in->format('h:i A') }}
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i> Not clocked in yet
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($todayAttendance->clock_out)
                                <div class="alert alert-info">
                                    <i class="bi bi-check-circle"></i> <strong>Clocked Out:</strong> 
                                    {{ $todayAttendance->clock_out->format('h:i A') }}
                                </div>
                            @else
                                <div class="alert alert-secondary">
                                    <i class="bi bi-dash-circle"></i> Not clocked out yet
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($todayAttendance->hours_worked)
                        <div class="alert alert-primary">
                            <i class="bi bi-clock"></i> <strong>Hours Worked:</strong> 
                            {{ $todayAttendance->formatted_hours_worked }}
                        </div>
                    @endif

                    @if($todayAttendance->notes)
                        <div class="mt-3">
                            <strong>Notes:</strong>
                            <p class="text-muted">{{ $todayAttendance->notes }}</p>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> No attendance record for today
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-stopwatch"></i> Actions</h5>
            </div>
            <div class="card-body">
                @if(!$todayAttendance || !$todayAttendance->clock_in)
                    <!-- Clock In Button -->
                    <button type="button" class="btn btn-success btn-lg w-100 mb-3" data-bs-toggle="modal" data-bs-target="#clockInModal">
                        <i class="bi bi-play-circle"></i> Clock In
                    </button>
                @elseif(!$todayAttendance->clock_out)
                    <!-- Clock Out Button -->
                    <button type="button" class="btn btn-danger btn-lg w-100 mb-3" data-bs-toggle="modal" data-bs-target="#clockOutModal">
                        <i class="bi bi-stop-circle"></i> Clock Out
                    </button>
                @else
                    <div class="alert alert-success text-center">
                        <i class="bi bi-check-circle"></i><br>
                        <strong>All done for today!</strong>
                    </div>
                @endif

                <a href="{{ route('attendance.history') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-clock-history"></i> View History
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list"></i> Recent Attendance</h5>
            </div>
            <div class="card-body">
                @if($recentAttendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Hours Worked</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->date->format('M d, Y') }}</td>
                                        <td>
                                            {{ $attendance->clock_in ? $attendance->clock_in->format('h:i A') : '-' }}
                                        </td>
                                        <td>
                                            {{ $attendance->clock_out ? $attendance->clock_out->format('h:i A') : '-' }}
                                        </td>
                                        <td>
                                            {{ $attendance->hours_worked ? round($attendance->hours_worked, 2) . ' hrs' : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No attendance records found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Clock In Modal -->
<div class="modal fade" id="clockInModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('attendance.clock-in') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-play-circle"></i> Clock In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="photo" class="form-label">Take a Photo <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="photo" accept="image/*" capture="user" required>
                        <div class="form-text">Please take a clear photo of yourself</div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-play-circle"></i> Clock In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Clock Out Modal -->
<div class="modal fade" id="clockOutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('attendance.clock-out') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-stop-circle"></i> Clock Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="photo" class="form-label">Take a Photo <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="photo" accept="image/*" capture="user" required>
                        <div class="form-text">Please take a clear photo of yourself</div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-stop-circle"></i> Clock Out
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection