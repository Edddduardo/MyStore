<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\InfoUser;
use App\Http\Resources\UsersViewCollection;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();      
        $status = $user->status;

        if(!$status){
            return response()->json([
                'message' => 'No se encontro usuario o ha sido eliminado'
            ],404);
        }

        $name_rol = $user->rol()->first()->name;
        

        if($name_rol != 'Admin'){
            return response()->json([
                'message' => 'No tienes acceso a este modulo'
            ],404);
        }


        $users = User::latest()->orderBy('id')->get(); //->paginate(7); if you want the pagination functionality
        return $users;
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = Auth::user();      

        if(!$user){
            return response()->json([
                'message' => 'No se encontro usuario logeado'
            ],401);
        }

        $status = $user->status;

        if(!$status){
            return response()->json([
                'message' => 'No se encontro usuario o ha sido eliminado'
            ],404);
        }


        $inf = InfoUser::where('user_id','=',$user->id)->first();
        $rol = $user->rol()->first();
        // return $user;
         return response()->json([
             'name'=>$user->name,
             'last_name'=>$user->last_name,
             'email'=>$user->email,
             'last_login'=>$user->last_login,
             'rol_id'=>$user->rol_id,
             'rol'=>$rol->name,
             'birthday'=>$inf->birthday,
             'genre'=>$inf->genre,
             'phone'=>$inf->phone
        ],200);

    }

    public function auth_user(){
        $user = Auth::user();

        if(!$user){
            return response()->json([
                'message' => 'No se encontro usuario logeado'
            ],401);
        }

        $status = $user->status;

        if(!$status){
            return response()->json([
                'message' => 'No se encontro usuario o ha sido eliminado'
            ],404);
        }

        $inf = InfoUser::where('user_id','=',$user->id)->first();
        $rol = $user->rol()->first();
        // return $user;
        return response()->json([
             'name'=>$user->name,
             'last_name'=>$user->last_name,
             'email'=>$user->email,
             'last_login'=>$user->last_login,
             'rol_id'=>$user->rol_id,
             'rol'=>$rol->name,
             'birthday'=>$inf->birthday,
             'genre'=>$inf->genre,
             'phone'=>$inf->phone
             
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();      
        $status = $user->status;
        $id = $user->id;

        if(!$status){
            return response()->json([
                'message' => 'No se encontro usuario o ha sido eliminado'
            ],404);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'last_name' => $request->last_name,
        ]);
            // Post::where('slug','=', $slug)->firstOrFail();
        $asd = [
            'birthday' => $request->birthday,
            'genre' => $request->genre,
            'phone' => $request->phone 
        ];
        $ius = InfoUser::where('user_id','=', $id)->update($asd);
        $ius = InfoUser::where('user_id','=', $id)->get();
        

        return [
            'us'=>$user,
            'if'=>$ius
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();      
        $status = $user->status;
       

        if(!$status){
            return response()->json([
                'message' => 'No se encontro usuario o ha sido eliminado'
            ],404);
        }

        $name_rol = $user->rol()->first()->name;

        if($name_rol != 'Admin'){
            $id = $user->id;
        }      

        $asd = [
            'status' => false,
        ];
        $user = User::where('id','=', $id)->update($asd);

        if(!$user){
            return response()->json([
                'message' => 'No se encontro usuario'
            ],404);
        }

        return response()->json([
            'message' => 'Usuario eliminado con exito'
        ],200);
    }
}
