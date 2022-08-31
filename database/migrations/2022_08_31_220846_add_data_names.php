<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = file_get_contents('https://pokeapi.co/api/v2/pokemon/?limit=9999');
        $data = json_decode($data);

        foreach($data->results as $d) {
            $id = explode('/',$d->url)[6];
            DB::table('names')->insert([
                'name'        => $d->name,
                'external_id' => $id
            ]);
            unset($id);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
