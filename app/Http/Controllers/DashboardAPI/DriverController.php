<?php

namespace App\Http\Controllers\DashboardAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class DriverController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }
}
