<?php

namespace App\Http\Controllers\MobileAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255'
        ]);

        if (auth()->attempt($credentials)) {
            return auth()->user()->createToken('Mobile App')->plainTextToken;
        }

        abort(401);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birthday' => 'required|date_format:d.m.Y',
            'password' => 'required|string|max:255',
            'is_male' => 'required|boolean',
            'region' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'education' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'height' => 'required|numeric|max:300',
            'weight' => 'required|numeric|max:300',
            'is_served_army' => 'required|boolean',
            'car_model' => 'required|string|max:255',
            'car_year' => 'required|date_format:Y',
            'car_number' => 'required|string|max:255',
            'iban' => 'required|string|max:20',
            'identification' => 'required|image',
        ]);

        $data['identification'] = $request->file('identification')->store('public/identifications');
        $user = User::create($data);

        return $user->createToken('Mobile App')->plainTextToken;
    }

    public function profile()
    {
        return new UserResource(auth()->user());
    }

    public function isUniqueEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:users',
        ]);
    }
}
