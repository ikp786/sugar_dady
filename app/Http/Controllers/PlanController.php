<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StorePlanRequest;
use App\Http\Resources\PlanCollection;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends BaseController
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPlan()
    {
        $planData = Plan::get();
        return $this->sendSuccess('GET PLANS SUCCESSFULLY', new PlanCollection($planData));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Plan';
        $plans = Plan::paginate(10);
        return view('admin_panel.index_plan',compact('plans','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Plan';
        return view('admin_panel.add_plan',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePlanRequest $request)
    {
        $input = $request->validated();
        $save = Plan::create($input);        
        return redirect()->route('plans.index')->with('Success','Plan Created Successfully');
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
        $title = 'Plan';
        $plans = Plan::find($id);
        return view('admin_panel.edit_plan',compact('title','plans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePlanRequest $request, $id)
    {   
        $input = $request->validated();
        $plans = Plan::find($id)->update($input);
        return redirect()->route('plans.index')->with('Success','Plan Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plan = Plan::find($id)->delete();
        return redirect()->route('plans.index')->with('Success','Plan deleted successfully');
    }
}
