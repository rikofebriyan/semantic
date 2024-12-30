<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Reader;
use EasyRdf\Graph;

class CsvController extends Controller
{
    /**
     * Mengonversi file CSV menjadi format JSON dari file yang ada di server.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertCsvToJson(Request $request)
    {
        // Path file CSV yang ada di server
        $csvFilePath = storage_path('app/csv/movies.csv'); // Sesuaikan dengan lokasi file CSV Anda

        // Mengecek apakah file ada
        if (!file_exists($csvFilePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File CSV tidak ditemukan.'
            ], 404);
        }

        // Membaca file CSV menggunakan League CSV
        try {
            $csv = Reader::createFromPath($csvFilePath, 'r');
            $csv->setHeaderOffset(0); // Mengambil header sebagai key untuk setiap baris

            // Mengambil semua data CSV sebagai array
            $records = $csv->getRecords();
            $data = iterator_to_array($records);

            // Mendapatkan keyword dari request (jika ada)
            $keyword = $request->input('search');

            // Jika ada keyword, filter data berdasarkan keyword
            if ($keyword) {
                $data = array_filter($data, function ($row) use ($keyword) {
                    // Menggunakan stripos untuk pencarian yang tidak case-sensitive
                    foreach ($row as $column) {
                        if (stripos($column, $keyword) !== false) {
                            return true;
                        }
                    }
                    return false;
                });
            } else {
                // Jika tidak ada keyword, kembalikan array kosong
                $data = [];
            }

            // Mengembalikan data CSV dalam format JSON
            return response()->json([
                'success' => true,
                'data' => array_values($data) // Menyusun ulang array setelah difilter
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses file CSV: ' . $e->getMessage()
            ], 500);
        }
    }


    public function convertToJson(Request $request)
    {
        // Nama file RDF yang disimpan di storage
        $filePath = storage_path('app/csv/pizza.rdf');

        // Cek apakah file RDF ada
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File RDF tidak ditemukan'], 404);
        }

        try {
            // Load file RDF menggunakan EasyRdf
            $graph = new Graph();
            $graph->parse(file_get_contents($filePath), 'rdfxml');

            // Konversi data RDF ke JSON
            $json = $graph->serialise('json');

            // Kembalikan response JSON
            return response()->json(json_decode($json), 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengonversi file RDF ke JSON', 'message' => $e->getMessage()], 500);
        }
    }
}
