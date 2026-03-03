<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\DeviseController;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\VilleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategorieController;   
use App\Http\Controllers\TypesDispositifController;
use App\Http\Controllers\DispositifController;
use App\Http\Controllers\DispositifPhotoController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\SysParamController;
use App\Http\Controllers\LocalisationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\NotificationController;

Route::get('/change-pays/{pays}', [PaysController::class, 'change'])->name('change.pays');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Afficher une publication spécifique
Route::get('/publications/{publication}', [HomeController::class, 'show'])
    ->name('publications.show');

// Formulaire pour créer une réservation depuis une publication
Route::get('/publications/{publication}/reservation', [HomeController::class, 'createReservation'])
    ->name('reservations.create');

// Stocker la réservation
Route::post('/publications/{publication}/reservation', [HomeController::class, 'storeReservation'])
    ->name('reservations.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::get('/pays/create', [PaysController::class, 'create'])->name('pays.create');
    Route::post('/pays', [PaysController::class, 'store'])->name('pays.store');
    Route::get('/pays/{pays}/edit', [PaysController::class, 'edit'])->name('pays.edit');
    Route::put('/pays/{pays}', [PaysController::class, 'update'])->name('pays.update');
    Route::delete('/pays/{pays}', [PaysController::class, 'destroy'])->name('pays.destroy');
});
Route::middleware(['auth','role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('devises', DeviseController::class);
    Route::resource('pays', PaysController::class)->parameters(['pays' => 'pays']);
    Route::resource('regions', RegionController::class);
    Route::resource('villes', VilleController::class);
    Route::resource('users', UserController::class);
    Route::resource('categories', CategorieController::class);    
    Route::resource('types_dispositifs', TypesDispositifController::class);
    Route::resource('sys_params', SysParamController::class);
});

Route::middleware(['auth', 'role:Admin,User'])->prefix('user')->name('user.')->group(function () {
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('dispositifs', [DispositifController::class, 'index'])->name('dispositifs.index');
    Route::get('dispositifs/create', [DispositifController::class, 'create'])->name('dispositifs.create');
    Route::post('dispositifs', [DispositifController::class, 'store'])->name('dispositifs.store');
    Route::get('dispositifs/{dispositif}/edit', [DispositifController::class, 'edit'])->name('dispositifs.edit');
    Route::put('dispositifs/{dispositif}', [DispositifController::class, 'update'])->name('dispositifs.update');
    Route::delete('dispositifs/{dispositif}', [DispositifController::class, 'destroy'])->name('dispositifs.destroy');
    // Page "Voir plus"
    Route::get('dispositifs/{dispositif}/show', [DispositifController::class, 'show'])->name('dispositifs.show');
    
    Route::resource('publications', PublicationController::class);
    // Création d'une publication à partir d'un dispositif existant
    Route::get('publications/create/{dispositif}', [PublicationController::class, 'createByDispositif'])->name('publications.createByDispositif');
    
    Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{publication}/create',[ReservationController::class, 'create'])->name('reservations.create');
    Route::get('reservations/{id}/approve', [ReservationController::class, 'approveForm'])->name('reservations.approve.form');
    Route::post('reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::get('reservations/{id}/reject', [ReservationController::class, 'rejectForm'])->name('reservations.reject.form');
    Route::post('reservations/{id}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');
    Route::get('reservations/{reservation}/show', [ReservationController::class, 'show'])->name('reservations.show');
    
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('notification', [NotificationController::class, 'store'])->name('notifications.store');
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{transaction}/show', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('transactions/{user}/depot', [TransactionController::class, 'deposit'])->name('transactions.deposit');
    Route::post('transactions/depot', [TransactionController::class, 'storeDeposit'])->name('transactions.storeDeposit');
    Route::get('/transactions/retrait', [TransactionController::class, 'retrait'])->name('transactions.retrait');
    Route::post('/transactions/retrait', [TransactionController::class, 'storeRetrait'])->name('transactions.storeRetrait');
});

Route::middleware(['auth'])->group(function () {

    Route::delete(
        '/user/dispositifs/photos/{photo}',
        [DispositifPhotoController::class, 'destroy']
    )->name('user.dispositifs.photos.destroy');

});

Route::get('/ajax/types-dispositifs/{categorie}', function ($categorie) {
    return \App\Models\TypesDispositif::where('categorie_id', $categorie)
        ->select('id','nom')
        ->get();
})->middleware('auth');

Route::get('/dispositifs/{dispositif}/tarif-min', function (\App\Models\Dispositif $dispositif) {
    return response()->json([
        'tarif_min' => $dispositif->type_dispositif->tarif_min
    ]);
});

Route::get('/pays/by-continent/{continent}', [LocalisationController::class, 'paysByContinent']);
Route::get('/regions/by-pays/{pays}', [LocalisationController::class, 'regionsByPays']);
Route::get('/villes/by-region/{region}', [LocalisationController::class, 'villesByRegion']);

Route::get('/types_dispositif/by-categorie/{categorie}', [TypesDispositifController::class, 'typesByCategorie']);
Route::get('/types_dispositif/search', [TypesDispositifController::class, 'search']);
Route::get('/types_dispositif/{id}', [TypesDispositifController::class, 'show']);
Route::get('/types_dispositif/{id}/params', [TypesDispositifController::class, 'params']);