<?php

namespace App\Http\Controllers;


use App\Models\Cause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CauseContoller extends Controller
{
    public function retreive () {
        $cause = Cause::all();

        $data = [
             'status' => 'success',
                'message' => $cause
        ];

        return response()->json($data, 200);

    }


    // Create a new cause
    public function create (Request $request) 
    {


    
        $validator = Validator::make($request->all(), 
        [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }
    
        else {
         $cause = new Cause();
            $cause->title = $request->title;
            $cause->description = $request->description;
            $cause->image_url = $request->image_url;
            $cause->save();
            return response()->json([
                'status' => 'success',
                'data' => $cause
            ], 201);
        }
    }


// Show a specific cause
    public function show ($id) {
        $cause = Cause::find($id);

        if (!$cause) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cause not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $cause
        ], 200);
    }


// Update a cause
   public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
        'image_url' => 'required|url',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()
        ], 422);
    }

    $cause = Cause::find($id);

    if (!$cause) {
        return response()->json([
            'status' => 'error',
            'message' => 'Cause not found'
        ], 404);
    }

    $cause->title = $request->title;
    $cause->description = $request->description;
    $cause->image_url = $request->image_url;
    $cause->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Cause updated successfully',
    ], 200);
}

public function delete($id) 
{

    $cause = Cause::find($id);

    $cause->delete();

     return response()->json([
        'status' => 'success',
        'message' => 'Cause deleted successfully',
    ], 200);

  
}

 }
