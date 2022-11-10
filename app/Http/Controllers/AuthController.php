<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProfileCollection;
use App\Models\User;
use App\Models\Admin;
use App\Models\UsersImage;
use App\Rules\UploadUserImageCount;
use Hash;
use Auth;

class AuthController extends BaseController
{
    //control_panel
    public function login_index()
    {        
        if(Auth::guard('admin')->user()){
            return redirect()->route('dashboard');
        }
        $title         = "Login";
        $data          = compact('title');
        return view('admin_panel.login', $data);
    }

    public function login_user(Request $request)
    {
        $error_message = [
            'email_address.required'=> 'Email address should be required',
            'user_password.required'=> 'Password required',
            'email_address.regex'   => 'Provide email address in valid format',
            'user_password.regex'   => 'Provide password in valid format',
            'min'                   => 'Password should be minimum :min character'
        ];

        $validatedData = $request->validate([
            'email_address'         => 'required|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
            'user_password'         => 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ], $error_message);

        try
        {
            if(Auth::guard('admin')->attempt(['email_address' => $request->email_address, 'password' => $request->user_password]))
            {
                return redirect()->route('dashboard');
            }
            return redirect()->back()->With('Failed', 'Invalid login details')->withInput($request->only(['email_address']));
        }
        catch(\Throwable $e)
        {
            return redirect()->back()->With('Failed', $e->getLine());
        }
    }

    public function admin_edit($admin_id)
    {
        $title         = "Admin User Profile";
        $record_data   = Admin::findOrfail(base64_decode($admin_id));
        $data          = compact('title','record_data');
        return view('admin_panel.admin_edit', $data);
    }

    public function admin_update(Request $request, $admin_id)
    {
        $admin_id      = base64_decode($admin_id);

        $error_message = [
            'full_name.required'    => 'Full name should be required',
            'full_name.max'         => 'Full name max length 32 character',
            'email_address.required'=> 'Email address should be required',
            'email_address.unique'  => 'Email address has been taken',
            'admin_photo.required'  => 'Image should be required',
            'mimes.required'        => 'Image format jpg,jpeg,png',
            'password.required'     => 'Password should be required',
            'password.regex'        => 'Password Should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character',
            'min'                   => 'Password minimum lenght should be :min characters'
        ];

        $rules         = [
            'full_name'             => 'required|max:32',
            'email_address'         => 'required|email|unique:admin,email_address,'.$admin_id.',id',
        ];

        if(!empty($request->file('admin_photo'))) {
            $rules['admin_photo'] = 'required|mimes:jpg,jpeg,png';
        }
        if(!empty($request->password))
        {
            $rules['password'] = 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/';
        }

        $this->validate($request, $rules, $error_message);
        
        try
        {
            if($admin_id == 0)
            {
                if(!empty($request->file('admin_photo'))) 
                {
                    $admin_pic = time().'_'.rand(1111,9999).'.'.$request->file('admin_photo')->getClientOriginalExtension();  
                    $request->file('admin_photo')->storeAs('admin_images', $admin_pic, 'public');
                    $request['admin_image'] = $admin_pic;
                }
                \DB::beginTransaction();
                $admin = new Admin();
                $admin->fill($request->all());
                $admin->password  = bcrypt($request->password);
                $admin->save();
                \DB::commit();
                
                return redirect()->back()->with('Success','Admin created successfully');
            }
            else
            {
                $admin_details      = Admin::findOrfail($admin_id);
                if(!empty($request->file('admin_photo'))) 
                {
                    if(Storage::disk('public')->exists('admin_images/'.$admin_details->admin_image))
                    {
                        Storage::disk('public')->delete('admin_images/'.$admin_details->admin_image); 
                    }

                    $admin_pic = time().'_'.rand(1111,9999).'.'.$request->file('admin_photo')->getClientOriginalExtension();  
                    $request->file('admin_photo')->storeAs('admin_images', $admin_pic, 'public');
                    $request['admin_image'] = $admin_pic;
                }
                \DB::beginTransaction();
                if(!empty($request->password))
                {
                    $request['password'] = bcrypt($request->password);
                }
                $admin_update = Admin::findOrfail($admin_id)->fill($request->all())->save();
                    //$count_row  = Admin::findOrfail($admin_id)->update($request->all());
                \DB::commit();
                return redirect()->back()->with('Success','Admin updated successfully');
            }
        }
        catch (\Throwable $e)
        {
            \DB::rollback();
            return back()->with('Failed',$e->getMessage())->withInput();
        }
    }

    //API
	/**
	 * Registration
	 */
	public function registerWithMobile(Request $request)
	{        
		$error_message = 	[
			'full_name.required'    => 'Full name should be required',
			'full_name.max'         => 'Full name max length 32 character',			
			'email_address.unique'  => 'Email address has been taken',
			'mobile_number.required'=> 'Mobile number should be required',
			'mobile_number.unique'  => 'Mobile number has been taken',
			'gender_id .required'   => 'Gender should be required',
            'gender_id.exists'      => 'Gender did not match exist',
			'sexcual_orientation_id.required' => 'Sexcual Orientation  should be required',
            'sexcual_orientation_id.exists' => 'Sexcual Orientation  did not exist',
			'intrested_ids.required'=> 'Intrested should be required',
            'user_images.required'  => 'User Images should be required',
            'user_images.array'     => 'User Images Accepted in array',
            'mimes.required'        => 'Image format jpg,jpeg,png,gif,svg,webp',
			// 'device_token.required' => 'Device Token is required'
		];
		$rules = [
			'full_name'              => 'required|max:32',
			'email_address'          => 'email|unique:users,email_address',
			'mobile_number'          => 'required|unique:users,mobile_number|max:10|min:10',
			'intrested_ids'          => 'required',
            'gender_id'              => 'required|exists:misc_mst,misc_id',            
			'sexcual_orientation_id' => 'required|exists:misc_mst,misc_id',            
            'user_images'            => 'required|array|mimes:jpg,jpeg,png,gif,svg,webp',
            'user_images'            => [new UploadUserImageCount()],
			// 'device_token'			 => 'required',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);   
		if($validator->fails()){
			return $this->sendFailed(implode(", ",$validator->errors()->all()), 200);       
		}		
		try
		{ 		$otp = rand(1000,9999);
			\DB::beginTransaction();
				$user = new User();
				$user->fill($request->all());			
                $user->password = Hash::make($otp);
				$user->save();
                if($request->hasfile('user_images')){
                    foreach($request->file('user_images') as $file){
                        $fileName = rand(1000,9999).time().'_'.str_replace(" ","_",$file->getClientOriginalName());            
                        $filePath = $file->storeAs('user_images', $fileName, 'public');
                    // save User image
                    UsersImage::create([
                            'user_id'       => $user->user_id,                            
                            'image_name'    => $fileName,                            
                        ]);
                    }
                }                
			\DB::commit();
			return $this->sendSuccess('ACCOUNT CREATED SUCCESSFULLY');
		}
		catch (\Throwable $e)
		{
			\DB::rollback();
			return $this->sendFailed($e->getMessage().' on line '.$e->getLine(), 400);  
		}
	}

    public function registerWithSocialMedia(Request $request)
	{         
		$error_message = 	[
			'full_name.required'    => 'Full name should be required',
			'full_name.max'         => 'Full name max length 32 character',			
			'email_address.unique'  => 'Email address has been taken',
			'social_media_id.required'=> 'Social Media Id should be required',
            // 'social_media_id.unique'=> 'User Already exist.',			
			'gender_id .required'   => 'Gender should be required',
            'gender_id.exists'      => 'Gender did not match exist',
			'sexcual_orientation_id.required' => 'Sexcual Orientation  should be required',
            'sexcual_orientation_id.exists' => 'Sexcual Orientation  did not exist',
			'intrested_ids.required'=> 'Intrested should be required',
            'user_images.required'  => 'User Images should be required',
            'user_images.array'     => 'User Images Accepted in array',
            'mimes.required'        => 'Image format jpg,jpeg,png,gif,svg,webp',
			'device_token.required'	    => 'Device Token should be required'	
		];
		$rules = [
			'full_name'              => 'required|max:32',
			'email_address'          => 'email|unique:users,email_address',
			'social_media_id'        => 'required',
			'intrested_ids'          => 'required',
            'gender_id'              => 'required|exists:misc_mst,misc_id|numeric',            
			'sexcual_orientation_id' => 'required|exists:misc_mst,misc_id|numeric',            
            'user_images'            => 'required|array|mimes:jpg,jpeg,png,gif,svg,webp',
            'user_images'            => [new UploadUserImageCount()],
			'device_token'	    	 => 'required'	
		];
		$validator = Validator::make($request->all(), $rules, $error_message);   
		if($validator->fails()){
			return $this->sendFailed(implode(", ",$validator->errors()->all()), 200);       
		}		
		// CHECK ALREADY EXIST.
		$checkExist = User::where('social_media_id',$request->social_media_id)->first();
		if (!empty($checkExist)) {
				if(auth()->loginUsingId($checkExist->user_id)){
					auth()->user()->update(['device_token' => $request->device_token]);
				$access_token       = auth()->user()->createToken(auth()->user()->full_name)->accessToken;				
				return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['access_token' => $access_token]);
			}else{
				return $this->sendFailed('SOME ERROR OCURED', 200);
			}
		}
		try
		{ 		
			\DB::beginTransaction();
				$user = new User();
				$user->fill($request->all());
				$user->save();
                if($request->hasfile('user_images')){
                    foreach($request->file('user_images') as $file){
                        $fileName = rand(1000,9999).time().'_'.str_replace(" ","_",$file->getClientOriginalName());            
                        $filePath = $file->storeAs('user_images', $fileName, 'public');
                    // save Users image
                    UsersImage::create([
                            'user_id'       => $user->user_id,                            
                            'image_name'   => $fileName,                            
                        ]);
                    }
                }
			\DB::commit();
			return $this->sendSuccess('ACCOUNT CREATED SUCCESSFULLY');
		}
		catch (\Throwable $e)
		{
			\DB::rollback();
			return $this->sendFailed($e->getMessage().' on line '.$e->getLine(), 400);  
		}
	}

    public function otpVerify(Request $request)
	{       
		$error_message = 	[
			'mobile_number.required'    => 'Mobile number should be required',
			'otp.required' 				=> 'Otp should be required',		
			'device_token.required'	    => 'Device Token should be required'	
		];
		$rules = [
			'mobile_number'  => 'required',
			'otp'            => 'required',			
			'device_token'   => 'required'
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if($validator->fails()){
			return $this->sendFailed($validator->errors()->all(), 200);
		}	
		try
		{   
			if (auth()->attempt(['mobile_number' => $request->mobile_number, 'password' => $request->otp])) {
				$access_token       = auth()->user()->createToken(auth()->user()->full_name)->accessToken;
				auth()->user()->update(['device_token' => $request->device_token]);
				return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['access_token' => $access_token]);
			} else {
				return $this->sendFailed('INVALID VERIFACTION CODE', 200);
			}
		}
		catch (\Throwable $e)
		{
			\DB::rollback();
			return $this->sendFailed($e->getMessage().' on line '.$e->getLine(), 400);  
		}
	}
    /**
     * If User Not Authorized
     * 
     */   

    public function unauthorizedUser(){
        return $this->sendFailed('UNAUTHORIZED ACCESS', 200);
    }

    /**
	 * Login
	 */
	public function loginWithMobile(Request $request)
	{
		$error_message = 	[
			'mobile_number.required'	=> 'Mobile number should be required',
            'mobile_number.exists'      => 'Mobile Number does not exist',
			// 'device_token.required' 	=> 'Device Token should be required'
		];
		$rules = [
            'mobile_number' 			=> 'required|exists:users,mobile_number',
			// 'device_token'          	=> 'required' 
		];
		$validator = Validator::make($request->all(), $rules, $error_message);   
		if($validator->fails()){
			return $this->sendFailed($validator->errors()->all(), 200);       
		}		
		try
		{
			$user_data = User::where('mobile_number',$request->mobile_number)->first();
			if($user_data) {
				$verification_otp    = rand(1111,9999);
				$request['password'] = Hash::make($verification_otp);
				\DB::beginTransaction();
					User::find($user_data->user_id)->update(['device_token' => $request->device_token]);
					User::find($user_data->user_id)->update($request->only(['password']));
				\DB::commit();
				$this->sendOtp($request->mobile_number, $verification_otp);
				return $this->sendSuccess('VALIDATE SUCCESSFULLY', ['verification_otp' => $verification_otp]);
			}
			return $this->sendFailed('WE COULD NOT FOUND ANY ACCOUNT WITH THAT INFO', 200);       
		}
		catch (\Throwable $e)
		{
			\DB::rollback();
			return $this->sendFailed($e->getMessage().' on line '.$e->getLine(), 400);  
		}
	}

      /**
	 * Login
	 */
	public function loginWithSocialMedia(Request $request)
	{
		$error_message = 	[
			'social_media_id.required'	=> 'Social Media Id should be required',
            'social_media_id.exists'    => 'Social Media Id does not exist',
			'device_token.required' 	=> 'Device Token should be required'
		];
		$rules = [
            'social_media_id'		    => 'required|exists:users,social_media_id',
			'device_token'          	=> 'required'
		];
		$validator = Validator::make($request->all(), $rules, $error_message);   
		if($validator->fails()){
			return $this->sendFailed($validator->errors()->all(), 200);       
		}		
		try
		{
		
			$checkExist = User::where('social_media_id',$request->social_media_id)->first();
			if (!empty($checkExist)) {
					if(auth()->loginUsingId($checkExist->user_id)){
						auth()->user()->update(['device_token' => $request->device_token]);
					$access_token       = auth()->user()->createToken(auth()->user()->full_name)->accessToken;					
					return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['access_token' => $access_token]);
				}else{
					return $this->sendFailed('SOME ERROR OCURED', 200);
				}
			}else{
				return $this->sendFailed('USER DETAIL NOT FOUND', 200);
				
			}

			return $this->sendFailed('WE COULD NOT FOUND ANY ACCOUNT WITH THAT INFO', 200);       
		}
		catch (\Throwable $e)
		{
			\DB::rollback();
			return $this->sendFailed($e->getMessage().' on line '.$e->getLine(), 400);  
		}
	}

    public function resendOtp(Request $request)
	{
		$error_message = 	[
			'mobile_number.required'=> 'Mobile number should be required',
		];
		$rules = [
			'mobile_number'         => 'required',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);   
		if($validator->fails()){
			return $this->sendFailed($validator->errors()->all(), 200);       
		}
		try
		{
			$user_data = User::where('mobile_number',$request->mobile_number)->first();
			if($user_data) {
				$verification_otp   = rand(1111,9999);
				$request['password'] = Hash::make($verification_otp);
				\DB::beginTransaction();
					User::find($user_data->user_id)->update(['device_token' => $request->device_token]);
					User::find($user_data->user_id)->update($request->only(['password']));
				\DB::commit();
				$this->sendOtp($request->mobile_number, $verification_otp);
				return $this->sendSuccess('OTP SENT SUCCESSFULLY', ['verification_otp' => $verification_otp]);
			}
			return $this->sendFailed('WE COULD NOT FOUND ANY ACCOUNT WITH TAHT INFO', 200); 
		}
		catch (\Throwable $e)
		{
			\DB::rollback();
			return $this->sendFailed($e->getMessage().' on line '.$e->getLine(), 400);  
		}
	}
}