<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class ProfileController extends Controller
{

    // send email
    public function send($email){
    
        $profile = Profile::where('email',$email) -> first();

        $host = request()->getHttpHost();
        $URLtoken = $host . '/api/confirmRegistration/' . $profile->token;
        $data = array (
            'name' => $profile->name,
            'token' => $URLtoken
        );

        Mail::to($profile->email)->send(new SendMail($data));

        return response('email sent!');
        
    }

    //confirm registration
    public function confirmRegistration($token){
        Profile::where('token', $token)
        ->update(['isConfirmed' => 1, 'token' => '']);
        
        return response('Profile Verified!');
    }


    // CREATE API
    public function create(Request $request){
    	
    	$this->validate($request, [
    		'name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'age' => 'required'
    	]);

        //Generate a random string.
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        
        $profile = new Profile;
    	$profile->name = $request->input('name');
        $profile->address = $request->input('address');
        $profile->email = $request->input('email');
        $profile->age = $request->input('age');
        $profile->token = $token;
        $profile->isConfirmed = 0;

        $profile->save();
    	// return redirect('/')->with('info','Profile saved successfully!');

        return response()->json($profile);

    }

    // READ ALL API
    public function read(Request $request){
        $profile = Profile::all();
    	return response()->json($profile);
    }

    //GET ID API
     public function readID($id){
    	$profile = Profile::find($id);
    	return response()->json($profile);

    }

    // emailCheck

     public function emailCheck($email){
    	$profile = Profile::where('email',$email) -> first();

    	return response()->json($profile);
    }

    // UPDATE API
    public function update(Request $request, $id){
    	$this->validate($request, [
    		'name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'age' => 'required'
        ]);
        
    	$profile = array(
    		'name' => $request->input('name'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
    		'age' => $request->input('age')
        );
        
    	Profile::where('id', $id)
    	->update($profile);

        return response()->json($profile);
    }

    // DELETE API
    public function delete($id){
    	Profile::where('id', $id)
    	->delete();

    	return response('successfully deleted');
    }
}
