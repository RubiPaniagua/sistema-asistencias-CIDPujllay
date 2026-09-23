<?php

namespace Database\Factories;

use App\Models\Carrera;
use App\Models\Institucion;
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
            'carrera_id' => Carrera::factory(),
            'institucion_id' => Institucion::factory(),
    
            'rol' => fake()->randomElement([
                'admin',
                'practicante',
            ]),
    
            'modalidad' => fake()->randomElement([
                'presencial',
                'remoto',
            ]),
    
            'activo' => fake()->boolean(),
    
            'email' => fake()->unique()->safeEmail(),
    
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
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