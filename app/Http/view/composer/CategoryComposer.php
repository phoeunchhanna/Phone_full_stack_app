
<?php

namespace App\Http\view\composer;

use App\Models\Category;
use Illuminate\View\View;

class CategoryComposer
{
    public function compose(View $view)
    {
        $view->with('categories', Category::all());
        
        // If you want to include the current category as well
        if(request()->route() && request()->route()->getName() == 'categories.show') {
            $view->with('category', Category::with('products')
                ->findOrFail(request()->route('id')));
        } else {
            $view->with('category', Category::with('products')->first());
        }
    }
}