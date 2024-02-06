<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SourceMarketPlace;

class TargetMarketPlace extends Model
{
    use HasFactory;
    protected $table = 'target_marketplaces';
    protected $fillable = ['source_id', 'site_id'];
    
    public function source_marketplaces(){
        return $this->belongsTo(SourceMarketPlace::class,'source_id','id');
    }
}
