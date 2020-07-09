<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;

class ProfileController extends Controller
{
    // CREATE API
    public function create(Request $request){
    	
    	$this->validate($request, [
    		'name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'age' => 'required'
    	]);

    	$profile = new Profile;
    	$profile->name = $request->input('name');
        $profile->address = $request->input('address');
        $profile->email = $request->input('email');
    	$profile->age = $request->input('age');
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
