<?php

use App\Http\Controllers\Auth\DiscordAuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\RosterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogController::class, 'index'])->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/auth/discord/redirect', [DiscordAuthController::class, 'redirect'])->name('auth.discord.redirect');
    Route::get('/auth/discord/callback', [DiscordAuthController::class, 'callback'])->name('auth.discord.callback');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [DiscordAuthController::class, 'logout'])->name('logout');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar/events', [CalendarController::class, 'store'])
        ->middleware('calendar.admin')
        ->name('calendar.store');
});

Route::get('/roster', [RosterController::class, 'index'])->name('roster.index');
