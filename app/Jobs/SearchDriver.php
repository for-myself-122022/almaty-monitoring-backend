<?php

namespace App\Jobs;

use App\Models\MapObject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class SearchDriver implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public MapObject $object;
    public int $radius;
    private int $maxRadius = 70;

    public function __construct(MapObject $object, int $radius = 1)
    {
        $this->object = $object;
        $this->radius = $radius;
    }
    public function handle()
    {
        $objectId = $this->object->id;
        $accepts = json_decode(Redis::get("object:$objectId:accepts") ?? '[]');

        if (Redis::get("object:$objectId") != 1) {
            Log::info('Is Null');
            return;
        }

        if (count($accepts) >= 2) {
//            Redis::del("object:$objectId");
//            Redis::del("object:$objectId:accepts");
            Redis::publish('alarm-end', $objectId);
            Log::info('All Drivers done');
            return;
        }

        Redis::set("object:$objectId:radius", $this->radius);
        $driverIds = Redis::georadius('locations', $this->object->latitude, $this->object->longitude, $this->radius * 1000, 'm', 'ASC');

        foreach ($driverIds as $driverId) {
            Redis::set("user:$driverId:alarm", $this->object->id, 'EX', 10);
        }

        Log::info(count($driverIds) < 10 && $this->radius <= $this->maxRadius ? 'Yes' : 'no');
        Log::info($this->radius);

        Redis::publish('alarm-start', $this->object->id);

        if (count($driverIds) < 10 && $this->radius <= $this->maxRadius) {
            $this->radius++;
            self::dispatch($this->object, $this->radius)->delay(now()->addSeconds(10));
        }
    }
}
