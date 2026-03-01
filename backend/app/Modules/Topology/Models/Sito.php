<?php

namespace App\Modules\Topology\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Enti\Models\Ente;
use App\Modules\Network\Models\Apparato;

class Sito extends Model
{
    use HasFactory;

    protected $table = 'siti';

    protected $fillable = [
        'nome',
        'ente_id',
        'indirizzo',
        'lat',
        'lng',
        'tipo',
        'note',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function ente()
    {
        return $this->belongsTo(Ente::class);
    }

    public function apparati()
    {
        return $this->hasMany(Apparato::class);
    }

    public function subnets()
    {
        return $this->hasMany(Subnet::class);
    }

    public function collegamentiOrigine()
    {
        return $this->hasMany(Collegamento::class, 'sito_origine_id');
    }

    public function collegamentiDestinazione()
    {
        return $this->hasMany(Collegamento::class, 'sito_destinazione_id');
    }
}
