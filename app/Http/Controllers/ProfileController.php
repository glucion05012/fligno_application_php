<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class ProfileController extends Controller
{


    //send sms
    public function itexmo($contact){

        $profile = Profile::where('contact',$contact) -> first();
        $message = 'Verification Code: ' . $profile->SMStoken;

		$url = 'https://www.itexmo.com/php_api/api.php';
		$itexmo = array('1' => $contact, '2' => $message, '3' => 'TR-SCARL721191_CTY6R', 'passwd' => '}x[g$kaqd8');
		$param = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($itexmo),
			),
		);
		$context  = stream_context_create($param);
        $result = file_get_contents($url, false, $context);
        
        if ($result == ""){
        return response("iTexMo: No response from server!!!
        Please check the METHOD used (CURL or CURL-LESS). If you are using CURL then try CURL-LESS and vice versa.	
        Please CONTACT US for help. ");	
        }else if ($result == 0){
        return response('message sent!');
        }
        else{	
        return response("Error Num ". $result . " was encountered!");
        }
    }

    //confirm registration sms
    public function SMSverify($id){
        Profile::where('id', $id)
        ->update(['isConfirmed' => 1, 'token' => '', 'SMStoken' => '']);
        
        return response('Profile Verified!');
    }
    

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

    //confirm registration email
    public function confirmRegistration($token){
        Profile::where('token', $token)
        ->update(['isConfirmed' => 1, 'token' => '', 'SMStoken' => '']);
        
        return response('Profile Verified!');
    }


    // CREATE API
    public function create(Request $request){
    	
    	$this->validate($request, [
    		'name' => 'required',
            'address' => 'required',
            'age' => 'required',
            'contact' => 'required',
            'email' => 'required',
            
    	]);

        //Generate a random string.
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $SMStoken = rand ( 1000 , 9999 );
        
        $profile = new Profile;
    	$profile->name = $request->input('name');
        $profile->address = $request->input('address');
        $profile->age = $request->input('age');
        $profile->contact = $request->input('contact');
        $profile->email = $request->input('email');  
        $profile->token = $token;
        $profile->SMStoken = $SMStoken;
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
            'age' => 'required',
            'contact' => 'required',
            'email' => 'required',
            
        ]);
        
    	$profile = array(
    		'name' => $request->input('name'),
            'address' => $request->input('address'),
            'age' => $request->input('age'),
            'contact' => $request->input('contact'),
            'email' => $request->input('email'),
    		
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
