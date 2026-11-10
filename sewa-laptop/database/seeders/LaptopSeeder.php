<?php

namespace Database\Seeders;

use App\Models\Laptop;
use Illuminate\Database\Seeder;

class LaptopSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $laptops = [
            // Dell (7 karakter serial number)
            ['merk' => 'Dell', 'tipe' => 'Latitude 5420', 'spesifikasi' => 'Core i5 Gen 11, 8GB RAM, 256GB SSD', 'serial_number' => 'DL12345', 'harga_sewa_per_hari' => 150000, 'stok' => 5, 'status' => 'ready'],
            ['merk' => 'Dell', 'tipe' => 'Latitude 7490', 'spesifikasi' => 'Core i7 Gen 8, 16GB RAM, 512GB SSD', 'serial_number' => 'DL98765', 'harga_sewa_per_hari' => 170000, 'stok' => 3, 'status' => 'ready'],
            ['merk' => 'Dell', 'tipe' => 'Inspiron 3511', 'spesifikasi' => 'Core i3 Gen 11, 4GB RAM, 256GB SSD', 'serial_number' => 'DL54321', 'harga_sewa_per_hari' => 100000, 'stok' => 4, 'status' => 'ready'],
            ['merk' => 'Dell', 'tipe' => 'XPS 13 9310', 'spesifikasi' => 'Core i7 Gen 11, 16GB RAM, 512GB SSD', 'serial_number' => 'DL11223', 'harga_sewa_per_hari' => 250000, 'stok' => 2, 'status' => 'ready'],
            ['merk' => 'Dell', 'tipe' => 'Vostro 3400', 'spesifikasi' => 'Core i5 Gen 11, 8GB RAM, 512GB SSD', 'serial_number' => 'DL33445', 'harga_sewa_per_hari' => 135000, 'stok' => 6, 'status' => 'ready'],
            ['merk' => 'Dell', 'tipe' => 'Precision 3550', 'spesifikasi' => 'Core i7, 32GB RAM, 1TB SSD, Nvidia Quadro', 'serial_number' => 'DL55667', 'harga_sewa_per_hari' => 350000, 'stok' => 2, 'status' => 'ready'],

            // Lenovo (8 karakter serial number)
            ['merk' => 'Lenovo', 'tipe' => 'ThinkPad T480', 'spesifikasi' => 'Core i5 Gen 8, 16GB RAM, 512GB SSD', 'serial_number' => 'LN123456', 'harga_sewa_per_hari' => 125000, 'stok' => 4, 'status' => 'ready'],
            ['merk' => 'Lenovo', 'tipe' => 'ThinkPad X1 Carbon', 'spesifikasi' => 'Core i7 Gen 10, 16GB RAM, 512GB SSD', 'serial_number' => 'LN654321', 'harga_sewa_per_hari' => 220000, 'stok' => 3, 'status' => 'ready'],
            ['merk' => 'Lenovo', 'tipe' => 'IdeaPad Slim 3', 'spesifikasi' => 'Ryzen 3 5300U, 8GB RAM, 256GB SSD', 'serial_number' => 'LN112233', 'harga_sewa_per_hari' => 95000, 'stok' => 7, 'status' => 'ready'],
            ['merk' => 'Lenovo', 'tipe' => 'ThinkBook 14 G2', 'spesifikasi' => 'Core i5 Gen 11, 8GB RAM, 512GB SSD', 'serial_number' => 'LN445566', 'harga_sewa_per_hari' => 140000, 'stok' => 5, 'status' => 'ready'],
            ['merk' => 'Lenovo', 'tipe' => 'Legion 5 Pro', 'spesifikasi' => 'Ryzen 7, 16GB RAM, 512GB SSD, RTX 3060', 'serial_number' => 'LN778899', 'harga_sewa_per_hari' => 320000, 'stok' => 2, 'status' => 'ready'],
            ['merk' => 'Lenovo', 'tipe' => 'Yoga Slim 7i', 'spesifikasi' => 'Core i7 Gen 11, 16GB RAM, 512GB SSD', 'serial_number' => 'LN990011', 'harga_sewa_per_hari' => 230000, 'stok' => 3, 'status' => 'ready'],

            // HP (10 karakter serial number)
            ['merk' => 'HP', 'tipe' => 'EliteBook 840 G5', 'spesifikasi' => 'Core i7 Gen 8, 16GB RAM, 512GB SSD', 'serial_number' => 'HP12345678', 'harga_sewa_per_hari' => 175000, 'stok' => 4, 'status' => 'ready'],
            ['merk' => 'HP', 'tipe' => 'ProBook 430 G6', 'spesifikasi' => 'Core i5 Gen 8, 8GB RAM, 256GB SSD', 'serial_number' => 'HP87654321', 'harga_sewa_per_hari' => 115000, 'stok' => 5, 'status' => 'ready'],
            ['merk' => 'HP', 'tipe' => 'Pavilion x360', 'spesifikasi' => 'Core i5 Gen 11, 8GB RAM, 512GB SSD', 'serial_number' => 'HP11223344', 'harga_sewa_per_hari' => 160000, 'stok' => 4, 'status' => 'ready'],
            ['merk' => 'HP', 'tipe' => 'Envy 13 ba1030TX', 'spesifikasi' => 'Core i7 Gen 11, 16GB RAM, 512GB SSD', 'serial_number' => 'HP55667788', 'harga_sewa_per_hari' => 210000, 'stok' => 3, 'status' => 'ready'],
            ['merk' => 'HP', 'tipe' => 'Omen 15 en0013AX', 'spesifikasi' => 'Ryzen 7, 16GB RAM, 512GB SSD, GTX 1660Ti', 'serial_number' => 'HP99887766', 'harga_sewa_per_hari' => 280000, 'stok' => 2, 'status' => 'ready'],
            ['merk' => 'HP', 'tipe' => 'ZBook Firefly 14', 'spesifikasi' => 'Core i7, 32GB RAM, 1TB SSD', 'serial_number' => 'HP44332211', 'harga_sewa_per_hari' => 330000, 'stok' => 2, 'status' => 'ready'],

            // Acer (10 karakter serial number)
            ['merk' => 'Acer', 'tipe' => 'Aspire 5 A514', 'spesifikasi' => 'Core i5 Gen 11, 8GB RAM, 512GB SSD', 'serial_number' => 'AC12345678', 'harga_sewa_per_hari' => 120000, 'stok' => 6, 'status' => 'ready'],
            ['merk' => 'Acer', 'tipe' => 'Swift 3 OLED', 'spesifikasi' => 'Ryzen 5 5600U, 16GB RAM, 512GB SSD', 'serial_number' => 'AC87654321', 'harga_sewa_per_hari' => 170000, 'stok' => 4, 'status' => 'ready'],
            ['merk' => 'Acer', 'tipe' => 'Nitro 5 AN515', 'spesifikasi' => 'Core i5 Gen 11, 16GB RAM, 512GB SSD, RTX 3050', 'serial_number' => 'AC11223344', 'harga_sewa_per_hari' => 240000, 'stok' => 3, 'status' => 'ready'],
            ['merk' => 'Acer', 'tipe' => 'TravelMate P2', 'spesifikasi' => 'Core i5 Gen 10, 8GB RAM, 256GB SSD', 'serial_number' => 'AC55667788', 'harga_sewa_per_hari' => 110000, 'stok' => 5, 'status' => 'ready'],

            // Gigabyte (10 karakter serial number)
            ['merk' => 'Gigabyte', 'tipe' => 'G5 KC', 'spesifikasi' => 'Core i5 Gen 10, 16GB RAM, 512GB SSD, RTX 3060', 'serial_number' => 'GB12345678', 'harga_sewa_per_hari' => 250000, 'stok' => 3, 'status' => 'ready'],
            ['merk' => 'Gigabyte', 'tipe' => 'Aero 15 OLED', 'spesifikasi' => 'Core i7 Gen 10, 16GB RAM, 512GB SSD', 'serial_number' => 'GB87654321', 'harga_sewa_per_hari' => 310000, 'stok' => 2, 'status' => 'ready'],

            // Asus (12 karakter serial number)
            ['merk' => 'Asus', 'tipe' => 'Vivobook Pro 14', 'spesifikasi' => 'Ryzen 5, 16GB RAM, 512GB SSD', 'serial_number' => 'AS1234567890', 'harga_sewa_per_hari' => 200000, 'stok' => 4, 'status' => 'ready'],
            ['merk' => 'Asus', 'tipe' => 'Zenbook 14 UX425', 'spesifikasi' => 'Core i5 Gen 11, 8GB RAM, 512GB SSD', 'serial_number' => 'AS0987654321', 'harga_sewa_per_hari' => 210000, 'stok' => 4, 'status' => 'ready'],
            ['merk' => 'Asus', 'tipe' => 'TUF Gaming F15', 'spesifikasi' => 'Core i5 Gen 11, 8GB RAM, 512GB SSD, RTX 3050', 'serial_number' => 'AS1122334455', 'harga_sewa_per_hari' => 260000, 'stok' => 5, 'status' => 'ready'],
            ['merk' => 'Asus', 'tipe' => 'ROG Strix G15', 'spesifikasi' => 'Ryzen 7, 16GB RAM, 512GB SSD, RTX 3070', 'serial_number' => 'AS5566778899', 'harga_sewa_per_hari' => 350000, 'stok' => 2, 'status' => 'ready'],

            // Apple (12 karakter serial number)
            ['merk' => 'Apple', 'tipe' => 'MacBook Air M1', 'spesifikasi' => 'Apple M1 Chip, 8GB RAM, 256GB SSD', 'serial_number' => 'AP1234567890', 'harga_sewa_per_hari' => 300000, 'stok' => 3, 'status' => 'ready'],
            ['merk' => 'Apple', 'tipe' => 'MacBook Pro 14 M2', 'spesifikasi' => 'Apple M2 Pro, 16GB RAM, 512GB SSD', 'serial_number' => 'AP0987654321', 'harga_sewa_per_hari' => 450000, 'stok' => 2, 'status' => 'ready'],
            ['merk' => 'Apple', 'tipe' => 'MacBook Air M2', 'spesifikasi' => 'Apple M2 Chip, 8GB RAM, 256GB SSD', 'serial_number' => 'AP1122334455', 'harga_sewa_per_hari' => 370000, 'stok' => 3, 'status' => 'ready'],
            ['merk' => 'Apple', 'tipe' => 'MacBook Pro 16 M1', 'spesifikasi' => 'Apple M1 Max, 32GB RAM, 1TB SSD', 'serial_number' => 'AP5566778899', 'harga_sewa_per_hari' => 600000, 'stok' => 1, 'status' => 'ready'],
        ];

        foreach ($laptops as $laptop) {
            Laptop::firstOrCreate(
                ['serial_number' => $laptop['serial_number']],
                $laptop
            );
        }
    }
}
