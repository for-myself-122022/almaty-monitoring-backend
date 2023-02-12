<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Redis;

class MapObjectResource extends JsonResource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['alarm'] = (boolean)Redis::get("object:$this->id");

        return $data;
    }
}
