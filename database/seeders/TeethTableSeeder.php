<?php

namespace Database\Seeders;

use App\Models\Tooth;
use Illuminate\Database\Seeder;

class TeethTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teeth = [
            ['name' => 'Upper Right Third Molar', 'number' => 1, 'image' => 'teeth/1.png'],
            ['name' => 'Upper Right Second Molar', 'number' => 2, 'image' => 'teeth/2.png'],
            ['name' => 'Upper Right First Molar', 'number' => 3, 'image' => 'teeth/3.png'],
            ['name' => 'Upper Right Second Premolar', 'number' => 4, 'image' => 'teeth/4.png'],
            ['name' => 'Upper Right First Premolar', 'number' => 5, 'image' => 'teeth/5.png'],
            ['name' => 'Upper Right Canine', 'number' => 6, 'image' => 'teeth/6.png'],
            ['name' => 'Upper Right Lateral Incisor', 'number' => 7, 'image' => 'teeth/7.png'],
            ['name' => 'Upper Right Central Incisor', 'number' => 8, 'image' => 'teeth/8.png'],
            ['name' => 'Upper Left Central Incisor', 'number' => 9, 'image' => 'teeth/9.png'],
            ['name' => 'Upper Left Lateral Incisor', 'number' => 10, 'image' => 'teeth/10.png'],
            ['name' => 'Upper Left Canine', 'number' => 11, 'image' => 'teeth/11.png'],
            ['name' => 'Upper Left First Premolar', 'number' => 12, 'image' => 'teeth/12.png'],
            ['name' => 'Upper Left Second Premolar', 'number' => 13, 'image' => 'teeth/13.png'],
            ['name' => 'Upper Left First Molar', 'number' => 14, 'image' => 'teeth/14.png'],
            ['name' => 'Upper Left Second Molar', 'number' => 15, 'image' => 'teeth/15.png'],
            ['name' => 'Upper Left Third Molar', 'number' => 16, 'image' => 'teeth/16.png'],

            ['name' => 'Lower Left Third Molar', 'number' => 17, 'image' => 'teeth/17.png'],
            ['name' => 'Lower Left Second Molar', 'number' => 18, 'image' => 'teeth/18.png'],
            ['name' => 'Lower Left First Molar', 'number' => 19, 'image' => 'teeth/19.png'],
            ['name' => 'Lower Left Second Premolar', 'number' => 20, 'image' => 'teeth/20.png'],
            ['name' => 'Lower Left First Premolar', 'number' => 21, 'image' => 'teeth/21.png'],
            ['name' => 'Lower Left Canine', 'number' => 22, 'image' => 'teeth/22.png'],
            ['name' => 'Lower Left Lateral Incisor', 'number' => 23, 'image' => 'teeth/23.png'],
            ['name' => 'Lower Left Central Incisor', 'number' => 24, 'image' => 'teeth/24.png'],
            ['name' => 'Lower Right Central Incisor', 'number' => 25, 'image' => 'teeth/25.png'],
            ['name' => 'Lower Right Lateral Incisor', 'number' => 26, 'image' => 'teeth/26.png'],
            ['name' => 'Lower Right Canine', 'number' => 27, 'image' => 'teeth/27.png'],
            ['name' => 'Lower Right First Premolar', 'number' => 28, 'image' => 'teeth/28.png'],
            ['name' => 'Lower Right Second Premolar', 'number' => 29, 'image' => 'teeth/29.png'],
            ['name' => 'Lower Right First Molar', 'number' => 30, 'image' => 'teeth/30.png'],
            ['name' => 'Lower Right Second Molar', 'number' => 31, 'image' => 'teeth/31.png'],
            ['name' => 'Lower Right Third Molar', 'number' => 32, 'image' => 'teeth/32.png'],
        ];

        foreach ($teeth as $toothData) {
            Tooth::firstOrCreate(
                ['number' => $toothData['number']],
                [
                    'name' => $toothData['name'],
                    'image' => $toothData['image'],
                    'extra' => json_encode([
                        'type' => $this->getToothType($toothData['number']),
                        'quadrant' => $this->getToothQuadrant($toothData['number']),
                    ])
                ]
            );
        }
    }

    /**
     * Get the type of tooth based on its number
     */
    protected function getToothType(int $number): string
    {
        $types = [
            'incisor' => [7, 8, 9, 10, 23, 24, 25, 26],
            'canine' => [6, 11, 22, 27],
            'premolar' => [4, 5, 12, 13, 20, 21, 28, 29],
            'molar' => [1, 2, 3, 14, 15, 16, 17, 18, 19, 30, 31, 32],
        ];

        foreach ($types as $type => $numbers) {
            if (in_array($number, $numbers)) {
                return $type;
            }
        }

        return 'other';
    }

    /**
     * Get the quadrant of the tooth based on its number
     */
    protected function getToothQuadrant(int $number): int
    {
        if ($number >= 1 && $number <= 8) {
            return 1; // Upper right
        } elseif ($number >= 9 && $number <= 16) {
            return 2; // Upper left
        } elseif ($number >= 17 && $number <= 24) {
            return 3; // Lower left
        } else {
            return 4; // Lower right
        }
    }
}
