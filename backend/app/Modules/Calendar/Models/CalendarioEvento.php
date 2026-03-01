<?php

namespace App\Modules\Calendar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Auth\Models\User;
use App\Modules\Tasks\Models\Intervento;

class CalendarioEvento extends Model
{
    use HasFactory;

    protected $table = 'calendario_eventi';

    protected $fillable = [
        'intervento_id',
        'user_id',
        'titolo',
        'data_inizio',
        'data_fine',
        'colore',
        'note',
    ];

    protected $casts = [
        'data_inizio' => 'datetime',
        'data_fine' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function intervento()
    {
        return $this->belongsTo(Intervento::class);
    }
}
