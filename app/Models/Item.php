<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item_specifics';

    protected $fillable = [

        'site_id',
        'name1',
        'value1',
        'name2',
        'value2',
        'name3',
        'value3',
        'name4',
        'value4',
    ];
}
