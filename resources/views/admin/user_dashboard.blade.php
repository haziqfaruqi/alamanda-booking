<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Lists | Alamanda Houseboat</title>
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

/* ===== TABLE ===== */
.table th{
  font-weight:600;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
@include('layouts.admin-sidebar', ['activePage' => 'users'])

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
    <h4 class="fw-bold mb-0">User Lists</h4>
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
            <th>Full Name</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Registered Date</th>
          </tr>
        </thead>

        <tbody>
          @forelse($users ?? [] as $index => $user)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->phone ?? '-' }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->created_at->format('d M Y') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">No users found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
