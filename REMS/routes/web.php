<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\GuestPassController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // OCA Routes
    Route::prefix('oca')->middleware(['role:oca'])->name('oca.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'ocaDashboard'])->name('dashboard');

        // Calendar Routes
        Route::prefix('calendar')->group(function () {
            Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
            Route::post('/events', [CalendarController::class, 'events'])->name('calendar.events');
        });

        // Events
        Route::get('/events/pending', [EventController::class, 'pendingEvents'])->name('oca.events.pending');
        Route::get('/events/approved', [EventController::class, 'approvedEvents'])->name('oca.events.approved');
        Route::post('/events/{event}/approve', [EventController::class, 'approve'])->name('oca.events.approve');
        Route::post('/events/{event}/reject', [EventController::class, 'reject'])->name('oca.events.reject');
        Route::resource('events', EventController::class);
        
        // Clubs
        Route::prefix('clubs')->middleware(['role:bucc,robu,bizbee,buedf'])->name('clubs.')->group(function () {
            Route::get('/', [ClubController::class, 'index'])->name('index');
            Route::get('/{club}/events', [ClubController::class, 'events'])->name('events');
            Route::get('/{club}/members', [ClubController::class, 'members'])->name('members');
            Route::resource('clubs', ClubController::class);
        });
        
        // Venues
        Route::get('/venues/{venue}/bookings', [VenueController::class, 'bookings'])->name('venues.bookings');
        Route::resource('venues', VenueController::class);
        
        // Budgets
        Route::get('/budgets/{club}/allocate', [BudgetController::class, 'allocate'])->name('budgets.allocate');
        Route::post('/budgets/{club}/store', [BudgetController::class, 'storeBudget'])->name('budgets.store-allocation');
        Route::resource('budgets', BudgetController::class);
    });

    // Club Routes
    Route::prefix('club')->middleware(['role:bucc,robu,bizbee,buedf'])->name('club.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'clubDashboard'])->name('dashboard');
        Route::get('/events/pending', [EventController::class, 'pendingEvents'])->name('events.pending');
        Route::get('/events/approved', [EventController::class, 'approvedEvents'])->name('events.approved');
        Route::resource('events', EventController::class);
        
        // Members
        Route::post('/members/{member}/approve', [MemberController::class, 'approve'])->name('members.approve');
        Route::resource('members', MemberController::class);
        
        // Calendar Routes
        Route::prefix('calendar')->group(function () {
            Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
            Route::post('/events', [CalendarController::class, 'events'])->name('calendar.events');
        });
        
        // Budget & Transactions
        Route::resource('transactions', TransactionController::class);
        Route::get('/budget', [BudgetController::class, 'clubBudget'])->name('budget');
        Route::resource('transactions', TransactionController::class);
        
        // Announcements
        Route::resource('announcements', AnnouncementController::class);
        
        // Guest Passes
        Route::resource('guest-passes', GuestPassController::class);
        
        // Chat
        Route::get('/chat', [ChatController::class, 'index'])->name('chat');
        Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    });

    // Admin Routes
    Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
