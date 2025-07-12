<?php

namespace App\Http\Controllers\Api;

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
        $category = $category->load('recipes');
        return  new CategoryResource($category);
    }
    public function update(){}
    public function destroy(){}
}
