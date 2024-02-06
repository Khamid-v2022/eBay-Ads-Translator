<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SourceMarketPlace;
use App\Models\MarketPlaceDetail;

class MarketPlace extends Model
{
    use HasFactory;
    protected $table = 'marketplaces';

    public function sourceMarketplaces()
    {
        return $this->hasMany(SourceMarketplace::class, 'site_id', 'site_id');
    }

    public function marketplaceDetail()
    {
        return $this->belongsTo(MarketPlaceDetail::class, 'site_id', 'site_id');
    }
}
