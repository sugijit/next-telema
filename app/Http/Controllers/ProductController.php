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

        $header_jp = $list_items[0]['header'];
        $header_jp = explode(",", $header_jp);

        foreach($list_items as &$list_item) {
            unset($list_item["header"]);
        }
        unset($list_item);

        $telema_column_jp = [
            'telema_call_date' => '架電日',
            'telema_result' => '結果',
            'telema_call_user_name' => '架電担当者',
            'telema_call_count' => '架電回数',
            'telema_atokaku' => 'アトカク',
            'telema_call_plan_date' => '再架電予定、状況',
            'telema_service_now' => '現利用サービス',
            'telema_acq_server' => '獲得サーバー',
            'telema_server_size' => 'サイズ',
            'telema_server_color' => '色',
            'telema_mail' => 'メールアドレス',
            'telema_arrival_date' => '配送日',
            'telema_arrival_time' => '到着時間',
            'telema_benefits' => '補償優待',
        ];

        $header_jp = array_merge($header_jp, $telema_column_jp);
        array_unshift($header_jp, "id");
        $last_two = ['反映日', '変更日'];
        array_push($header_jp, ...$last_two);



        // dd($list_items);
        // dd($header_jp);
        return view('product.show', compact('products', 'id', 'list_items', 'header_jp'));
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

        //テレマリストの後ろの列　※ここ変えたら必ず show()の配列も変更するように！！！
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
        $encoding = mb_detect_encoding($csvContent, ['UTF-8', 'SJIS', 'EUC-JP']);
        if ($encoding != "UTF-8") {
            $csvContent = mb_convert_encoding($csvContent, 'UTF-8', 'SJIS');
        }
        // dd($encoding);
        file_put_contents($filePath, $csvContent);
        $csvFile = fopen($filePath, 'r');

        $header = fgetcsv($csvFile);
        $header_to_text = implode(',', $header);
        $rows = [];
        
        while (($row = fgetcsv($csvFile)) !== false) {
            $row["header"] = $header_to_text;
            $rows[] = $row;
        }
        // dd($rows);
        fclose($csvFile);
        $table = str_replace(' ', '', $request->input('table_name'));
        $table = strtolower($table);

        $table_name = "product_" .$table . 's';
        $model_name = "product_" . ucfirst($table);
        $product_name = $request->input('product_name');
        $column_names = array_shift($rows);
        $column_names['header'] = "header";
        $table_full_columns = array_merge($column_names, $telema_columns);


        // dd($rows);


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
        // dd($rows);


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
        // dd(count($rows[0]));
        foreach ($rows as $row) {
            $data = array_combine($column_names, $row);

            // ここで既存のデータがあるかチェックし、なければ挿入、あれば更新
            DB::table($tableName)->updateOrInsert($data);
        }
    }

    public function upsert(Request $request) {

    }

    public function updateCell(Request $request)
    {
        try {
            $rowIndex = $request->input('rowIndex');
            $colIndex = $request->input('colIndex');
            $value = $request->input('value');
            $productId = $request->input('id');
    
            if (!$productId) {
                return response()->json(['success' => false, 'message' => 'Product ID is required'], 400);
            }
            $product_table = ProductsMst::find($productId);
    
            if (!$product_table) {
                return response()->json(['success' => false, 'message' => 'Product table not found'], 404);
            }

            $modelClass = 'App\Models\Product' . ucfirst($product_table['table_name']);
            
            if (!class_exists($modelClass)) {
                return response()->json(['success' => false, 'message' => 'Model class not found'], 404);
            }
    
            $products = $modelClass::all();
    
            if ($rowIndex >= count($products)) {
                return response()->json(['success' => false, 'message' => 'Row index out of range'], 400);
            }
    
            $product = $products[$rowIndex];
    
            if (!array_key_exists($colIndex, $product->toArray())) {
                return response()->json(['success' => false, 'message' => 'Column index not found'], 400);
            }
    
            $product[$colIndex] = $value;
            $product->save();
    
            return response()->json(['success' => true]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


}
