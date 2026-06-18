<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use App\Models\Destination;
use App\Models\Amenity;
use App\Models\PackageAmenity;
use App\Models\PackageItinerary;
use App\Models\PackagePhoto;
use App\Models\PackageVideo;
use App\Models\PackageFaq;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Wishlist;

class AdminPackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('destination')
            ->withCount(['tours', 'package_photos', 'package_videos', 'package_amenities', 'package_itineraries', 'package_faqs'])
            ->orderBy('name')
            ->get();
        return view('admin.package.index', compact('packages'));
    }

    public function create()
    {
        $destinations = Destination::orderBy('name','asc')->get();
        return view('admin.package.create', compact('destinations'));
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:packages',
            'slug' => 'required|alpha_dash|unique:packages',
            'description' => 'required',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'featured_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'package_featured_'.time().'.'.$request->featured_photo->extension();
        $request->featured_photo->move(public_path('uploads'), $final_name);

        $final_name1 = 'package_banner_'.time().'.'.$request->banner->extension();
        $request->banner->move(public_path('uploads'), $final_name1);

        $obj = new Package();
        $obj->destination_id = $request->destination_id;
        $obj->featured_photo = $final_name;
        $obj->banner = $final_name1;
        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->description = $request->description;
        $obj->price = $request->price;
        $obj->old_price = $request->filled('old_price') ? $request->old_price : null;
        $obj->map = $request->map;
        $obj->total_rating = 0;
        $obj->total_score = 0;
        $obj->sold_out = $request->boolean('sold_out');
        $obj->save();

        return redirect()->route('admin_package_index')->with('success','Package is Created Successfully');
    }

    public function edit($id)
    {
        $package = Package::where('id',$id)->first();
        $destinations = Destination::orderBy('name','asc')->get();
        return view('admin.package.edit',compact('package','destinations'));
    }
    
    public function edit_submit(Request $request, $id)
    {
        $package = Package::where('id',$id)->first();
        
        $request->validate([
            'name' => 'required|unique:packages,name,'.$id,
            'slug' => 'required|alpha_dash|unique:packages,slug,'.$id,
            'description' => 'required',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
        ]);

        if($request->hasFile('featured_photo'))
        {
            $request->validate([
                'featured_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $old_photo = public_path('uploads/'.$package->featured_photo);
            if ($package->featured_photo && file_exists($old_photo)) {
                unlink($old_photo);
            }
            $final_name = 'package_featured_'.time().'.'.$request->featured_photo->extension();
            $request->featured_photo->move(public_path('uploads'), $final_name);
            $package->featured_photo = $final_name;
        }

        if($request->hasFile('banner'))
        {
            $request->validate([
                'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $old_banner = public_path('uploads/'.$package->banner);
            if ($package->banner && file_exists($old_banner)) {
                unlink($old_banner);
            }
            $final_name1 = 'package_banner_'.time().'.'.$request->banner->extension();
            $request->banner->move(public_path('uploads'), $final_name1);
            $package->banner = $final_name1;
        }
        
        $package->destination_id = $request->destination_id;
        $package->name = $request->name;
        $package->slug = $request->slug;
        $package->description = $request->description;
        $package->price = $request->price;
        $package->old_price = $request->filled('old_price') ? $request->old_price : null;
        $package->map = $request->map;
        $package->sold_out = $request->boolean('sold_out');
        $package->save();

        return redirect()->route('admin_package_index')->with('success','Package is Updated Successfully');
    }

    public function delete($id)
    {
        $total = PackagePhoto::where('package_id',$id)->count();
        if($total > 0) {
            return redirect()->back()->with('error','First Delete All Photos of This Package');
        }

        $total1 = PackageVideo::where('package_id',$id)->count();
        if($total1 > 0) {
            return redirect()->back()->with('error','First Delete All Videos of This Package');
        }

        $total2 = PackageAmenity::where('package_id',$id)->count();
        if($total2 > 0) {
            return redirect()->back()->with('error','First Delete All Amenities of This Package');
        }

        $total3 = PackageItinerary::where('package_id',$id)->count();
        if($total3 > 0) {
            return redirect()->back()->with('error','First Delete All Itineraries of This Package');
        }

        $total4 = PackageFaq::where('package_id',$id)->count();
        if($total4 > 0) {
            return redirect()->back()->with('error','First Delete All FAQs of This Package');
        }

        $total5 = Tour::where('package_id',$id)->count();
        if($total5 > 0) {
            return redirect()->back()->with('error','First Delete All Tours of This Package');
        }

        $package = Package::where('id',$id)->first();
        $featured_path = public_path('uploads/'.$package->featured_photo);
        if ($package->featured_photo && file_exists($featured_path)) {
            unlink($featured_path);
        }
        $banner_path = public_path('uploads/'.$package->banner);
        if ($package->banner && file_exists($banner_path)) {
            unlink($banner_path);
        }
        $package->delete();
        return redirect()->route('admin_package_index')->with('success','Package is Deleted Successfully');
    }

    /**
     * Force delete package and all related data (Super Admin only).
     */
    public function package_force_delete($id)
    {
        if (!Auth::guard('superadmin')->check()) {
            abort(403, 'Only super admin can force delete packages.');
        }

        $package = Package::find($id);
        if (!$package) {
            return redirect()->route('admin_package_index')->with('error', 'Package not found.');
        }

        // Delete related records in safe order
        Booking::where('package_id', $id)->delete();
        Review::where('package_id', $id)->delete();
        Wishlist::where('package_id', $id)->delete();
        Tour::where('package_id', $id)->delete();

        foreach (PackagePhoto::where('package_id', $id)->get() as $photo) {
            $photo_path = public_path('uploads/' . $photo->photo);
            if ($photo->photo && file_exists($photo_path)) {
                unlink($photo_path);
            }
            $photo->delete();
        }
        PackageVideo::where('package_id', $id)->delete();
        PackageAmenity::where('package_id', $id)->delete();
        PackageItinerary::where('package_id', $id)->delete();
        PackageFaq::where('package_id', $id)->delete();

        $featured_path = public_path('uploads/' . $package->featured_photo);
        if ($package->featured_photo && file_exists($featured_path)) {
            unlink($featured_path);
        }
        $banner_path = public_path('uploads/' . $package->banner);
        if ($package->banner && file_exists($banner_path)) {
            unlink($banner_path);
        }

        $package->delete();

        return redirect()->route('admin_package_index')->with('success', 'Package and all related data have been permanently deleted.');
    }

    public function package_amenities($id)
    {
        $package = Package::where('id',$id)->first();
        $package_amenities_include = PackageAmenity::with('amenity')->where('package_id',$id)->where('type','Include')->get();
        $package_amenities_exclude = PackageAmenity::with('amenity')->where('package_id',$id)->where('type','Exclude')->get();
        $amenities = Amenity::orderBy('name','asc')->get();
        return view('admin.package.amenities',compact('package', 'package_amenities_include', 'package_amenities_exclude', 'amenities'));
    }

    public function package_amenity_submit(Request $request, $id)
    {
        $total = PackageAmenity::where('package_id',$id)->where('amenity_id',$request->amenity_id)->count();
        if($total>0) {
            return redirect()->back()->with('error','This Item is Already Inserted');
        }

        $obj = new PackageAmenity;
        $obj->package_id = $id;
        $obj->amenity_id = $request->amenity_id;
        $obj->type = $request->type;
        $obj->save();

        return redirect()->back()->with('success','Item is Inserted Successfully');
    }

    public function package_amenity_delete($id)
    {
        $obj = PackageAmenity::where('id',$id)->first();
        $obj->delete();
        return redirect()->back()->with('success','Item is Deleted Successfully');
    }



    public function package_itineraries($id)
    {
        $package = Package::where('id',$id)->first();
        $package_itineraries = PackageItinerary::where('package_id',$id)->get();
        return view('admin.package.itineraries',compact('package', 'package_itineraries'));
    }

    public function package_itinerary_submit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        $obj = new PackageItinerary;
        $obj->package_id = $id;
        $obj->name = $request->name;
        $obj->description = $request->description;
        $obj->save();

        return redirect()->back()->with('success','Item is Inserted Successfully');
    }

    public function package_itinerary_delete($id)
    {
        $obj = PackageItinerary::where('id',$id)->first();
        $obj->delete();
        return redirect()->back()->with('success','Item is Deleted Successfully');
    }


    public function package_photos($id)
    {
        $package = Package::where('id',$id)->first();
        $package_photos = PackagePhoto::where('package_id',$id)->get();
        return view('admin.package.photos',compact('package', 'package_photos'));
    }

    public function package_photo_submit(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'package_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $final_name);

        $obj = new PackagePhoto;
        $obj->package_id = $id;
        $obj->photo = $final_name;
        $obj->save();

        return redirect()->back()->with('success','Photo is Inserted Successfully');
    }

    public function package_photo_delete($id)
    {
        $package_photo = PackagePhoto::where('id',$id)->first();
        $photo_path = public_path('uploads/'.$package_photo->photo);
        if ($package_photo->photo && file_exists($photo_path)) {
            unlink($photo_path);
        }
        $package_photo->delete();
        return redirect()->back()->with('success','Photo is Deleted Successfully');
    }


    public function package_videos($id)
    {
        $package = Package::where('id',$id)->first();
        $package_videos = PackageVideo::where('package_id',$id)->get();
        return view('admin.package.videos',compact('package', 'package_videos'));
    }

    public function package_video_submit(Request $request, $id)
    {
        $request->validate([
            'video' => 'required',
        ]);

        $videoId = extract_youtube_video_id($request->video);
        if (!$videoId) {
            return redirect()->back()->with('error', 'Invalid YouTube URL. Please enter a valid YouTube link or video ID.');
        }

        $obj = new PackageVideo;
        $obj->package_id = $id;
        $obj->video = $videoId;
        $obj->save();

        return redirect()->back()->with('success','Video is Inserted Successfully');
    }

    public function package_video_delete($id)
    {
        $package_video = PackageVideo::where('id',$id)->first();
        $package_video->delete();
        return redirect()->back()->with('success','Video is Deleted Successfully');
    }


    public function package_faqs($id)
    {
        $package = Package::where('id',$id)->first();
        $package_faqs = PackageFaq::where('package_id',$id)->get();
        return view('admin.package.faqs',compact('package', 'package_faqs'));
    }

    public function package_faq_submit(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $obj = new PackageFaq;
        $obj->package_id = $id;
        $obj->question = $request->question;
        $obj->answer = $request->answer;
        $obj->save();

        return redirect()->back()->with('success','FAQ is Inserted Successfully');
    }

    public function package_faq_delete($id)
    {
        $package_faq = PackageFaq::where('id',$id)->first();
        $package_faq->delete();
        return redirect()->back()->with('success','FAQ is Deleted Successfully');
    }
}
