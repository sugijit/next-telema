<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Termwind\Components\Raw;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint; 
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

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

        $telema_columns = [
            'telema_call_date',
            'telema_result',
            'telema_call_user_name',
            'telema_call_count',
            'telema_atokaku',
            'telema_call_plan_date',
            'telema_service_now',
            'telema_acq_server',
            'telema_server_size',
            'telema_server_color',
            'telema_mail',
            'telema_arrival_date',
            'telema_arrival_time',
            'telema_benefits',
        ];


        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
            'table_name' => 'required|string',
            'product_name' => 'required|string',
        ]);
    

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

        $table_name = "product_" . $request->input('table_name');
        $product_name = $request->input('product_name');
        $column_names = array_shift($rows);
        $table_full_columns = array_merge($column_names, $telema_columns);

        // dd($table_full_columns);

        // dd(ucfirst(Str::camel($table_name)));
    

        // dd($rows);


        // テーブル作成
        try {
            $this->createTable($table_name, $column_names, $telema_columns);
        } catch (Exception $e) {
            return back();
        }

        try {
            $this->createModel($table_name, $table_full_columns);
            return back()->with('success', "MODELが正常に作成されました。");
        } catch (Exception $e) {
            return back();
        }


        // モデル生成

        // Bladeファイル生成
        // $this->generateBlade($filename, $translatedHeaders);
        // CSVデータ挿入
        // $this->insertCsvData($filename, $translatedHeaders, array_slice($csvData, 1));
        // return back()->with('success', 'CSVファイルをアップロードし、テーブル・モデル・Bladeを生成しました。');
        
    }
    

    protected function createTable($table_name, $column_names, $telema_columns)
    {
        try {
            DB::beginTransaction();
            if (Schema::hasTable($table_name)) {
                throw new Exception("テーブル {$table_name} は既に存在しています。");
                return false;
            }
            Schema::create($table_name, function (Blueprint $table) use ($column_names,$telema_columns) {
                $table->id();
                foreach ($column_names as $column_name) {
                    $table->string($column_name)->nullable();
                }
                foreach ($telema_columns as $telema_column) {
                    $table->string($telema_column)->nullable();
                }
                $table->timestamps();
            });
            return back()->with('success', "テーブル {$table_name} が正常に作成されました。");
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'クエリエラーが発生しました: ' . $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'エラーが発生しました: ' . $e->getMessage());
        }
    }


    protected function createModel($tableName, $table_full_columns)
    {
        $modelName = ucfirst(Str::camel($tableName));

        Artisan::call('make:model', ['name' => $modelName]);
    
        $modelPath = app_path("Models/{$modelName}.php");
    
        $modelContent = file_get_contents($modelPath);
        $fillableColumns = array_map(fn($column) => "'{$column}'", $table_full_columns);
        $fillableString = implode(', ', $fillableColumns);
        $fillableProperty = "protected \$fillable = [\n    {$fillableString}\n];";
        $useFactoryPosition = strpos($modelContent, 'use HasFactory;') + strlen('use HasFactory;') + 1;
        $extendsPosition = strpos($modelContent, 'extends Model') - 1;
        $modelContent = substr_replace($modelContent, "\n    {$fillableProperty}\n", $useFactoryPosition, $extendsPosition - $useFactoryPosition);
        file_put_contents($modelPath, $modelContent);

        return;
    }


    public function upsert(Request $request) {

    }


}
