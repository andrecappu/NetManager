<?php

namespace App\Modules\Tasks\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Auth\Resources\UserResource;
use Illuminate\Support\Facades\Storage;

class AllegatoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'intervento_id' => $this->intervento_id,
            'filename' => $this->filename,
            'mime_type' => $this->mime_type,
            'url' => Storage::disk($this->disk)->url($this->path_storage),
            'uploaded_by' => $this->uploaded_by,
            'uploaded_by_user' => new UserResource($this->whenLoaded('uploadedBy')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
