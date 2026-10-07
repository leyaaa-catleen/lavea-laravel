<?php
namespace Database\Seeders;

use App\Models\Perfume;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@lavea.test'], [
            'name' => 'Lavéa Admin', 'username' => 'admin', 'password' => 'Admin#1234', 'role' => 'admin',
        ]);

        $rows = [
            ['Violet Hour', 'Floral', 'Violet leaf, iris, white musk', 2450, 24, '#8e5bb0'],
            ['Rose Atelier', 'Floral', 'Damask rose, peony, amber', 2890, 18, '#d9779f'],
            ['Cedar & Smoke', 'Woody', 'Cedarwood, vetiver, black tea', 3150, 12, '#8a6344'],
            ['Midnight Oud', 'Oriental', 'Oud, saffron, vanilla bean', 4200, 4, '#4a2a5e'],
            ['Amber Dusk', 'Oriental', 'Amber, cardamom, tonka', 3400, 20, '#c98a3d'],
            ['Sea Salt Linen', 'Fresh', 'Sea salt, bergamot, cotton', 2250, 31, '#5fa8c4'],
            ['Green Fig', 'Fresh', 'Fig leaf, mint, cucumber', 2300, 3, '#6fa36f'],
            ['Sandal Veil', 'Woody', 'Sandalwood, cream, jasmine', 3250, 15, '#c7a98a'],
        ];
        foreach ($rows as [$n, $c, $no, $p, $s, $col]) {
            Perfume::updateOrCreate(['name' => $n], ['category' => $c, 'notes' => $no, 'price' => $p, 'stock' => $s, 'color' => $col]);
        }
    }
}
