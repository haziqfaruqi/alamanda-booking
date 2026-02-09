<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Package Management | Alamanda Houseboat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --main-bg: #f4f7fa;
            --accent: #4e73df;
            --text-main: #2d3748;
            --text-muted: #718096;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.05);
            --success-soft: #f0fdf4;
            --success-border: #10b981;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--main-bg);
            color: var(--text-main);
            margin: 0;
        }

        .main-content {
            margin-left: 260px;
            padding: 2.5rem;
            min-height: 100vh;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 2rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            padding: 6px 14px;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
        }

        .avatar-small {
            width: 32px;
            height: 32px;
            background: var(--accent);
            color: #fff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            background: #fff;
        }

        /* Tabs & Buttons */
        .package-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            background: #f1f5f9;
            padding: 5px;
            border-radius: 12px;
            width: fit-content;
        }

        .package-tab {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            transition: 0.3s;
            background: transparent;
            color: var(--text-muted);
        }

        .package-tab.active {
            background: #fff;
            color: var(--accent);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .duration-btn {
            padding: 8px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-main);
            transition: 0.2s;
        }

        .duration-btn.active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        /* Package Card Sleek */
        .package-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 25px;
            transition: 0.3s;
            background: #fff;
        }

        .package-card.exists {
            border: 1.5px solid var(--success-border);
            background: var(--success-soft);
        }

        .price-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .form-control {
            border-radius: 8px;
            font-size: 0.9rem;
            padding: 10px;
        }
    </style>
</head>

<body>

@include('layouts.admin-sidebar', ['activePage' => 'packages'])

<main class="main-content">
    <div class="top-bar">
        <div class="user-profile">
            <div class="text-end">
                <div style="font-size: 0.8rem; font-weight: 700;">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div style="font-size: 0.65rem; color: var(--text-muted);">Administrator</div>
            </div>
            <div class="avatar-small">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</div>
        </div>
    </div>

    <h4 class="fw-bold mb-4" style="font-size: 1.25rem;">Package Management</h4>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px; font-size: 0.85rem;">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card p-4">
        <div class="package-tabs">
            <button class="package-tab @if(request('type', 'standard') == 'standard') active @endif" onclick="switchTab('standard')">
                Standard Charter
            </button>
            <button class="package-tab @if(request('type') == 'fullboard') active @endif" onclick="switchTab('fullboard')">
                Full Board (Meals)
            </button>
        </div>

        <div class="d-flex gap-2 flex-wrap mb-4">
            @foreach(['2D1N', '3D2N', '4D3N', '5D4N'] as $dur)
                <button class="duration-btn @if(request('duration', '2D1N') == $dur) active @endif" onclick="switchDuration('{{ $dur }}')">{{ $dur }}</button>
            @endforeach
            <button class="duration-btn @if(request('duration') && !in_array(request('duration'), ['2D1N', '3D2N', '4D3N', '5D4N'])) active @endif" onclick="showCustomDuration()" id="dur-custom">
                <i class="fa-solid fa-plus me-1"></i> Custom
            </button>
        </div>

        <div id="customDurationInput" class="mb-4 bg-light p-3 rounded-3" style="display:none;">
            <div class="row align-items-end g-2">
                <div class="col-md-4">
                    <label class="form-label">Custom Duration Name</label>
                    <input type="text" id="customDurationValue" class="form-control" placeholder="e.g., 6D5N">
                </div>
                <div class="col-md-2">
                    <button type="button" onclick="applyCustomDuration()" class="btn btn-primary w-100 py-2 fw-bold">Apply</button>
                </div>
            </div>
        </div>

        @php
        $currentType = request('type', 'standard');
        $currentDuration = request('duration', '2D1N');
        $searchName = $currentType == 'standard' ? 'Standard ' : 'Full Board ';
        // Try to find package by combined name first (e.g., "Standard 2D1N")
        $currentPackage = $packages->where('name', $searchName . $currentDuration)->first();
        // Fallback to separate name and duration check
        if (!$currentPackage) {
            $currentPackage = $packages->where('name', $currentType == 'standard' ? 'Standard' : 'Full Board')->where('duration', $currentDuration)->first();
        }
        @endphp

        <div class="package-card @if($currentPackage) exists @endif">
            <div class="mb-4">
                <h5 class="fw-bold mb-1">{{ $currentType == 'standard' ? 'Standard Package' : 'Full Board Package' }}</h5>
                <span class="badge bg-secondary" style="font-size: 0.7rem;">{{ $currentDuration }}</span>
            </div>

            @if($currentPackage)
                <!-- Display View -->
                <div id="displayView">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-success small fw-bold mb-2"><i class="fa-solid fa-check-circle me-1"></i> Package Active</p>
                            @if($currentType == 'standard')
                                <div class="price-text">RM {{ number_format($currentPackage->price_standard, 0) }} <small class="text-muted fs-6">/ Charter</small></div>
                            @else
                                <div class="d-flex gap-4">
                                    <div>
                                        <label class="form-label d-block">Adult</label>
                                        <div class="price-text">RM {{ number_format($currentPackage->price_fullboard_adult, 0) }}</div>
                                    </div>
                                    <div>
                                        <label class="form-label d-block">Child</label>
                                        <div class="price-text">RM {{ number_format($currentPackage->price_fullboard_child, 0) }}</div>
                                    </div>
                                </div>
                            @endif
                            @if($currentPackage->description)
                                <p class="text-muted small mt-2 mb-0">{{ $currentPackage->description }}</p>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="showEditForm()" class="btn btn-outline-primary px-4 fw-bold">
                                <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                            </button>
                            <form method="POST" action="{{ route('admin.packages.destroy', $currentPackage->id) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger px-3 fw-bold" onclick="return confirm('Remove this package?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Form (hidden by default) -->
                <form id="editForm" method="POST" action="{{ route('admin.packages.update', $currentPackage->id) }}" style="display:none;">
                    @csrf @method('PUT')
                    <input type="hidden" name="name" value="{{ $currentType == 'standard' ? 'Standard' : 'Full Board' }}">
                    <input type="hidden" name="duration" value="{{ $currentDuration }}">
                    <input type="hidden" name="is_active" value="1">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <p class="text-primary small fw-bold mb-2"><i class="fa-solid fa-pen-to-square me-1"></i> Edit Package</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="hideEditForm()" class="btn btn-outline-secondary px-4 fw-bold">
                                <i class="fa-solid fa-times me-2"></i> Cancel
                            </button>
                        </div>
                    </div>

                    <div class="row g-3">
                        @if($currentType == 'standard')
                            <div class="col-md-6">
                                <label class="form-label">Standard Price (RM)</label>
                                <input type="number" name="price_standard" class="form-control" required step="0.01" value="{{ $currentPackage->price_standard }}">
                            </div>
                            <input type="hidden" name="price_fullboard_adult" value="0">
                            <input type="hidden" name="price_fullboard_child" value="0">
                        @else
                            <div class="col-md-4">
                                <label class="form-label">Adult Price (RM)</label>
                                <input type="number" name="price_fullboard_adult" class="form-control" required step="0.01" value="{{ $currentPackage->price_fullboard_adult }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Child Price (RM)</label>
                                <input type="number" name="price_fullboard_child" class="form-control" required step="0.01" value="{{ $currentPackage->price_fullboard_child }}">
                            </div>
                            <input type="hidden" name="price_standard" value="0">
                        @endif
                        <div class="col-md-12">
                            <label class="form-label">Short Description</label>
                            <input type="text" name="description" class="form-control" placeholder="What's included in this price?" value="{{ $currentPackage->description ?? '' }}">
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                                <i class="fa-solid fa-save me-2"></i> Update Package
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <form method="POST" action="{{ route('admin.packages.store') }}">
                    @csrf
                    <input type="hidden" name="name" value="{{ $currentType == 'standard' ? 'Standard' : 'Full Board' }}">
                    <input type="hidden" name="duration" value="{{ $currentDuration }}">
                    <input type="hidden" name="is_active" value="1">

                    <div class="row g-3">
                        @if($currentType == 'standard')
                            <div class="col-md-6">
                                <label class="form-label">Standard Price (RM)</label>
                                <input type="number" name="price_standard" class="form-control" required step="0.01">
                            </div>
                            <input type="hidden" name="price_fullboard_adult" value="0">
                            <input type="hidden" name="price_fullboard_child" value="0">
                        @else
                            <div class="col-md-4">
                                <label class="form-label">Adult Price (RM)</label>
                                <input type="number" name="price_fullboard_adult" class="form-control" required step="0.01">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Child Price (RM)</label>
                                <input type="number" name="price_fullboard_child" class="form-control" required step="0.01">
                            </div>
                            <input type="hidden" name="price_standard" value="0">
                        @endif
                        <div class="col-md-12">
                            <label class="form-label">Short Description</label>
                            <input type="text" name="description" class="form-control" placeholder="What's included in this price?">
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                                <i class="fa-solid fa-plus me-2"></i> Create Package
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</main>

<script>
function showEditForm() {
    document.getElementById('displayView').style.display = 'none';
    document.getElementById('editForm').style.display = 'block';
}

function hideEditForm() {
    document.getElementById('displayView').style.display = 'block';
    document.getElementById('editForm').style.display = 'none';
}

function switchTab(type) {
    const url = new URL(window.location);
    url.searchParams.set('type', type);
    window.location.href = url.toString();
}

function switchDuration(duration) {
    const url = new URL(window.location);
    url.searchParams.set('duration', duration);
    window.location.href = url.toString();
}

function showCustomDuration() {
    document.getElementById('customDurationInput').style.display = 'block';
}

function applyCustomDuration() {
    const val = document.getElementById('customDurationValue').value;
    if(val) switchDuration(val);
}

document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const dur = params.get('duration');
    if(dur && !['2D1N', '3D2N', '4D3N', '5D4N'].includes(dur)) {
        document.getElementById('customDurationInput').style.display = 'block';
        document.getElementById('customDurationValue').value = dur;
    }
});
</script>
</body>
</html>