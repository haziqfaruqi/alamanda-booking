<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Package Management | Alamanda Houseboat</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
  font-family:'Inter',sans-serif;
  background:#f4f6fb;
}

/* SIDEBAR */
.sidebar{
  width:260px;
  background:#fff;
  min-height:100vh;
  border-right:1px solid #e5e7eb;
  position:fixed;
  padding:24px;
}

/* MAIN */
.main-content{
  margin-left:260px;
  padding:32px;
}

/* TOP BAR */
.top-bar{
  display:flex;
  justify-content:flex-end;
  gap:16px;
  margin-bottom:30px;
}
.avatar{
  width:44px;
  height:44px;
  border-radius:50%;
  background:#4f46e5;
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
}

/* CARD */
.card{
  border:none;
  border-radius:18px;
  box-shadow:0 10px 28px rgba(0,0,0,.06);
}
</style>
</head>

<body>

<!-- SIDEBAR -->
@include('layouts.admin-sidebar', ['activePage' => 'packages'])

<!-- MAIN -->
<main class="main-content">

  <!-- TOP BAR -->
  <div class="top-bar">
    <div class="text-end">
      <div class="fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</div>
      <small class="text-muted">Administrator</small>
    </div>
    <div class="avatar">{{ substr(auth()->user()->name, 0, 1) ?? 'A' }}</div>
  </div>

  <!-- HEADER -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Package Management</h4>
    <button class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#addPackageModal">
      + Add Package
    </button>
  </div>

  <!-- SUCCESS MESSAGE -->
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <!-- TABLE -->
  <div class="card p-4">
    <table class="table align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Package Name</th>
          <th>Duration</th>
          <th>Standard Price</th>
          <th>Full Board (Adult/Child)</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($packages ?? [] as $index => $package)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td><strong>{{ $package->name }}</strong></td>
          <td>{{ $package->duration }}</td>
          <td>RM {{ number_format($package->price_standard, 2) }}</td>
          <td>
            RM {{ number_format($package->price_fullboard_adult, 2) }} /
            RM {{ number_format($package->price_fullboard_child, 2) }}
          </td>
          <td>
            @if($package->is_active)
              <span class="badge bg-success">Active</span>
            @else
              <span class="badge bg-secondary">Inactive</span>
            @endif
          </td>
          <td>
            <button class="btn btn-sm btn-outline-primary"
              data-bs-toggle="modal"
              data-bs-target="#editPackageModal{{ $package->id }}">
              Edit
            </button>
            <form method="POST" action="{{ route('admin.packages.destroy', $package->id) }}" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this package?')">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">No packages found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</main>

<!-- ADD PACKAGE MODAL -->
<div class="modal fade" id="addPackageModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Package</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ route('admin.packages.store') }}">
        @csrf

        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Package Name</label>
              <input type="text" name="name" class="form-control" placeholder="e.g., Standard, Full Board" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Duration</label>
              <input type="text" name="duration" class="form-control" placeholder="e.g., 2 Days 1 Night, 3 Days 2 Nights" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Standard Price (RM)</label>
              <input type="number" step="0.01" name="price_standard" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Full Board Adult (RM)</label>
              <input type="number" step="0.01" name="price_fullboard_adult" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Full Board Child (RM)</label>
              <input type="number" step="0.01" name="price_fullboard_child" class="form-control" value="0">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_active" id="isActiveAdd" checked>
              <label class="form-check-label" for="isActiveAdd">
                Active (Visible to users)
              </label>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Package</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach($packages ?? [] as $package)
<!-- EDIT PACKAGE MODAL -->
<div class="modal fade" id="editPackageModal{{ $package->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Package</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ route('admin.packages.update', $package->id) }}">
        @csrf
        @method('PUT')

        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Package Name</label>
              <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Duration</label>
              <input type="text" name="duration" class="form-control" value="{{ $package->duration }}" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Standard Price (RM)</label>
              <input type="number" step="0.01" name="price_standard" class="form-control" value="{{ $package->price_standard }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Full Board Adult (RM)</label>
              <input type="number" step="0.01" name="price_fullboard_adult" class="form-control" value="{{ $package->price_fullboard_adult }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Full Board Child (RM)</label>
              <input type="number" step="0.01" name="price_fullboard_child" class="form-control" value="{{ $package->price_fullboard_child }}">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ $package->description ?? '' }}</textarea>
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_active" id="isActiveEdit{{ $package->id }}" {{ $package->is_active ? 'checked' : '' }}>
              <label class="form-check-label" for="isActiveEdit{{ $package->id }}">
                Active (Visible to users)
              </label>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
