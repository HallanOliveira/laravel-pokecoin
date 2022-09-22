<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Name extends Model
{
    use HasFactory;
    protected $table = 'names';

    public function getNames(): object
    {
        return $this->select(['name as label', 'external_id as value'])->orderBy('name', 'asc')->get();
    }
}
