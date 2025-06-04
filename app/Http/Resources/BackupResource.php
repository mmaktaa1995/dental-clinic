<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BackupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'filename' => $this->resource['filename'],
            'path' => $this->resource['path'],
            'size' => $this->resource['size'],
            // 'size_human' => $this->resource['size_human'],
            'created_at' => $this->resource['created_at'],
            'download_url' => $this->resource['download_url'] ?? null,
        ];
    }
}
