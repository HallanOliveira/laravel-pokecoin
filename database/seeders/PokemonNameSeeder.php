<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Services\PokemonNameService;

class PokemonNameSeeder extends Seeder
{
    /**
     * Generate pokemons name on database.
     *
     * @return void
     */
    public function run()
    {
        $pokemonsJson  = file_get_contents('https://pokeapi.co/api/v2/pokemon/?limit=9999');
        $pokemonsArray = json_decode($pokemonsJson);

        foreach ($pokemonsArray->results as $pokemon) {
            $service = new PokemonNameService;
            $service->create([
                'name'        => $pokemon->name,
                'external_id' => explode('/',$pokemon->url)[6] ?? 0
            ]);
        }
    }
}
