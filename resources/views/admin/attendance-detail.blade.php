@extends('layouts.app')

@section('title', 'Attendance Detail')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-eye"></i> Attendance Details</h2>
                <p class="text-muted">Detailed view of attendance record</p>
            </div>
            <div>
                <a href="{{ route('admin.attendance.edit', $attendance->id) }}" class="btn btn-warning me-2">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="{{ route('admin.attendances') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Attendances
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person"></i> {{ $attendance->user->name }}
                    <span class="text-muted">- {{ $attendance->date->format('F d, Y') }}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="bi bi-play-circle display-4 text-success"></i>
                                <h5 class="mt-2">Clock In</h5>
                                @if($attendance->clock_in)
                                    <p class="h4">{{ $attendance->clock_in->format('h:i A') }}</p>
                                    <p class="text-muted">{{ $attendance->clock_in->format('M d, Y') }}</p>
                                @else
                                    <p class="text-muted">Not clocked in</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <i class="bi bi-stop-circle display-4 text-danger"></i>
                                <h5 class="mt-2">Clock Out</h5>
                                @if($attendance->clock_out)
                                    <p class="h4">{{ $attendance->clock_out->format('h:i A') }}</p>
                                    <p class="text-muted">{{ $attendance->clock_out->format('M d, Y') }}</p>
                                @else
                                    <p class="text-muted">Not clocked out</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($attendance->hours_worked)
                    <div class="alert alert-info">
                        <i class="bi bi-clock"></i> <strong>Total Hours Worked:</strong> 
                        {{ $attendance->formatted_hours_worked }}
                    </div>
                @endif

                @if($attendance->notes)
                    <div class="card border-info mt-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-sticky"></i> Notes</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $attendance->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- User Information -->
                <div class="card border-secondary mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-person-badge"></i> User Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $attendance->user->name }}</p>
                                <p><strong>Email:</strong> {{ $attendance->user->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Member Since:</strong> {{ $attendance->user->created_at->format('M d, Y') }}</p>
                                <p><strong>Role:</strong> 
                                    <span class="badge bg-secondary">{{ ucfirst($attendance->user->role) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Photos -->
        @if($attendance->clock_in_photo || $attendance->clock_out_photo)
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-images"></i> Photos</h6>
                </div>
                <div class="card-body">
                    @if($attendance->clock_in_photo)
                        <div class="mb-3">
                            <h6 class="text-success">Clock In Photo</h6>
                            <img src="{{ asset('storage/' . $attendance->clock_in_photo) }}" 
                                 class="img-fluid rounded mb-2 cursor-pointer" 
                                 style="max-height: 200px; width: 100%; object-fit: cover;"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#clockInPhotoModal"
                                 alt="Clock In Photo">
                            <p class="text-muted small">
                                Taken at {{ $attendance->clock_in->format('h:i A') }}
                            </p>
                        </div>
                    @endif

                    @if($attendance->clock_out_photo)
                        <div class="mb-3">
                            <h6 class="text-danger">Clock Out Photo</h6>
                            <img src="{{ asset('storage/' . $attendance->clock_out_photo) }}" 
                                 class="img-fluid rounded mb-2 cursor-pointer" 
                                 style="max-height: 200px; width: 100%; object-fit: cover;"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#clockOutPhotoModal"
                                 alt="Clock Out Photo">
                            <p class="text-muted small">
                                Taken at {{ $attendance->clock_out->format('h:i A') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="bi bi-camera-slash display-1 text-muted"></i>
                    <h5 class="text-muted">No Photos</h5>
                    <p class="text-muted">No attendance photos available for this record.</p>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="card shadow mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.user.attendances', $attendance->user->id) }}" 
                   class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-clock-history"></i> User's All Records
                </a>
                <a href="{{ route('admin.reports') }}?start_date={{ $attendance->date->format('Y-m-d') }}&end_date={{ $attendance->date->format('Y-m-d') }}" 
                   class="btn btn-outline-success w-100 mb-2">
                    <i class="bi bi-bar-chart"></i> Daily Report
                </a>
                <a href="{{ route('admin.attendance.edit', $attendance->id) }}" 
                   class="btn btn-outline-warning w-100">
                    <i class="bi bi-pencil-square"></i> Edit Record
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modals -->
@if($attendance->clock_in_photo)
<div class="modal fade" id="clockInPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clock In Photo - {{ $attendance->date->format('M d, Y') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $attendance->clock_in_photo) }}" 
                     class="img-fluid rounded" alt="Clock In Photo">
                <div class="mt-3">
                    <p><strong>User:</strong> {{ $attendance->user->name }}</p>
                    <p><strong>Time:</strong> {{ $attendance->clock_in->format('h:i A on M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($attendance->clock_out_photo)
<div class="modal fade" id="clockOutPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clock Out Photo - {{ $attendance->date->format('M d, Y') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $attendance->clock_out_photo) }}" 
                     class="img-fluid rounded" alt="Clock Out Photo">
                <div class="mt-3">
                    <p><strong>User:</strong> {{ $attendance->user->name }}</p>
                    <p><strong>Time:</strong> {{ $attendance->clock_out->format('h:i A on M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('styles')
<style>
.cursor-pointer {
    cursor: pointer;
}
</style>
@endsection