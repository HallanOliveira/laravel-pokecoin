<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Components\WebServices\PokeApi\PokeApiWebService;
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
        $webService    = new PokeApiWebService;
        $service       = new PokemonNameService;
        $pokemonsArray = json_decode($webService->getPokemonNames()->body());

        foreach ($pokemonsArray->results as $pokemon) {
            $service->create([
                'name'        => $pokemon->name,
                'external_id' => explode('/',$pokemon->url)[6] ?? 0
            ]);
        }
    }
}
