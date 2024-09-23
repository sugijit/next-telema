<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class initUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => "システム管理者",
                'user_cd' => "admin",
                'company_id' => "1",
                'role' => "nl_admin",
                'email' => "b.suldsaikhan@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('admin_dev'),
                'pass_decrypt' => "admin_dev",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "森屋敷暁",
                'user_cd' => "nextlink_a",
                'company_id' => "1",
                'role' => "user",
                'email' => "a.moriyashiki@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Akira0728'),
                'pass_decrypt' => "Akira0728",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "半田真彩",
                'user_cd' => "nextlink_hm",
                'company_id' => "1",
                'role' => "user",
                'email' => "m.handa@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Maya0000'),
                'pass_decrypt' => "Maya0000",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "井上海斗",
                'user_cd' => "nextlink_ik",
                'company_id' => "1",
                'role' => "user",
                'email' => "k.inoue@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Taka1230'),
                'pass_decrypt' => "Taka1230",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "板井瑞季",
                'user_cd' => "nextlink_im",
                'company_id' => "1",
                'role' => "user",
                'email' => "m.itai@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Taka1230'),
                'pass_decrypt' => "Taka1230",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "岩崎 裕也",
                'user_cd' => "nextlink_iy",
                'company_id' => "1",
                'role' => "admin",
                'email' => "y.iwasaki@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Yuya0809'),
                'pass_decrypt' => "Yuya0809",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "垣内崇広",
                'user_cd' => "nextlink_k",
                'company_id' => "1",
                'role' => "admin",
                'email' => "t.kakiuchi@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Taka1230'),
                'pass_decrypt' => "Taka1230",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "増井美樹",
                'user_cd' => "nextlink_m",
                'company_id' => "1",
                'role' => "admin",
                'email' => "m.masui@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Miki0103'),
                'pass_decrypt' => "Miki0103",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "森川詠",
                'user_cd' => "nextlink_mu",
                'company_id' => "1",
                'role' => "admin",
                'email' => "u.morikawa@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Taka1230'),
                'pass_decrypt' => "Taka1230",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "白井良子",
                'user_cd' => "nextlink_sr",
                'company_id' => "1",
                'role' => "admin",
                'email' => "r.shirai@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Ryoko0710'),
                'pass_decrypt' => "Ryoko0710",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "臼井令",
                'user_cd' => "nextlink_u",
                'company_id' => "1",
                'role' => "user",
                'email' => "r.usui@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Taka1230'),
                'pass_decrypt' => "Taka1230",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "渡壁麗香",
                'user_cd' => "nextlink_w",
                'company_id' => "1",
                'role' => "user",
                'email' => "r.watakabe@nl-nextlink.com",
                'email_verified_at' => now(),
                'password' => Hash::make('Reika0424'),
                'pass_decrypt' => "Reika0424",
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('companies')->insert([
            [
                'name' => "株式会社NextLink",
                'manager_name' => "増井美樹",
                'manager_phone' => "0120-983-777",
                'manager_mail' => "m.masui@nl-nextlink.com",
                'address' => "〒541-0053 大阪府大阪市中央区本町4丁目4－17RE-012ビル6階",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('product_lists')->insert([
            [
                'list_name' => "エブリィフレシャス",
                'list_alias' => "everyfrecious",
                'company_id' => "0120-983-777",
                'nl_link' => "https://stg.nextlink-portal.com/agent/product/015/regist",
                'entry_link' => "https://www.frecious.jp/regi/index.php",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
