<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetStore extends Model
{
    use HasFactory;

    protected $table = 'target_stores';

    protected $fillable = [
        'source_store_id',
        'store_id',
        'target_name'
    ];

    public function sourceStore()
    {
        return $this->belongsTo(SourceStore::class, 'source_store_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
