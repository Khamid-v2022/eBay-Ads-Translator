<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MarketPlace;
class MarketPlaceDetail extends Model
{
    use HasFactory;
    protected $table = 'marketplace_detail';

    protected $fillable = [
        'site_id',
        'location',
        'shipping_service',
        'shipping_service_cost',
        'free_shipping',
        'shipping_type',
        'dispatch_time_max',
        'returns_accepted_option',
        'returns_accepted',
    ];
    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class, 'site_id', 'site_id');
    }
   
}
