<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        if (!empty($categories)) {
            return $this->onSuccess($categories, 'All Categories');
        }

        return $this->onError(404, 'No Categories Found');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if($this->IsAdmin($user)) {
            $validator = Validator::make($request->all(), $this->categoryValidationRules());

            if ($validator->passes()) {
                $category = Category::create([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                ]);
                return $this->onSuccess($category, 'Category Created');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $category = Category::find($id);

        if (!empty($category)) {
            return $this->onSuccess($category, 'Category Found');
        }

        return $this->onError(404, 'Category Not Found');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $request->user();

        if($this->IsAdmin($user)) {
            $validator = Validator::make($request->all(), $this->categoryValidationRules());

            if ($validator->passes()) {
                $category = Category::find($id);
                $category->update([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                ]);
                return $this->onSuccess($category, 'Category Updated');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        if($this->IsAdmin($user)) {
            $category = Category::find($id);
            if (!empty($category)) {
                $category->delete();
                return $this->onSuccess($category, 'Category Deleted');
            }
            return $this->onError(404, 'Category Not Found');
        }
        return $this->onError(401, 'Unauthorized');

    }
}
