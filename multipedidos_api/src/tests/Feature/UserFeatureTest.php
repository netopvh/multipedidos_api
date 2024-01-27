<?php

use function Pest\Faker\fake;
use App\Models\{Car, User};

test('list all users', function () {
    $response = $this->getJson('/api/users');

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Lista carregada com sucesso'
    ]);
});

test('show errors on submit empty attributes', function () {
    $response = $this->postJson('/api/users', []);

    $response->assertStatus(422);
});

test('show errors on submit withou password confirmation', function () {
    $response = $this->postJson('/api/users', [
        'name' => fake('pt_BR')->name,
        'email' => fake()->email,
        'password' => fake()->password,
    ]);

    $response->assertStatus(422);
});

test('show errors on submit invalid field params', function () {
    $response = $this->postJson('/api/users', [
        'name' => fake('pt_BR')->name,
        'email' => fake()->firstName,
        'password' => fake()->password,
        'password_confirmation' => fake()->password
    ]);

    $response->assertStatus(422);
});

test('create new user', function () {

    $password = fake()->password;

    $response = $this->postJson('/api/users', [
        'name' => fake('pt_BR')->name,
        'email' => fake()->email,
        'password' => $password,
        'password_confirmation' => $password
    ]);

    $response->assertStatus(201);
    $response->assertJson([
        'message' => 'Usuário criado com sucesso'
    ]);
});

test('show errors on invalid id param', function () {

    $response = $this->getJson('/api/users/' . fake()->randomDigit);

    $response->assertStatus(404);
});

test('show user by id', function () {

    $user = User::factory()->create();

    $response = $this->getJson('/api/users/' . $user->id);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Usuário carregado com sucesso',
        'data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]
    ]);
});

test('update user email and password', function () {

    $user = User::factory()->create();

    $password = fake()->password;

    $response = $this->putJson('/api/users/update/' . $user->id, [
        'email' => fake()->email,
        'password' => $password,
        'password_confirmation' => $password
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Senha atualizada com sucesso'
    ]);
});

test('attach car to user', function () {

    $user = User::factory()->create();
    $car = Car::factory()->create();

    $response = $this->postJson('/api/users/attach-car/' . $user->id, [
        'car_id' => $car->id
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Carro associado com sucesso'
    ]);
});

test('detach car from user', function () {

    $user = User::factory()->create();
    $car = Car::factory()->create();
    $user->cars()->attach($car->id);


    $response = $this->postJson('/api/users/detach-car/' . $user->id, [
        'car_id' => $car->id
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Carro desassociado com sucesso'
    ]);
});

test('show errors on attach car to user', function () {

    $user = User::factory()->create();
    $car = Car::factory()->create();
    $user->cars()->attach($car->id);

    $response = $this->postJson('/api/users/attach-car/' . $user->id, [
        'car_id' => $car->id
    ]);

    $response->assertStatus(400);
    $response->assertJson([
        'message' => 'Carro já associado ao usuário'
    ]);
});

test('show error on detach car from user', function () {

    $user = User::factory()->create();
    $car = Car::factory()->create();

    $response = $this->postJson('/api/users/detach-car/' . $user->id, [
        'car_id' => $car->id
    ]);

    $response->assertStatus(400);
    $response->assertJson([
        'message' => 'Carro não associado ao usuário'
    ]);
});
