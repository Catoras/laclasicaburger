<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\User;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "nombre"=> $this->faker->unique()->sentence(1),
            "categoria_id"=> Categoria::all()->random()->id,
            "descripcion"=> $this->faker->paragraph(3),
            "id_usuario"=> User::all()->random()->id,
            "precio"=>90,
            "created_at"=>now()
        ];
    }
}
