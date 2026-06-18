<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Package;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user','package'])->orderBy('created_at', 'desc')->get();
        return view('admin.review.index', compact('reviews'));
    }

    public function approve($id)
    {
        $obj = Review::where('id',$id)->first();
        
        // Only update package ratings if review was not already approved
        if($obj->status != 'Approved') {
            $package_data = Package::where('id',$obj->package_id)->first();
            
            // If it was previously rejected, we need to add it back
            if($obj->status == 'Rejected') {
                $package_data->total_rating = $package_data->total_rating + 1;
                $package_data->total_score = $package_data->total_score + $obj->rating;
            } else {
                // If it was pending, add it for the first time
                $package_data->total_rating = $package_data->total_rating + 1;
                $package_data->total_score = $package_data->total_score + $obj->rating;
            }
            
            $package_data->save();
        }
        
        $obj->status = 'Approved';
        $obj->admin_viewed_at = now();
        $obj->save();

        return redirect()->back()->with('success','Review is Approved Successfully');
    }

    public function reject($id)
    {
        $obj = Review::where('id',$id)->first();
        
        // Only update package ratings if review was previously approved
        if($obj->status == 'Approved') {
            $package_data = Package::where('id',$obj->package_id)->first();
            $updated_total_rating = $package_data->total_rating - 1;
            $updated_total_score = $package_data->total_score - $obj->rating;
            $package_data->total_rating = $updated_total_rating;
            $package_data->total_score = $updated_total_score;
            $package_data->save();
        }
        
        $obj->status = 'Rejected';
        $obj->admin_viewed_at = now();
        $obj->save();

        return redirect()->back()->with('success','Review is Rejected Successfully');
    }
}
