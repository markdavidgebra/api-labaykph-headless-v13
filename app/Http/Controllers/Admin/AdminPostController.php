<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\BlogCategory;

class AdminPostController extends Controller
{
    public function index()
    {
        $posts = Post::with('blog_category')->get();
        return view('admin.post.index',compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::get();
        return view('admin.post.create', compact('categories'));
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|alpha_dash|unique:posts',
            'description' => 'required',
            'short_description' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'post_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $final_name);

        $obj = new Post();
        $obj->blog_category_id = $request->blog_category_id;
        $obj->title = $request->title;
        $obj->slug = $request->slug;
        $obj->description = $request->description;
        $obj->short_description = $request->short_description;
        $obj->photo = $final_name;
        $obj->save();

        return redirect()->route('admin_post_index')->with('success','Post is Created Successfully');
    }

    public function edit($id)
    {
        $categories = BlogCategory::get();
        $post = Post::where('id',$id)->first();
        return view('admin.post.edit',compact('post', 'categories'));
    }
    
    public function edit_submit(Request $request, $id)
    {
        $obj = Post::where('id',$id)->first();
        
        $request->validate([
            'title' => 'required',
            'slug' => 'required|alpha_dash|unique:posts,slug,'.$id,
            'description' => 'required',
            'short_description' => 'required',
        ]);

        if($request->hasFile('photo'))
        {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            unlink(public_path('uploads/'.$obj->photo));

            $final_name = 'post_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $final_name);
            $obj->photo = $final_name;
        }
        
        $obj->blog_category_id = $request->blog_category_id;
        $obj->title = $request->title;
        $obj->slug = $request->slug;
        $obj->description = $request->description;
        $obj->short_description = $request->short_description;
        $obj->save();

        return redirect()->route('admin_post_index')->with('success','Post is Updated Successfully');
    }

    public function delete($id)
    {
        $obj = Post::where('id',$id)->first();
        unlink(public_path('uploads/'.$obj->photo));
        $obj->delete();
        return redirect()->route('admin_post_index')->with('success','Post is Deleted Successfully');
    }
}
