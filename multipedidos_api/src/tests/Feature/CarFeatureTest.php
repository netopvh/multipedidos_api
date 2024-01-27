<?php

namespace Tests\Feature;

use Database\Seeders\CarSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarFeatureTest extends TestCase
{
    use WithFaker, DatabaseMigrations;

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
     * A basic feature test example.
     */
    public function test_show_errors_on_empty_fields() : void
    {
        $response = $this->postJson('/api/cars', []);

        $response->assertStatus(422);
    }

    public function test_show_errors_on_invalid_fields() : void
    {
        $response = $this->postJson('/api/cars', [
            'name' => $this->faker->randomElement($this->models),
            'brand' => $this->faker->randomElement($this->brands),
            'model' => $this->faker->randomElement($this->models),
            'year' => $this->faker->year
        ]);

        $response->assertStatus(422);
    }

    public function test_show_errors_on_invalid_year() : void
    {
        $response = $this->postJson('/api/cars', [
            'model' => $this->faker->randomElement($this->models),
            'brand' => $this->faker->randomElement($this->brands),
            'color' => $this->faker->colorName,
            'year' => 1234
        ]);

        $response->assertStatus(422);
    }

    public function test_create_new_car() : void
    {
        $response = $this->postJson('/api/cars', [
            'model' => $this->faker->randomElement($this->models),
            'brand' => $this->faker->randomElement($this->brands),
            'color' => $this->faker->colorName,
            'year' => 2022
        ]);

        $response->assertStatus(201);
    }

    public function test_show_all_cars() : void
    {

        $this->seed(CarSeeder::class);

        $response = $this->getJson('/api/cars');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Lista carregada com sucesso'
        ]);
    }

    public function test_car_not_exists_in_db() : void
    {
        $response = $this->getJson('/api/cars/1000');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'message' => 'Carro não encontrado'
        ]);
    }

    public function test_show_car_by_id() : void
    {
        $this->seed(CarSeeder::class);

        $response = $this->getJson('/api/cars/1');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Carro carregado com sucesso'
        ]);
    }

    public function test_update_car_by_id() : void
    {
        $this->seed(CarSeeder::class);

        $response = $this->putJson('/api/cars/1', [
            'model' => $this->faker->randomElement($this->models),
            'brand' => $this->faker->randomElement($this->brands),
            'color' => $this->faker->colorName,
            'year' => 2022
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Carro atualizado com sucesso'
        ]);
    }

    public function test_delete_car_by_id() : void
    {
        $this->seed(CarSeeder::class);

        $response = $this->deleteJson('/api/cars/1');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Carro deletado com sucesso'
        ]);
    }
}
