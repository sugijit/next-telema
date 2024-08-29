<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Termwind\Components\Raw;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint; 

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = [];
        if(isEmpty($products)){
            return view('product.add', compact('products'));
        }
        return view('product.index', compact('products'));
    }


    public function getProductsByType($type)
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function add()
    {
        return view('product.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function upload(Request $request) {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
            'table_name' => 'required|string',
            'product_name' => 'required|string',
        ]);
    
        $table_name = "product_" . $request->input('table_name');
        $product_name = $request->input('product_name');
        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();
        $csvContent = file_get_contents($filePath);
        $csvContent = mb_convert_encoding($csvContent, 'UTF-8', 'SJIS');
        file_put_contents($filePath, $csvContent);
        $csvFile = fopen($filePath, 'r');

        $header = fgetcsv($csvFile);
        $rows = [];
        
        while (($row = fgetcsv($csvFile)) !== false) {
            $rows[] = $row;
        }
        fclose($csvFile);
    




        dd($header);






        $this->createTable($tableName, $translatedHeaders);
    }
    

    protected function createTable($tableName, $headers)
    {
        Schema::create($tableName, function (Blueprint $table) use ($headers) {
            $table->id();
            foreach ($headers as $header) {
                $table->string($header)->nullable();
            }
            $table->timestamps();
        });
    }


    public function upsert(Request $request) {

    }


}
