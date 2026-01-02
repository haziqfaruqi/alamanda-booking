<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reviews Management | Alamanda Houseboat</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Iconify -->
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<style>
body{
  font-family:'Inter',sans-serif;
  background:#f4f6fb;
}

.sidebar{
  width:260px;
  background:#fff;
  min-height:100vh;
  border-right:1px solid #e5e7eb;
}

.search-box{
  max-width:320px;
}

.card{
  border:none;
  border-radius:18px;
  box-shadow:0 8px 24px rgba(0,0,0,.05);
}

.avatar{
  width:42px;
  height:42px;
  border-radius:50%;
  background:#4f46e5;
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:600;
}

.star-filled {
  color: #fbbf24;
}

.star-empty {
  color: #d1d5db;
}

.review-card {
  background: #fff;
  border-radius: 16px;
  padding: 20px;
  margin-bottom: 16px;
  border: 1px solid #e5e7eb;
}

.admin-reply {
  background: #f0fdf4;
  border-left: 4px solid #22c55e;
  border-radius: 8px;
  padding: 16px;
  margin-top: 16px;
}

.sidebar-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 1px solid #e5e7eb;
}

.sidebar-logo img {
  width: 40px;
  height: 40px;
  object-fit: contain;
}

.sidebar-logo span {
  font-weight: 700;
  font-size: 18px;
  color: #374151;
}

.sidebar-section-label {
  color: #9ca3af;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  margin-top: 16px;
  margin-bottom: 8px;
  padding-left: 6px;
}

.sidebar-link {
  text-decoration: none;
  color: #374151;
  padding: 12px 18px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  gap: 12px;
  font-weight: 500;
  margin-bottom: 6px;
  transition: all 0.2s ease;
}

.sidebar-link:hover,
.sidebar-link.active {
  background: #eef2ff;
  color: #4f46e5;
}
</style>
</head>

<body>

<div class="container-fluid">
  <div class="row">

    <!-- SIDEBAR -->
    @include('layouts.admin-sidebar', ['activePage' => 'reviews'])

    <!-- MAIN -->
    <main class="col p-4">

      <!-- TOPBAR -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="fw-bold mb-1">Reviews Management</h4>
          <p class="text-muted mb-0">Manage and reply to customer reviews</p>
        </div>

        <div class="d-flex align-items-center gap-3">
          <div class="text-end">
            <div class="fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</div>
            <small class="text-muted">Administrator</small>
          </div>
          <div class="avatar">{{ substr(auth()->user()->name, 0, 1) ?? 'A' }}</div>
        </div>
      </div>

      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      @endif

      <!-- Stats Cards -->
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="card p-4">
            <h6 class="text-muted">Total Reviews</h6>
            <h3 class="fw-bold">{{ $reviews->total() }}</h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card p-4">
            <h6 class="text-muted">Average Rating</h6>
            <h3 class="fw-bold">{{ $reviews->avg('rating') ? number_format($reviews->avg('rating'), 1) : '0.0' }}</h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card p-4">
            <h6 class="text-muted">5 Star Reviews</h6>
            <h3 class="fw-bold text-success">{{ $reviews->where('rating', 5)->count() }}</h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card p-4">
            <h6 class="text-muted">Pending Replies</h6>
            <h3 class="fw-bold text-warning">{{ $reviews->whereNull('admin_reply')->count() }}</h3>
          </div>
        </div>
      </div>

      <!-- Reviews List -->
      <div class="card p-4">
        @forelse($reviews as $review)
        <div class="review-card">
          <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="avatar" style="width:48px;height:48px;font-size:18px;">
                  {{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}
                </div>
                <div>
                  <h6 class="fw-bold mb-0">{{ $review->user->name ?? 'Guest' }}</h6>
                  <small class="text-muted">
                    {{ $review->created_at->format('M d, Y') }}
                    @if($review->package)
                      · {{ $review->package->name }}
                    @endif
                  </small>
                </div>
              </div>

              <!-- Star Rating -->
              <div class="mb-2">
                @for($i = 1; $i <= 5; $i++)
                  <iconify-icon icon="lucide:star" width="16"
                    class="@if($i <= $review->rating) star-filled @else star-empty @endif"
                    @if($i <= $review->rating) style="fill: currentColor;" @endif>
                  </iconify-icon>
                @endfor
              </div>

              <!-- Review Feedback -->
              @if($review->feedback)
              <p class="mb-0">{{ $review->feedback }}</p>
              @endif

              <!-- Admin Reply -->
              @if($review->admin_reply)
              <div class="admin-reply">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <small class="fw-semibold text-success">
                    <iconify-icon icon="lucide:shield-check" width="14"></iconify-icon> Admin Reply
                  </small>
                  <small class="text-muted">{{ $review->replied_at?->format('M d, Y') }}</small>
                </div>
                <p class="mb-2">{{ $review->admin_reply }}</p>
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#editReplyModal{{ $review->id }}">
                    Edit Reply
                  </button>
                  <form action="{{ route('admin.reviews.delete-reply', $review) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this reply?')">Delete</button>
                  </form>
                </div>
              </div>
              @endif
            </div>

            <!-- Actions -->
            <div class="text-end">
              @if($review->admin_reply)
                <span class="badge bg-success">Replied</span>
              @else
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#replyModal{{ $review->id }}">
                  Reply
                </button>
              @endif
              <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="mt-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this review?')">
                  Delete Review
                </button>
              </form>
            </div>
          </div>
        </div>
        @empty
        <div class="text-center py-5">
          <iconify-icon icon="lucide:star" width="64" class="text-muted mb-3"></iconify-icon>
          <p class="text-muted">No reviews yet.</p>
        </div>
        @endforelse

        <!-- Pagination -->
        @if($reviews->hasPages())
        <div class="d-flex justify-content-center mt-4">
          {{ $reviews->links() }}
        </div>
        @endif
      </div>

    </main>

  </div>
</div>

@php
// Store reviews for modal generation outside the main loop
$reviewsForModals = $reviews->items();
@endphp

@foreach($reviewsForModals as $review)
<!-- Add Reply Modal -->
@if(!$review->admin_reply)
<div class="modal fade" id="replyModal{{ $review->id }}" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('admin.reviews.reply', $review) }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reply to Review</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Original Review:</label>
            <div class="p-3 bg-light rounded">
              <div class="mb-2">
                @for($i = 1; $i <= 5; $i++)
                  <iconify-icon icon="lucide:star" width="14"
                    class="@if($i <= $review->rating) star-filled @else star-empty @endif"
                    @if($i <= $review->rating) style="fill: currentColor;" @endif>
                  </iconify-icon>
                @endfor
              </div>
              <p class="mb-0">{{ $review->feedback ?? 'No feedback provided' }}</p>
              <small class="text-muted">by {{ $review->user->name ?? 'Guest' }}</small>
            </div>
          </div>
          <div>
            <label for="reply{{ $review->id }}" class="form-label">Your Reply:</label>
            <textarea name="admin_reply" id="reply{{ $review->id }}" class="form-control" rows="4" required placeholder="Write your reply..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Post Reply</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endif

<!-- Edit Reply Modal -->
@if($review->admin_reply)
<div class="modal fade" id="editReplyModal{{ $review->id }}" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('admin.reviews.update-reply', $review) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Reply</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div>
            <label for="editReply{{ $review->id }}" class="form-label">Your Reply:</label>
            <textarea name="admin_reply" id="editReply{{ $review->id }}" class="form-control" rows="4" required>{{ $review->admin_reply }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Update Reply</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
