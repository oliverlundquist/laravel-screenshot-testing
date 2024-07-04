<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name'        => 'Rocket Evoluzione V1',
            'description' => 'These are espresso machines that I\'ve previously or currently own. Except for the La Marzocco Linea Classic which has always been my dream, there\'s no machine that\'s more beautiful!',
            'qty'         => 30,
            'image'       => '/images/rocket-evoluzione-v1.jpg'
        ]);
        Product::create([
            'name'        => 'La Marzocco Linea Classic',
            'description' => 'These are espresso machines that I\'ve previously or currently own. Except for the La Marzocco Linea Classic which has always been my dream, there\'s no machine that\'s more beautiful!',
            'qty'         => 30,
            'image'       => '/images/marzocco-linea-classic.webp'
        ]);
        Product::create([
            'name'        => 'La Pavoni Europiccola 8-Cup',
            'description' => 'These are espresso machines that I\'ve previously or currently own. Except for the La Marzocco Linea Classic which has always been my dream, there\'s no machine that\'s more beautiful!',
            'qty'         => 30,
            'image'       => '/images/pavoni-europiccola.jpg'
        ]);
        Product::create([
            'name'        => 'Flair 58',
            'description' => 'These are espresso machines that I\'ve previously or currently own. Except for the La Marzocco Linea Classic which has always been my dream, there\'s no machine that\'s more beautiful!',
            'qty'         => 30,
            'image'       => '/images/flair58.jpg'
        ]);
    }
}
