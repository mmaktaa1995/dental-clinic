<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Tooth;
use Illuminate\Database\Eloquent\Factories\Factory;

class ToothFactory extends Factory
{
    protected $model = Tooth::class;

    public function definition(): array
    {
        $toothNames = [
            'Upper Right Third Molar', 'Upper Right Second Molar', 'Upper Right First Molar',
            'Upper Right Second Premolar', 'Upper Right First Premolar', 'Upper Right Canine',
            'Upper Right Lateral Incisor', 'Upper Right Central Incisor', 'Upper Left Central Incisor',
            'Upper Left Lateral Incisor', 'Upper Left Canine', 'Upper Left First Premolar',
            'Upper Left Second Premolar', 'Upper Left First Molar', 'Upper Left Second Molar',
            'Upper Left Third Molar', 'Lower Left Third Molar', 'Lower Left Second Molar',
            'Lower Left First Molar', 'Lower Left Second Premolar', 'Lower Left First Premolar',
            'Lower Left Canine', 'Lower Left Lateral Incisor', 'Lower Left Central Incisor',
            'Lower Right Central Incisor', 'Lower Right Lateral Incisor', 'Lower Right Canine',
            'Lower Right First Premolar', 'Lower Right Second Premolar', 'Lower Right First Molar',
            'Lower Right Second Molar', 'Lower Right Third Molar'
        ];
        
        return [
            'name' => $this->faker->unique()->randomElement($toothNames),
            'number' => $this->faker->numberBetween(1, 32),
            'image' => 'teeth/tooth-'.$this->faker->numberBetween(1, 16).'.svg',
            'extra' => [
                'type' => $this->faker->randomElement(['incisor', 'canine', 'premolar', 'molar']),
                'quadrant' => $this->faker->numberBetween(1, 4),
                'status' => $this->faker->randomElement(['healthy', 'crowned', 'missing', 'decayed']),
            ],
        ];
    }
}
