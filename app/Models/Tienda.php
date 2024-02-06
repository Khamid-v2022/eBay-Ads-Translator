<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SourceMarketPlace;
class Tienda extends Model
{
    use HasFactory;

    protected $table = 'tiendas';

    protected $fillable = ['access_token', 'store_name', 'marketplaces'];

    public function sourceMarketplaces()
    {
        return $this->hasMany(SourceMarketplace::class, 'store_id', 'id');
    }


}
