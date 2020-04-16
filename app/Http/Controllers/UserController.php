<?php

namespace App\Http\Controllers;

use App\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponser;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();

        return $this->successResponse($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\User
     */
    public function store(Request $request)
    {
        $rules = [
        	'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $fields = $request->all();
        $fields['password'] = Hash::make($request->password);

        $user = User::create($fields);
        $user->token = $user->createToken($user->email)->accessToken;

        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $user = User::findOrfail($user);

        return $this->successResponse($user);
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        $rules = [
        	'name' => 'max:255',
            'email' => 'email|unique:users,email,' . $user,
            'password' => 'min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $user = User::findOrFail($user);

        $user->fill($request->all());

        if ($request->has('password')) {
        	$user->password = Hash::make($request->password);
        }

        if ($user->isClean()) {
        	return $this->errorResponse('Pelo menos um valor deve mudar', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->save();

        return $this->successResponse($user);
    }

    /**
     * Allows the user to login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\User
     */
    public function login(Request $request) 
    {
        $rules = [
        	'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        if(Auth::attempt(['email' => strtolower($data['email']), 'password' => $data['password']])) {
            $user = auth()->user();
            $user->token = $user->createToken($user->email)->accessToken;
            return $this->successResponse($user);
        }

        return $this->errorResponse('Login inválido', Response::HTTP_UNAUTHORIZED);
    }
}
