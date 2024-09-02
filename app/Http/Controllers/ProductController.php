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
use App\Models\ProductsMst;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = ProductsMst::all()->toArray();

        // リストない場合、addをみせる
        if (Empty($products)){
            return view('product.add', compact('products'));
        } else {
            return redirect()->route('products.show', 1);
        } 
    }


    public function getProductsByType($type)
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function add()
    {
        $products = ProductsMst::all()->toArray();
        return view('product.add', compact('products'));
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

        $product_table = ProductsMst::find($id);

        if($product_table) {
            if ($product_table['table_name']){
                $modelClass = 'App\Models\Product'.ucfirst($product_table['table_name']);
                $list_items = $modelClass::all()->toArray();
            } 
        } else {
            $products = ProductsMst::all()->toArray();
            $list_items = [];
            return view('product.add', compact('products'));
        }
        
        $products = ProductsMst::all()->toArray();
        return view('product.show', compact('products', 'id', 'list_items'));
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

        $table_name = "product_" . $request->input('table_name') . 's';
        $model_name = "product_" . $request->input('table_name');
        $product_name = $request->input('product_name');
        $column_names = array_shift($rows);
        $table_full_columns = array_merge($column_names, $telema_columns);





        // テーブル作成
        try {
            $this->createTable($table_name, $column_names, $telema_columns);
        } catch (Exception $e) {
            return back();
        }
        // モデル生成
        try {
            $this->createModel($model_name, $table_full_columns);
            
        } catch (Exception $e) {
            return back();
        }
        // products_msts table マスター登録
        $user = Auth::user();
        $user_id = $user->id;
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'table_name' => 'nullable|string|max:255',
        ]);
        $product_mst = new ProductsMst();
        $product_mst->product_name = $validatedData['product_name'];
        $product_mst->table_name = $validatedData['table_name'];
        $product_mst->company_id = $user_id;
        $product_mst->created_user_id = $user_id;
        $product_mst->save();


        // CSVデータ挿入
        $this->insertCsvData($table_name, $column_names, array_slice($rows, 1));

        return redirect()->route('products.show', $product_mst->id)
            ->with('success', "新規リストがが正常に作成されました。");

  

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



    protected function insertCsvData($tableName, $column_names, $rows)
    {
        foreach ($rows as $row) {
            $data = array_combine($column_names, $row);

            // ここで既存のデータがあるかチェックし、なければ挿入、あれば更新
            DB::table($tableName)->updateOrInsert($data);
        }
    }

    public function upsert(Request $request) {

    }


}
