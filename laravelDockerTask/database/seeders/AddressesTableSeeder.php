<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Address::create([
            'ip_address'=>'172.16.4.122',
            'location'=>'PF Ground 1'
        ]);

        Address::create([
            'ip_address'=>'192.168.1.1',
            'location'=>'Remote'
        ]);
    }
}
