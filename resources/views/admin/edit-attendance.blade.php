@extends('layouts.app')

@section('title', 'Edit Attendance')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-pencil-square"></i> Edit Attendance Record</h2>
                <p class="text-muted">Update clock in/out times for {{ $attendance->user->name }}</p>
            </div>
            <div>
                <a href="{{ route('admin.attendance.detail', $attendance->id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <!-- User Info -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-circle bg-primary text-white" style="width: 60px; height: 60px; font-size: 24px;">
                            {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="mb-1">{{ $attendance->user->name }}</h5>
                        <p class="text-muted mb-1">{{ $attendance->user->email }}</p>
                        <p class="text-muted mb-0">{{ $attendance->date->format('F d, Y (l)') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock"></i> Update Times</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clock_in" class="form-label">
                                <i class="bi bi-play-circle text-success"></i> Clock In
                            </label>
                            <input type="datetime-local" 
                                   class="form-control @error('clock_in') is-invalid @enderror" 
                                   id="clock_in" 
                                   name="clock_in"
                                   value="{{ $attendance->clock_in ? $attendance->clock_in->format('Y-m-d\TH:i') : '' }}">
                            @error('clock_in')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                @if($attendance->clock_in)
                                    Current: {{ $attendance->clock_in->format('M d, Y h:i A') }}
                                @else
                                    No clock in time recorded
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="clock_out" class="form-label">
                                <i class="bi bi-stop-circle text-danger"></i> Clock Out
                            </label>
                            <input type="datetime-local" 
                                   class="form-control @error('clock_out') is-invalid @enderror" 
                                   id="clock_out" 
                                   name="clock_out"
                                   value="{{ $attendance->clock_out ? $attendance->clock_out->format('Y-m-d\TH:i') : '' }}">
                            @error('clock_out')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                @if($attendance->clock_out)
                                    Current: {{ $attendance->clock_out->format('M d, Y h:i A') }}
                                @else
                                    No clock out time recorded
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Hours Calculation Display -->
                    @if($attendance->clock_in && $attendance->clock_out)
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle"></i>
                            <strong>Calculated Hours:</strong> 
                            <span id="hours-display">{{ round($attendance->hours_worked, 2) }} hours</span>
                            <small class="text-muted">(Updated automatically when you change times)</small>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="bi bi-sticky"></i> Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="4"
                                  placeholder="Add any notes about this attendance record">{{ $attendance->notes }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Max 500 characters. Optional.</div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                        <a href="{{ route('admin.attendance.detail', $attendance->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Helpful Info -->
        <div class="card border-info mt-4">
            <div class="card-header bg-info bg-opacity-10">
                <h6 class="mb-0 text-info"><i class="bi bi-lightbulb"></i> Tips</h6>
            </div>
            <div class="card-body small">
                <ul class="mb-0">
                    <li>Use the datetime picker to select the exact date and time for clock in and out.</li>
                    <li>If you clear a field, it will be set to NULL (empty).</li>
                    <li>Hours worked are automatically calculated when both clock in and clock out times are set.</li>
                    <li>You can add notes to explain any adjustments made to this record.</li>
                    <li>Changes will be saved immediately when you click "Save Changes".</li>
                </ul>
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

input[type="datetime-local"] {
    max-width: 100%;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clockInInput = document.getElementById('clock_in');
    const clockOutInput = document.getElementById('clock_out');
    const hoursDisplay = document.getElementById('hours-display');

    function calculateHours() {
        if (clockInInput.value && clockOutInput.value) {
            const clockIn = new Date(clockInInput.value);
            const clockOut = new Date(clockOutInput.value);
            
            if (clockOut > clockIn) {
                const diffMs = clockOut - clockIn;
                const diffHours = diffMs / (1000 * 60 * 60);
                
                if (hoursDisplay) {
                    hoursDisplay.textContent = diffHours.toFixed(2) + ' hours';
                }
            }
        }
    }

    if (clockInInput) clockInInput.addEventListener('change', calculateHours);
    if (clockOutInput) clockOutInput.addEventListener('change', calculateHours);
});
</script>
@endsection