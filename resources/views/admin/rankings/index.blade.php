@extends('layouts.admin')

@section('title', 'Festival Rankings & Scoreboard')

@section('content')
    <div class="container-fluid px-lg-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Festival Rankings & Scoreboard</h2>
                <p class="text-muted small mb-0">{{ $festName }} — Live group points and medal standings.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.rankings.print') }}" target="_blank" class="btn btn-outline-dark shadow-sm">
                    <i class="bi bi-printer me-1"></i> Print Scoresheet
                </a>
                <a href="{{ route('public.rankings') }}" target="_blank" class="btn btn-teal text-white shadow-sm"
                    style="background-color: #0d9488;">
                    <i class="bi bi-eye me-1"></i> View Public Leaderboard
                </a>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills mb-4 bg-white p-2 rounded-3 shadow-sm border" id="rankingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold" id="overall-tab" data-bs-toggle="pill" data-bs-target="#overall"
                    type="button" role="tab">
                    <i class="bi bi-trophy-fill text-warning me-1"></i> Overall Rankings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="bakalooriyya-tab" data-bs-toggle="pill"
                    data-bs-target="#bakalooriyya" type="button" role="tab">
                    <i class="bi bi-layers-fill text-teal me-1" style="color: #0d9488;"></i> Bakalooriyya
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="sanaviyya-tab" data-bs-toggle="pill" data-bs-target="#sanaviyya"
                    type="button" role="tab">
                    <i class="bi bi-layers-fill text-teal me-1" style="color: #0d9488;"></i> Sanaviyya Ulya
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="categories-tab" data-bs-toggle="pill" data-bs-target="#categories"
                    type="button" role="tab">
                    <i class="bi bi-tags-fill text-primary me-1"></i> Category-wise
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="rankingTabsContent">
            <!-- Overall Tab -->
            <div class="tab-pane fade show active" id="overall" role="tabpanel">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Overall Group Leaderboard</h5>
                        <span class="badge bg-light text-muted border">Tie-Breaker: Points &rarr; 1st &rarr; 2nd &rarr; 3rd
                            Prizes</span>
                    </div>
                    <div class="card-body p-0">
                        @include('admin.rankings.partials.table', ['rankings' => $overallRankings, 'showSection' => false])
                    </div>
                </div>
            </div>

            <!-- Bakalooriyya Tab -->
            <div class="tab-pane fade" id="bakalooriyya" role="tabpanel">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Bakalooriyya Section Rankings</h5>
                        <a href="{{ route('admin.rankings.print', ['section' => 'Bakalooriyya']) }}" target="_blank"
                            class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-printer me-1"></i> Print Section Scoresheet
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @include('admin.rankings.partials.table', ['rankings' => $bakalooriyyaRankings, 'showSection' => false])
                    </div>
                </div>
            </div>

            <!-- Sanaviyya Ulya Tab -->
            <div class="tab-pane fade" id="sanaviyya" role="tabpanel">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Sanaviyya Ulya Section Rankings</h5>
                        <a href="{{ route('admin.rankings.print', ['section' => 'Sanaviyya Ulya']) }}" target="_blank"
                            class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-printer me-1"></i> Print Section Scoresheet
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @include('admin.rankings.partials.table', ['rankings' => $sanaviyyaRankings, 'showSection' => false])
                    </div>
                </div>
            </div>

            <!-- Category Tab -->
            <div class="tab-pane fade" id="categories" role="tabpanel">
                <div class="row g-4">
                    @foreach($categoryRankings as $catData)
                        <div class="col-lg-6">
                            <div class="card admin-card border-0 shadow-sm h-100">
                                <div
                                    class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $catData['category']->name }} Rankings</h6>
                                        <small class="text-muted">1st: {{ $catData['category']->first_marks }} | 2nd:
                                            {{ $catData['category']->second_marks }} | 3rd:
                                            {{ $catData['category']->third_marks }} pts</small>
                                    </div>
                                    <span class="badge bg-teal text-white"
                                        style="background-color: #0d9488;">{{ $catData['category']->code }}</span>
                                </div>
                                <div class="card-body p-0">
                                    @include('admin.rankings.partials.table', ['rankings' => $catData['rankings'], 'showSection' => false, 'compact' => true])
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection