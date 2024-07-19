<?php

use App\Models\PD\Deal;
use App\Http\Controllers\MD;
use App\Http\Controllers\MD\FunctionsController;
use App\Http\Controllers\MD\ItemControllerMTK;
use App\Http\Controllers\PD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PD\OauthController;
use App\Http\Controllers\PD\DB\DealController;

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

// Materioteca group
Route::group(['prefix'=>'materioteca'],function(){
    Route::controller(ItemControllerMTK::class)->group(function(){
        Route::get('/',"index");
        Route::get('/{id}','item');
        Route::get('/admin/update', 'refreshMaterials');        
    });
});

// Monday group
Route::group(['prefix'=>'md'],function(){
    Route::controller(FunctionsController::class)->group(function(){
        Route::get('/test',"writeMaterials");
    });
});





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

Route::get('/PD/deal/{id}', function ($id){
    $g=new App\Http\Controllers\PD\FunctionsController;
    return  $g->listDealsProducts($id);
});

Route::get('/PD/loadDeals', [PD\FunctionsController::class,'writeDeals']);
Route::get('/PD/loadProducts', [App\Http\Controllers\PD\FunctionsController::class,'writeProducts']);
Route::get('/PD/listProducts', [App\Http\Controllers\PD\FunctionsController::class,'listDealsProducts']);
//Route::get('/PD', [App\Http\Controllers\PD\FunctionsController::class,'updateDuration']);

Route::get('/PD/updateQuantity', function () {

    $deals=Deal::select('deals.id as id','deals.title as title',DB::raw('count(products.id) as products'))
            ->join('products','products.deal_id','=','deals.id')
            ->whereNull('products.processed')
            ->where('products.duration','>',1)
            ->groupBy('deals.id','deals.title')
            ->get()->toArray();

    return view('listDuration')->with('deals',$deals);
})->name('updateQuantity');

Route::get('/PD/updateQuantity/deal/{id}', function ($id) {
    $d=new PD\FunctionsController;
    $d->updateDuration($id);

    return redirect()->route('updateQuantity');
})->name('updateDeal');

Route::get('/PD/options', function () {
    return view('options');
})->middleware(['auth', 'verified']);

Route::resource('PD/deal',DealController::class);

require __DIR__.'/auth.php';
