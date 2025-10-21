<?php 

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share parent categories with the navbar
        View::composer('admin.layouts.navbar', function ($view) {
            $view->with('parent_category', Category::whereNull('parent_id')->get());
        });
    }
}
