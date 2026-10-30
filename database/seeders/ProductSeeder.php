<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['Mechanical Keyboard', 'HW-001', 'Peripherals', 1850, 25, 5, 'TechSource'],
            ['Wireless Mouse', 'HW-002', 'Peripherals', 650, 40, 10, 'TechSource'],
            ['24-inch Monitor', 'HW-003', 'Displays', 5200, 12, 3, 'Display Hub'],
            ['8GB DDR4 RAM', 'HW-004', 'Memory', 950, 30, 8, 'PC Parts Center'],
            ['512GB SSD', 'HW-005', 'Storage', 2100, 18, 5, 'PC Parts Center'],
            ['1TB HDD', 'HW-006', 'Storage', 1850, 15, 5, 'PC Parts Center'],
            ['ATX Power Supply', 'HW-007', 'Power', 2600, 10, 3, 'PowerTech'],
            ['Micro-ATX Motherboard', 'HW-008', 'Components', 4200, 8, 3, 'PC Parts Center'],
            ['Intel Core Processor', 'HW-009', 'Components', 6500, 7, 2, 'Processor Depot'],
            ['USB Headset', 'HW-010', 'Peripherals', 780, 22, 5, 'TechSource'],
            ['Webcam', 'HW-011', 'Peripherals', 1200, 14, 4, 'TechSource'],
            ['Ethernet Cable', 'HW-012', 'Networking', 180, 50, 10, 'Network Supplies'],
            ['Gigabit Network Switch', 'HW-013', 'Networking', 1600, 9, 3, 'Network Supplies'],
            ['USB Flash Drive 64GB', 'HW-014', 'Storage', 350, 35, 8, 'TechSource'],
            ['Laptop Cooling Pad', 'HW-015', 'Accessories', 550, 4, 5, 'Computer Accessories Co.'],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['sku' => $item[1]],
                [
                    'name' => $item[0],
                    'sku' => $item[1],
                    'category' => $item[2],
                    'description' => $item[0] . ' for computer hardware inventory.',
                    'price' => $item[3],
                    'quantity' => $item[4],
                    'reorder_level' => $item[5],
                    'supplier' => $item[6],
                ]
            );
        }
    }
}