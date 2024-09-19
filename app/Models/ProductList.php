<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductList extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'list_name',
        'list_alias',
        'company_id',
        'nl_link',
        'fields',
        'deleted_at',
    ];
}
