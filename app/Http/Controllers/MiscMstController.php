<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController;
use App\Http\Requests\MiscRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Misc;
use App\Models\User;
use App\Rules\UploadUserImageCount;
use Hash;
use Auth;

class MiscMstController extends BaseController
{

    public function getGender(Type $var = null)
    {
        $data = Misc::where('misc_type','1001')->get();
        if ($data) {            
         return $this->sendSuccess('GENDER GET SUCCESSFULLY', $data);
        }
    }

    public function intrestedIn(Type $var = null)
    {
        $data = Misc::where('misc_type','1003')->get();
        if ($data) {            
         return $this->sendSuccess('INTREST GET SUCCESSFULLY', $data);
        }
    }

    public function sexualOrientation(Type $var = null)
    {
        $data = Misc::where('misc_type','1002')->get();
        if ($data) {            
         return $this->sendSuccess('SEXUAL ORIENTATION GET SUCCESSFULLY', $data);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Misc';
        $misce = Misc::paginate(5);
        return view('admin_panel.index_misc',compact('title','misce'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Mics';
        return view('admin_panel.add_misc',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MiscRequest $request)
    {
        $input = $request->validated();
         Misc::create($input);
        return redirect()->route('misces.index')->with('success','New Misc Created');
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
    public function edit($id)
    {
        $misc_data = Misc::find($id);
        $title = 'Misc';
        return view('admin_panel.edit_misc',compact('title','misc_data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MiscRequest $request, $id)
    {
        $input =  $request->validated();
        $misc = Misc::find($id);
        $misc->update($input);
        return redirect()->route('misces.index')->with('success','Misc Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('gender_id',$id)->orWhere('sexcual_orientation_id',$id)->get();
        $user_id = isset($user[0]->user_id) ? 1 : 0;
        if ($user_id == 1) {            
            return redirect()->back()->with(['Failed' => "this Misc cannot be deleted. Because this Misc foreign key save in User table"]);
        }
        $misc = Misc::find($id);
        $misc->delete();
        return redirect()->back()->with(['success' => "Misc Deleted"]);
    }
}
