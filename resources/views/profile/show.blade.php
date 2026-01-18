@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" 
                                 class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #007bff;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 150px; height: 150px; font-size: 3rem;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        @endif
                        
                        <h3 class="mt-3 mb-1">{{ $user->name }}</h3>
                        <p class="text-muted">{{ $user->email }}</p>
                        <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                    </div>

                    <!-- Upload Profile Picture -->
                    <div class="card border-light mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-image"></i> Profile Picture</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.update-picture') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="profile_picture" class="form-label">Choose Image</label>
                                    <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" 
                                           id="profile_picture" name="profile_picture" accept="image/*" required>
                                    @error('profile_picture')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-2">
                                        <i class="bi bi-info-circle"></i> Supported formats: JPEG, PNG, JPG, GIF (Max 5MB)
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload"></i> Upload Picture
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Profile Information -->
                    <div class="card border-light">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="bi bi-person-badge"></i> Profile Information</h6>
                                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i> Edit Profile
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">Full Name</div>
                                <div class="col-sm-8">
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">Email</div>
                                <div class="col-sm-8">
                                    <strong>{{ $user->email }}</strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 text-muted">Account Role</div>
                                <div class="col-sm-8">
                                    <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('attendance.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [data-bs-theme="dark"] .card {
        background-color: #2b2d31;
        border-color: #3f4147;
    }

    [data-bs-theme="dark"] .card-header {
        background-color: #3f4147 !important;
        border-color: #3f4147;
    }

    [data-bs-theme="dark"] .text-muted {
        color: #949ba4 !important;
    }

    [data-bs-theme="dark"] .border-light {
        border-color: #3f4147 !important;
    }
</style>
@endsection
