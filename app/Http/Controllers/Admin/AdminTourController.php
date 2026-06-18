<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tour;
use App\Models\Package;
use App\Models\Booking;
use App\Models\Setting;

class AdminTourController extends Controller
{
    public function index()
    {
        $tours = Tour::with('package')->get();
        foreach ($tours as $tour) {
            $tour->unviewed_bookings_count = $tour->package_id
                ? Booking::where('tour_id', $tour->id)
                    ->where('package_id', $tour->package_id)
                    ->whereNull('admin_viewed_at')
                    ->count()
                : 0;
        }
        return view('admin.tour.index', compact('tours'));
    }

    public function create()
    {
        $packages = Package::orderBy('name','asc')->get();
        return view('admin.tour.create', compact('packages'));
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'tour_start_date' => 'required',
            'tour_end_date' => 'required',
            'booking_end_date' => 'required',
            'total_seat' => 'required',
        ]);

        $obj = new Tour();
        $obj->package_id = $request->package_id;
        $obj->tour_start_date = $request->tour_start_date;
        $obj->tour_end_date = $request->tour_end_date;
        $obj->booking_end_date = $request->booking_end_date;
        $obj->total_seat = $request->total_seat;
        $obj->save();

        return redirect()->route('admin_tour_index')->with('success','Tour is Created Successfully');
    }

    public function edit($id)
    {
        $tour = Tour::where('id',$id)->first();
        $packages = Package::orderBy('name','asc')->get();
        return view('admin.tour.edit',compact('tour', 'packages'));
    }
    
    public function edit_submit(Request $request, $id)
    {
        $obj = Tour::where('id',$id)->first();
        
        $request->validate([
            'tour_start_date' => 'required',
            'tour_end_date' => 'required',
            'booking_end_date' => 'required',
            'total_seat' => 'required',
        ]);

        $obj->package_id = $request->package_id;
        $obj->tour_start_date = $request->tour_start_date;
        $obj->tour_end_date = $request->tour_end_date;
        $obj->booking_end_date = $request->booking_end_date;
        $obj->total_seat = $request->total_seat;
        $obj->save();

        return redirect()->route('admin_tour_index')->with('success','Tour is Updated Successfully');
    }

    public function delete($id)
    {

        $total = Booking::where('tour_id',$id)->count();
        if($total > 0)
        {
            return redirect()->back()->with('error','This Tour has Bookings. So, it can not be deleted');
        }

        $obj = Tour::where('id',$id)->first();
        $obj->delete();
        return redirect()->route('admin_tour_index')->with('success','Tour is Deleted Successfully');
    }

    /**
     * Force delete tour and all related bookings (Super Admin only).
     */
    public function tour_force_delete($id)
    {
        if (!Auth::guard('superadmin')->check()) {
            abort(403, 'Only super admin can force delete tours.');
        }

        $tour = Tour::find($id);
        if (!$tour) {
            return redirect()->route('admin_tour_index')->with('error', 'Tour not found.');
        }

        Booking::where('tour_id', $id)->delete();
        $tour->delete();

        return redirect()->route('admin_tour_index')->with('success', 'Tour and all related bookings have been permanently deleted.');
    }

    public function tour_booking($tour_id,$package_id)
    {
        // Mark all bookings for this tour/package as viewed by admin
        Booking::where('tour_id', $tour_id)
            ->where('package_id', $package_id)
            ->whereNull('admin_viewed_at')
            ->update(['admin_viewed_at' => now()]);

        $all_data = Booking::with('user')
            ->where('tour_id',$tour_id)
            ->where('package_id',$package_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.tour.booking',compact('all_data'));
    }

    public function tour_booking_delete($id)
    {
        $obj = Booking::where('id',$id)->first();
        $obj->delete();
        return redirect()->back()->with('success','Booking is Deleted Successfully');
    }

    public function tour_invoice($invoice_no)
    {
        $booking = Booking::with(['user','tour','package'])->where('invoice_no',$invoice_no)->first();
        $setting = Setting::where('id',1)->first();
        return view('admin.tour.reference',compact('booking','setting'));
    }

    public function tour_booking_approve($id)
    {
        Booking::where('id',$id)->update(['payment_status'=>'Completed']);
        return redirect()->back()->with('success','Booking is Approved Successfully');
    }
}
