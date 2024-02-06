<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SourceMarketPlace;

class MarketPlace extends Model
{
    use HasFactory;
    protected $table = 'marketplaces';

    public function sourceMarketplaces()
    {
        return $this->hasMany(SourceMarketplace::class, 'site_id', 'site_id');
    }
}
