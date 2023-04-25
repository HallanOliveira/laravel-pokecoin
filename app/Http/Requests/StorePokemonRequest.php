<?php

namespace App\Http\Requests;

use App\Models\Pokemon;
use Illuminate\Foundation\Http\FormRequest;

class StorePokemonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return Pokemon::$rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return [
            'name'            => 'Nome do Pokemon',
            'buy_price'       => 'Valor de compra',
            'sell_price'      => 'Valor de venda',
            'image'           => 'Imagem'
        ];
    }
}
