<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_status')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $statusOrder = [
            'Pendende',
            'Aguardando Aprovação',
            'Aprovado',
            'Reprovado',
            'Cancelado',
            'Entregue',
            'Retirado'
        ];

        foreach($statusOrder as $status) {
            OrderStatus::create([
                'status_name' => $status
            ]);

        }
    }
}
