<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAuuuuuhikari extends Model
{
    use HasFactory;

    protected $fillable = [
    'ids', 'callbacked_at', 'line_class', 'customer_family_name', 'customer_first_name', 'customer_family_name_kana', 'customer_first_name_kana', 'sex', 'birthdate', 'contact_tel', 'mail', 'zip_code', 'address', 'building', 'room_number', 'telema_call_date', 'telema_result', 'telema_call_user_name', 'telema_call_count', 'telema_atokaku', 'telema_call_plan_date', 'telema_service_now', 'telema_acq_server', 'telema_server_size', 'telema_server_color', 'telema_mail', 'telema_arrival_date', 'telema_arrival_time', 'telema_benefits'
];
}
