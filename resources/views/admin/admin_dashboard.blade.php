<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Lists | Alamanda Houseboat</title>
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
            overflow: hidden;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .table thead th {
            background-color: #f8f9fc;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            border: none;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            font-size: 0.85rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .action-btn {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
        }

        /* Modal styling */
        .modal-content { border: none; border-radius: 15px; }
        .form-control { border-radius: 8px; font-size: 0.9rem; padding: 10px; }
    </style>
</head>

<body>

@include('layouts.admin-sidebar', ['activePage' => 'admin'])

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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Admin Management</h4>
        </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px; font-size: 0.85rem;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card p-2">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Admin Name</th>
                        <th>Email</th>
                        <th>Joined Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins ?? [] as $index => $admin)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->created_at->format('d M Y') }}</td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn btn-sm btn-outline-primary action-btn me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editAdminModal{{ $admin->id }}">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                            
                            <form method="POST" action="{{ route('admin.admins.destroy', $admin->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger action-btn" onclick="return confirm('Delete this admin?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">No admins found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

@foreach($admins ?? [] as $admin)
<div class="modal fade" id="editAdminModal{{ $admin->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Edit Administrator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.admins.update', $admin->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Admin Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label small fw-bold">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave empty to keep current">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Update Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>