<?php

namespace Database\Seeders;

use App\Models\Booth;
use Illuminate\Database\Seeder;

class BoothSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $booth = Booth::create([
            'name' => 'Astra Zeneca',
            'x' => 3600,
            'y' => 100,
        ]);

        $booth->assets()->create([
            'name' => 'Astra Zeneca Background',
            'type' => 'Booth',
            'category' => 'background',
            'url' => 'https://dev.convention.psp.com.ph/images/lt.png',
        ]);

        $booth->assets()->create([
            'name' => 'Astra Zeneca Booth',
            'type' => 'Booth',
            'category' => 'booth',
            'url' => 'https://dev.convention.psp.com.ph/images/bt.png',
        ]);

        $hotspot = $booth->hotspots()->create([
            'name' => 'Brochures',
            'x' => 600,
            'y' => 600,
        ]);

        $hotspot->assets()->create([
            'name' => 'Sample PDF',
            'type' => 'Brochure',
            'category' => 'Brochures',
            'url' => 'https://dev.convention.psp.com.ph/images/bt.png',
        ]);

    }
}
