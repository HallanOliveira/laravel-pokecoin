<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pokemon;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Services\PokemonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StorePokemonRequest;

class PokemonController extends Controller
{
    public function __construct(
        private PokemonService $pokemonService
    ){}

    /**
     * Get Index data.
     *
     * @return array
     */
    public function index(): JsonResponse
    {
        try {
            $pokemons = $this->pokemonService->getAll();
        } catch(\Exception $e) {
            return response()->json($e, 500);
        }

        return response()->json([
            'data'    => $pokemons,
            'success' => true
        ], 200);

    }

    // /**
    //  * Build data for index page.
    //  *
    //  * @return array
    //  */
    // public function index(): array
    // {
    //     $model = new Pokemon;
    //     $modelNames = new PokemonName;
    //     $model->getUnitPricePokemon();
    //     return [
    //        'inventory'     => $model->getInventoryAndAmount(),
    //     //    'amountCurrent' => $model->getAmountCurrentUSD(),
    //     //    'amountApplied' => $model->getAmountApplied(),
    //     //    'optionsNames'  => $modelNames->getNames()
    //     ];

    // }

    /**
     * Get Index data.
     *
     * @return array
     */
    public function show(Pokemon $pokemon)
    {
        try {
            $pokemons = $this->pokemonService->show($pokemon->id);
        } catch(\Exception $error) {
            return response()->json($error, 500);
        }

        return response()->json([
            'data'    => $pokemons,
            'success' => true
        ], 200);

    }

    /**
     * Create a new pokemons and add transaction on database.
     *
     */
    public function store(StorePokemonRequest $request)
    {
        try {
            $payload = $request->validated();
            return response()->json([
                'data'    => 'teste',
                'success' => true
            ], 200);
        } catch (\Exception $error) {
            return response()->json($error, 500);
        }
        // if ($request->validate(Pokemon::$rules)) {
        //     $this->pokemonService->create($request->all());
        // }
        // $array_data  = json_decode($json_data, true);
        // $idPokemon   = $array_data['id'];
        // $namePokemon = $array_data['name'];
        // $buy_price   = $array_data['buyPrice'];
        // $buy_date    = $array_data['buyDate'];
        // $data = file_get_contents(Pokemon::API_POKEMONS.$idPokemon);
        // $data = json_decode($data);

        // $dataImg = file_get_contents($data->forms[0]->url);
        // $image = json_decode($dataImg)->sprites->front_default;

        // $pokemonModel                  = new Pokemon;
        // $pokemonModel->name            = $namePokemon;
        // $pokemonModel->buy_price       = $buy_price;
        // $pokemonModel->imagem          = $image ;
        // $pokemonModel->base_experience = $data->base_experience;
        // if($pokemonModel->save()) {
        //     $transactionModel             = new Transaction;
        //     $transactionModel->pokemon_id = $pokemonModel->id;
        //     $transactionModel->date       = $buy_date;
        //     $transactionModel->type       = $transactionModel::TYPE_BUY;
        //     $transactionModel->save();

        //     return http_response_code(200);
        // }
        // return http_response_code(500);
    }

    /**
     * Create a new pokemons and add transaction on database.
     *
     */
    public function destroy(Pokemon $pokemon, Request $request)
    {
        dd($request);
    }
    // /**
    //  * Create a new pokemons and add transaction on database.
    //  *
    //  */
    // public function create($json_data)
    // {
    //     $array_data  = json_decode($json_data, true);
    //     $idPokemon   = $array_data['id'];
    //     $namePokemon = $array_data['name'];
    //     $buy_price   = $array_data['buyPrice'];
    //     $buy_date    = $array_data['buyDate'];
    //     $data = file_get_contents(Pokemon::API_POKEMONS.$idPokemon);
    //     $data = json_decode($data);

    //     $dataImg = file_get_contents($data->forms[0]->url);
    //     $image = json_decode($dataImg)->sprites->front_default;

    //     $pokemonModel                  = new Pokemon;
    //     $pokemonModel->name            = $namePokemon;
    //     $pokemonModel->buy_price       = $buy_price;
    //     $pokemonModel->imagem          = $image ;
    //     $pokemonModel->base_experience = $data->base_experience;
    //     if($pokemonModel->save()) {
    //         $transactionModel             = new Transaction;
    //         $transactionModel->pokemon_id = $pokemonModel->id;
    //         $transactionModel->date       = $buy_date;
    //         $transactionModel->type       = $transactionModel::TYPE_BUY;
    //         $transactionModel->save();

    //         return http_response_code(200);
    //     }
    //     return http_response_code(500);
    // }

    /**
     * Sell the specified pokemon and add transaction on database.
     *
     */
    public function sellPokemon($json_data)
    {
        $array_data = json_decode($json_data,true);
        Pokemon::find($array_data['id'])->update(['sell_price' => $array_data['sellPrice']]);

        $transactionModel             = new Transaction;
        $transactionModel->pokemon_id = $array_data['id'];
        $transactionModel->date       = $array_data['sellDate'];
        $transactionModel->type       = $transactionModel::TYPE_SELL;

        if($transactionModel->save()) {
            return http_response_code(200);
        }
        return http_response_code(500);
    }

    /**
     * Return data of transactions.
     */
    public function historyOperations()
    {
        return DB::select(DB::raw('
            SELECT
                t.id,
                p.name,
                t.type,
                DATE_FORMAT(t.date, "%d-%m-%Y") AS date,
                (CASE
                    WHEN t.type = 1 THEN p.buy_price
                    ELSE p.sell_price
                END) AS value
            FROM
                transactions t
            JOIN pokemons p ON
                t.pokemon_id = p.id
            ORDER BY
                t.date DESC
        '));
    }
}
