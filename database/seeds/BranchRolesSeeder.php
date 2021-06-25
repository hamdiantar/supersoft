<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branches_roles')->insert([
            'branch_id'=> 1,
            'role_id'=> 1,
        ]);
    }
}
