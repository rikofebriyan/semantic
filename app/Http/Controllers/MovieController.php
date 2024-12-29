<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return view('movies.index'); // Mengembalikan view utama yang akan menampilkan aplikasi Vue
    }
}
