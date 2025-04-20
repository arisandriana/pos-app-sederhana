<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spareparts = [
            [
                'nama' => 'Processor Intel Core i5-12400F',
                'harga' => 2850000,
                'stok' => 15,
                'deskripsi' => 'Processor Intel Core i5-12400F (12M Cache, up to 4.40 GHz) LGA 1700 Alder Lake',
                'barcode' => 'PROC-INT-I5-12400F'
            ],
            [
                'nama' => 'Processor AMD Ryzen 5 5600X',
                'harga' => 2750000,
                'stok' => 12,
                'deskripsi' => 'Processor AMD Ryzen 5 5600X (35MB Cache, up to 4.6GHz) Socket AM4',
                'barcode' => 'PROC-AMD-5600X'
            ],
            [
                'nama' => 'Motherboard ASUS ROG STRIX B550-F GAMING',
                'harga' => 3250000,
                'stok' => 8,
                'deskripsi' => 'Motherboard ASUS ROG STRIX B550-F GAMING Socket AM4 DDR4 ATX',
                'barcode' => 'MB-ASUS-B550F'
            ],
            [
                'nama' => 'Motherboard MSI MAG B660M MORTAR',
                'harga' => 2950000,
                'stok' => 10,
                'deskripsi' => 'Motherboard MSI MAG B660M MORTAR DDR4 Socket LGA 1700 mATX',
                'barcode' => 'MB-MSI-B660M'
            ],
            [
                'nama' => 'RAM Corsair Vengeance LPX 16GB (2x8GB) DDR4',
                'harga' => 1100000,
                'stok' => 25,
                'deskripsi' => 'RAM Corsair Vengeance LPX 16GB (2x8GB) DDR4 3200MHz C16 Memory Kit',
                'barcode' => 'RAM-COR-16GB-3200'
            ],
            [
                'nama' => 'RAM G.Skill Trident Z RGB 32GB (2x16GB) DDR4',
                'harga' => 2300000,
                'stok' => 15,
                'deskripsi' => 'RAM G.Skill Trident Z RGB 32GB (2x16GB) DDR4 3600MHz C18 Memory Kit with RGB Lighting',
                'barcode' => 'RAM-GSK-32GB-3600'
            ],
            [
                'nama' => 'SSD Samsung 970 EVO Plus 1TB M.2 NVMe',
                'harga' => 1650000,
                'stok' => 20,
                'deskripsi' => 'SSD Samsung 970 EVO Plus 1TB M.2 NVMe PCIe 3.0 Internal Solid State Drive',
                'barcode' => 'SSD-SAM-970-1TB'
            ],
            [
                'nama' => 'SSD WD Black SN850 2TB M.2 NVMe',
                'harga' => 4300000,
                'stok' => 8,
                'deskripsi' => 'SSD WD Black SN850 2TB M.2 NVMe PCIe 4.0 Internal Solid State Drive',
                'barcode' => 'SSD-WD-SN850-2TB'
            ],
            [
                'nama' => 'HDD Seagate Barracuda 2TB',
                'harga' => 850000,
                'stok' => 30,
                'deskripsi' => 'HDD Seagate Barracuda 2TB 7200 RPM 256MB Cache SATA 6.0Gb/s 3.5"',
                'barcode' => 'HDD-SEA-2TB'
            ],
            [
                'nama' => 'VGA ASUS TUF Gaming GeForce RTX 3060 OC 12GB',
                'harga' => 5500000,
                'stok' => 7,
                'deskripsi' => 'VGA ASUS TUF Gaming GeForce RTX 3060 OC Edition 12GB GDDR6',
                'barcode' => 'VGA-ASUS-3060-12GB'
            ],
            [
                'nama' => 'VGA MSI Radeon RX 6600 XT GAMING X 8GB',
                'harga' => 5350000,
                'stok' => 5,
                'deskripsi' => 'VGA MSI Radeon RX 6600 XT GAMING X 8GB GDDR6',
                'barcode' => 'VGA-MSI-6600XT-8GB'
            ],
            [
                'nama' => 'Power Supply Corsair RM750x 750 Watt 80+ Gold',
                'harga' => 1950000,
                'stok' => 12,
                'deskripsi' => 'Power Supply Corsair RM750x 750 Watt 80+ Gold Certified Fully Modular',
                'barcode' => 'PSU-COR-RM750X'
            ],
            [
                'nama' => 'Casing NZXT H510 Compact Mid-Tower',
                'harga' => 1250000,
                'stok' => 10,
                'deskripsi' => 'Casing NZXT H510 Compact Mid-Tower ATX Case with Tempered Glass',
                'barcode' => 'CASE-NZXT-H510'
            ],
            [
                'nama' => 'CPU Cooler Noctua NH-D15',
                'harga' => 1550000,
                'stok' => 9,
                'deskripsi' => 'CPU Cooler Noctua NH-D15 Premium Dual-Tower CPU Cooler with 2x NF-A15 PWM 140mm Fans',
                'barcode' => 'COOL-NOC-NHD15'
            ],
            [
                'nama' => 'CPU Cooler Cooler Master Hyper 212 RGB',
                'harga' => 575000,
                'stok' => 18,
                'deskripsi' => 'CPU Cooler Cooler Master Hyper 212 RGB Black Edition CPU Air Cooler with RGB Fan',
                'barcode' => 'COOL-CM-212RGB'
            ],
            [
                'nama' => 'Monitor LG UltraGear 27" IPS 165Hz 1ms',
                'harga' => 3850000,
                'stok' => 6,
                'deskripsi' => 'Monitor LG UltraGear 27" IPS 1ms 165Hz HDR 10 WQHD (2560 x 1440) Gaming Monitor',
                'barcode' => 'MON-LG-27GL850'
            ],
            [
                'nama' => 'Keyboard Mechanical Logitech G Pro X',
                'harga' => 1850000,
                'stok' => 14,
                'deskripsi' => 'Keyboard Mechanical Logitech G Pro X Tenkeyless Mechanical Gaming Keyboard with Swappable Switches',
                'barcode' => 'KB-LOG-GPROX'
            ],
            [
                'nama' => 'Mouse Gaming Razer Viper Ultimate',
                'harga' => 1950000,
                'stok' => 11,
                'deskripsi' => 'Mouse Gaming Razer Viper Ultimate Hyperspeed Wireless Gaming Mouse with Charging Dock',
                'barcode' => 'MOUSE-RZ-VIPER'
            ],
            [
                'nama' => 'Fan Corsair LL120 RGB 120mm',
                'harga' => 450000,
                'stok' => 35,
                'deskripsi' => 'Fan Corsair LL120 RGB 120mm Dual Light Loop RGB LED PWM Fan — Single Pack',
                'barcode' => 'FAN-COR-LL120'
            ],
            [
                'nama' => 'Thermal Paste Arctic MX-4 4g',
                'harga' => 120000,
                'stok' => 50,
                'deskripsi' => 'Thermal Paste Arctic MX-4 4g Thermal Compound Paste, Carbon Based High Performance',
                'barcode' => 'THERMAL-ARCTIC-MX4'
            ]
        ];

        foreach ($spareparts as $sparepart) {
            Produk::create([
                'NamaProduk' => $sparepart['nama'],
                'Harga' => $sparepart['harga'],
                'Stok' => $sparepart['stok'],
                'Deskripsi' => $sparepart['deskripsi'],
                'Barcode' => $sparepart['barcode'],
            ]);
        }
    }
}