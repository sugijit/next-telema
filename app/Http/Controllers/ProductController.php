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
use App\Models\ProductList;
use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $company_id = $user->company_id;
        $first_product = ProductsMst::where('company_id', $company_id)->first();
        $lists = ProductList::all();
        $companies = Company::select('id', 'name')->get();
        $products_all = ProductsMst::all()->toArray();
        $products = [];

        foreach($products_all as $product) {
            $company_ids = json_decode($product['company_id'], true);
            if(in_array($company_id, $company_ids)){
                $products[] = $product;
                
            }
        }

        // リストない場合、addをみせる
        if (empty($products)) {
            if ($user->role == "nl_admin") {
                return view('product.add', compact('products','lists', 'companies'));
            } else {
                return view('product.nolist');
            }
        } else {
            $first_product = $products[0];
            return redirect()->route('products.show', $first_product['id']);
        }
    }


    public function getProductsByType($type) {}

    /**
     * Show the form for creating a new resource.
     */
    public function add()
    {
        $user = Auth::user();
        $company_id = $user->company_id;
        $products_all = ProductsMst::all()->toArray();
        $products = [];

        foreach($products_all as $product) {
            $company_ids = json_decode($product['company_id'], true);
            if(in_array($company_id, $company_ids)){
                $products[] = $product;
            }
        }
        $lists = ProductList::all();
        $companies = Company::select('id', 'name')->get();
        return view('product.add', compact('products','lists','companies'));
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

        if(!ProductsMst::isOurProduct($id)){
            return view('dashboard');
        }

        // dd($product_table['table_name']);

        if ($product_table) {
            if ($product_table['table_name']) {

                $productModel = new Product();
                $productModel->setTableName('product_'.$product_table['table_name'] . '1s')->get();
                $list_items = $productModel->get()->toArray(); 
                
                $current_list = $product_table->toArray();
            }
        } else {
            $user = Auth::user();
            $company_id = $user->company_id;
            $products = ProductsMst::where('company_id', $company_id)->get()->toArray();
            $companies = Company::select('id', 'name')->get();
            $list_items = [];
            $current_list = [];
            return view('product.add', compact('products', 'current_list', 'companies'));
        }


        $user = Auth::user();
        $company_id = $user->company_id;
        $products_all = ProductsMst::all()->toArray();
        $products = [];

        foreach($products_all as $product) {
            $company_ids = json_decode($product['company_id'], true);
            if(in_array($company_id, $company_ids)){
                $products[] = $product;
            }
        }

        // dd($products);

        $can_views = json_decode($current_list["view"], TRUE);
        $view_settings = json_decode($current_list["view"], TRUE);
        $header = json_decode($current_list["header"], TRUE);
        $hard_header = json_decode($current_list["header"], TRUE);

        foreach ($can_views as $key => $can_view) {

            if ($can_view == 0) {
                unset($header[$key]);
                foreach ($list_items as $a => &$item) {
                    unset($item[$key]);
                }
                unset($item);
            }
        }

        $fields = json_decode($product_table->custom_fields, TRUE);
        $fields = $fields ? $fields : [];
        // dd(session('products', []));
        
        // dd($fields);
        // $list_items = session('products', []);
        $selectFields = [];
        $selectFields = array_filter($fields, function ($item) {
            foreach ($item as $key => $value) {
                if (strpos($key, 'field_type') === 0 && $value === 'select') {
                    return true;
                }
            }
            return false;
        });
        // dd($selectFields);

        $productList = ProductList::find($product_table->product_list_id);
        $productMst = [];

        $selectUsers = [];
        if ($user->role == "nl_admin") {
            $productMst = ProductsMst::find($id)->get('company_id')->toArray();
            $company_ids = json_decode($productMst[0]["company_id"], true);
            // dd($company_ids);
            $selectUsers = User::whereIn('company_id', $company_ids)->get()->toArray(); //nl_admin
            // dd($selectUsers);
        } elseif($user->role == "admin") {
            $selectUsers = User::where('company_id', $company_id)->get()->toArray(); //admin
            // dd($selectUsers);
        } else {
            $selectUsers[] = User::find($user->id)->toArray(); //user
            // dd($selectUsers);
        }

        $companies = Company::all()->toArray();
        $companiess = [];
        $companiess = Company::select('id', 'name')->get();

        if ($user->role == 'nl_admin') {
            $got_count = $productModel->where('telema_tel_status', '獲得')->count();
        } elseif ($user->role == 'admin') {
            $company_ids = User::where('id', $user->id)->pluck('company_id')->toArray();
            $company_user_names = User::whereIn('company_id', $company_ids)->pluck('name')->toArray();
            $got_count = $productModel->where('telema_tel_status', '獲得')
                ->where(function($query) use ($user, $company_user_names) {
                    $query->where('telema_last_called_agent', $user->name)
                          ->orWhereIn('telema_last_called_agent', $company_user_names);
                })
                ->count();
        } else {
            $got_count = $productModel->where('telema_tel_status', '獲得')->where('telema_last_called_agent',$user->name)->count();
        }

        // dd($companiess);
        // dd($company_ids);
        return view('product.show', compact('products', 'id', 'list_items', 'header', 'current_list', 'can_views', 'view_settings', 'hard_header', 'fields', 'selectFields','user', 'selectUsers','companies', 'companiess', 'company_ids','got_count'));
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

    public function upload(Request $request)
    {
        // dd($request->input());


        $product_list_id = $request->input('list_select');
        // $productListModel = ProductList::find($product_list_id);
        // dd($productListModel->fields);

        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
            'table_name' => 'required|string|regex:/^[a-zA-Z0-9_]+$/',
            'product_name' => 'required|string',
        ]);

        // テーブル名がダブらないように
        $existingProduct = ProductsMst::where('table_name', $request->input('table_name'))->first();
        if ($existingProduct) {
            return redirect()->back()->with('error', '同じ英語のリスト名が存在します。別の英語の名前を使用してください。後ろに数字入れる事もできます。');
        }

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();
        $csvContent = file_get_contents($filePath);
        $encoding = mb_detect_encoding($csvContent, ['UTF-8', 'SJIS', 'EUC-JP']);
        if ($encoding != "UTF-8") {
            $csvContent = mb_convert_encoding($csvContent, 'UTF-8', 'SJIS');
        }

        file_put_contents($filePath, $csvContent);
        $csvFile = fopen($filePath, 'r');

        $header = fgetcsv($csvFile);
        // $header_to_text = implode(',', $header);
        $rows = [];

        while (($row = fgetcsv($csvFile)) !== false) {
            $rows[] = $row;
        }


        fclose($csvFile);
        $table = str_replace(' ', '', $request->input('table_name'));
        $table = strtolower($table);

        $table_name = "product_" . $table . '1s';
        $model_name = "product_" . ucfirst($table) . "1";
        $product_name = $request->input('product_name');
        $uploaded_header_eng = $rows[0];
        $column_names = array_shift($rows);

        foreach ($column_names as $column_name){
            if (!preg_match('/^(?=.*[a-zA-Z_])[a-zA-Z0-9_]+$/', $column_name)) {
                return redirect()->back()->with('error', 'csvファイルの2行目を全てローマ字で書いてください');
            }
        }

        $table_full_columns = $column_names;

        // 表示非表示用に生成 productsmstsに登録
        $product_mst_view = $table_full_columns;
        array_unshift($product_mst_view, "id");
        $last_two_date = ['created_at', 'updated_at'];
        array_push($product_mst_view, ...$last_two_date);
        foreach ($product_mst_view as $keys => $view) {
            unset($product_mst_view[$keys]);
            $product_mst_view[$view] = 1;
        }
        $product_mst_view = json_encode($product_mst_view);


        // 表示非表示用にヘッダーをeng-to-jp生成 productsmstsに登録
        $full_header_eng_jp_arr['id'] = 'id';
        $last_two_header_eng_jp_arr['created_at'] = '反映日';
        $last_two_header_eng_jp_arr['updated_at'] = '更新日';
        $term_header = array_combine($uploaded_header_eng, $header);
        $full_header_eng_jp_arr = array_merge($full_header_eng_jp_arr, $term_header);
        $full_header_eng_jp_arr = array_merge($full_header_eng_jp_arr, $last_two_header_eng_jp_arr);
        $full_header_eng_jp_arr = json_encode($full_header_eng_jp_arr);


        // テーブル作成
        try {
            $this->createTable($table_name, $column_names);
        } catch (Exception $e) {
            return back();
        }
        // モデル生成
        // try {
        //     $this->createModel($model_name, $table_full_columns);
        // } catch (Exception $e) {
        //     return back();
        // }
        // products_msts table マスター登録
        $user = Auth::user();
        $user_id = $user->id;
        $user_name = $user->name;
        $users = User::all()->toArray();
        $names = collect($users)->pluck('name')->implode(',');

        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'table_name' => 'nullable|string|max:255',
        ]);

        //company_idに表示できる会社のidを配列で入れる
        $companyIds = array_map(function($company) {
            $companyData = json_decode($company, true); // JSONを配列に変換
            return $companyData['id']; // idだけを返す
        }, $request->input('companies'));
        $companyIds = json_encode($companyIds);
        // dd($companyIds);


        $product_mst = new ProductsMst();
        $product_mst->product_name = $validatedData['product_name'];
        $product_mst->table_name = $validatedData['table_name'];
        $product_mst->company_id = $companyIds;
        $product_mst->created_user_id = $user_id;
        $product_mst->view = $product_mst_view;
        $product_mst->header = $full_header_eng_jp_arr;
        $product_mst->product_list_id = $product_list_id;
        $product_mst->custom_fields = null;
        $product_mst->save();

        $add_field = [
            0 => null,
            'product_id' => $product_mst->id,
            "field_name_1"=>"tel_status",
            "field_value_1"=>"テレマステータス",
            "field_type_1"=>"select",
            "options_1"=>"不在,NG,再架電,架電禁止,獲得",
            "field_name_2"=>"agent",
            "field_value_2"=>"テレマ担当",
            "field_type_2"=>"select",
            "options_2"=>$names,
            "field_name_3"=>"last_called_agent",
            "field_value_3"=>"最終架電",
            "field_type_3"=>"text",
            "options_3"=>null,
            "field_name_4"=>"count",
            "field_value_4"=>"架電数",
            "field_type_4"=>"text",
            "options_4"=>null,
            "field_name_5"=>"call_last_date",
            "field_value_5"=>"最終架電日時",
            "field_type_5"=>"date",
            "options_5"=>null,
            "field_name_6"=>"call_memo",
            "field_value_6"=>"メモ",
            "field_type_6"=>"textarea",
            "options_6"=>null,
            // "field_name_7"=>"et_done",
            // "field_value_7"=>"ET登録状況",
            // "field_type_7"=>"select",
            // "options_7"=>"未,済",
            // "field_name_8"=>"nl_et_done",
            // "field_value_8"=>"NextLink登録状況",
            // "field_type_8"=>"select",
            // "options_8"=>"未,済",
        ];
        $this->firstFields($add_field);


        // CSVデータ挿入
        $this->insertCsvData($table_name, $column_names, array_slice($rows, 1));

        return redirect()->route('products.show', $product_mst->id)
            ->with('success', "新規リストがが正常に作成されました。");
    }


    protected function createTable($table_name, $column_names)
    {
        try {
            DB::beginTransaction();
            if (Schema::hasTable($table_name)) {
                throw new Exception("テーブル {$table_name} は既に存在しています。");
                return false;
            }
            Schema::create($table_name, function (Blueprint $table) use ($column_names) {
                $table->id();
                foreach ($column_names as $column_name) {
                    $table->string($column_name)->nullable();
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

        // Insert the $fillable property
        $useFactoryPosition = strpos($modelContent, 'use HasFactory;') + strlen('use HasFactory;') + 1;
        $extendsPosition = strpos($modelContent, 'extends Model') - 1;
        $modelContent = substr_replace($modelContent, "\n    {$fillableProperty}\n", $useFactoryPosition, $extendsPosition - $useFactoryPosition);

        // Insert the updateFillable method after the $fillable property
        $updateFillableMethod = <<<EOD
    
        public function updateFillable(array  \$columnNames)
        {
            \$this->fillable = array_merge(\$this->fillable,  \$columnNames);
        }
        EOD;

        // Find the position to insert the method after the $fillable property
        $fillableEndPosition = strpos($modelContent, '];', $useFactoryPosition) + 2;
        $modelContent = substr_replace($modelContent, "{$updateFillableMethod}\n", $fillableEndPosition, 0);

        // Ensure the model file is saved correctly
        file_put_contents($modelPath, $modelContent);

        return;
    }



    protected function insertCsvData($tableName, $column_names, $rows)
    {
        // dd(count($rows[0]));
        foreach ($rows as $row) {
            $data = array_combine($column_names, $row);
            $data['created_at'] = now();
            // ここで既存のデータがあるかチェックし、なければ挿入、あれば更新
            DB::table($tableName)->updateOrInsert($data);
        }
    }

    public function upsert(Request $request) {}

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

            $productModel = new Product();
            $productModel->setTableName('product_'.$product_table['table_name'] . '1s')->get();
            $products = $productModel->get(); 

            // if (!class_exists($products)) {
            //     return response()->json(['success' => false, 'message' => 'Model class not found'], 404);
            // }


            if ($rowIndex >= count($products)) {
                return response()->json(['success' => false, 'message' => 'Row index out of range'], 400);
            }

            $product = $products[$rowIndex];

            if (!array_key_exists($colIndex, $product->toArray())) {
                return response()->json(['success' => false, 'message' => 'Column index not found'], 400);
            }

            $product->{$colIndex} = $value;
            $product->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function canView(Request $request)
    {
        $product_id = $request->input('product_id');
        $product = ProductsMst::find($product_id);

        $posted_data = $request->input();
        $update_data = array_slice($posted_data, 2);
        $update_data = json_encode($update_data);
        $product->view = $update_data;
        $product->save();
        return redirect()->route('products.show', $product->id);
    }

    public function firstFields($add_field)
    {
        
        $product_id = $add_field['product_id'];
        $posted_data = array_slice($add_field, 2);
        $product = ProductsMst::find($product_id);
        $table_name = "product_" . $product->table_name . '1s';


        $posted_data_in_array = array_chunk($posted_data, 4, true);

        $old_mst_fields = json_decode($product->custom_fields, TRUE);
        if ($old_mst_fields != null) {
            $posted_data_in_array = array_filter($posted_data_in_array, function ($item) use ($old_mst_fields) {
                foreach ($old_mst_fields as $compareItem) {
                    // 配列2の各要素と一致する場合にフィルタリング
                    if ($item == $compareItem) {
                        return false;
                    }
                }
                return true;
            });
            // dd($posted_data_in_array);
            $field_names = [];
            $copy = [];

            foreach ($posted_data_in_array as $item) {
                foreach ($item as $key => $value) {
                    $copy[$key] = $value;
                }
            }
            $posted_data_in_array = $copy;



            foreach ($posted_data_in_array as $key => $value) {
                if (strpos($key, 'field_name_') === 0) {
                    $field_names[] = "telema_" . $value;
                }
            }
        } else {
            $field_names = [];
            foreach ($posted_data as $key => $value) {
                if (strpos($key, 'field_name_') === 0) {
                    $field_names[] = "telema_" . $value;
                }
            }
        }

        try {
            DB::beginTransaction();
            if (Schema::hasTable($table_name)) {
                Schema::table($table_name, function (Blueprint $table) use ($field_names) {
                    foreach ($field_names as $field_name) {
                        $table->text($field_name)->nullable();
                    }
                });
                $field_names_copy = $field_names;
                $last_field = array_pop($field_names_copy);
                // dd($last_field);
                DB::statement("ALTER TABLE {$table_name} MODIFY COLUMN created_at TIMESTAMP NULL AFTER {$last_field}");
                DB::statement("ALTER TABLE {$table_name} MODIFY COLUMN updated_at TIMESTAMP NULL AFTER created_at");
            } else {
                throw new Exception("テーブル {$table_name} は存在しません。");
                return false;
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->with('error', 'クエリエラーが発生しました: ' . $e->getMessage());
        }

        // ProductsMstsにフォーム情報を追加
        $posted_data_in_array = array_chunk($posted_data, 4, true);
        $posted_data_in_json = json_encode($posted_data_in_array);
        $product->custom_fields = $posted_data_in_json;
        $product->save();

        // ヘッダー生成
        $posted_data_in_array = array_chunk($posted_data, 4, true);
        $this->headerAndViewUpdate($product_id, $posted_data_in_array);


        // showへリダイレクト
        return redirect()->route('products.show', $product->id)
            ->with('success', "フィールド追加しました。");
    }


    // showで見せるためのproductsmstsのheader, view更新
    public function headerAndViewUpdate($product_id, $new_field)
    {
        $product = ProductsMst::find($product_id);
        $old_header = $product->header;
        $old_view = $product->view;
        $old_header_array = json_decode($old_header, TRUE);
        $old_view_array = json_decode($old_view, TRUE);

        // 追加されるヘッダーの配列生成
        $header_tobe_added = [];
        foreach ($new_field as $item) {
            $fieldKey = null;
            foreach ($item as $key => $value) {
                if (strpos($key, 'field_name_') === 0) {
                    $fieldKey = $value;
                } elseif (strpos($key, 'field_value_') === 0 && $fieldKey !== null) {
                    $header_tobe_added["telema_" . $fieldKey] = $value;
                }
            }
        }

        // 新しいヘッダー生成（結合）
        $index = count($old_header_array) - 2;
        $arrayBefore = array_slice($old_header_array, 0, $index, true);
        $arrayAfter = array_slice($old_header_array, $index, null, true);
        $new_header = array_merge($arrayBefore, $header_tobe_added, $arrayAfter);

        //view　結合
        foreach ($header_tobe_added as $key => $view) {
            $view_tobe_added[$key] = 1;
        }
        $viewArrayBefore = array_slice($old_view_array, 0, $index, true);
        $viewArrayAfter = array_slice($old_view_array, $index, null, true);
        $new_view = array_merge($viewArrayBefore, $view_tobe_added, $viewArrayAfter);

        //masterへ保存
        $new_header = json_encode($new_header);
        $new_view = json_encode($new_view);
        $product->header = $new_header;
        $product->view = $new_view;
        $product->save();

        return true;
    }

    public function deleteField(Request $request, $productId)
    {


        $product = ProductsMst::find($productId);
        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        $customFields = json_decode($product->custom_fields, true);
        $view = json_decode($product->view, true);
        $header = json_decode($product->header, true);

        $fieldName = $request->input('field_name');

        $filtered_array = array_filter($customFields, function ($item) use ($fieldName) {
            foreach ($item as $key => $value) {
                if (strpos($key, 'field_name') !== false && $value === $fieldName) {
                    return false;
                }
            }
            return true;
        });
        $filtered_array = array_values($filtered_array);

        foreach ($filtered_array as $index => &$item) {
            if (isset($item["field_name_" . ($index + 2)])) {
                $item = [
                    "field_name_" . ($index + 1) => $item["field_name_" . ($index + 2)],
                    "field_value_" . ($index + 1) => $item["field_value_" . ($index + 2)],
                    "field_type_" . ($index + 1) => $item["field_type_" . ($index + 2)],
                    "options_" . ($index + 1) => $item["options_" . ($index + 2)],
                ];
            }
        }




        $view_name = "telema_" . $fieldName;
        if (array_key_exists($view_name, $view)) {
            unset($view[$view_name]);
        }


        if (array_key_exists($view_name, $header)) {
            unset($header[$view_name]);
        }


        $field_name_db = "telema_" . $fieldName;
        $table_name = "product_" . $product->table_name . '1s';


        try {
            Schema::table($table_name, function (Blueprint $table) use ($field_name_db) {
                $table->dropColumn($field_name_db);
            });
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting column: ' . $e->getMessage()], 500);
        }

        if (empty($filtered_array)) {
            $product->custom_fields = null;
        } else {
            $product->custom_fields = json_encode($filtered_array);
        }
        $product->view = json_encode($view);
        $product->header = json_encode($header);
        $product->save();

        return response()->json(['success' => 'Field deleted successfully.', 'pyzda' => $table_name]);
    }




    public function updateField(Request $request)
    {
        $posted_data = $request->input();

        $product = ProductsMst::find($request->input('product_id'));
        $custom_fields = $product->custom_fields;
        $custom_fields_array = json_decode($custom_fields, TRUE);
        $header = json_decode($product->header, true);

        foreach ($custom_fields_array as $index => &$item) {
            $fieldNumber = $index + 1;

            if (isset($posted_data["field_name_{$fieldNumber}"])) {
                $item["field_name_{$fieldNumber}"] = $posted_data["field_name_{$fieldNumber}"];
            }

            if (isset($posted_data["field_value_{$fieldNumber}"])) {
                $item["field_value_{$fieldNumber}"] = $posted_data["field_value_{$fieldNumber}"];

                $headerKey = "telema_" . $posted_data["field_name_{$fieldNumber}"];
                if (array_key_exists($headerKey, $header)) {
                    $header[$headerKey] = $posted_data["field_value_{$fieldNumber}"];
                }
            }

            if (isset($posted_data["field_type_{$fieldNumber}"])) {
                $item["field_type_{$fieldNumber}"] = $posted_data["field_type_{$fieldNumber}"];
            }

            if (isset($posted_data["options_{$fieldNumber}"])) {
                if ($posted_data["field_type_{$fieldNumber}"] === "text") {
                    $item["options_{$fieldNumber}"] = null;
                } else {
                    $item["options_{$fieldNumber}"] = $posted_data["options_{$fieldNumber}"];
                }
            }
        }

        $custom_fields = json_encode($custom_fields_array);

        $product->custom_fields = $custom_fields;
        $product->header = $header;
        $product->save();

        return redirect()->route('products.show', $product->id)->with('success', 'フィールドが更新されました。');
    }




        public function filter(Request $request)
    {

        $product_table = ProductsMst::find($request->input('product_id'));

        if(!ProductsMst::isOurProduct($request->input('product_id'))){
            return view('dashboard');
        }

        $fields = json_decode($product_table->custom_fields, TRUE);
        $fields = $fields ? $fields : [];
        $selectFields = array_filter($fields, function ($item) {
            foreach ($item as $key => $value) {
                if (strpos($key, 'field_type') === 0 && $value === 'select') {
                    return true;
                }
            }
            return false;
        });

        $modelClass = new Product();
        $modelClass->setTableName('product_'.$product_table['table_name'] . '1s')->get();

        if ($product_table['table_name']) {
            $modelClass = new Product();
            $modelClass->setTableName('product_'.$product_table['table_name'] . '1s')->get();
            $query = $modelClass::query();
    
            // Apply date filters
            if ($request->filled('date_from')) {
                $query->where('telema_call_last_date', '>=', $request->input('date_from'));
            }
            if ($request->filled('date_to')) {
                $query->where('telema_call_last_date', '<', $request->input('date_to') . ' 23:59:59');
            }
    
            // Apply select field filters
            foreach ($selectFields as $key => $value) {
                foreach($value as $keyy => $val) {
                    if ($request->filled($val)) {
                        $query->where("telema_".$val, 'LIKE', '%' . $request->input($val) . '%');
                    }
                }
            }
    
            // Apply search keyword filter
            if ($request->filled('search_keyword')) {
                $columns = Schema::getColumnListing("product_" . $product_table['table_name'] . "1s");
                $search_keyword = $request->input('search_keyword');
                foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', "%{$search_keyword}%");
                }
            }
    
            // dd($query);
            // Execute the query
            $list_items = $query->from('product_'.$product_table['table_name'] . '1s')->get()->toArray();
            $current_list = $product_table->toArray();
        
        } else {
             $user = Auth::user();
            $company_id = $user->company_id;
            $products = ProductsMst::where('company_id', $company_id)->get()->toArray();
            $list_items = [];
            $current_list = [];
            return view('product.add', compact('products', 'current_list'));
        }


        $user = Auth::user();
        $company_id = $user->company_id;
        $products = ProductsMst::where('company_id', $company_id)->get()->toArray();

        $can_views = json_decode($current_list["view"], TRUE);
        $view_settings = json_decode($current_list["view"], TRUE);
        $header = json_decode($current_list["header"], TRUE);
        $hard_header = json_decode($current_list["header"], TRUE);

        foreach ($can_views as $key => $can_view) {

            if ($can_view == 0) {
                unset($header[$key]);
                foreach ($list_items as $a => &$item) {
                    unset($item[$key]);
                }
                unset($item);
            }
        }

        $id = $request->input('product_id');

        $productList = ProductList::find($product_table->product_list_id);

        // $nl_link = $productList->nl_link;
        // $entry_link = $productList->entry_link;
        $companiess = [];
        $companiess = Company::select('id', 'name')->get();

        $productMst = ProductsMst::find($id)->get('company_id')->toArray();
        $company_ids = json_decode($productMst[0]["company_id"], true);

        if ($user->role == 'nl_admin') {
            $got_count = $modelClass->where('telema_tel_status', '獲得')->count();
        } elseif ($user->role == 'admin') {
            $company_ids = User::where('id', $user->id)->pluck('company_id')->toArray();
            $company_user_names = User::whereIn('company_id', $company_ids)->pluck('name')->toArray();
            $got_count = $modelClass->where('telema_tel_status', '獲得')
                ->where(function($query) use ($user, $company_user_names) {
                    $query->where('telema_last_called_agent', $user->name)
                          ->orWhereIn('telema_last_called_agent', $company_user_names);
                })
                ->count();
        } else {
            $got_count = $modelClass->where('telema_tel_status', '獲得')->where('telema_last_called_agent',$user->name)->count();
        }



        $selectUsers = [];
        if ($user->role == "nl_admin") {
            $productMst = ProductsMst::find($id)->get('company_id')->toArray();
            $company_ids = json_decode($productMst[0]["company_id"], true);
            // dd($company_ids);
            $selectUsers = User::whereIn('company_id', $company_ids)->get()->toArray(); //nl_admin
            // dd($selectUsers);
        } elseif($user->role == "admin") {
            $selectUsers = User::where('company_id', $company_id)->get()->toArray(); //admin
            // dd($selectUsers);
        } else {
            $selectUsers[] = User::find($user->id)->toArray(); //user
            // dd($selectUsers);
        }

        $companies = Company::all()->toArray();
        $companiess = [];
        $companiess = Company::select('id', 'name')->get();



        return view('product.show', compact('products', 'id', 'list_items', 'header', 'current_list', 'can_views', 'view_settings', 'hard_header', 'fields', 'selectFields','user','companiess', 'company_ids', 'got_count', 'selectUsers','companies','companiess'));
        }


        public function downloadCSV($id, Request $request)
        {
            $product_table = ProductsMst::find($id);
            $queryParams = $request->query(); 

            $downloadHeader = $this->getDownloadHeader($id);
            $downloadItems = $this->getDownloadItems($id, $queryParams);
            
            $csvFileName = $product_table->product_name . '_' . now() . '.csv';
            $handle = fopen('php://output', 'w');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $csvFileName . '"');

            fputcsv($handle, $downloadHeader); 
            foreach ($downloadItems as $item) {
                fputcsv($handle, $item); 
            }

            fclose($handle);
            exit;
        }

        public function getDownloadHeader($id) {
            $all_header = ProductsMst::find($id)->header;
            $all_header = json_decode($all_header, TRUE);

            $filtered_header = ProductsMst::find($id)->view;
            $filtered_header = json_decode($filtered_header, TRUE);

            $downloadHeader = [];
            foreach ($filtered_header as $key => $value) {
                if ($value == "1" && isset($all_header[$key])) {
                    $downloadHeader[$key] = $all_header[$key];
                }
            }

            return $downloadHeader;
        }


        public function getDownloadItems($id, $queryParams) {
            $product_table = ProductsMst::find($id);
            $modelClass = new Product();
            $modelClass->setTableName('product_'.$product_table['table_name'] . '1s')->get();
            $query = $modelClass::query();


            
            if(!empty($queryParams)) {
                // filteriiiiingggggggggg
                $modelClass = new Product();
                $modelClass->setTableName('product_'.$product_table['table_name'] . '1s')->get();
                $query = $modelClass::query();
                $list_items = $query->from('product_'.$product_table['table_name'] . '1s')->get()->toArray();
                    $query = $modelClass::query();
                    if (isset($queryParams['date_from']) && $queryParams['date_from']) {
                        $query->where('updated_at', '>=', $queryParams['date_from']);
                    }
                    if (isset($queryParams['date_to']) && $queryParams['date_to']) {
                        $query->where('updated_at', '<', $queryParams['date_to'] . ' 23:59:59');
                    }
                    if ($queryParams['search_keyword']) {
                        $columns = Schema::getColumnListing("product_" . $product_table['table_name'] . "1s");
                        $search_keyword = $queryParams['search_keyword'];
                        foreach ($columns as $column) {
                            $query->orWhere($column, 'LIKE', "%{$search_keyword}%");
                        }
                    }

                    array_splice($queryParams, 0, 4);
                    foreach($queryParams as $key => $queryParam){
                        if($queryParam){
                            $query->where("telema_" . $key, 'LIKE', $queryParam);
                        }
                    }
                    $list_items = $query->from('product_'.$product_table['table_name'] . '1s')->get()->toArray();
                    // $list_items = $query->get()->toArray();
                // filteriiiiingggggggggg
            } else {
                $modelClass = new Product();
                $modelClass->setTableName('product_'.$product_table['table_name'] . '1s')->get();
                $query = $modelClass::query();
                $list_items = $modelClass->get()->toArray();
            }

            $downloadHeader = $this->getDownloadHeader($id);

            $listItems = [];

            foreach ($list_items as $record) {
                $filtered = array_intersect_key($record, $downloadHeader);
                $listItems[] = $filtered;
            }

            return $listItems;
        }



        public function delete($id)
        {
            $product = ProductsMst::findOrFail($id);
            $product->delete(); // Soft delete

            return response()->json(['success' => true]);
        }


        public function calledChange(Request $request){
            try {
                $rowIndex = $request->input('rowIndex');
                $colIndex = $request->input('colIndex');
                $user_name = $request->input('user_name');
                $productId = $request->input('id');
    
                $product_table = ProductsMst::find($productId);
    
                $productModel = new Product();
                $productModel->setTableName('product_'.$product_table['table_name'] . '1s')->get();
                $products = $productModel->get(); 

                $product = $products[$rowIndex];

                if($product->telema_count == null){
                    $product->telema_count = 0;
                }
                $product->telema_count = $product->telema_count + 1;
                $product->telema_last_called_agent = $user_name;
                $product->telema_call_last_date = now();
                $product->save();
    
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

        }


        public function update_companies(Request $request){
            $product_id = $request->input('product_id');
            $company_ids = $request->input('companies');
            $product = ProductsMst::find($product_id);


            $companyIds = json_encode($company_ids);
            $product->company_id = $companyIds;
            $product->save();

            // showへリダイレクト
            return redirect()->route('products.show', $product_id)
            ->with('success', "表示企業を保存しました");
        }

}
