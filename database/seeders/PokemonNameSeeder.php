<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Components\WebServices\PokeApi\PokeApiWebService;
use App\Services\PokemonNameService;

class PokemonNameSeeder extends Seeder
{

    public function __construct(
        private PokemonNameService $service
    ) {}

    /**
     * Generate pokemons name on database.
     *
     * @return void
     */
    public function run()
    {
        $webService    = new PokeApiWebService;
        $pokemonsArray = json_decode($webService->getPokemonNames()->body());

        foreach ($pokemonsArray->results as $pokemon) {
            $this->service->create([
                'name'        => $pokemon->name,
                'external_id' => explode('/',$pokemon->url)[6] ?? 0
            ]);
        }
    }
}
