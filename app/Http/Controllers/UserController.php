<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'address' => 'required|string',
            'position' => 'required|string',
            'birthdate' => 'required|date',
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password'),
            'address'=> $request->address,
            'position'=> $request->position,
            'birthdate'=> $request->birthdate,
        ]);
        $skills=explode( ',', $request->skills );
//        return response()->json($request->skills);
        $user->save();
        foreach ($skills as $skill){
            $skillSave=new Skill();
            $skillSave->name=$skill;
            $user->skills()->save($skillSave);
        }

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
    public function load(Request $request){
        return User::with('skills')->paginate($request->itemsPerPage);
    }
}
