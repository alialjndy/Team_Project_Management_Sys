<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Http\Requests\UpdatedActivityRequest;
use App\Models\Activity;
use App\Models\Project;
use App\Service\CrudActivityService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $crudActivityService;
    public function __construct(CrudActivityService $crudActivityService){
        $this->crudActivityService = $crudActivityService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allActivity = $this->crudActivityService->getAllActivity();
        return response()->json([
            'status'=>'success',
            'All Activity'=>$allActivity
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActivityRequest $request)
    {
        $validatedData = $request->validated();
        $activity = $this->crudActivityService->createActivity($validatedData);
        return response()->json([
            'status'=>'success',
            'activity'=>$activity
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activity = $this->crudActivityService->getActivityById($id);
        return response()->json([
            'status'=>'success',
            'activity'=>$activity
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedActivityRequest $request, $id)
    {
        $validatedData = $request->validated();
        $this->crudActivityService->updateActivity($validatedData ,$id);
        $UpadtedActivity = Activity::findOrFail($id);
        return response()->json([
            'status'=>'success',
            'message'=>'Activity Updated Successfully',
            'data'=>$UpadtedActivity
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->crudActivityService->deleteActivity($id);
        return response()->json([
            'status'=>'success',
            'message'=>'Activity deleted successfully'
        ],200);
    }

}
