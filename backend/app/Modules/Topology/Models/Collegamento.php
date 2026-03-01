<?php

namespace App\Modules\Topology\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collegamento extends Model
{
    use HasFactory;

    protected $table = 'collegamenti';

    protected $fillable = [
        'sito_origine_id',
        'sito_destinazione_id',
        'tipo',
        'banda_mbps',
        'note',
    ];

    public function sitoOrigine()
    {
        return $this->belongsTo(Sito::class, 'sito_origine_id');
    }

    public function sitoDestinazione()
    {
        return $this->belongsTo(Sito::class, 'sito_destinazione_id');
    }
}
