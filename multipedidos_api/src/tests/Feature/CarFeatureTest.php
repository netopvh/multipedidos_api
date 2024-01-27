<?php

use App\Models\Car;
use function Pest\Faker\fake;

test('show errors on empty fields', function () {
    $response = $this->postJson('/api/cars', []);

    expect($response->status())->toBe(422);
    expect($response->json('errors'))->toHaveCount(4);
    expect($response->json('errors.model'))->toContain('O Modelo é obrigatório!');
    expect($response->json('errors.brand'))->toContain('A Marca é obrigatória!');
    expect($response->json('errors.color'))->toContain('A Cor é obrigatória!');
    expect($response->json('errors.year'))->toContain('O Ano é obrigatório!');

});

test('show errors on submit invalid fields', function () {

    $response = $this->postJson('/api/cars', [
        'name' => fake('pt_BR')->name,
        'brand' => fake()->randomElement(['Honda', 'Hyundai', 'Jeep']),
        'model' => fake()->randomElement(['Accord', 'Accent', 'Cherokee']),
        'year' => fake()->year
    ]);

    expect($response->status())->toBe(422);

});

test('show errors on submit invalid year', function () {

    $response = $this->postJson('/api/cars', [
        'model' => fake()->randomElement(['Accord', 'Accent', 'Cherokee']),
        'brand' => fake()->randomElement(['Honda', 'Hyundai', 'Jeep']),
        'color' => fake()->colorName,
        'year' => 1234
    ]);

    expect($response->status())->toBe(422);
    expect($response->json('errors.year'))->toContain('O Ano deve ser maior ou igual a 1900!');

});

test('create new car', function () {

    $model = fake()->randomElement(['Accord', 'Accent', 'Cherokee']);
    $brand = fake()->randomElement(['Honda', 'Hyundai', 'Jeep']);
    $color = fake()->colorName;
    $year = fake()->year;

    $response = $this->postJson('/api/cars', [
        'model' => $model,
        'brand' => $brand,
        'color' => $color,
        'year' => $year
    ]);

    expect($response->status())->toBe(201);
    expect($response->json('message'))->toBe('Carro criado com sucesso');
    expect($response->json('data.model'))->toBe($model);
    expect($response->json('data.brand'))->toBe($brand);
    expect($response->json('data.color'))->toBe($color);
    expect($response->json('data.year'))->toBe($year);

});


test('show all cars', function () {

    $cars = Car::factory()->count(10)->create();

    $response = $this->getJson('/api/cars');

    expect($response->status())->toBe(200);
    expect($response->json('message'))->toBe('Lista carregada com sucesso');
    expect($response->json('data'))->toHaveCount(10);

});

test('show car by id', function () {

    $car = Car::factory()->create();

    $response = $this->getJson('/api/cars/' . $car->id);

    expect($response->status())->toBe(200);
    expect($response->json('message'))->toBe('Carro carregado com sucesso');
    expect($response->json('data.model'))->toBe($car->model);
    expect($response->json('data.brand'))->toBe($car->brand);
    expect($response->json('data.color'))->toBe($car->color);
    expect($response->json('data.year'))->toBe($car->year);

});

test('car not exists in database', function () {

    $response = $this->getJson('/api/cars/' . fake()->randomDigit);

    expect($response->status())->toBe(404);
    expect($response->json('message'))->toBe('Carro não encontrado');

});

test('update car by id', function () {

    $car = Car::factory()->create();

    $model = fake()->randomElement(['Accord', 'Accent', 'Cherokee']);
    $brand = fake()->randomElement(['Honda', 'Hyundai', 'Jeep']);
    $color = fake()->colorName;
    $year = (int) fake()->year;

    $response = $this->putJson('/api/cars/' . $car->id, [
        'model' => $model,
        'brand' => $brand,
        'color' => $color,
        'year' => $year
    ]);

    expect($response->status())->toBe(200);
    expect($response->json('message'))->toBe('Carro atualizado com sucesso');
    expect($response->json('data.model'))->toBe($model);
    expect($response->json('data.brand'))->toBe($brand);
    expect($response->json('data.color'))->toBe($color);
    expect($response->json('data.year'))->toBe($year);

});

test('delete car by id', function () {

    $car = Car::factory()->create();

    $response = $this->deleteJson('/api/cars/' . $car->id);

    expect($response->status())->toBe(200);
    expect($response->json('message'))->toBe('Carro deletado com sucesso');

});
