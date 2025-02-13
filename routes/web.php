<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\QuestionController;
use App\Http\Controllers\Web\AdvertisementController;
use App\Http\Controllers\Web\StoreController;
use App\Http\Controllers\Web\TransferController;
use App\Http\Controllers\Web\PrivilegeController;
use App\Http\Controllers\Web\UserPrivilegeController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\ChangePasswordController;
use App\Http\Controllers\Web\Auth\DeleteAccountController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\Privileges\AdvertisementsMiddleware;
use App\Http\Middleware\Privileges\PrivilegesMiddleware;
use App\Http\Middleware\Privileges\QuestionsMiddleware;
use App\Http\Middleware\Privileges\TransfersMiddleware;
use App\Http\Middleware\Privileges\UsersMiddleware;
use App\Http\Middleware\Privileges\StoreMiddleware;
use Illuminate\Support\Facades\Auth;

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
    return redirect('login');
});


Auth::routes();

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/auth/google', [DeleteAccountController::class, 'google'])->name('google');

Route::get('/auth/google/callback', [DeleteAccountController::class, 'googleCallback'])->name('googleCallback');



Route::middleware([IsAdmin::class])->group(function () {


    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Transfer Routes

    Route::middleware([TransfersMiddleware::class])->group(function () {

        Route::get('/transfer', [TransferController::class, 'transfer'])->name('transfer');

        Route::get('/transferRequest', [TransferController::class, 'transfer_request'])->name('transferRequest');

        Route::post('/deleteTransfer/{id}', [TransferController::class, 'destroy_transfer'])->name('deleteTransfer');

        Route::post('/deleteTransferRequest/{id}', [TransferController::class, 'destroy_transfer_request'])->name('deleteTransferRequest');

        Route::post('/transferDone/{id}', [TransferController::class, 'transfer_done'])->name('transferDone');

        Route::get('/points', [TransferController::class, 'points'])->name('points');

        Route::post('/updatePoints', [TransferController::class, 'update_point'])->name('updatePoint');
    });




    // Questions Routes

    Route::middleware([QuestionsMiddleware::class])->group(function () {

        Route::resource('question', QuestionController::class);

        Route::post('/question/status', [QuestionController::class, 'status'])->name('questionStatus');
    });




    // Users Routes

    Route::middleware([UsersMiddleware::class])->group(function () {

        Route::resource('user', UserController::class);

        Route::post('/user/status', [UserController::class, 'status'])->name('userStatus');

        Route::resource('admin', AdminController::class);
    });




    // Privileges Routes

    Route::middleware([PrivilegesMiddleware::class])->group(function () {

        Route::get('/privilege', [PrivilegeController::class, 'privilege'])->name('privilege');

        Route::post('/checkPrivilege', [PrivilegeController::class, 'checkPrivilege'])->name('checkPrivilege');
    });



    // Advertisements Routes

    Route::middleware([AdvertisementsMiddleware::class])->group(function () {

        Route::resource('advertisement', AdvertisementController::class);

        Route::post('/advertisement/status', [AdvertisementController::class, 'status'])->name('advertisementStatus');
    });

    // Store Routes
    Route::middleware([StoreMiddleware::class])->group(function () {
        Route::resource('/store', StoreController::class);
        Route::resource('/product', ProductController::class);
    });
});




Route::get('/changePassword', [ChangePasswordController::class, 'index']);

Route::post('/updatePassword', [ChangePasswordController::class, 'update'])->name('updatePassword');


Route::get('/deleteAccount', [DeleteAccountController::class, 'index']);

Route::post('/deleteAccount', [DeleteAccountController::class, 'deleteAccount'])->name('deleteAccount');

Route::get('/deletedSuccessfuly', [DeleteAccountController::class, 'deletedSuccessfuly'])->name('deletedSuccessfuly');


Route::get('/termsOfUse', function () {
    return view('termsOfUse');
})->name('termsOfUse');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
