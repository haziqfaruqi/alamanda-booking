<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Lists | Alamanda Houseboat</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
  font-family:'Inter',sans-serif;
  background:#f4f6fb;
  overflow-x:hidden;
}

/* ===== SIDEBAR ===== */
.sidebar{
  width:260px;
  background:#fff;
  min-height:100vh;
  border-right:1px solid #e5e7eb;
  position:fixed;
  top:0;
  left:0;
  padding:24px;
}

/* ===== MAIN ===== */
.main-content{
  margin-left:260px;
  padding:32px;
  width:calc(100vw - 260px);
}

/* ===== TOP BAR ===== */
.top-bar{
  display:flex;
  justify-content:flex-end;
  align-items:center;
  gap:16px;
  margin-bottom:30px;
}

.avatar{
  width:46px;
  height:46px;
  border-radius:50%;
  background:#4f46e5;
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
}

/* ===== CARD ===== */
.card{
  border:none;
  border-radius:18px;
  box-shadow:0 10px 28px rgba(0,0,0,.06);
}

.password{
  font-family:monospace;
  letter-spacing:1px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
@include('layouts.admin-sidebar', ['activePage' => 'admin'])

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
    <h4 class="fw-bold mb-0">Admin Lists</h4>
    {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
      + Add Admin
    </button> --}}
  </div>

  <!-- SUCCESS MESSAGE -->
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <!-- ERROR MESSAGE -->
  @if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  @if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <!-- CARD -->
  <div class="card p-4">

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Admin Name</th>
            <th>Email</th>
            <th>Created Date</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          @forelse($admins ?? [] as $index => $admin)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $admin->name }}</td>
            <td>{{ $admin->email }}</td>
            <td>{{ $admin->created_at->format('d M Y') }}</td>
            <td>
              <button class="btn btn-sm btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#editAdminModal{{ $admin->id }}">
                Edit
              </button>
              <form method="POST" action="{{ route('admin.admins.destroy', $admin->id) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this admin?')">
                  Delete
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">No admins found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>

</main>

<!-- ADD ADMIN MODAL -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ route('admin.admins.store') }}">
        @csrf

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Admin Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Admin</button>
        </div>
      </form>

    </div>
  </div>
</div>

@foreach($admins ?? [] as $admin)
<!-- EDIT ADMIN MODAL -->
<div class="modal fade" id="editAdminModal{{ $admin->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ route('admin.admins.update', $admin->id) }}">
        @csrf
        @method('PUT')

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Admin Name</label>
            <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">New Password (leave empty to keep current)</label>
            <input type="password" name="password" class="form-control" minlength="6">
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
