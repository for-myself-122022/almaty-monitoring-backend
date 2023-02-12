<?php

namespace App\Http\Controllers\MobileAPI;

use App\Http\Controllers\Controller;
use App\Models\MapObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ObjectController extends Controller
{
    public function show($id)
    {
        return MapObject::find($id);
    }

    public function accept($id)
    {
        $accepts = json_decode(Redis::get("object:$id:accepts") ?? '[]');

        if (count($accepts) > 2 || in_array(auth()->id(), $accepts)) {
            return;
        }

        array_push($accepts, auth()->id());
        Redis::set("object:$id:accepts", json_encode($accepts));
    }
}
