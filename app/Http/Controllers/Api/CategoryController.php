<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    protected $model;
    public function __construct(
        Category $model
    ) {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $categories = $this->model->all();
            $response = [
                'code' => 200,
                'message' => 'Success',
                'data' => new CategoryResource($categories)
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $categories = $this->model->create($request->all());
            
            $response = [
                'code' => 200,
                'message' => 'Data created successfully.',
                'data' => new CategoryResource($categories)
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $categories = $this->model->find($id);
            $response = [
                'code' => 200,
                'message' => 'Success',
                'data' => new CategoryResource($categories)
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->model->find($id)->update($request->all());
            $response = [
                'code' => 200,
                'message' => 'Data updated successfully.',
                'data' => null
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->model->destroy($id);
            $response = [
                'code' => 204,
                'message' => 'Data deleted successfully.',
                'data' => null
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }
}
