<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        for($i=0; $i<=100; $i++){
            DB::table('users')->insert([
                'name' => Str::random(10),
                'email' => Str::random(10).'@gmail.com',
                'role_id' => 2,
                'franchise_id' => 169,
                'p_ref_id' => 1,
                'confirmed_at' => Carbon::now(),
                'password' => Hash::make('password'),
            ]);
        }
            
    }
}
