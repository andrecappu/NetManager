<?php

namespace App\Modules\Enti\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Auth\Models\User;
use App\Modules\Topology\Models\Sito;

class Ente extends Model
{
    use HasFactory;

    protected $table = 'enti';

    protected $fillable = [
        'nome',
        'tipo',
        'codice_istat',
        'indirizzo',
        'referente',
        'contatto',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'ente_user');
    }

    public function siti()
    {
        return $this->hasMany(Sito::class);
    }
}
