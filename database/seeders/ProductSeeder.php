<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::insert([
            ['name' => 'Beras', 'stock' => 100, 'price' => 12000],
            ['name' => 'Minyak Goreng', 'stock' => 80, 'price' => 15000],
            ['name' => 'Gula Pasir', 'stock' => 50, 'price' => 13000],
        ]);
    }

    public function run()
{
    $this->call([
        ProductSeeder::class,
    ]);
}

}
