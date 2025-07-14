<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V2\RecipeController;




Route::prefix('v2')->group(function(){

    Route::get('recipes',            [RecipeController::class,'index']);
  
});