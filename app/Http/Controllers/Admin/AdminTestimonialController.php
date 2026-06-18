<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class AdminTestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::get();
        return view('admin.testimonial.index',compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonial.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'designation' => 'nullable',
            'comment' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $obj = new Testimonial();
        $obj->name = $request->name;
        $obj->designation = $request->designation ?: null;
        $obj->comment = $request->comment;

        if ($request->hasFile('photo')) {
            $final_name = 'testimonial_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $final_name);
            $obj->photo = $final_name;
        } else {
            $obj->photo = null;
        }

        $obj->save();

        return redirect()->route('admin_testimonial_index')->with('success','Testimonial is Created Successfully');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::where('id',$id)->first();
        return view('admin.testimonial.edit',compact('testimonial'));
    }
    
    public function edit_submit(Request $request, $id)
    {
        $testimonial = Testimonial::where('id',$id)->first();
        
        $request->validate([
            'name' => 'required',
            'designation' => 'nullable',
            'comment' => 'required',
        ]);

        if($request->hasFile('photo'))
        {
            $request->validate([
                'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($testimonial->photo) {
                $path = public_path('uploads/'.$testimonial->photo);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $final_name = 'testimonial_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $final_name);
            $testimonial->photo = $final_name;
        }
        
        $testimonial->name = $request->name;
        $testimonial->designation = $request->designation ?: null;
        $testimonial->comment = $request->comment;
        $testimonial->save();

        return redirect()->route('admin_testimonial_index')->with('success','Testimonial is Updated Successfully');
    }

    public function delete($id)
    {
        $testimonial = Testimonial::where('id',$id)->first();
        if ($testimonial->photo) {
            $path = public_path('uploads/'.$testimonial->photo);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $testimonial->delete();
        return redirect()->route('admin_testimonial_index')->with('success','Testimonial is Deleted Successfully');
    }
}
