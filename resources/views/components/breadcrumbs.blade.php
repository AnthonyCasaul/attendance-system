@props(['items' => []])

@if(count($items) > 0)
<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <div class="container-fluid px-3 px-md-4">
        <ol class="breadcrumb mb-0">
            <!-- Home Link -->
            <li class="breadcrumb-item">
                <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('attendance.dashboard')) : route('login') }}" class="breadcrumb-link">
                    <i class="bi bi-house"></i> <span class="d-none d-sm-inline">Home</span>
                </a>
            </li>

            <!-- Dynamic Breadcrumb Items -->
            @foreach($items as $key => $item)
                @if($loop->last)
                    <!-- Current Page (Not a Link) -->
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="breadcrumb-text">
                            @if(isset($item['icon']))
                                <i class="bi bi-{{ $item['icon'] }}"></i>
                            @endif
                            {{ $item['label'] }}
                        </span>
                    </li>
                @else
                    <!-- Parent Pages (Links) -->
                    <li class="breadcrumb-item">
                        <a href="{{ $item['url'] }}" class="breadcrumb-link">
                            @if(isset($item['icon']))
                                <i class="bi bi-{{ $item['icon'] }}"></i>
                            @endif
                            <span class="d-none d-sm-inline">{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ol>
    </div>
</nav>
@endif

<style>
.breadcrumb-nav {
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    padding: 0.75rem 0;
}

[data-bs-theme="dark"] .breadcrumb-nav {
    background-color: rgba(255, 255, 255, 0.03);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.breadcrumb {
    padding: 0;
    margin-bottom: 0;
    background-color: transparent;
    gap: 0.5rem;
}

.breadcrumb-item {
    font-size: 0.95rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    padding: 0 0.5rem;
    color: #6c757d;
}

[data-bs-theme="dark"] .breadcrumb-item + .breadcrumb-item::before {
    color: #999;
}

.breadcrumb-link {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.breadcrumb-link:hover {
    color: #0a58ca;
    text-decoration: underline;
}

[data-bs-theme="dark"] .breadcrumb-link {
    color: #66b3ff;
}

[data-bs-theme="dark"] .breadcrumb-link:hover {
    color: #99ccff;
}

.breadcrumb-link i {
    font-size: 0.9rem;
}

.breadcrumb-item.active {
    color: #6c757d;
    font-weight: 500;
}

[data-bs-theme="dark"] .breadcrumb-item.active {
    color: #a0a0a0;
}

.breadcrumb-text {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.breadcrumb-text i {
    font-size: 0.9rem;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .breadcrumb {
        gap: 0.3rem;
    }

    .breadcrumb-item {
        font-size: 0.85rem;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        padding: 0 0.3rem;
    }
}
</style>