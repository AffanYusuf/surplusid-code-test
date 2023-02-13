<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use App\Models\ProductCategory;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    protected $productModel;
    protected $categoryModel;
    protected $imageModel;
    public function __construct(
        Product $productModel,
        Category $categoryModel,
        Image $imageModel
    ) {
        $this->productModel     = $productModel;
        $this->categoryModel    = $categoryModel;
        $this->imageModel       = $imageModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = $this->productModel->with('categories')->with('images')->get();
            $response = [
                'code' => 200,
                'message' => 'Success',
                'data' => new ProductResource($products)
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
            $this->productModel->name = $request->name;
            $this->productModel->description = $request->description;
            $this->productModel->enable = $request->enable;
            $this->productModel->save();
            
            $categories = $this->categoryModel->find($request->categories);
            $images = $this->imageModel->find($request->images);

            $this->productModel->categories()->attach($categories);
            $this->productModel->images()->attach($images);

            $response = [
                'code' => 200,
                'message' => 'Data created successfully.',
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $products = $this->productModel->with('categories')->with('images')->find($id);
            $response = [
                'code' => 200,
                'message' => 'Success',
                'data' => new ProductResource($products)
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
            $categories = $this->productModel->with('categories')
            ->find($id)->categories;
            
            $categoryIds = $categories->map(function ($item, $key) {
                return $item->id;
            });

            foreach ($request->categories as $key => $value) {
                ProductCategory::where('product_id', '=', $id, 'and')
                ->whereIn('category_id', $categoryIds)->update(['product_id' => $id, 'category_id' => $value]);
            }
            $this->productModel->find($id)->update($request->all());

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
            $categories = $this->productModel->with('categories')->find($id)->categories;
            $this->productModel->categories()->detach($categories);

            $images = $this->productModel->with('images')->find($id)->images;
            $this->productModel->images()->detach($images);

            $this->productModel->destroy($id);
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
