<?php

use App\Http\Controllers\EconomicactivityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SaleController;
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
    Route::get('getclientbycompany/{company}', [ClientController::class, 'getclientbycompany'])->name('getclientbycompany');
    Route::get('view/{client}', [CompanyController::class, 'show'])->name('view');
    Route::get('edit/{client}', [ClientController::class, 'edit'])->name('edit');
    Route::get('getClientid/{client}', [ClientController::class, 'getClientid'])->name('getClientid');
    Route::get('gettypecontri/{client}', [ClientController::class, 'gettypecontri'])->name('gettypecontri');
    Route::patch('update', [ClientController::class, 'update'])->name('update');
    Route::get('create', [ClientController::class, 'create'])->name('create');
    Route::post('store', [ClientController::class, 'store'])->name('store');
    Route::get('destroy/{client}', [ClientController::class, 'destroy'])->name('destroy');

    });

Route::group(['prefix' => 'company', 'as' => 'company.'], function(){

    Route::get('index', [CompanyController::class, 'index'])->name('index');
    Route::get('view/{company}', [CompanyController::class, 'show'])->name('view');
    Route::get('getCompany', [CompanyController::class, 'getCompany'])->name('getCompany');
    Route::get('getCompanybyuser/{iduser}', [CompanyController::class, 'getCompanybyuser'])->name('getCompanybyuser');
    Route::get('gettypecontri/{company}', [CompanyController::class, 'gettypecontri'])->name('gettypecontri');
    Route::get('getCompanytag', [CompanyController::class, 'getCompanytag'])->name('getCompanytag');
    Route::get('getCompanyid/{company}', [CompanyController::class, 'getCompanyid'])->name('getCompanyid');
    Route::post('store', [CompanyController::class, 'store'])->name('store');
    Route::patch('update', [CompanyController::class, 'update'])->name('update');
    Route::get('destroy/{company}', [CompanyController::class, 'destroy'])->name('destroy');

    });

    Route::get('getcountry', [CountryController::class, 'getcountry'])->name('getcountry');
    Route::get('getdepartment/{pais}', [DepartmentController::class, 'getDepartment'])->name('getDepartment');
    Route::get('getmunicipality/{dep}', [MunicipalityController::class, 'getMunicipality'])->name('getmunicipios');
    Route::get('geteconomicactivity/{pais}', [EconomicactivityController::class, 'geteconomicactivity'])->name('geteconomicactivity');
    Route::get('getroles', [RolController::class, 'getRoles'])->name('getroles');

Route::group(['prefix' => 'user', 'as' => 'user.'], function(){
    Route::get('index', [UserController::class, 'index'])->name('index');
    Route::get('getusers', [UserController::class, 'getusers'])->name('getusers');
    Route::get('getuserid/{user}', [UserController::class, 'getuserid'])->name('getuserid');
    Route::get('valmail/{mail}', [UserController::class, 'valmail'])->name('valmail');
    Route::post('store', [UserController::class, 'store'])->name('store');
    Route::patch('update', [UserController::class, 'update'])->name('update');
    Route::get('changedtatus/{user}/status/{status}', [UserController::class, 'changedtatus'])->name('changedtatus');
    Route::get('destroy/{user}', [UserController::class, 'destroy'])->name('destroy');

    });

Route::group(['prefix' => 'rol', 'as' => 'rol.'], function(){
    Route::get('index', [RolController::class, 'index'])->name('index');
    Route::patch('update', [RolController::class, 'update'])->name('update');
    Route::post('store', [RolController::class, 'store'])->name('store');

    });

Route::group(['prefix' => 'permission', 'as' => 'permission.'], function(){
    Route::get('index', [PermissionController::class, 'index'])->name('index');
    Route::patch('update', [PermissionController::class, 'update'])->name('update');
    Route::post('store', [PermissionController::class, 'store'])->name('store');
    Route::get('destroy', [PermissionController::class, 'destroy'])->name('destroy');
    Route::get('getpermission', [PermissionController::class, 'getpermission'])->name('getpermission');

    });
Route::group(['prefix' => 'provider', 'as' => 'provider.'], function(){
        Route::get('index', [ProviderController::class, 'index'])->name('index');
        Route::get('getproviders', [ProviderController::class, 'getproviders'])->name('getproviders');
        Route::get('getproviderid/{id}', [ProviderController::class, 'getproviderid'])->name('getproviderid');
        Route::patch('update', [ProviderController::class, 'update'])->name('update');
        Route::post('store', [ProviderController::class, 'store'])->name('store');
        Route::get('destroy/{id}', [ProviderController::class, 'destroy'])->name('destroy');
        Route::get('getpermission', [ProviderController::class, 'getpermission'])->name('getpermission');

    });

Route::group(['prefix' => 'product', 'as' => 'product.'], function(){
        Route::get('index', [ProductController::class, 'index'])->name('index');
        Route::get('getproductid/{id}', [ProductController::class, 'getproductid'])->name('getproductid');
        Route::get('getproductall', [ProductController::class, 'getproductall'])->name('getproductall');
        Route::patch('update', [ProductController::class, 'update'])->name('update');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::get('destroy/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('getpermission', [ProductController::class, 'getpermission'])->name('getpermission');

    });

Route::group(['prefix' => 'sale', 'as' => 'sale.'], function(){
        Route::get('index', [SaleController::class, 'index'])->name('index');
        Route::get('create', [SaleController::class, 'create'])->name('create');
        Route::get('getproductid/{id}', [SaleController::class, 'getproductid'])->name('getproductid');
        Route::get('getproductbyid/{id}', [SaleController::class, 'getproductbyid'])->name('getproductbyid');
        Route::get('getdatadocbycorr/{corr}', [SaleController::class, 'getdatadocbycorr'])->name('getdatadocbycorr');
        Route::get('getdatadocbycorr2/{corr}', [SaleController::class, 'getdatadocbycorr2'])->name('getdatadocbycorr2');
        Route::patch('update', [SaleController::class, 'update'])->name('update');
        Route::post('store', [SaleController::class, 'store'])->name('store');
        Route::get('createdocument/{corr}/{amount}', [SaleController::class, 'createdocument'])->name('createdocument');
        Route::get('destroy/{id}', [SaleController::class, 'destroy'])->name('destroy');
        Route::get('savefactemp/{idsale}/{clientid}/{productid}/{cantida}/{price}/{nosujeto}/{exento}/{gravado}/{iva}/{retenido}/{acuenta}/{fpago}', [SaleController::class, 'savefactemp'])->name('savefactemp');
        Route::get('newcorrsale/{idempresa}/{iduser}/{typedocument}', [SaleController::class, 'newcorrsale'])->name('newcorrsale');
        Route::get('getdetailsdoc/{corr}', [SaleController::class, 'getdetailsdoc'])->name('getdetailsdoc');
        Route::get('destroysaledetail/{idsaledetail}', [SaleController::class, 'destroysaledetail'])->name('destroysaledetail');

    });
});



require __DIR__.'/auth.php';
