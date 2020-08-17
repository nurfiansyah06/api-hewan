<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Transformers\UserTransformer;
use Auth;

class AuthController extends Controller
{

    //Pembuatan semua akun digunakan oleh admin dan superadmin

    public function register(Request $request, User $user)
    {
        $this->validate($request, [
            'name'          => 'required',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6',
            'tmpt_lahir'    => 'required',
            'tanggal_lahir' => 'required',
            'no_hp'         => 'required',
            'photo'         => 'mimes:jpeg,bmp,png,gif,svg,pdf',
            'bio'           => 'string',
            'roles'         => 'required|string|in:PETUGAS,ADMIN,SUPERADMIN'
        ]);

       $user = $user->create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => bcrypt($request->password),
            'tmpt_lahir'    => $request->tmpt_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp'         => $request->no_hp,
            'photo'         => $request->file('photo')->store('photo','public'),
            'bio'           => $request->bio,
            'roles'         => $request->roles,
            'api_token'      => bcrypt($request->email)
       ]);


       return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->addMeta([
                'token' => $user->api_token,
            ])
            ->toArray();
    }

    //login hanya untuk petugas

    public function login(Request $request, User $user)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['errors' => 'Your credential is wrong', 401]);
        };

        $user = $user->find(Auth::user()->id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->addMeta([
                'token' => $user->api_token,
            ])
            ->toArray();
    }

    //login untuk admin dan superadmin
    public function loginadmin(Request $request, User $user)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['errors' => 'Your credential is wrong', 401]);
        };

        $user = $user->find(Auth::user()->id);

        if ($user->roles == "PETUGAS") {
             return ResponseFormatter::error(401,'Your roles is PETUGAS');
        } else {
   
        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->addMeta([
                'token' => $user->api_token,
            ])
            ->toArray();
        }
    }


    //Pembuatan akun untuk petugas

    public function registerPetugas(Request $request, User $user)
    {
        $this->validate($request, [
            'name'          => 'required',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6',
            'tmpt_lahir'    => 'required',
            'tanggal_lahir' => 'required',
            'no_hp'         => 'required',
            'photo'         => 'mimes:jpeg,bmp,png,gif,svg,pdf|nullable',
            'bio'           => 'string',
            'roles'         => 'required|string|in:PETUGAS',
        ]);

       $user = $user->create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => bcrypt($request->password),
            'tmpt_lahir'    => $request->tmpt_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp'         => $request->no_hp,
            'photo'         => $request->file('photo')->store('photo','public'),
            'bio'           => $request->bio,
            'roles'         => 'PETUGAS',
            'api_token'     => bcrypt($request->email)
       ]);


       return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->addMeta([
                'token' => $user->api_token,
            ])
            ->toArray();
    }

    
}
