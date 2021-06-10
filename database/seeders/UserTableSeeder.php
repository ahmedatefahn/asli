<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Hash;
class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();
        User::create(array(
            'name'     => 'asli',
            
            'email'    => 'asli@megatrust.co',
            'password' => Hash::make('asli'),
        ));
    }

}