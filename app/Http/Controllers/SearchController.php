<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Debug log untuk memastikan controller dipanggil
        Log::info("SearchController@search called");

        // Validasi input pencarian
        $request->validate([
            'query' => 'required|string|min:1',
        ]);

        // Ambil query pencarian dari input form
        $query = $request->input('query');

        // Memanggil fungsi untuk membaca dan memproses file RDF
        $results = $this->parseRdf($query);

        // Debug log untuk melihat hasil query
        Log::info("Search results: ", $results);

        // Mengirim hasil pencarian ke halaman Vue
        return inertia('SearchResults', ['results' => $results]);
    }

    private function parseRdf($query)
    {
        // Mengambil konten dari file RDF
        $rdfContent = file_get_contents(public_path('pizza.rdf'));

        // Memparse menggunakan SimpleXML
        $xml = simplexml_load_string($rdfContent);

        // Menyaring hasil pencarian berdasarkan query
        $results = [];
        foreach ($xml->children() as $child) {
            // Asumsi RDF memiliki elemen "name" yang ingin dicari
            if (strpos(strtolower($child->name), strtolower($query)) !== false) {
                $results[] = $child;
            }
        }

        return $results;
    }
}
