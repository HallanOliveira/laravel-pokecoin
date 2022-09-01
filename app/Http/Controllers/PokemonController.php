<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pokemon;
use App\Models\Transaction;
use Illuminate\Http\Request;
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
        return Pokemon::all()->whereNull('sell_price');
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
        return DB::table('transactions')
            ->join('pokemons', 'transactions.pokemon_id', '=', 'pokemons.id')
            ->select(['transactions.id', 'pokemons.name', 'transactions.type', 'transactions.date'])
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getNames()
    {
        return DB::table('names')
            ->select(['name as label', 'external_id as value'])
            ->orderBy('name', 'asc')
            ->get();
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
