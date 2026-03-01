<?php

namespace App\Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Enti\Models\Ente;
use App\Modules\Topology\Models\Sito;
use App\Modules\Network\Models\Apparato;
use App\Modules\Auth\Models\User;

class Intervento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'interventi';

    protected $fillable = [
        'titolo',
        'descrizione',
        'ente_id',
        'sito_id',
        'apparato_id',
        'stato',
        'priorita',
        'assegnato_a',
        'creato_da',
        'data_scadenza',
    ];

    protected $casts = [
        'data_scadenza' => 'datetime',
    ];

    public function ente()
    {
        return $this->belongsTo(Ente::class);
    }

    public function sito()
    {
        return $this->belongsTo(Sito::class);
    }

    public function apparato()
    {
        return $this->belongsTo(Apparato::class);
    }

    public function assegnatoA()
    {
        return $this->belongsTo(User::class, 'assegnato_a');
    }

    public function creatoDa()
    {
        return $this->belongsTo(User::class, 'creato_da');
    }

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function allegati()
    {
        return $this->hasMany(Allegato::class);
    }
}
