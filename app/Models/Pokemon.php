<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    const API_BITCOIN         = 'https://blockchain.info/ticker';
    const API_POKEMONS        = 'https://pokeapi.co/api/v2/pokemon/';
    const BITCOIN_TO_POKECOIN =  0.00000001;

    protected $fillable = [
        'name',
        'buy_price',
        'sell_price',
        'image',
        'base_experience'
    ];

    protected $unitPricePokemon;

    protected $amountCurrentUSD;

    public static $rules = [
        'name'            => 'required|string|max:255',
        'buy_price'       => 'nullable|numeric|between:0,99999999',
        'sell_price'      => 'nullable|numeric|between:0,99999999',
        'image'           => 'required|string|max:255',
        'base_experience' => 'required|integer|max:8'
    ];

    /**
     * get inventory
     */
    public function search(): object
    {
        return self::all();
    }

    /**
     *
     */
    public function getInventoryAndAmount(): object
    {
        $inventory = $this->search()->whereNull('sell_price');
        foreach($inventory as $p) {
            $p->currentPriceUSD = $this->getPricePokemon($p->base_experience);
            $this->amountCurrentUSD += $p->currentPriceUSD;
        }

        return $inventory;
    }

     /**
     *
     */
    public function getUnitPricePokemon(): void
    {
        $dataBitcoin     = file_get_contents(self::API_BITCOIN);
        $dataDecode      = json_decode($dataBitcoin);
        $priceBitcoinUSD = $dataDecode->USD->last;

        $this->unitPricePokemon = $priceBitcoinUSD * self::BITCOIN_TO_POKECOIN;
    }

     /**
     *
     */
    public function getPricePokemon(int $base_xp): float
    {
        return $base_xp * $this->unitPricePokemon;
    }

     /**
     *
     */
    public function getAmountCurrentUSD(): float
    {
        return $this->amountCurrentUSD;
    }

     /**
     *
     */
    public function getAmountApplied(): float
    {
        return $this->search()->whereNull('sell_price')->sum('buy_price');
    }
}


