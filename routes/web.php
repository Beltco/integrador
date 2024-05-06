<?php

use App\Http\Controllers\PD\DB\DealController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PD\OauthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/PD/callback', [OauthController::class,'callback']);

Route::get('/PD/oauth',[OauthController::class,'index'])->name('oauth');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/PD/{id}', function ($id){
    $g=new App\Http\Controllers\PD\FunctionsController;
    return  $g->loadDealsProducts($id);
});

//Route::get('/PD', [App\Http\Controllers\PD\FunctionsController::class,'writeProducts']);
//Route::get('/PD', [App\Http\Controllers\PD\FunctionsController::class,'loadDealsProductsPD']);
//Route::get('/PD', [App\Http\Controllers\PD\FunctionsController::class,'updateDuration']);
Route::get('/MD', [App\Http\Controllers\MD\FunctionsController::class,'getBoards']);


Route::resource('PD/deal',DealController::class);

require __DIR__.'/auth.php';
