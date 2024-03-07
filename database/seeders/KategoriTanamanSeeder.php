<?php

namespace Database\Seeders;

use App\Models\Operator\KategoriTanamanPalawija;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriTanamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriTanamanPalawija::create([
            'name' => 'kacang-kacangan',
            'description' => 'Kacang-kacangan adalah tanaman yang memiliki biji yang dapat dimakan. Kacang-kacangan merupakan sumber protein nabati yang baik untuk tubuh.',
            'image' => ('image_kategori_palawija/kacan-kacangan.png'),
        ]);
        KategoriTanamanPalawija::create([
            'name' => 'biji-bijian',
            'description' => 'Biji-bijian adalah tanaman yang memiliki biji yang dapat dimakan. Biji-bijian merupakan sumber karbohidrat yang baik untuk tubuh.',
            'image' => ('image_kategori_palawija/biji-bijian.jpg'),
        ]);
        KategoriTanamanPalawija::create([
            'name' => 'umbi-umbian',
            'description' => 'Umbi-umbian adalah tanaman yang memiliki umbi yang dapat dimakan. Umbi-umbian merupakan sumber karbohidrat yang baik untuk tubuh.',
            'image' => ('image_kategori_palawija/umbi-umbian.png'),
        ]);
    }
}
