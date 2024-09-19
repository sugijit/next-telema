<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = ''; 

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // デフォルトのテーブル名を設定
    }

    // 動的にテーブル名を変更するメソッド
    public function setTableName($tableName)
    {
        // setTableを使用してテーブル名を設定
        $this->setTable($tableName);
        return $this;
    }
}
