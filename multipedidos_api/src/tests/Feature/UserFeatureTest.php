<?php

use function Pest\Faker\fake;
use App\Models\{Car, User};

test('list all users', function () {

    $users = User::factory()->count(3)->create();

    $response = $this->getJson('/api/users');

    expect(200)->toBe($response->status());
    expect($response->json('data'))->toHaveCount(3);

});

test('show errors on submit empty attributes', function () {

    $response = $this->postJson('/api/users', []);

    expect($response->status())->toBe(422);
});

test('show errors on submit withou password confirmation', function () {
    $response = $this->postJson('/api/users', [
        'name' => fake('pt_BR')->name,
        'email' => fake()->email,
        'password' => fake()->password,
    ]);

    expect($response->status())->toBe(422);
    expect($response->json('message'))->toBe('O campo senha não confere com a confirmação');

});

test('show errors on submit invalid email format', function () {

    $password = fake()->password;

    $response = $this->postJson('/api/users', [
        'name' => fake('pt_BR')->name,
        'email' => fake()->firstName,
        'password' => $password,
        'password_confirmation' => $password
    ]);

    expect($response->status())->toBe(422);
    expect($response->json('message'))->toBe('O campo email deve ser um email válido');

});

test('create new user', function () {

    $password = fake()->password;

    $response = $this->postJson('/api/users', [
        'name' => fake('pt_BR')->name,
        'email' => fake()->email,
        'password' => $password,
        'password_confirmation' => $password
    ]);

    expect($response->status())->toBe(201);
    expect($response->json('message'))->toBe('Usuário criado com sucesso');

});

test('show errors on invalid id param', function () {

    $response = $this->getJson('/api/users/' . fake()->randomDigit);

    expect($response->status())->toBe(404);
    expect($response->json('message'))->toBe('Usuário não encontrado');

});

test('show user by id', function () {

    $user = User::factory()->create();

    $response = $this->getJson('/api/users/' . $user->id);

    expect($response->status())->toBe(200);
    expect($response->json('message'))->toBe('Usuário carregado com sucesso');
    expect($response->json('data'))
        ->toContain($user->name)
        ->toContain($user->email);

});

test('update user email and password', function () {

    $user = User::factory()->create();

    $password = fake()->password;

    $response = $this->putJson('/api/users/update/' . $user->id, [
        'email' => fake()->email,
        'password' => $password,
        'password_confirmation' => $password
    ]);

    expect($response->status())->toBe(200);
    expect($response->json('message'))->toBe('Senha atualizada com sucesso');

});

test('attach car to user', function () {

    $user = User::factory()->create();
    $car = Car::factory()->create();

    $response = $this->postJson('/api/users/attach-car/' . $user->id, [
        'car_id' => $car->id
    ]);

    expect($response->status())->toBe(200);
    expect($response->json('message'))->toBe('Carro associado com sucesso');

});

test('detach car from user', function () {

    $user = User::factory()->create();
    $car = Car::factory()->create();
    $user->cars()->attach($car->id);


    $response = $this->postJson('/api/users/detach-car/' . $user->id, [
        'car_id' => $car->id
    ]);

    expect($response->status())->toBe(200);
    expect($response->json('message'))->toBe('Carro desassociado com sucesso');

});

test('show errors on attach car to user', function () {

    $user = User::factory()->create();
    $car = Car::factory()->create();
    $user->cars()->attach($car->id);

    $response = $this->postJson('/api/users/attach-car/' . $user->id, [
        'car_id' => $car->id
    ]);

    expect($response->status())->toBe(400);
    expect($response->json('message'))->toBe('Carro já associado ao usuário');

});

test('show error on detach car from user', function () {

    $user = User::factory()->create();
    $car = Car::factory()->create();

    $response = $this->postJson('/api/users/detach-car/' . $user->id, [
        'car_id' => $car->id
    ]);

    expect($response->status())->toBe(400);
    expect($response->json('message'))->toBe('Carro não associado ao usuário');

});
