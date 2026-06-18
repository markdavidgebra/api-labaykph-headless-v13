<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\Post;

class AdminBlogCategoryController extends Controller
{
    public function index()
    {
        $blog_categories = BlogCategory::get();
        return view('admin.blog_category.index',compact('blog_categories'));
    }

    public function create()
    {
        return view('admin.blog_category.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|alpha_dash|unique:blog_categories',
        ]);

        $obj = new BlogCategory();
        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->save();

        return redirect()->route('admin_blog_category_index')->with('success','Blog Category is Created Successfully');
    }

    public function edit($id)
    {
        $blog_category = BlogCategory::where('id',$id)->first();
        return view('admin.blog_category.edit',compact('blog_category'));
    }
    
    public function edit_submit(Request $request, $id)
    {
        $obj = BlogCategory::where('id',$id)->first();
        
        $request->validate([
            'name' => 'required',
            'slug' => 'required|alpha_dash|unique:blog_categories,slug,'.$id,
        ]);

        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->save();

        return redirect()->route('admin_blog_category_index')->with('success','Blog Category is Updated Successfully');
    }

    public function delete($id)
    {
        $total = Post::where('blog_category_id',$id)->count();
        if($total > 0)
        {
            return redirect()->back()->with('error','This Blog Category is in use. So you can not delete it.');
        }

        $faq = BlogCategory::where('id',$id)->first();
        $faq->delete();
        return redirect()->route('admin_blog_category_index')->with('success','Blog Category is Deleted Successfully');
    }
}
