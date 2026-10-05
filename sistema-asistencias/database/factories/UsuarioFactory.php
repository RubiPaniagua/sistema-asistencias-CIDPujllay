<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dni' => fake()->unique()->numerify('########'),
            'nombres' => fake()->firstName(),
            'apellidos' => fake()->lastName(),

            // Se asignarán desde el seeder usando registros reales.
            'carrera_id' => null,
            'institucion_id' => null,

            'rol' => 'practicante',

            'modalidad' => fake()->randomElement([
                'presencial',
                'remoto',
            ]),

            'activo' => true,

            'email' => fake()->unique()->safeEmail(),

            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function presencial(): static
    {
        return $this->state(fn (array $attributes) => [
            'modalidad' => 'presencial',
        ]);
    }

    public function remoto(): static
    {
        return $this->state(fn (array $attributes) => [
            'modalidad' => 'remoto',
        ]);
    }

    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => false,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}