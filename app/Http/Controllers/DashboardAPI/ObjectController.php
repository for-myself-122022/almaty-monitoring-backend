<?php

namespace App\Http\Controllers\DashboardAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\MapObjectResource;
use App\Jobs\SearchDriver;
use App\Models\MapObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Nette\Utils\Random;

class ObjectController extends Controller
{
    public function index()
    {
        $objects = MapObject::all();
        return MapObjectResource::collection($objects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|numeric',
            'coordinates' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $coordinates = explode(',', $validated['coordinates']);

        if (count($coordinates) != 2) {
            abort(422);
        }

        $validated['latitude'] = $coordinates[0];
        $validated['longitude'] = $coordinates[1];

        return MapObject::create($validated);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'alarm' => 'required|boolean',
        ]);

        if ($data['alarm']) {
            Redis::set("object:$id", true);
            $object = MapObject::find($id);
            SearchDriver::dispatch($object);
        } else {
            Redis::del("object:$id:accepts");
            Redis::del("object:$id:radius");
            Redis::del("object:$id");
            Redis::publish('alarm-end', $id);
        }
    }

    public function destroy($id)
    {
        //
    }

    public function radius()
    {
        $objectIds = MapObject::pluck('id');
        $radius = [];

        foreach ($objectIds as $objectId) {
            $value = Redis::get("object:$objectId:radius");

            if ($value) {
                $radius[$objectId] = $value;
            }
        }

        return $radius;
    }
}
