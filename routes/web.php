<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SearchController;


Route::get('/hello', function () {
    return Inertia::render('HelloWorld');
});


Route::get('/search', function () {
    return Inertia::render('SearchForm');
});

// Route::get('/search', function () {
//     return Inertia::render('SearchResults');
// });

// Route::get('/search', [SearchController::class, 'search'])->name('search');


require __DIR__ . '/auth.php';
