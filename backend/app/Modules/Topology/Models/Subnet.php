<?php

namespace App\Modules\Topology\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subnet extends Model
{
    use HasFactory;

    protected $fillable = [
        'sito_id',
        'cidr',
        'gateway',
        'vlan_id',
        'descrizione',
    ];

    public function sito()
    {
        return $this->belongsTo(Sito::class);
    }
}
