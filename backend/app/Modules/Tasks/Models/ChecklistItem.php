<?php

namespace App\Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Auth\Models\User;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'intervento_id',
        'descrizione',
        'completato',
        'completato_at',
        'completato_da',
    ];

    protected $casts = [
        'completato' => 'boolean',
        'completato_at' => 'datetime',
    ];

    public function intervento()
    {
        return $this->belongsTo(Intervento::class);
    }

    public function completatoDa()
    {
        return $this->belongsTo(User::class, 'completato_da');
    }
}
