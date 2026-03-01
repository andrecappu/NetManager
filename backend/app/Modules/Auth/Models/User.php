<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Modules\Enti\Models\Ente;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'password',
        'ente_id',
        'attivo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'attivo' => 'boolean',
    ];

    public function ente()
    {
        return $this->belongsTo(Ente::class);
    }

    public function enti()
    {
        return $this->belongsToMany(Ente::class, 'ente_user');
    }
}
