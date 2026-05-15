<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/locale/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'hi', 'bn'])) {
        session(['locale' => $lang]);
    }
    return back();
})->name('locale.switch');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role ?? 'farmer';
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'proposer') return redirect()->route('proposer.dashboard');
        return redirect()->route('farmer.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    });

    Route::post('/notifications/{notification}/read', function (\App\Models\Notification $notification) {
        if ($notification->user_id === auth()->id()) {
            $notification->update(['is_read' => true]);
        }
        return back();
    })->name('notifications.read');

    // Proposer Routes
    Route::middleware('proposer')->prefix('proposer')->name('proposer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\ProposerController::class, 'dashboard'])->name('dashboard');
        Route::post('/profile/complete', [\App\Http\Controllers\ProposerController::class, 'storeProfile'])->name('profile.store');
        Route::resource('plans', \App\Http\Controllers\InsurancePlanController::class);
        Route::get('/claims', [\App\Http\Controllers\ProposerController::class, 'claims'])->name('claims.index');
        Route::get('/claims/{claim}', [\App\Http\Controllers\ProposerController::class, 'showClaim'])->name('claims.show');
        Route::post('/claims/{claim}/status', [\App\Http\Controllers\ProposerController::class, 'updateClaimStatus'])->name('claims.update');
        Route::get('/policies', [\App\Http\Controllers\ProposerController::class, 'policies'])->name('policies.index');
        Route::post('/policies/{policy}/status', [\App\Http\Controllers\ProposerController::class, 'updatePolicyStatus'])->name('policies.update');
    });

    // Farmer Routes
    Route::middleware('farmer')->prefix('farmer')->name('farmer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\FarmerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile/complete', [\App\Http\Controllers\FarmerController::class, 'completeProfile'])->name('profile');
        Route::post('/profile/complete', [\App\Http\Controllers\FarmerController::class, 'storeProfile'])->name('profile.store');
        Route::get('/plans', [\App\Http\Controllers\FarmerController::class, 'plans'])->name('plans');
        Route::post('/plans/compare', [\App\Http\Controllers\FarmerController::class, 'compare'])->name('plans.compare');
        Route::post('/plans/{plan}/apply', [\App\Http\Controllers\FarmerController::class, 'apply'])->name('plans.apply');
        Route::get('/policies', [\App\Http\Controllers\FarmerController::class, 'policies'])->name('policies');
        Route::get('/policies/{policy}/pdf', [\App\Http\Controllers\FarmerController::class, 'downloadPolicyPdf'])->name('policies.pdf');
        Route::resource('claims', \App\Http\Controllers\ClaimController::class);
    });
});

require __DIR__.'/auth.php';
