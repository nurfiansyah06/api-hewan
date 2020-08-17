<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Transformers\UserTransformer;
use Auth;

class UserController extends Controller
{
    public function users(User $user)
    {
        $users = $user->all();
        return fractal()
            ->collection($users)
            ->transformWith(new UserTransformer)
            ->includeAnimals()
            ->toArray();
    }

    public function usersAdmin(User $user)
    {
        $users = $user->where('roles','ADMIN')->get();
        return fractal()
            ->collection($users)
            ->transformWith(new UserTransformer)
            ->toArray();
    }

    public function usersPetugas(User $user)
    {
        $users = $user->where('roles','PETUGAS')->get();
        return fractal()
            ->collection($users)
            ->transformWith(new UserTransformer)
            ->toArray();
    }

    public function profile(User $user)
    {
        $user = User::find(Auth::user()->id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->includeAnimals()
            ->toArray();
    }

    public function profileById(User $user, $id)
    {
        $user = User::find($id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->includeAnimals()
            ->toArray();
    }

    public function updateprofile(Request $request)
    {
        
        $user                   = User::find(Auth::user()->id);
        $user->name             = $request->name;
        $user->email            = $request->email;
        $user->tmpt_lahir       = $request->tmpt_lahir; 
        $user->tanggal_lahir    = $request->tanggal_lahir;
        
        // $user->photo            = $request->file('photo')->store('photo','public');
        $user->no_hp            = $request->no_hp;
        $user->bio              = $request->bio;

        if (request()->hasFile('photo')) {
            $file = request()->file('photo')->store('photo');
        };
        $user->save();


        $response = fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->toArray();

        return ResponseFormatter::success($response, 'Data berhasil diupdate');
    }


    public function updateprofileById(Request $request,$id)
    {
        
        $user                   = User::find($id);

        if ($user->roles == 'PETUGAS') {
            $user->name             = $request->name;
            $user->email            = $request->email;
            $user->tmpt_lahir       = $request->tmpt_lahir; 
            $user->tanggal_lahir    = $request->tanggal_lahir;
            
            // $user->photo            = $request->file('photo')->store('photo','public');
            $user->no_hp            = $request->no_hp;
            $user->bio              = $request->bio;

            if (request()->hasFile('photo')) {
                $file = request()->file('photo')->store('photo');
            };
            $user->save();

            $response = fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->toArray();

        return ResponseFormatter::success($response, 'Data berhasil diupdate');
        } else {
            return ResponseFormatter::error(404,'Data tidak ada');
        }
        

        
    }

    public function updateprofileSuperAdmin(Request $request)
    {
        
        $user                   = User::where('roles','SUPERADMIN')->first();
        $user->name             = $request->name;
        $user->email            = $request->email;
        $user->tmpt_lahir       = $request->tmpt_lahir; 
        $user->tanggal_lahir    = $request->tanggal_lahir;
        
        // $user->photo            = $request->file('photo')->store('photo','public');
        $user->no_hp            = $request->no_hp;
        $user->bio              = $request->bio;

        if (request()->hasFile('photo')) {
            $file = request()->file('photo')->store('photo');
        };
        $user->save();


        $response = fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->toArray();

        return ResponseFormatter::success($response, 'Data berhasil diupdate');
    }

    public function deleteByAdmin(Request $request,$id)
    {

        $user = User::find($id);
        if ($user->roles == 'PETUGAS') {
            $destroy =  $user->delete();
            return ResponseFormatter::success($destroy,'Data berhasil dihapus');
        }
        else {
            return ResponseFormatter::error(null, 'Data tidak ada','404');
        }
    }

    public function deleteBySuperAdmin(Request $request, $id)
    {
        
        $user = User::destroy($id);

        if ($user)
            return ResponseFormatter::success($user,'Data berhasil dihapus');
        else
            return ResponseFormatter::error(null, 'Data tidak ada','404');

    }
    
}
