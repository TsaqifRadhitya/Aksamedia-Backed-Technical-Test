<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    use HasUuids;
    protected $fillable = [
        'image',
        'name',
        'phone',
        'division',
        'position'
    ];

    public function division(){
        return $this->belongsTo(division::class,'division_id');
    }
}
