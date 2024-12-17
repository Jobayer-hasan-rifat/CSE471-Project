<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;

// Public Routes
Route::view('/', 'welcome')->name('welcome');

// Authentication Routes (already included via auth.php)
require __DIR__ . '/auth.php';

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Routes - Role Based
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/oca/dashboard', [DashboardController::class, 'ocaDashboard'])->middleware('role:oca')->name('oca.dashboard');
    Route::get('/club/dashboard', [DashboardController::class, 'clubDashboard'])->middleware('role:club')->name('club.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->middleware('role:admin')->name('admin.dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Event Management Routes
    Route::get('/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');
    Route::get('/events/reports', [EventController::class, 'reports'])->name('reports.events');
    Route::get('/events/book', [EventController::class, 'create'])->name('events.book');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/approve', [EventController::class, 'approve'])->name('events.approve');
    Route::post('/events/{event}/reject', [EventController::class, 'reject'])->name('events.reject');

    // Club Management Routes
    Route::middleware(['role:club'])->group(function () {
        Route::get('/club/members', [ClubController::class, 'members'])->name('club.members');
        Route::post('/club/members/add', [ClubController::class, 'addMember'])->name('club.members.add');
        Route::delete('/club/members/{member}', [ClubController::class, 'removeMember'])->name('club.members.remove');
        Route::post('/club/members/import', [ClubController::class, 'importMembers'])->name('club.members.import');
        Route::get('/club/members/export', [ClubController::class, 'exportMembers'])->name('club.members.export');
        Route::get('/club/activity-log', [ClubController::class, 'activityLog'])->name('club.activity');
    });

    // Event Booking Routes
    Route::prefix('events')->group(function () {
        // Viewing Events
        Route::get('/', [EventController::class, 'index'])->name('events.index');
        
        // Booking Management
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        
        // Document Upload
        Route::post('/{event}/documents', [EventController::class, 'uploadDocuments'])->name('events.documents.upload');
        
        // OCA Approval Routes
        Route::middleware(['role:oca'])->group(function () {
            Route::get('/pending', [EventController::class, 'pendingApprovals'])->name('events.pending');
        });
    });

    // Venue Management Routes
    Route::prefix('venues')->middleware(['role:oca'])->group(function () {
        Route::get('/', [VenueController::class, 'index'])->name('venues.index');
        Route::get('/create', [VenueController::class, 'create'])->name('venues.create');
        Route::post('/', [VenueController::class, 'store'])->name('venues.store');
        Route::get('/{venue}/edit', [VenueController::class, 'edit'])->name('venues.edit');
        Route::put('/{venue}', [VenueController::class, 'update'])->name('venues.update');
        Route::delete('/{venue}', [VenueController::class, 'destroy'])->name('venues.destroy');
    });

    // Reports and Analytics Routes
    Route::prefix('reports')->middleware(['role:oca,admin'])->group(function () {
        Route::get('/venues', [VenueController::class, 'reports'])->name('reports.venues');
        Route::get('/clubs', [ClubController::class, 'reports'])->name('reports.clubs');
    });
});
