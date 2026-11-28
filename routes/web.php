<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\RankingController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Group\AuthController as GroupAuthController;
use App\Http\Controllers\Group\PortalController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Festival Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::get('/results', [PublicController::class, 'results'])->name('public.results');
Route::get('/rankings', [PublicController::class, 'rankings'])->name('public.rankings');
Route::get('/programs', [PublicController::class, 'programs'])->name('public.programs');

/*
|--------------------------------------------------------------------------
| Super Admin Authentication & Management
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('super_admin')->group(function () {
        Route::get('/', fn() => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Sections
        Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
        Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
        Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');

        // Groups
        Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
        Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
        Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
        Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
        Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
        Route::post('/groups/{group}/reset-password', [GroupController::class, 'resetPassword'])->name('groups.reset-password');
        Route::match(['post', 'patch'], '/groups/{group}/toggle-status', [GroupController::class, 'toggleStatus'])->name('groups.toggle-status');
        Route::match(['post', 'patch'], '/groups/{group}/toggle', [GroupController::class, 'toggleStatus'])->name('groups.toggle');
        Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

        // Members
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
        Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
        Route::get('/members/export/csv', [MemberController::class, 'export'])->name('members.export');

        // Categories & Position Marks
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Programs
        Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
        Route::get('/programs/create', [ProgramController::class, 'create'])->name('programs.create');
        Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
        Route::get('/programs/{program}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
        Route::put('/programs/{program}', [ProgramController::class, 'update'])->name('programs.update');
        Route::delete('/programs/{program}', [ProgramController::class, 'destroy'])->name('programs.destroy');

        // Event Registrations
        Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
        Route::get('/registrations/create', [RegistrationController::class, 'create'])->name('registrations.create');
        Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');
        Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');
        Route::post('/registrations/toggle-status', [RegistrationController::class, 'toggleStatus'])->name('registrations.toggle-status');
        Route::get('/registrations/eligible-members', [RegistrationController::class, 'getEligibleMembers'])->name('registrations.eligible-members');

        // Results
        Route::get('/results', [ResultController::class, 'index'])->name('results.index');
        Route::get('/results/create', [ResultController::class, 'create'])->name('results.create');
        Route::post('/results', [ResultController::class, 'store'])->name('results.store');
        Route::get('/results/program-participants/{program}', [ResultController::class, 'getProgramParticipants'])->name('results.program-participants');
        Route::get('/results/{result}/edit', [ResultController::class, 'edit'])->name('results.edit');
        Route::put('/results/{result}', [ResultController::class, 'update'])->name('results.update');
        Route::delete('/results/{result}', [ResultController::class, 'destroy'])->name('results.destroy');
        Route::match(['post', 'patch'], '/results/{result}/toggle-publish', [ResultController::class, 'togglePublish'])->name('results.toggle-publish');
        Route::post('/results/publish-all', [ResultController::class, 'publishAllDrafts'])->name('results.publish-all');
        Route::get('/results/category-marks/{program}/{position}', [ResultController::class, 'getCategoryMarks'])->name('results.category-marks');

        // Rankings & Printable Reports
        Route::get('/rankings', [RankingController::class, 'index'])->name('rankings.index');
        Route::get('/rankings/print', [RankingController::class, 'printView'])->name('rankings.print');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Admin Change Own Password
        Route::get('/change-password', [AdminAuthController::class, 'showChangePassword'])->name('password.edit');
        Route::post('/change-password', [AdminAuthController::class, 'updatePassword'])->name('password.update');
    });
});

/*
|--------------------------------------------------------------------------
| Individual Group Portal
|--------------------------------------------------------------------------
*/
Route::prefix('group')->name('group.')->group(function () {
    Route::get('/login', [GroupAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [GroupAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [GroupAuthController::class, 'logout'])->name('logout');

    Route::middleware('group_user')->group(function () {
        Route::get('/', fn() => redirect()->route('group.dashboard'));
        Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [PortalController::class, 'profile'])->name('profile');

        // Change Group Password
        Route::get('/change-password', [GroupAuthController::class, 'showChangePassword'])->name('password.edit');
        Route::post('/change-password', [GroupAuthController::class, 'updatePassword'])->name('password.update');

        // Group Members
        Route::get('/members', [PortalController::class, 'members'])->name('members');
        Route::post('/members', [PortalController::class, 'storeMember'])->name('members.store');
        Route::delete('/members/{member}', [PortalController::class, 'destroyMember'])->name('members.destroy');

        // Group Programs
        Route::get('/programs', [PortalController::class, 'programs'])->name('programs');

        // Group Event Registrations
        Route::get('/registrations', [PortalController::class, 'registrations'])->name('registrations.index');
        Route::post('/registrations', [PortalController::class, 'storeRegistration'])->name('registrations.store');
        Route::delete('/registrations/{registration}', [PortalController::class, 'destroyRegistration'])->name('registrations.destroy');
        Route::get('/registrations/eligible-members/{program}', [PortalController::class, 'getEligibleMembersForProgram'])->name('registrations.eligible-members');

        // Group Results
        Route::get('/results', [PortalController::class, 'results'])->name('results');

        // Group Rankings
        Route::get('/rankings', [PortalController::class, 'rankings'])->name('rankings');
    });
});
