@extends('layouts.app')

@section('title', 'Attendance History')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="bi bi-clock-history"></i> My Attendance History</h2>
        <p class="text-muted">View your complete attendance records</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Attendance Records</h5>
                <a href="{{ route('attendance.dashboard') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-house"></i> Back to Dashboard
                </a>
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
                                    <th>Photos</th>
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
                                                <span class="badge bg-warning">In Progress</span>
                                            @else
                                                <span class="badge bg-secondary">No Record</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->clock_in_photo || $attendance->clock_out_photo)
                                                <div class="btn-group" role="group">
                                                    @if($attendance->clock_in_photo)
                                                        <button type="button" class="btn btn-outline-success btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#photoModal{{ $attendance->id }}In">
                                                            <i class="bi bi-camera"></i> In
                                                        </button>
                                                    @endif
                                                    @if($attendance->clock_out_photo)
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#photoModal{{ $attendance->id }}Out">
                                                            <i class="bi bi-camera"></i> Out
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">No photos</span>
                                            @endif
                                        </td>
                                    </tr>

                                    @if($attendance->notes)
                                        <tr>
                                            <td colspan="6">
                                                <small class="text-muted">
                                                    <strong>Notes:</strong> {{ $attendance->notes }}
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
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <h4 class="text-muted">No attendance records found</h4>
                        <p class="text-muted">Start by clocking in today!</p>
                        <a href="{{ route('attendance.dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-house"></i> Go to Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Photo Modals -->
@foreach($attendances as $attendance)
    @if($attendance->clock_in_photo)
        <div class="modal fade" id="photoModal{{ $attendance->id }}In" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Clock In Photo - {{ $attendance->date->format('M d, Y') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $attendance->clock_in_photo) }}" 
                             class="img-fluid rounded" alt="Clock In Photo">
                        <div class="mt-2">
                            <small class="text-muted">
                                Clocked in at {{ $attendance->clock_in->format('h:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($attendance->clock_out_photo)
        <div class="modal fade" id="photoModal{{ $attendance->id }}Out" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Clock Out Photo - {{ $attendance->date->format('M d, Y') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $attendance->clock_out_photo) }}" 
                             class="img-fluid rounded" alt="Clock Out Photo">
                        <div class="mt-2">
                            <small class="text-muted">
                                Clocked out at {{ $attendance->clock_out->format('h:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection