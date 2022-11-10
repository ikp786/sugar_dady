<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProfileCollection;
use App\Models\User;
use App\Models\UsersImage;
use App\Models\Like;
use App\Models\Notification;
use App\Models\UserMatch;
use App\Rules\UploadUserImageCount;
use Hash;
use Auth;
use DB;
// use PhpParser\Node\Expr\Match_;

class UserController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $title = 'User';       
        $users = User::with('userImages')->with('gender')->with('sexcualOrientation')->paginate(5);        
        return view('admin_panel.index_users',compact('title','users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        User::with('userImages')->with('gender')->with('sexcualOrientation')->find($id);
        $user = User::find($id);
        $title = 'User';
        return view('admin_panel.show_users',compact('user','title'));
    }


    //API
	/**
	 * Update User Profile
	 */
    public function updateUserProfile(Request $request)
	{        
		$error_message = 	[
			'full_name.required'    => 'Full name should be required',
			'full_name.max'         => 'Full name max length 32 character',            
			'gender_id .required'   => 'Gender should be required',
            'gender_id.exists'      => 'Gender did not match exist',
			'sexcual_orientation_id.required' => 'Sexcual Orientation  should be required',
            'sexcual_orientation_id.exists' => 'Sexcual Orientation  did not exist',			
            'user_images.required'  => 'User Images should be required',
            'user_images.array'     => 'User Images Accepted in array',
            'mimes.required'        => 'Image format jpg,jpeg,png',
            'user_bio.required'     => 'User Bio should be required',
            'about_me.required'     => 'About Me should be required',
            'user_location.required'=> 'User Location should be required'
		];
		$rules = [
			'full_name'              => 'required|max:32',
            'gender_id'              => 'required|exists:misc_mst,misc_id|numeric',            
			'sexcual_orientation_id' => 'required|exists:misc_mst,misc_id|numeric',            
            'user_images'            => 'required|array|mimes:jpg,jpeg,png',
            'user_images'            => [new UploadUserImageCount()],
            'user_bio'               => 'required',
            'user_location'          => 'required',
            'about_me'               => 'required'
		];
		$validator = Validator::make($request->all(), $rules, $error_message);   
		if($validator->fails()){
			return $this->sendFailed(implode(", ",$validator->errors()->all()), 200);       
		}		
		try
		{ 		
			\DB::beginTransaction();
            $user = auth()->user()->fill($request->all())->save();			
                if($request->hasfile('user_images')){
                    foreach($request->file('user_images') as $file){
                        $fileName = rand(1000,9999).auth()->user()->user_id.time().'_'.str_replace(" ","_",$file->getClientOriginalName());            
                        $filePath = $file->storeAs('user_images', $fileName, 'public');
                    // save Users image
                    UsersImage::create([
                            'user_id'       => auth()->user()->user_id,                            
                            'image_name'   => $fileName,                            
                        ]);
                    }
                }
			\DB::commit();
			return $this->sendSuccess('PROFILE UPDATE SUCCESSFULLY');
		}
		catch (\Throwable $e)
		{
			\DB::rollback();
			return $this->sendFailed($e->getMessage().' on line '.$e->getLine(), 400);  
		}
	}

    //API
	/**
	 * Delete User Image
	 */

    function userImageDelete(Request $request){ 
    
        $error_message = 	[					
            'image_name.required'  => 'User Images should be required',
            'image_name.exist'     => 'User Image Not Found'
		];
		$rules = [			          
            'image_name'            => 'required|exists:users_images,image_name',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);   
		if($validator->fails()){
			return $this->sendFailed(implode(", ",$validator->errors()->all()), 200);       
		}	
       $imageData = UsersImage::where(['user_id' => auth()->user()->user_id,'image_name' => $request->image_name ])->get();
       if (isset($imageData[0]->image_name) && $imageData[0]->image_name != '') {
        if(Storage::disk('public')->exists('user_images/'.$imageData[0]->image_name))
        {
            Storage::disk('public')->delete('user_images/'.$imageData[0]->image_name); 
           UsersImage::where(['user_id' => auth()->user()->user_id,'image_name' => $request->image_name ])->delete();
        }
        try
		{ 		
			\DB::beginTransaction();
            $fff =   UsersImage::where(['user_id' => auth()->user()->user_id,'image_name' => $request->image_name ])->get();
        return $this->sendSuccess('USER IMAGE DELETE SUCCESSFULLY');
        }
        catch (\Throwable $e)
		{
			\DB::rollback();
			return $this->sendFailed($e->getMessage().' on line '.$e->getLine(), 400);  
		}
       }  else {
        return $this->sendFailed('USER IMAGE NOT FOUND', 200);

       }      
    }

    //API
	/**
	 * Get Auth User Profile
	 */

    public function getMyProfile()
    {
        $userData = auth()->user()->with('userImages')->with('gender')->with('sexcualOrientation')->where('user_id',auth()->user()->user_id)->get();
        $data =   ProfileCollection::collection($userData);
        return $this->sendSuccess('GET USER PROFILE SUCCESSFULLY', ($data));
    }


    
    //API
	/**
	 * Get Other By User Id User Profile
	 */

    public function getUserProfile($user_id=null)
    {
        $userData = User::with('userImages')->with('gender')->with('sexcualOrientation')->where('user_id',$user_id)->get();
        if (count($userData) > 0) {
            $data =   ProfileCollection::collection($userData);
        return $this->sendSuccess('GET USER PROFILE SUCCESSFULLY', ($data));
        }        
        return $this->sendFailed('USER NOT FOUND', 200);

    }

    //API
	/**
	 * User Like Or Unlike and save match table data
	 */

    public function likeUser(Request $request)
    {
        $error_message = 	[
			'to_user_id.required'=> 'To User Id should be required',
            'to_user_id.exists'      => 'To User Id does not exist',
		];
		$rules = [
            'to_user_id' => 'required|exists:users,user_id',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);   
		if($validator->fails()){
			return $this->sendFailed($validator->errors()->all(), 200);       
		}
        // CHECK ALREADY LIKE OR NOT
        $like = Like::where(['to_user_id' => $request->to_user_id,'from_user_id' => auth()->user()->user_id])->get();
                if (isset($like[0]->id) && $like[0]->id != '') {
                    try
		        { 
                    // Unlike and delete
                    $like = Like::where(['to_user_id' => $request->to_user_id,'from_user_id' => auth()->user()->user_id])->delete();
                     // \DB::beginTransaction();
					// User::find($like[0]->id)->delete();
                    $matchData = UserMatch::where(['to_user_id' => $request->to_user_id,'from_user_id' => auth()->user()->user_id])->get();
                    $matchData2 = UserMatch::where(['to_user_id' => auth()->user()->user_id,'from_user_id' => $request->to_user_id])->get();
                    // check User if match so delete
                    if (isset($matchData[0]->id) && $matchData[0]->id != '') {                        
                        UserMatch::where(['to_user_id' => $request->to_user_id,'from_user_id' => auth()->user()->user_id])->delete();                        
                        // check User if match so delete
                    }elseif (isset($matchData2[0]->id) && $matchData2[0]->id != '') {                        
                        UserMatch::find($matchData2[0]->id)->delete();
                    }
                    return $this->sendSuccess('USER UNLIKED SUCCESSFULLY', 200);
				    \DB::commit();
                }
                catch (\Throwable $e)
                {
                    \DB::rollback();
                    return $this->sendFailed($e->getMessage().' on line '.$e->getLine(), 400);  
                }                   
                }else{                    
                    try
                    { 
                        // Like User
                        // \DB::beginTransaction();                        
                        Like::create([
                            'from_user_id' => auth()->user()->user_id,                            
                            'to_user_id'   => $request->to_user_id,                            
                        ]);         
                        // check for availbale match this user or not                    
                        $like2 = Like::where(['to_user_id' => auth()->user()->user_id,'from_user_id' =>  $request->to_user_id])->get();
                if (isset($like2[0]->id) && $like2[0]->id != '') {
                    UserMatch::create([
                            'from_user_id' => auth()->user()->user_id,                            
                            'to_user_id'   => $request->to_user_id,                            
                        ]);
                    }
                    // Save Notification
                    $message =  auth()->user()->full_name . ' Like Your Profile';
                    Notification::create([
                        'from_user_id'  => auth()->user()->user_id,
                        'to_user_id'    => $request->to_user_id,
                        'message'       => $message,
                        'deep_link'     => 'www.google.com'
                    ]);
                    return $this->sendSuccess('USER LIKED SUCCESSFULLY', 200);
                        \DB::commit();
                    }
                    catch (\Throwable $e)
                    {
                        \DB::rollback();
                        return $this->sendFailed($e->getMessage().' on line '.$e->getLine(), 400);  
                    }
                }
    }

    public function getMatchProfile()
    {
        $userData   = UserMatch::Where('to_user_id',auth()->user()->user_id)->select('from_user_id')->get()->toArray();
        $userData2  = UserMatch::Where('from_user_id', auth()->user()->user_id)->select('to_user_id')->get()->toArray();
        $data = array_merge($userData,$userData2);
        if (isset($data) && !empty($data)) {
            $userIds = array();
            foreach ($data as $key => $value) {
                foreach ($value as $key2 => $row) {
                    $userIds[] = $row;
                }
            }
            $userProfile = User::whereIn('user_id',array_unique($userIds))->get();
            if (isset($userProfile)) {                        
               return $this->sendSuccess('MATCHED DATA GET SUCCESSFULLY', ProfileCollection::collection($userProfile));
            }
        }
     return $this->sendFailed('NOT PROFILE MATCHED', 200);       
    }

    public function getNotification()
    {
        $data = auth()->user()->notification;
        if ($data) {
            return $this->sendSuccess('NOTIFICATOIN GET SUCCESSFULLY', ($data));            
        }
        return $this->sendFailed('NOTIFICAION NOT FOUND', 200);
    }

    public function getAllInterestedUser()
    {   
        $interestedIds = explode(",",auth()->user()->intrested_ids);        
        $userData = auth()->user()->where('user_id','!=',2)->where(function($q) use($interestedIds){
            foreach ($interestedIds as $key => $value) {
                $q->orWhereRaw("find_in_set(".$value.",intrested_ids)");        
            }       
        })->get();
                    return $this->sendSuccess('MATCHED DATA GET SUCCESSFULLY', ProfileCollection::collection($userData));
    }
}