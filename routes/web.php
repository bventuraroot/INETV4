<?php

use App\Http\Controllers\EconomicactivityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


Route::group(['prefix' => 'client', 'as' => 'client.'], function(){

    Route::get('index/{company?}', [ClientController::class, 'index'])->name('index');
    Route::get('view/{client}', [CompanyController::class, 'show'])->name('view');
    Route::get('edit/{client}', [ClientController::class, 'edit'])->name('edit');
    Route::get('getClientid/{client}', [ClientController::class, 'getClientid'])->name('getClientid');
    Route::patch('update', [ClientController::class, 'update'])->name('update');
    Route::get('create', [ClientController::class, 'create'])->name('create');
    Route::post('store', [ClientController::class, 'store'])->name('store');
    Route::get('destroy/{client}', [ClientController::class, 'destroy'])->name('destroy');

    });

Route::group(['prefix' => 'company', 'as' => 'company.'], function(){

    Route::get('index', [CompanyController::class, 'index'])->name('index');
    Route::get('view/{company}', [CompanyController::class, 'show'])->name('view');
    Route::get('getCompany', [CompanyController::class, 'getCompany'])->name('getCompany');
    Route::get('getCompanyid/{company}', [CompanyController::class, 'getCompanyid'])->name('getCompanyid');
    Route::post('store', [CompanyController::class, 'store'])->name('store');
    Route::patch('update', [CompanyController::class, 'update'])->name('update');
    Route::get('destroy/{company}', [CompanyController::class, 'destroy'])->name('destroy');

    });

    Route::get('getcountry', [CountryController::class, 'getcountry'])->name('getcountry');
    Route::get('getdepartment/{pais}', [DepartmentController::class, 'getDepartment'])->name('getDepartment');
    Route::get('getmunicipality/{dep}', [MunicipalityController::class, 'getMunicipality'])->name('getmunicipios');
    Route::get('geteconomicactivity/{pais}', [EconomicactivityController::class, 'geteconomicactivity'])->name('geteconomicactivity');

Route::group(['prefix' => 'user', 'as' => 'user.'], function(){
    Route::get('index', [UserController::class, 'index'])->name('index');
    Route::get('roles/index', [RolController::class, 'index'])->name('roles/index');
    Route::get('permissions/index', [PermissionController::class, 'index'])->name('permissions/index');

    });
});



require __DIR__.'/auth.php';
