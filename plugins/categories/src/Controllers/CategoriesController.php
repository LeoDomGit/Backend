<?php

namespace Leo\Categories\Controllers;

use App\Http\Controllers\BaseCrudController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends BaseCrudController
{
    /**
     * Display a listing of the resource.
     */
    protected $model = Category::class;
    protected $viewPath = "Categories/Index";
    protected $validate =[
        'name'=>'required|unique:categories,name'
    ];
}
