<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ShortUrlController,CompanyController,UserController};

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('short-urls/{shortUrl}', [ShortUrlController::class, 'show'])
    ->name('short-urls.show');

Route::middleware('auth')->group(function () {
    Route::resource('companies', CompanyController::class);
    Route::resource('users', UserController::class);
    Route::resource('short-urls', ShortUrlController::class)
        ->except(['show']);

    Route::get('/dashboard',[CompanyController::class,'dashboard'])->name('dashboard');
    

    Route::get('short-url/download', [ShortUrlController::class, 'download'])
    ->name('short-urls.download');

});

require __DIR__.'/auth.php';
