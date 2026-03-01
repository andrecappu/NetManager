<?php

namespace App\Modules\Network\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Topology\Models\Sito;

class Apparato extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'apparati';

    protected $fillable = [
        'sito_id',
        'tipo',
        'marca',
        'modello',
        'ip_address',
        'mac_address',
        'subnet',
        'seriale',
        'stato',
        'data_installazione',
        'note',
    ];

    protected $casts = [
        'data_installazione' => 'date',
    ];

    public function sito()
    {
        return $this->belongsTo(Sito::class);
    }
}
