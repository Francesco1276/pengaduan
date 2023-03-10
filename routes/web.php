<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseController;
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

Route::get('/',[ReportController::class, 'index'])->name('home');

// Route::get('/', function(){
//     return view('index');
// })->name('home');

Route::get('/login', function(){
    return view('login');
})->name('login');

Route::post('/store', [ReportController::class, 'store'])->name('store');

Route::post('/auth', [ReportController::class, 'auth'])->name('auth');

Route::middleware(['isLogin', 'CekRole:petugas'])->group(function() {
    Route::get('/data/petugas', [ReportController::class, 'dataPetugas'])->name('data.petugas');
    Route::get('/response/edit/{report_id}', [ResponseController::class, 'edit'])->name('response.edit');
    Route::patch('/response/update/{report_id}', [ResponseController::class, 'update'])->name('response.update');
});

Route::middleware(['isLogin', 'CekRole:admin,petugas'])->group(function() {
    Route::get('/logout',[ReportController::class, 'logout'])->name('logout');
});

Route::middleware(['isLogin', 'CekRole:admin'])->group(function() {
    Route::get('/data', [ReportController::class, 'data'])->name('data');
    Route::delete('/hapus/{id}',[ReportController::class, 'destroy'])->name('delete');
    Route::get('/export/pdf/', [ReportController::class, 'exportPDF'])->name('export.pdf');
    Route::get('/craeted/pdf/{id}', [ReportController::class, 'createdPDF'])->name('created.pdf');
    Route::get('/export/excel', [ReportController::class,  'createExcel'])->name('export.excel');

});