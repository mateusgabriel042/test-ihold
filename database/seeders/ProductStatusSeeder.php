<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductStatus;
use Illuminate\Support\Facades\DB;

class ProductStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products_status')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $statusProducts = [
            'Disponível',
            'Indisponível',
            'Reservado',
            'Bloqueado'
        ];

        foreach($statusProducts as $status) {
            ProductStatus::create([
                'status_name' => $status
            ]);

        }
    }
}
