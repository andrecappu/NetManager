<?php

namespace App\Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Auth\Models\User;

class Allegato extends Model
{
    use HasFactory;

    protected $table = 'allegati';

    protected $fillable = [
        'intervento_id',
        'filename',
        'mime_type',
        'path_storage',
        'disk',
        'uploaded_by',
    ];

    public function intervento()
    {
        return $this->belongsTo(Intervento::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
