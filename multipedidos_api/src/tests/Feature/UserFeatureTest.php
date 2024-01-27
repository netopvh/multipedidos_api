<?php

namespace Tests\Feature;

use Database\Seeders\CarSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{
    use WithFaker, DatabaseMigrations;
    /**
     * A basic feature test example.
     */
    public function test_list_all_users() : void
    {
        $this->seed(UserSeeder::class);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Lista carregada com sucesso'
        ]);
    }

    public function test_show_errors_on_empty_fields() : void
    {
        $response = $this->postJson('/api/users', []);

        $response->assertStatus(422);
    }

    public function test_error_on_password_confirmation() : void
    {
        $response = $this->postJson('/api/users', [
            'name' => $this->faker->name,
            'email' => $this->faker->firstName,
            'password' => $this->faker->password,
        ]);

        $response->assertStatus(422);
    }

    public function test_show_errors_on_invalid_field_data() : void
    {
        $response = $this->postJson('/api/users', [
            'name' => $this->faker->name,
            'email' => $this->faker->firstName,
            'password' => $this->faker->password,
            'password_confirmation' => $this->faker->password
        ]);

        $response->assertStatus(422);
    }

    public function test_create_new_user() : void
    {
        $password = $this->faker->password;

        $response = $this->postJson('/api/users', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password
        ]);

        $response->assertStatus(201);
    }

    public function test_not_exists_user() : void
    {
        $response = $this->getJson('/api/users/100');

        $response->assertStatus(404);
    }

    public function test_show_user_by_id() : void
    {
        $this->seed(UserSeeder::class);

        $response = $this->getJson('/api/users/1');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Usuário carregado com sucesso'
        ]);
    }

    public function test_update_user_password_and_email() : void
    {
        $this->seed(UserSeeder::class);

        $password = $this->faker->password;

        $response = $this->putJson('/api/users/update/1', [
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password
        ]);

        $response->assertStatus(200);
    }

    public function test_attach_car_to_user() : void
    {
        $this->seed(UserSeeder::class);
        $this->seed(CarSeeder::class);

        $response = $this->postJson('/api/users/attach-car/1', [
            'car_id' => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_detach_car_from_user() : void
    {
        $this->seed(UserSeeder::class);
        $this->seed(CarSeeder::class);

        $this->postJson('/api/users/attach-car/1', [
            'car_id' => 1
        ]);

        $response = $this->postJson('/api/users/detach-car/1', [
            'car_id' => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_error_if_users_has_car() : void
    {
        $this->seed(UserSeeder::class);
        $this->seed(CarSeeder::class);

        $this->postJson('/api/users/attach-car/1', [
            'car_id' => 1
        ]);

        $response = $this->postJson('/api/users/attach-car/1', [
            'car_id' => 1
        ]);

        $response->assertStatus(400);
    }
}
