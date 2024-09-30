<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsMst extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_name',
        'table_name',
        'company_id',
        'created_user_id',
        'view',
        'custom_fields',
        'deleted_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    static function isOurProduct($product_id) {
        $user = Auth::user();
        $my_company_id = $user->company_id;
        $product = self::where('id', $product_id)->first(); // Use first() instead of get()
        if ($product) { // Check if product exists
            $companies = json_decode($product->company_id, true);
            // dd($companies);
            if(in_array($my_company_id, $companies)) {
                return true;
            };
           
        }
        return false; // Simplified return
    }
}
