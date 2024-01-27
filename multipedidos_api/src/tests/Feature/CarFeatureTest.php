<?php

use App\Models\Car;
use function Pest\Faker\fake;

test('show errors on empty fields', function () {
    $response = $this->postJson('/api/cars', []);

    $response->assertStatus(422);
});

test('show errors on invalid fields', function () {

    $response = $this->postJson('/api/cars', [
        'name' => fake('pt_BR')->name,
        'brand' => fake()->randomElement(['Honda', 'Hyundai', 'Jeep']),
        'model' => fake()->randomElement(['Accord', 'Accent', 'Cherokee']),
        'year' => fake()->year
    ]);

    $response->assertStatus(422);
});

test('show errors on invalid year', function () {

    $response = $this->postJson('/api/cars', [
        'model' => fake()->randomElement(['Accord', 'Accent', 'Cherokee']),
        'brand' => fake()->randomElement(['Honda', 'Hyundai', 'Jeep']),
        'color' => fake()->colorName,
        'year' => 1234
    ]);

    $response->assertStatus(422);
});

test('create new car', function () {

    $response = $this->postJson('/api/cars', [
        'model' => fake()->randomElement(['Accord', 'Accent', 'Cherokee']),
        'brand' => fake()->randomElement(['Honda', 'Hyundai', 'Jeep']),
        'color' => fake()->colorName,
        'year' => fake()->year
    ]);

    $response->assertStatus(201);
});


test('show all cars', function () {

    $response = $this->getJson('/api/cars');

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Lista carregada com sucesso'
    ]);
});

test('show car by id', function () {

    $car = Car::factory()->create();

    $response = $this->getJson('/api/cars/' . $car->id);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Carro carregado com sucesso',
        'data' => [
            'id' => $car->id,
            'model' => $car->model,
            'brand' => $car->brand,
            'color' => $car->color,
            'year' => $car->year
        ]
    ]);
});

test('car not exists in database', function () {

    $response = $this->getJson('/api/cars/' . fake()->randomDigit);

    $response->assertStatus(404);
    $response->assertJson([
        'message' => 'Carro não encontrado'
    ]);
});

test('update car by id', function () {

    $car = Car::factory()->create();

    $model = fake()->randomElement(['Accord', 'Accent', 'Cherokee']);
    $brand = fake()->randomElement(['Honda', 'Hyundai', 'Jeep']);
    $color = fake()->colorName;
    $year = fake()->year;

    $response = $this->putJson('/api/cars/' . $car->id, [
        'model' => $model,
        'brand' => $brand,
        'color' => $color,
        'year' => $year
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Carro atualizado com sucesso',
        'data' => [
            'id' => $car->id,
            'model' => $model,
            'brand' => $brand,
            'color' => $color,
            'year' => $year
        ]
    ]);
});

test('delete car by id', function () {

    $car = Car::factory()->create();

    $response = $this->deleteJson('/api/cars/' . $car->id);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Carro deletado com sucesso'
    ]);
});

// namespace Tests\Feature;

// use Database\Seeders\CarSeeder;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
// use Tests\TestCase;

// class CarFeatureTest extends TestCase
// {
//     use WithFaker, DatabaseMigrations;

//     private $brands = [
//         'Honda',
//         'Hyundai',
//         'Jeep',
//         'Mitsubishi',
//         'Nissan',
//         'Volkswagen',
//         'Volvo',
//         'Audi',
//         'BMW',
//         'Chevrolet',
//         'Citroen',
//         'Dodge',
//         'Ferrari',
//         'Fiat',
//         'Ford'
//     ];

//     private $models = [
//         'Accord',
//         'Accent',
//         'Cherokee',
//         'Lancer',
//         'Sentra',
//         'Golf',
//         'XC90',
//         'A4',
//         'X5',
//         'Cruze',
//         'C3',
//         'Challenger',
//         '488',
//         '500',
//         'Fiesta'
//     ];

//     /**
//      * A basic feature test example.
//      */


//     public function test_delete_car_by_id() : void
//     {
//         $this->seed(CarSeeder::class);

//         $response = $this->deleteJson('/api/cars/1');

//         $response->assertStatus(200);
//         $response->assertJsonFragment([
//             'message' => 'Carro deletado com sucesso'
//         ]);
//     }
// }
