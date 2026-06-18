<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeItem;

class AdminHomeItemController extends Controller
{
    public function index()
    {
        $home_item = HomeItem::where('id',1)->first();
        return view('admin.home_item.index',compact('home_item'));
    }

    
    public function update(Request $request)
    {
        $obj = HomeItem::where('id',1)->first();
        
        $request->validate([
            'destination_heading' => 'required',
            'destination_subheading' => 'required',
            'package_heading' => 'required',
            'package_subheading' => 'required',
            'testimonial_heading' => 'required',
            'testimonial_subheading' => 'required',
            'blog_heading' => 'required',
            'blog_subheading' => 'required',
        ]);

        if($request->hasFile('testimonial_background'))
        {
            $request->validate([
                'testimonial_background' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
            ]);
            if($obj->testimonial_background != '')
            {
                unlink(public_path('uploads/'.$obj->testimonial_background));
            }
            $final_name = 'testimonial_background_'.time().'.'.$request->testimonial_background->extension();
            $request->testimonial_background->move(public_path('uploads'), $final_name);
            $obj->testimonial_background = $final_name;
        }
        
        $obj->destination_heading = $request->destination_heading;
        $obj->destination_subheading = $request->destination_subheading;
        $obj->destination_status = $request->destination_status;
        $obj->feature_status = $request->feature_status;
        $obj->package_heading = $request->package_heading;
        $obj->package_subheading = $request->package_subheading;
        $obj->package_status = $request->package_status;
        $obj->testimonial_heading = $request->testimonial_heading;
        $obj->testimonial_subheading = $request->testimonial_subheading;
        $obj->testimonial_status = $request->testimonial_status;
        $obj->blog_heading = $request->blog_heading;
        $obj->blog_subheading = $request->blog_subheading;
        $obj->blog_status = $request->blog_status;
        $obj->cta_label = $request->cta_label;
        $obj->cta_title = $request->cta_title;
        $obj->cta_text = $request->cta_text;
        $obj->cta_status = $request->cta_status ?? 'Show';
        if ($request->hasFile('cta_background')) {
            $request->validate(['cta_background' => 'image|mimes:jpeg,jpg,png,gif|max:2048']);
            $old_bg = $obj->cta_background;
            if ($old_bg) {
                $old_path = public_path('uploads/'.$old_bg);
                if (file_exists($old_path)) {
                    unlink($old_path);
                }
            }
            $final_name = 'cta-journey-bg_'.time().'.'.$request->cta_background->extension();
            $request->cta_background->move(public_path('uploads'), $final_name);
            $obj->cta_background = $final_name;
        }
        $obj->save();

        return redirect()->back()->with('success','Home Item is Updated Successfully');
    }
}
