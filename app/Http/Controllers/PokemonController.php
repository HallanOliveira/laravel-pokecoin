<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pokemon;
use App\Models\Transaction;
use App\Http\Controllers\Controller;


class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataBitcoin       = file_get_contents('https://blockchain.info/ticker');
        $priceBitcoinUSD   = json_decode($dataBitcoin)->USD->last;
        $unitValuePokemonUSD  = $priceBitcoinUSD * 0.00000001;
        $inventoryPokemons = Pokemon::all()->whereNull('sell_price');

        $amountCurrentUSD = 0;
        foreach($inventoryPokemons as $p) {
            $p->currentPriceUSD = $p->base_experience * $unitValuePokemonUSD;
            $amountCurrentUSD  += $p->currentPriceUSD;
        }
        $arrayReturn['inventory']     = $inventoryPokemons;
        $arrayReturn['amountCurrent'] = $amountCurrentUSD;
        $arrayReturn['amountApplied'] = DB::table('pokemons')->whereNull('sell_price')->sum('buy_price');
        $arrayReturn['optionsNames']  = DB::table('names')->select(['name as label', 'external_id as value'])->orderBy('name', 'asc')->get();
        return $arrayReturn;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($json_data)
    {
        $array_data  = json_decode($json_data, true);
        $idPokemon   = $array_data['id'];
        $namePokemon = $array_data['name'];
        $buy_price   = $array_data['buyPrice'];
        $buy_date    = $array_data['buyDate'];
        $data = file_get_contents(Pokemon::API_POKEMONS.$idPokemon);
        $data = json_decode($data);

        $dataImg = file_get_contents($data->forms[0]->url);
        $image = json_decode($dataImg)->sprites->front_default;

        $transactionModel              = new Transaction;
        $pokemonModel                  = new Pokemon;
        $pokemonModel->name            = $namePokemon;
        $pokemonModel->buy_price       = $buy_price;
        $pokemonModel->imagem          = $image ;
        $pokemonModel->base_experience = $data->base_experience;
        if($pokemonModel->save()) {
            $transactionModel->pokemon_id = $pokemonModel->id;
            $transactionModel->date       = $buy_date;
            $transactionModel->type       = $transactionModel::TYPE_BUY;
            $transactionModel->save();

            return http_response_code(200);
        }
        return http_response_code(500);
    }

    /**
     * Sell the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function sellPokemon($json_data)
    {
        $array_data                   = json_decode($json_data,true);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pokemon $pokemon)
    {
        //
    }
}
