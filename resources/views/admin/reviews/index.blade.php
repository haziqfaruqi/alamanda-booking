<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews Management | Alamanda Houseboat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <style>
        :root {
            --main-bg: #f4f7fa;
            --accent: #4e73df;
            --text-main: #2d3748;
            --text-muted: #718096;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.05);
            --star-color: #fbbf24;
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

        /* Topbar Style */
        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 2.5rem;
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

        /* Stats Cards */
        .card-stat {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            background: #fff;
            padding: 1.5rem;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0;
        }

        /* Review Item Style */
        .review-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            border: 1px solid #f1f5f9;
            transition: 0.3s;
        }

        .review-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .customer-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #f1f5f9;
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .star-filled { color: var(--star-color); }
        .star-empty { color: #e2e8f0; }

        .admin-reply-box {
            background: #f8fafc;
            border-left: 4px solid var(--accent);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .reply-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--accent);
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .action-btn {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

@include('layouts.admin-sidebar', ['activePage' => 'reviews'])

<main class="main-content">
    <div class="topbar">
        <div class="user-profile">
            <div class="text-end">
                <div style="font-size: 0.85rem; font-weight: 700;">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div style="font-size: 0.7rem; color: var(--text-muted);">Administrator</div>
            </div>
            <div class="avatar-small">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</div>
        </div>
    </div>

    <div class="mb-4">
        <h4 class="fw-bold" style="font-size: 1.25rem;">Reviews Management</h4>
        <p class="text-muted small">Manage guest feedback and build business reputation.</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px; font-size: 0.85rem;">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card-stat">
                <span class="stat-label">Total Reviews</span>
                <h3 class="stat-value">{{ $reviews->total() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stat">
                <span class="stat-label">Avg Rating</span>
                <h3 class="stat-value text-primary">
                    <i class="fa-solid fa-star text-warning me-1"></i>
                    {{ $reviews->avg('rating') ? number_format($reviews->avg('rating'), 1) : '0.0' }}
                </h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stat">
                <span class="stat-label">5-Star Feedback</span>
                <h3 class="stat-value text-success">{{ $reviews->where('rating', 5)->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stat">
                <span class="stat-label">Pending Replies</span>
                <h3 class="stat-value text-warning">{{ $reviews->whereNull('admin_reply')->count() }}</h3>
            </div>
        </div>
    </div>

    <div class="reviews-container">
        @forelse($reviews as $review)
        <div class="review-card">
            <div class="row">
                <div class="col-lg-9">
                    <div class="d-flex gap-3 mb-3">
                        <div class="customer-avatar">
                            {{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size: 0.95rem;">{{ $review->user->name ?? 'Guest' }}</h6>
                            <div class="text-muted small">
                                <i class="fa-regular fa-calendar me-1"></i> {{ $review->created_at->format('M d, Y') }}
                                @if($review->package)
                                    · <span class="text-primary fw-bold">{{ $review->package->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <iconify-icon icon="lucide:star" width="18"
                                class="@if($i <= $review->rating) star-filled @else star-empty @endif"
                                @if($i <= $review->rating) style="fill: currentColor;" @endif>
                            </iconify-icon>
                        @endfor
                    </div>

                    <p class="mb-0" style="font-size: 0.9rem; line-height: 1.6; color: #4a5568;">
                        {{ $review->feedback ?? 'No written feedback provided.' }}
                    </p>

                    @if($review->admin_reply)
                    <div class="admin-reply-box">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="reply-label"><i class="fa-solid fa-shield-check"></i> Admin Reply</span>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $review->replied_at?->format('M d, Y') }}</small>
                        </div>
                        <p class="mb-3 small" style="color: #4a5568;">{{ $review->admin_reply }}</p>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal" data-bs-target="#editReplyModal{{ $review->id }}">Edit</button>
                            <form action="{{ route('admin.reviews.delete-reply', $review) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger action-btn" onclick="return confirm('Delete this reply?')">Delete</button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-lg-3 text-end">
                    @if(!$review->admin_reply)
                        <button class="btn btn-primary action-btn w-100 mb-2" data-bs-toggle="modal" data-bs-target="#replyModal{{ $review->id }}">
                            <i class="fa-solid fa-reply me-1"></i> Post Reply
                        </button>
                    @else
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 mb-2 w-100" style="font-size: 0.7rem;">REPLIED</span>
                    @endif
                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger action-btn w-100" onclick="return confirm('Delete this entire review?')">
                            <i class="fa-solid fa-trash-can me-1"></i> Delete Review
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="card p-5 text-center">
            <i class="fa-regular fa-star-half-stroke text-muted fs-1 mb-3"></i>
            <p class="text-muted">Your houseboat guests haven't left any reviews yet.</p>
        </div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $reviews->links() }}
        </div>
    </div>
</main>

@foreach($reviews->items() as $review)
    @if(!$review->admin_reply)
    <div class="modal fade" id="replyModal{{ $review->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold">Reply to Guest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.reviews.reply', $review) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="p-3 bg-light rounded-3 mb-4">
                            <small class="text-muted d-block mb-1">Guest Review:</small>
                            <p class="small mb-0 fw-bold">"{{ $review->feedback }}"</p>
                        </div>
                        <label class="small fw-bold text-muted mb-2 uppercase">Your Message</label>
                        <textarea name="admin_reply" class="form-control" rows="4" required placeholder="Thank the guest for their stay..."></textarea>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold">Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if($review->admin_reply)
    <div class="modal fade" id="editReplyModal{{ $review->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold">Edit Admin Reply</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.reviews.update-reply', $review) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <textarea name="admin_reply" class="form-control" rows="4" required>{{ $review->admin_reply }}</textarea>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success fw-bold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>