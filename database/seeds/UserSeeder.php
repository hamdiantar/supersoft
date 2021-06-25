<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $checkAdmin= User::where('email','admin@admin.com')->first();

        if(empty($checkAdmin)){

            $user = User::create(
                [
                    'name' => 'superadmin',
                    'username' => 'superadmin',
                    'status' => 1,
                    'super_admin' => 1,
                    'email'=>'superadmin@superadmin.com',
                    'password'=>bcrypt('123456'),
                    'phone'=>'00201013046794',
                    'branch_id'=> 1
                ]);

        }
    }
}
