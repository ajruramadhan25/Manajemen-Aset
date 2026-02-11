<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssetLoanController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if (($user->role ?? 'karyawan') === 'karyawan') {
        $myBorrowed = \App\Models\AssetLoan::where('user_id', $user->id)->where('status', 'borrowed')->count();
        $myBorrowedUnits = \App\Models\AssetLoan::where('user_id', $user->id)->where('status', 'borrowed')->sum('quantity_borrowed');
        $myReturned = \App\Models\AssetLoan::where('user_id', $user->id)->where('status', 'returned')->count();

        return view('dashboard', compact('myBorrowed', 'myBorrowedUnits', 'myReturned'));
    }

    $totalAssets = \App\Models\Asset::count(); // number of asset types
    $totalUnits = \App\Models\AssetUnit::count(); // total individual units
    $availableUnits = \App\Models\AssetUnit::where('status', 'available')->count();
    $borrowedUnits = \App\Models\AssetUnit::where('status', 'borrowed')->count(); // units currently in use
    $deployedAssets = \App\Models\Asset::where('status', 'deployed')->count(); // assets fully deployed

    return view('dashboard', compact('totalAssets', 'totalUnits', 'availableUnits', 'borrowedUnits', 'deployedAssets'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('inventory', AssetController::class)
        ->names('assets')
        ->parameters(['inventory' => 'asset'])
        ->only(['index', 'show'])
        ->whereNumber('asset');

    // Loan Routes
    Route::get('inventory/{asset}/checkout', [AssetLoanController::class, 'create'])->name('loans.create');
    Route::post('inventory/{asset}/checkout', [AssetLoanController::class, 'store'])->name('loans.store');
    Route::get('loans', [AssetLoanController::class, 'index'])->name('loans.index');
    Route::get('loans/returned', [AssetLoanController::class, 'returnedIndex'])->name('loans.returned');
    Route::get('loans/{loan}', [AssetLoanController::class, 'show'])->name('loans.show');
    Route::post('loans/{loan}/return', [AssetLoanController::class, 'return'])->name('loans.return');
    Route::get('loans/{loan}/return-units', [AssetLoanController::class, 'showReturnUnitsForm'])->name('loans.return.units.form');
    Route::post('loans/{loan}/return-units', [AssetLoanController::class, 'returnUnits'])->name('loans.return.units');

    Route::middleware('role:admin,super_admin')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

        Route::resource('inventory', AssetController::class)
            ->names('assets')
            ->parameters(['inventory' => 'asset'])
            ->except(['index', 'show']);

        // AssetUnit management
        Route::resource('units', \App\Http\Controllers\AssetUnitController::class);
        Route::post('units/{unit}/retire', [\App\Http\Controllers\AssetUnitController::class, 'retire'])->name('units.retire');
        Route::post('units/{unit}/maintenance', [\App\Http\Controllers\AssetUnitController::class, 'maintenance'])->name('units.maintenance');
        Route::post('units/{unit}/available', [\App\Http\Controllers\AssetUnitController::class, 'available'])->name('units.available');

        Route::resource('categories', CategoryController::class);
        Route::resource('brands', BrandController::class);
    });

    Route::middleware('role:super_admin')->group(function () {
        Route::get('admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::patch('admin/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle');
        Route::delete('admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });
});

require __DIR__ . '/auth.php';
