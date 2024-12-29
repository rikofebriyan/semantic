<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\RdfController;


Route::get('/hello', function () {
    return Inertia::render('HelloWorld');
});


Route::get('/search', function () {
    return Inertia::render('SearchForm');
});

Route::get('/semantic', function () {
    return Inertia::render('SearchSemantic');
});

Route::get('/csv', [CsvController::class, 'convertCsvToJson']);
Route::get('/csv2', [CsvController::class, 'convertToJson']);
Route::get('/rdf', [RdfController::class, 'getRdfData']);



// Route::get('/search', function () {
//     return Inertia::render('SearchResults');
// });

// Route::get('/search', [SearchController::class, 'search'])->name('search');


require __DIR__ . '/auth.php';
