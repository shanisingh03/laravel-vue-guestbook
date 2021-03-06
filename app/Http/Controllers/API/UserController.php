<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        // Validate User Data
        $this->validate($request,[
            'id'=>'required|exists:users,id',
            'name'=>'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users,id,'.$request->id
        ]);
        $user = auth('api')->user();
        $currentPhoto = $user->photo;

        // Photo
        if($request->photo != $currentPhoto){
            $name = time().'.'. explode('/',explode(':',substr($request->photo,0,strpos($request->photo,';')))[1])[1];

            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo' => $name]);
        }

        // Update Detals
        $user->update($request->all());

        return ["message"=>"User Profile Updated SuccessFully."];
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getMyProfile()
    {
        $user = auth('api')->user();

        return ["profile"=>$user,'contacts'=>$user->contacts];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
