<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;


class CategoryController extends Controller{
    public function index(){
        return new CategoryCollection(Category::all());

    }
    public function store(){}
    public function show(Category $category){
        $category = $category->load('recipes.category','recipes.tags','recipes.user');
        return  new CategoryResource($category);
    }
    public function update(){}
    public function destroy(){}
}
