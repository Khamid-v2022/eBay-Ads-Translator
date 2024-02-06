<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tienda;
use App\Models\Marketplace;
use App\Models\TargetMarketPlace;

class SourceMarketPlace extends Model
{
    use HasFactory;
    protected $table = 'source_marketplaces';

    public function store()
    {
        return $this->belongsTo(Tienda::class, 'store_id', 'id');
    }

    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class, 'site_id', 'site_id');
    }

    public function target_marketplaces(){
        return $this->hasMany(TargetMarketplace::class, 'source_id', 'id');
    }

}
