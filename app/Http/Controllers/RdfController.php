<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EasyRdf\Graph;
use EasyRdf\Format;
use Illuminate\Support\Facades\Storage;

class RdfController extends Controller
{
    public function getRdfData()
    {
        $filePath = storage_path('app/csv/pizza.rdf');
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Membaca dan memuat file RDF
        $graph = new Graph();
        $graph->load($filePath);

        // Mengonversi data RDF ke JSON
        $jsonData = $graph->serialise('json');

        // Kembalikan sebagai response JSON
        return response()->json(json_decode($jsonData));
    }
}
