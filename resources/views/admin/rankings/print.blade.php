<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Scoresheet — {{ $festName }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            background: #fff;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
        .scoresheet-table th, .scoresheet-table td {
            padding: 8px 12px;
            border: 1px solid #333;
        }
    </style>
</head>
<body class="p-4">
    <div class="no-print mb-4 d-flex justify-content-between align-items-center bg-light p-3 rounded border">
        <div>
            <strong>Official Fest Scoresheet Report</strong> &bull; Ready for printing or export to PDF via browser print.
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary btn-sm me-2">
                <i class="bi bi-printer"></i> Print / Save as PDF
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-sm">Close</button>
        </div>
    </div>

    <div class="text-center mb-4 pb-3 border-bottom border-2 border-dark">
        <h3 class="fw-bold text-uppercase mb-1">{{ $collegeName }}</h3>
        <h4 class="fw-bold text-primary mb-1">{{ $festName }}</h4>
        <h5 class="fw-semibold text-muted mb-0">
            Official Group Standings & Score Report
            @if($sectionFilter)
                — <span class="text-dark">{{ $sectionFilter }} Section</span>
            @endif
        </h5>
        <div class="small text-muted mt-2">
            Generated on: {{ now()->format('d F Y, h:i A') }} &bull; Certified Official Result
        </div>
    </div>

    <table class="table scoresheet-table table-bordered align-middle mb-5">
        <thead class="table-dark text-center">
            <tr>
                <th style="width: 60px;">Rank</th>
                <th class="text-start">Participating Group</th>
                <th>Section</th>
                <th style="width: 80px;">1st Prizes</th>
                <th style="width: 80px;">2nd Prizes</th>
                <th style="width: 80px;">3rd Prizes</th>
                <th style="width: 90px;">Total Prizes</th>
                <th style="width: 120px;">Total Points</th>
            </tr>
        </thead>
        <tbody>
            @forelse($overallRankings as $row)
                <tr class="{{ $loop->iteration === 1 ? 'fw-bold' : '' }}">
                    <td class="text-center fs-5">#{{ $loop->iteration }}</td>
                    <td class="text-start">
                        <span class="fs-6">{{ $row['group']->name }}</span>
                        <span class="text-muted ms-1">({{ $row['group']->code }})</span>
                    </td>
                    <td class="text-center">{{ $row['group']->section->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $row['first_prizes'] }}</td>
                    <td class="text-center">{{ $row['second_prizes'] }}</td>
                    <td class="text-center">{{ $row['third_prizes'] }}</td>
                    <td class="text-center">{{ $row['total_prizes'] }}</td>
                    <td class="text-center fs-5 fw-bold text-dark">{{ $row['total_marks'] }} pts</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4">No results recorded.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="row mt-5 pt-5 text-center">
        <div class="col-4">
            <div class="border-top border-dark pt-2 fw-semibold">Arts Convener</div>
        </div>
        <div class="col-4">
            <div class="border-top border-dark pt-2 fw-semibold">Tabulation Controller</div>
        </div>
        <div class="col-4">
            <div class="border-top border-dark pt-2 fw-semibold">Principal / Chief Patron</div>
        </div>
    </div>
</body>
</html>
