<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsMst extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'table_name',
        'company_id',
        'created_user_id',
        'view',
        'custom_fields',
    ];
}
