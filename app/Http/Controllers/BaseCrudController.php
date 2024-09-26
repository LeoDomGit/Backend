<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
abstract class BaseCrudController extends Controller
{
    protected $model;    // The model class (string)
    protected $viewPath; // The Inertia view path
    protected $validate;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->model::all();
        if ($this->viewPath) {
            return Inertia::render($this->viewPath, ['dataset' => $data]);
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validate);
        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        // Create an instance of the model to access its properties
        $modelInstance = new $this->model;
        $validatedData= $request->all();
        // Check if the model has a 'slug' field, and if so, generate the slug
        if (in_array('slug', $modelInstance->getFillable())) {
            $validatedData['slug'] = Str::slug($request->name);
        }
        // Create the resource
        $this->model::create($validatedData);
        $data = $this->model::all();

        return response()->json(['check' => true, 'data' => $data], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), $this->validate);
        // if ($validator->fails()) {
        //     return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        // }
        // Create an instance of the model to access its properties
        $modelInstance = new $this->model;
        $validatedData= $request->all();

        // Check if the model has a 'slug' field, and if so, generate the slug
        if (in_array('slug', $modelInstance->getFillable())) {
            if($request->has('name') || $request->has('title')) {
                $validatedData['slug'] = Str::slug($request->name);
            }
        }
        $item= $this->model::find($id);
        $item->update($validatedData);
        $data = $this->model::all();

        return response()->json(['check' => true, 'data' => $data], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = $this->model::findOrFail($id); // Find the item by ID

        // Delete the item
        $item->delete();

        // Get the updated list of items
        $data = $this->model::all();

        // Return response with updated data
        return response()->json(['check' => true, 'data' => $data], 200);
    }

    /**
     * Get validation rules (should be overridden in child controllers).
     */
}
