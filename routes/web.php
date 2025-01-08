<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ClubChatController;
use App\Http\Controllers\ClubCalendarController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OcaEventController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OcaChatController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ClubPositionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Explicit welcome page route
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome.page');

// Authentication Routes
require __DIR__.'/auth.php';

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        
        // Resource Routes
        Route::resource('venues', VenueController::class);
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('clubs', ClubController::class);
        Route::resource('logs', LogController::class);
        Route::resource('events', EventController::class)->only(['index', 'show']);

        // System Logs
        Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
        Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');
    });

    // OCA Routes
    Route::middleware(['role:oca'])->prefix('oca')->name('oca.')->group(function () {
        Route::get('/', [DashboardController::class, 'ocaDashboard'])->name('dashboard');
        Route::get('/dashboard/content', [DashboardController::class, 'ocaDashboardContent'])->name('dashboard.content');
        
        // Event Management
        Route::get('/events/pending', [OcaEventController::class, 'index'])->name('events.pending');
        Route::get('/events/{event}', [OcaEventController::class, 'show'])->name('events.show');
        Route::post('/events/{event}/approve', [OcaEventController::class, 'approve'])->name('events.approve');
        Route::post('/events/{event}/reject', [OcaEventController::class, 'reject'])->name('events.reject');

        // Clubs
        Route::prefix('clubs')->name('clubs.')->group(function () {
            Route::get('/', [ClubController::class, 'ocaIndex'])->name('index');
            Route::get('/{club}', [ClubController::class, 'ocaShow'])->name('show');
        });

        // OCA Chat Routes
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [OcaChatController::class, 'index'])->name('index');
            Route::get('/{club}', [OcaChatController::class, 'show'])->name('show');
            Route::get('/{club}/messages', [OcaChatController::class, 'getMessages'])->name('messages');
            Route::post('/send', [OcaChatController::class, 'sendMessage'])->name('send');
        });

        // Calendar Routes
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

        // Venue Routes
        Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
        Route::get('/venues/{venue}', [VenueController::class, 'show'])->name('venues.show');
        Route::get('/venues/{venue}/bookings', [VenueController::class, 'bookings'])->name('venues.bookings');

        // Transaction Routes
        Route::get('/transactions', [TransactionController::class, 'ocaIndex'])->name('transactions.index');
        Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');

        // Announcement Routes
        Route::resource('announcements', AnnouncementController::class);
    });

    // Club Routes
    Route::middleware(['auth', 'role:club|bucc|robu|buac|bizbee'])->prefix('club')->name('club.')->group(function () {
        Route::get('/', [DashboardController::class, 'clubDashboard'])->name('dashboard');
        
        // Club Information
        Route::get('/information', [ClubController::class, 'clubInformation'])->name('information');

        // Club Calendar Routes
        Route::get('/club/calendar', [ClubCalendarController::class, 'index'])->name('club.calendar.index');
        Route::get('/club/calendar/events', [ClubCalendarController::class, 'events'])->name('club.calendar.events');

        // Calendar routes
        Route::get('/calendar', [ClubCalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [ClubCalendarController::class, 'events'])->name('calendar.events');
        
        // Events routes
        Route::resource('events', EventController::class);
        
        // Club Chat Routes
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [ClubChatController::class, 'index'])->name('index');
            Route::get('/messages', [ClubChatController::class, 'getMessages'])->name('messages');
            Route::post('/send', [ClubChatController::class, 'sendMessage'])->name('send');
        });

        // Transaction routes
        Route::resource('transactions', TransactionController::class);
        
        // Announcement routes - clubs can only view announcements
        Route::get('/announcements', [AnnouncementController::class, 'clubAnnouncements'])->name('announcements.index');

        // Club Position Routes
        Route::post('/positions', [ClubPositionController::class, 'store'])->name('positions.store');
        Route::get('/positions/{position}', [ClubPositionController::class, 'show'])->name('positions.show');
        Route::put('/positions/{position}', [ClubPositionController::class, 'update'])->name('positions.update');
        Route::delete('/positions/{position}', [ClubPositionController::class, 'destroy'])->name('positions.destroy');
    });

    // Shared Routes (OCA and Clubs)
    Route::middleware('role:oca|bucc|buac|robu|bizbee')->group(function () {
        // Documents
        Route::post('/events/{event}/documents', [EventController::class, 'uploadDocuments'])->name('events.documents.upload');
        Route::delete('/events/{event}/documents/{document}', [EventController::class, 'deleteDocument'])->name('events.documents.delete');
    });

    // Force logout route
    Route::get('/force-logout', function () {
        Auth::logout();
        Session::flush();
        Cookie::queue(Cookie::forget('remember_web'));
        return redirect('/login')->with('message', 'You have been logged out.');
    });
});
