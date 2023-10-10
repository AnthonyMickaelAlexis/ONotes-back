<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Models\Article;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = SubCategory::all();

        if (!empty($subcategories)){
            return $this->onSuccess($subcategories, 'All SubCategories');
        }

        return $this->onError(404, 'No SubCategories Found');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $user = $request->user();

       if($this->isAdmin($user)){
        $validator = Validator::make($request->all(), $this->subcategoryValidationRules($request->category_id));
        if ($validator->passes()) {
            $subcategory = SubCategory::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
            ]);
            return $this->onSuccess($subcategory, 'SubCategory Created');
        }
        return $this->onError(400,$validator->errors());
       }
       return $this->onError(401, 'Unauthorized');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subcategory = SubCategory::find($id);
        if (!empty($subcategory)) {
            return $this->onSuccess($subcategory, 'SubCategory Found');
        }
        return $this->onError(404, 'SubCategory Not Found');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $request->user();
        $subcategory = SubCategory::find($id);
        $categoryId = $subcategory->category_id;

        if($this->isAdmin($user)){
            $validator = Validator::make($request->all(), $this->subcategoryValidationRules($categoryId));
            if ($validator->passes()) {
                $subcategory->update([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'category_id' => $categoryId,
                ]);
                return $this->onSuccess($subcategory, 'SubCategory Updated');
            }
            return $this->onError(400,$validator->errors());
        }
        return $this->onError(401, 'Unauthorized');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        if($this->isAdmin($user)){
            $subcategory = SubCategory::find($id);
            if (!empty($subcategory)) {
                $subcategory->delete();
                return $this->onSuccess($subcategory, 'SubCategory Deleted');
            }
            return $this->onError(404, 'SubCategory Not Found');
        }
    }
}
