<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['identification'] = Storage::url($data['identification']);
        $data['location'] = Redis::command('GEOPOS', ['locations', $this->id])[0];
        $data['is_online'] = (boolean)Redis::get("user:$this->id:online");
        $data['received'] = (boolean)Redis::get("user:$this->id:received");

        return $data;
    }
}
