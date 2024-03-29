<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function user(){
         return request()->user();
     }

    public function index()
    {
        $users = User::all();
        return view('users.index', \compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;
        return view("users.form", \compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        DB::transaction(function ()  use ($request){
            $user = User::create(
                \request()->validate([
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'mobile_number' => 'required|string',
                    'email' => 'unique:users',
                    'password' => 'required',
                ])
            );
            $user->password = Hash::make($request->password);
            $user->save();

            if(\request()->role == 'admin'){
                $admin = Role::where('name', 'admin')->first();
                $user->assignRole($admin);
    
            }else{
                $sponsor = Role::where('name', 'sponsor')->first();
                $user->assignRole($sponsor);
    
            }
        });
        

        return \redirect()->route('cms.users.index')
            ->with('success', 'You have successfully added a User.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view("users.form", \compact('user'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        DB::transaction(function () use ($user) {
            $user->update(
                \request()->validate([
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'mobile_number' => 'required|integer',
                ])
            );
        });

        return \redirect()->route('cms.users.index')
            ->with('success', 'You have successfully added a user.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return \redirect()->route('cms.users.index')
            ->with('success', 'You have successfully deleted the user.');
    }
}
