<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * List of Brands
     */

    private $brands = [
        'Honda',
        'Hyundai',
        'Jeep',
        'Mitsubishi',
        'Nissan',
        'Volkswagen',
        'Volvo',
        'Audi',
        'BMW',
        'Chevrolet',
        'Citroen',
        'Dodge',
        'Ferrari',
        'Fiat',
        'Ford'
    ];

    private $models = [
        'Accord',
        'Accent',
        'Cherokee',
        'Lancer',
        'Sentra',
        'Golf',
        'XC90',
        'A4',
        'X5',
        'Cruze',
        'C3',
        'Challenger',
        '488',
        '500',
        'Fiesta'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'model' => $this->faker->randomElement($this->models),
            'brand' => $this->faker->randomElement($this->brands),
            'color' => $this->faker->colorName,
            'year' => (int) $this->faker->year,
        ];
    }
}
