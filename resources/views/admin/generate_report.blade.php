<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Report | Alamanda Houseboat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --main-bg: #f4f7fa;
            --accent: #4e73df;
            --accent-hover: #3754bc;
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

        /* Top Bar */
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

        /* Card & Form Style */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            background: #fff;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-select {
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 0.9rem;
            border: 1px solid #e2e8f0;
        }

        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
        }

        /* Info Box */
        .info-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-icon {
            color: #16a34a;
            font-size: 1.2rem;
        }

        /* Button Style */
        .generate-btn {
            background-color: var(--accent);
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            transition: all 0.3s ease;
            width: 100%;
        }

        .generate-btn:hover:not(:disabled) {
            background-color: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
        }

        .generate-btn:disabled {
            background-color: #cbd5e1;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .report-desc {
            font-size: 0.8rem;
            line-height: 1.6;
            color: var(--text-muted);
            padding: 15px;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 4px solid #e2e8f0;
        }
    </style>
</head>

<body>

@include('layouts.admin-sidebar', ['activePage' => 'reports'])

<main class="main-content">

    <div class="top-bar">
        <div class="user-profile">
            <div class="text-end">
                <div style="font-size: 0.8rem; font-weight: 700;">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div style="font-size: 0.65rem; color: var(--text-muted);">Administrator</div>
            </div>
            <div class="avatar-small">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>
        </div>
    </div>

    <h4 class="page-title mb-4">Generate Business Reports</h4>

    <div class="row">
        <div class="col-lg-7">
            <div class="card p-4">
                @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px; font-size: 0.85rem;">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.reports.generate') }}" id="reportForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Report Category</label>
                        <select class="form-select" name="report_type" id="reportType" required>
                            <option value="" selected disabled>Select the type of report</option>
                            @foreach($reportTypes ?? [] as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="report-desc mt-3">
                            <i class="fa-solid fa-circle-info me-2 text-primary"></i>
                            <strong>Guide:</strong><br>
                            • <strong>Booking:</strong> Detailed logs of guest names and dates.<br>
                            • <strong>Revenue:</strong> Financial breakdown of all paid charters.<br>
                            • <strong>Package:</strong> Analysis of houseboat rental performance.
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Filtering Month</label>
                            <select class="form-select" name="month" id="month">
                                <option value="">Full Year Summary</option>
                                @foreach([1=>'January', 2=>'February', 3=>'March', 4=>'April', 5=>'May', 6=>'June', 7=>'July', 8=>'August', 9=>'September', 10=>'October', 11=>'November', 12=>'December'] as $m => $name)
                                    <option value="{{ $m }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fiscal Year</label>
                            <select class="form-select" name="year" id="year" required>
                                <option value="" disabled>Select Year</option>
                                @foreach($years ?? [] as $y)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="info-box d-flex align-items-center gap-3">
                        <div class="info-icon">
                            <i class="fa-solid fa-file-csv"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 0.85rem; color: #166534;">Export Format: Microsoft Excel (CSV)</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Your report will be processed and downloaded instantly as a data spreadsheet.</div>
                        </div>
                    </div>

                    <button type="submit" class="generate-btn" id="generateBtn" disabled>
                        <i class="fa-solid fa-download me-2"></i>
                        <span id="btnText">Complete form to export</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card p-4 bg-light border-0">
                <h6 class="fw-bold mb-3" style="font-size: 0.95rem;">System Information</h6>
                <p class="text-muted small">The reporting engine compiles real-time data from your database. Large reports (All Months) might take a few seconds to process.</p>
                <div class="d-flex align-items-center gap-2 mt-3 p-3 bg-white rounded-3 shadow-sm">
                    <i class="fa-solid fa-database text-primary"></i>
                    <span class="small fw-bold">Database Status: <span class="text-success">Connected</span></span>
                </div>
            </div>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const reportType = document.getElementById('reportType');
    const year = document.getElementById('year');
    const generateBtn = document.getElementById('generateBtn');
    const btnText = document.getElementById('btnText');

    function validateForm() {
        const isValid = reportType.value !== '' && year.value !== '';

        if (isValid) {
            generateBtn.disabled = false;
            btnText.textContent = 'Download CSV Report';
        } else {
            generateBtn.disabled = true;
            btnText.textContent = 'Complete form to export';
        }
    }

    reportType.addEventListener('change', validateForm);
    year.addEventListener('change', validateForm);

    // Initial check on load
    document.addEventListener('DOMContentLoaded', validateForm);
</script>
</body>
</html>