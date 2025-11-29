<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name'=>'Eau 0.5L','sku'=>'WATER-05','unit'=>'bottle','qty_on_hand'=>120,'min_qty'=>20],
            ['name'=>'Coca-Cola','sku'=>'COKE-33','unit'=>'can','qty_on_hand'=>96,'min_qty'=>24],
            ['name'=>'Red Bull','sku'=>'RB-25','unit'=>'can','qty_on_hand'=>48,'min_qty'=>12],
            ['name'=>'Hawai','sku'=>'HW-33','unit'=>'can','qty_on_hand'=>72,'min_qty'=>12],
        ];
        foreach ($items as $i) { Stock::firstOrCreate(['sku'=>$i['sku']], $i); }
    }
}
