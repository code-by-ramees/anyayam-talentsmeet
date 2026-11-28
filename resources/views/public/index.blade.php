@extends('layouts.app')

@section('title', "Live Leaderboard & Results — {$festName}")

@section('content')
    <!-- Hero Section with Poster Art & Theme -->
    <section class="py-4 py-lg-5 text-white position-relative overflow-hidden"
        style="background: var(--fest-hero-gradient); border-bottom: 3px solid var(--fest-crimson);">
        <!-- Background Glow Blobs -->
        <div
            style="position: absolute; top: -120px; left: -100px; width: 450px; height: 450px; background: radial-gradient(circle, rgba(155,17,30,0.35) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;">
        </div>
        <div
            style="position: absolute; bottom: -80px; right: 10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(92,6,23,0.4) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;">
        </div>

        <div class="container py-lg-4 position-relative" style="z-index: 2;">
            <div class="row align-items-center gy-3 gy-lg-5">
                <!-- Left Column: Fest Branding, Title & Quote -->
                    <div class="col-lg-7 text-center text-lg-start animate-slide-up order-2 order-lg-1">
                        <!-- TOP: English Fest Name -->
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-start flex-wrap gap-2 mb-2">
                            <span class="text-white-50 fw-semibold"
                                style="font-size: clamp(0.95rem, 3vw, 1.25rem);">{{ $festName }}</span>
                        </div>

                        <!-- PRIMARY HEADING: Malayalam Anyayam -->
                        <h1 class="mb-2 lh-1 hero-anyayam"
                            style="font-family: 'Baloo Chettan 2', 'Manjari', sans-serif; font-size: clamp(3rem, 12vw, 6.5rem); font-weight: 800; color: #ef4444; text-shadow: 3px 3px 0px rgba(0,0,0,0.6), 0 0 40px rgba(239,68,68,0.3);">
                            അന്യായം
                        </h1>

                        <!-- UNDER FEST NAME: Malayalam Tagline from Poster -->
                        <div class="font-malayalam fw-bold mb-3 mb-lg-4"
                            style="color: #ff6b7a; letter-spacing: 0.5px; font-size: clamp(0.85rem, 2.5vw, 1.05rem);">
                            {{ $tagline ?? '"കല കലഹിക്കട്ടെ; സത്യം സംസാരിക്കട്ടെ"' }}
                        </div>

                        <p class="text-white-50 mb-4 d-none d-md-block"
                            style="max-width: 600px; font-size: clamp(0.9rem, 2vw, 1.1rem);">
                            Official festival scoreboards & live competition results across <strong>Bakalooriyya</strong>,
                            <strong>Sanaviyya Ulya</strong>, and <strong>General</strong> sections. Organized by
                            <strong>Manbaul
                                Afnan Students' Association (MASA)</strong>.
                        </p>

                        <!-- Action CTA Buttons -->
                        <div
                            class="hero-cta-wrap d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-2 gap-sm-3">
                            <a href="#leaderboard" class="btn btn-fest-primary btn-lg px-4 shadow-lg">
                                <i class="bi bi-trophy-fill me-2 text-warning"></i> Live Leaderboard
                            </a>
                            <a href="{{ route('public.results') }}" class="btn btn-outline-light rounded-pill px-4"
                                style="font-size: 1rem; padding-top: 0.65rem; padding-bottom: 0.65rem;">
                                <i class="bi bi-award me-1"></i> Results
                            </a>
                        </div>
                    </div>

                        <!-- Right Column: Clenched Fist Poster Visual & Stats (Top on mobile) -->
                        <div class="col-lg-5 text-center animate-slide-up order-1 order-lg-2"
                            style="animation-delay: 0.2s;">
                            <div class="position-relative d-inline-block">
                                <!-- Glow Behind Fist -->
                                <div
                                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width:
                                    240px; height: 240px; background: radial-gradient(circle, rgba(168,17,30,0.55) 0%,
                                    rgba(92,6,23,0.2) 50%, rgba(0,0,0,0) 75%); border-radius: 50%; filter: blur(20px);
                                    z-index: 0;">
                                </div>

                                <!-- Floating Clenched Fist Artwork -->
                                <div class="animate-float position-relative" style="z-index: 1;">
                                        <img src="{{ asset('images/logo.png') }}" alt="Masa Talents '26 Logo"
                                        class="img-fluid hero-logo-img"
                                        style="max-height: 220px; filter: drop-shadow(0 15px 25px rgba(0,0,0,0.7));">
                                </div>

                                <!-- MASA Banner Strip -->
                                <div class="bg-black bg-opacity-75 border border-danger border-opacity-25 rounded-4 p-2 px-3 mt-2 shadow-sm d-inline-block position-relative"
                                    style="z-index: 2;">
                                    <small class="text-white-50 fs-xs text-uppercase fw-bold d-block"
                                        style="letter-spacing: 1px; font-size: 0.65rem;">
                                        MANBAUL AFNAN STUDENTS' ASSOCIATION (MASA)
                                    </small>
                                </div>
                            </div>

                                        @php
                                            $heroStats = [
                                                ['label' => 'Groups', 'value' => $totalGroups, 'color' => '#ffffff'],
                                                ['label' => 'Events', 'value' => $totalPrograms, 'color' => '#ff6b7a'],
                                                ['label' => 'Done', 'value' => $completedPrograms, 'color' => '#198754'],
                                                ['label' => 'Sections', 'value' => $sections->count(), 'color' => '#ffc107'],
                                            ];
                                        @endphp

                                        <!-- Stats Grid Row (Desktop) -->
                                        <div class="row g-2 mt-2 pt-1 d-none d-lg-flex">
                                            @foreach($heroStats as $stat)
                                                    <div class="col-3">
                                                        <div class="p-2 rounded-3 text-center"
                                                            style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                                                            <span class="d-block text-white-50 text-uppercase"
                                                                style="font-size: 0.6rem;">{{ $stat['label'] }}</span>
                                                            <span class="fs-5 fw-bold"
                                                                style="color: {{ $stat['color'] }};">{{ $stat['value'] }}</span>
                                                        </div>
                                                    </div>
                                            @endforeach
                                            </div>
                                        </div>

                                        <!-- Stats Grid Row (Mobile bottom) -->
                                        <div class="col-12 d-lg-none order-3">
                                            <div class="row g-2 mt-1">
                                                @foreach($heroStats as $stat)
                                                        <div class="col-3">
                                                            <div class="p-2 rounded-3 text-center"
                                                                style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                                                                <span class="d-block text-white-50 text-uppercase"
                                                                    style="font-size: 0.6rem;">{{ $stat['label'] }}</span>
                                                                <span class="fs-5 fw-bold"
                                                                    style="color: {{ $stat['color'] }};">{{ $stat['value'] }}</span>
                                                            </div>
                                                        </div>
                                                @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
    </section>

    <!-- Podium & Leaderboard Section -->
    <section id="leaderboard" class="py-5" style="background: var(--fest-sand-light);">
        <div class="container">

            <!-- Section Tabs Navigation -->
            <div class="section-title-row d-flex flex-wrap justify-content-between align-items-center mb-3 mb-lg-4 gap-3">
                <div>
                    <h3 class="fw-extrabold mb-1 d-flex align-items-center gap-2"
                        style="font-size: clamp(1.1rem, 4vw, 1.5rem);">
                        <i class="bi bi-trophy-fill text-warning"></i>
                        <span>Festival Standings & Rankings</span>
                    </h3>
                    <p class="text-muted small mb-0 d-none d-sm-block">Official points awarded across completed events with
                        automated tie-breaking.</p>
                </div>

                <!-- Tab Pills — horizontally scrollable on mobile -->
                <div class="w-100 w-lg-auto overflow-auto" style="scrollbar-width: none;">
                    <ul class="nav nav-pills bg-white p-1 rounded-pill border shadow-sm flex-nowrap" id="pills-tab"
                        role="tablist" style="min-width: max-content;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold" id="pills-overall-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-overall" type="button" role="tab"
                                style="font-size: clamp(0.75rem, 2vw, 0.9rem); padding: 0.4rem 1rem;">
                                Overall
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="pills-bak-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-bak" type="button" role="tab"
                                style="font-size: clamp(0.75rem, 2vw, 0.9rem); padding: 0.4rem 1rem;">
                                Bakalooriyya
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="pills-san-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-san" type="button" role="tab"
                                style="font-size: clamp(0.75rem, 2vw, 0.9rem); padding: 0.4rem 1rem;">
                                Sanaviyya
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="pills-ind-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-ind" type="button" role="tab"
                                style="font-size: clamp(0.75rem, 2vw, 0.9rem); padding: 0.4rem 1rem;">
                                <i class="bi bi-person-badge-fill me-1"></i> Individual
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tab Contents -->
            <div class="tab-content" id="pills-tabContent">

                <!-- 1. Overall Tab -->
                <div class="tab-pane fade show active" id="pills-overall" role="tabpanel">
                    @include('public.partials.leaderboard_table', ['rankings' => $overallRankings, 'title' => 'Overall Festival Rankings'])
                </div>

                <!-- 2. Bakalooriyya Tab -->
                <div class="tab-pane fade" id="pills-bak" role="tabpanel">
                    @include('public.partials.leaderboard_table', ['rankings' => $bakalooriyyaRankings, 'title' => 'Bakalooriyya Section Rankings'])
                </div>

                <!-- 3. Sanaviyya Ulya Tab -->
                <div class="tab-pane fade" id="pills-san" role="tabpanel">
                    @include('public.partials.leaderboard_table', ['rankings' => $sanaviyyaRankings, 'title' => 'Sanaviyya Ulya Section Rankings'])
                </div>

                <!-- 4. Individual Score Leaderboard Tab -->
                <div class="tab-pane fade" id="pills-ind" role="tabpanel">
                    @include('partials.individual_leaderboard_table', ['individualRankings' => $individualRankings, 'title' => 'Individual Score Leaderboard'])
                </div>

            </div>

            <!-- Recent Published Results Row -->
            <div class="mt-4 mt-lg-5 pt-3 pt-lg-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h4 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: clamp(1rem, 3vw, 1.25rem);">
                        <i class="bi bi-clock-history text-crimson"></i> Latest Published Results
                    </h4>
                    <a href="{{ route('public.results') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                @if($latestResults->isEmpty())
                    <div class="fest-card p-4 text-center text-muted">
                        No results have been officially published yet.
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($latestResults as $res)
                            <div class="col-6 col-md-6 col-lg-3 result-card-col">
                                <div class="fest-card p-3 h-100 position-relative d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span
                                            class="badge bg-light text-dark border small">{{ $res->program->section->name }}</span>
                                        <span class="{{ $res->getPositionBadgeClass() }} rounded-pill px-2 py-1 fs-xs fw-bold">
                                            {{ $res->getPositionLabel() }}
                                        </span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: clamp(0.8rem, 2.5vw, 1rem);"
                                        title="{{ $res->program->name }}">
                                        {{ $res->program->name }}
                                    </h6>
                                    <p class="text-muted fs-xs mb-2 text-truncate">
                                        {{ $res->program->category->name }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between border-top pt-2 mt-auto">
                                        <div class="d-flex align-items-center gap-2">
                                            <span
                                                style="width: 10px; height: 10px; border-radius: 50%; background-color: {{ $res->group->color }};"></span>
                                            <span class="fw-bold small text-truncate"
                                                style="max-width: 100px;">{{ $res->group->name }}</span>
                                        </div>
                                        <span class="badge bg-crimson-subtle border border-danger-subtle fw-bold">
                                            +{{ $res->marks }} Pts
                                        </span>
                                    </div>
                                    <div class="text-muted fs-xs mt-1 text-truncate">
                                        <i class="bi bi-person me-1"></i>{{ $res->participant_name }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </section>
@endsection

@push('styles')
    <style>
        .nav-pills .nav-link {
            color: #475569;
            transition: all 0.25s ease;
        }

        .nav-pills .nav-link.active {
            background: var(--fest-primary-gradient) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px var(--fest-crimson-glow);
        }

        @media (max-width: 991.98px) {
            .hero-logo-img {
                max-height: 155px !important;
            }
        }
    </style>
@endpush