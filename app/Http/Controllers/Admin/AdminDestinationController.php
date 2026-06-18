<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Destination;
use App\Models\DestinationPhoto;
use App\Models\DestinationVideo;
use App\Models\Package;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\Tour;
use App\Models\PackagePhoto;
use App\Models\PackageVideo;
use App\Models\PackageAmenity;
use App\Models\PackageItinerary;
use App\Models\PackageFaq;

class AdminDestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::withCount(['packages', 'photos', 'videos'])
            ->orderBy('name')
            ->get();
        return view('admin.destination.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destination.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:destinations',
            'slug' => 'required|alpha_dash|unique:destinations',
            'description' => 'required',
            'featured_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'destination_featured_'.time().'.'.$request->featured_photo->extension();
        $request->featured_photo->move(public_path('uploads'), $final_name);

        $obj = new Destination();
        $obj->featured_photo = $final_name;
        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->description = $request->description;
        $obj->country = $request->country;
        $obj->language = $request->language;
        $obj->currency = $request->currency;
        $obj->area = $request->area;
        $obj->timezone = $request->timezone;
        $obj->visa_requirement = $request->visa_requirement;
        $obj->activity = $request->activity;
        $obj->best_time = $request->best_time;
        $obj->health_safety = $request->health_safety;
        $obj->map = $request->map;
        $obj->view_count = 1;
        $obj->save();

        return redirect()->route('admin_destination_index')->with('success','Destination is Created Successfully');
    }

    public function edit($id)
    {
        $destination = Destination::where('id',$id)->first();
        return view('admin.destination.edit',compact('destination'));
    }
    
    public function edit_submit(Request $request, $id)
    {
        $destination = Destination::where('id',$id)->first();
        
        $request->validate([
            'name' => 'required|unique:destinations,name,'.$id,
            'slug' => 'required|alpha_dash|unique:destinations,slug,'.$id,
            'description' => 'required',
        ]);

        if($request->hasFile('featured_photo'))
        {
            $request->validate([
                'featured_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $old_photo = public_path('uploads/'.$destination->featured_photo);
            if ($destination->featured_photo && file_exists($old_photo)) {
                unlink($old_photo);
            }

            $final_name = 'destination_featured_'.time().'.'.$request->featured_photo->extension();
            $request->featured_photo->move(public_path('uploads'), $final_name);
            $destination->featured_photo = $final_name;
        }
        
        $destination->name = $request->name;
        $destination->slug = $request->slug;
        $destination->description = $request->description;
        $destination->country = $request->country;
        $destination->language = $request->language;
        $destination->currency = $request->currency;
        $destination->area = $request->area;
        $destination->timezone = $request->timezone;
        $destination->visa_requirement = $request->visa_requirement;
        $destination->activity = $request->activity;
        $destination->best_time = $request->best_time;
        $destination->health_safety = $request->health_safety;
        $destination->map = $request->map;
        $destination->save();

        return redirect()->route('admin_destination_index')->with('success','Destination is Updated Successfully');
    }

    public function delete($id)
    {
        $total = DestinationPhoto::where('destination_id',$id)->count();
        if($total > 0) {
            return redirect()->back()->with('error','First Delete All Photos of This Destination');
        }

        $total1 = DestinationVideo::where('destination_id',$id)->count();
        if($total1 > 0) {
            return redirect()->back()->with('error','First Delete All Videos of This Destination');
        }

        $destination = Destination::where('id',$id)->first();
        $featured_path = public_path('uploads/'.$destination->featured_photo);
        if ($destination->featured_photo && file_exists($featured_path)) {
            unlink($featured_path);
        }
        $destination->delete();
        return redirect()->route('admin_destination_index')->with('success','Destination is Deleted Successfully');
    }

    /**
     * Force delete destination and all related data (Super Admin only).
     */
    public function destination_force_delete($id)
    {
        if (!Auth::guard('superadmin')->check()) {
            abort(403, 'Only super admin can force delete destinations.');
        }

        $destination = Destination::find($id);
        if (!$destination) {
            return redirect()->route('admin_destination_index')->with('error', 'Destination not found.');
        }

        $packageIds = Package::where('destination_id', $id)->pluck('id');

        foreach ($packageIds as $packageId) {
            Booking::where('package_id', $packageId)->delete();
            Review::where('package_id', $packageId)->delete();
            Wishlist::where('package_id', $packageId)->delete();
            Tour::where('package_id', $packageId)->delete();

            foreach (PackagePhoto::where('package_id', $packageId)->get() as $photo) {
                $photo_path = public_path('uploads/' . $photo->photo);
                if ($photo->photo && file_exists($photo_path)) {
                    unlink($photo_path);
                }
                $photo->delete();
            }
            PackageVideo::where('package_id', $packageId)->delete();
            PackageAmenity::where('package_id', $packageId)->delete();
            PackageItinerary::where('package_id', $packageId)->delete();
            PackageFaq::where('package_id', $packageId)->delete();

            $pkg = Package::find($packageId);
            if ($pkg) {
                $fp = public_path('uploads/' . $pkg->featured_photo);
                if ($pkg->featured_photo && file_exists($fp)) {
                    unlink($fp);
                }
                $bp = public_path('uploads/' . $pkg->banner);
                if ($pkg->banner && file_exists($bp)) {
                    unlink($bp);
                }
                $pkg->delete();
            }
        }

        foreach (DestinationPhoto::where('destination_id', $id)->get() as $photo) {
            $photo_path = public_path('uploads/' . $photo->photo);
            if ($photo->photo && file_exists($photo_path)) {
                unlink($photo_path);
            }
            $photo->delete();
        }
        DestinationVideo::where('destination_id', $id)->delete();

        $featured_path = public_path('uploads/' . $destination->featured_photo);
        if ($destination->featured_photo && file_exists($featured_path)) {
            unlink($featured_path);
        }

        $destination->delete();

        return redirect()->route('admin_destination_index')->with('success', 'Destination and all related data have been permanently deleted.');
    }

    public function destination_photos($id)
    {
        $destination = Destination::where('id',$id)->first();
        $destination_photos = DestinationPhoto::where('destination_id',$id)->get();
        return view('admin.destination.photos',compact('destination', 'destination_photos'));
    }

    public function destination_photo_submit(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'destination_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $final_name);

        $obj = new DestinationPhoto;
        $obj->destination_id = $id;
        $obj->photo = $final_name;
        $obj->save();

        return redirect()->back()->with('success','Photo is Inserted Successfully');
    }

    public function destination_photo_delete($id)
    {
        $destination_photo = DestinationPhoto::where('id',$id)->first();
        $photo_path = public_path('uploads/'.$destination_photo->photo);
        if ($destination_photo->photo && file_exists($photo_path)) {
            unlink($photo_path);
        }
        $destination_photo->delete();
        return redirect()->back()->with('success','Photo is Deleted Successfully');
    }


    public function destination_videos($id)
    {
        $destination = Destination::where('id',$id)->first();
        $destination_videos = DestinationVideo::where('destination_id',$id)->get();
        return view('admin.destination.videos',compact('destination', 'destination_videos'));
    }

    public function destination_video_submit(Request $request, $id)
    {
        $request->validate([
            'video' => 'required',
        ]);

        $videoId = extract_youtube_video_id($request->video);
        if (!$videoId) {
            return redirect()->back()->with('error', 'Invalid YouTube URL. Please enter a valid YouTube link or video ID.');
        }

        $obj = new DestinationVideo;
        $obj->destination_id = $id;
        $obj->video = $videoId;
        $obj->save();

        return redirect()->back()->with('success','Video is Inserted Successfully');
    }

    public function destination_video_delete($id)
    {
        $destination_video = DestinationVideo::where('id',$id)->first();
        $destination_video->delete();
        return redirect()->back()->with('success','Video is Deleted Successfully');
    }
}
