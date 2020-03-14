<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChinaAreaResource extends JsonResource
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
            'id'        => $this->id,
            'parent_id' => $this->id,
            'code'      => $this->code,
            'name'      => $this->name,
            // 'children'  => ChinaAreaResource::collection($this->children),
        ];
    }
}
