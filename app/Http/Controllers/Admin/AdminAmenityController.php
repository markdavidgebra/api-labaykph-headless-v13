<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;
use App\Models\PackageAmenity;

class AdminAmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::get();
        return view('admin.amenity.index',compact('amenities'));
    }

    public function create()
    {
        return view('admin.amenity.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:amenities,name',
        ]);

        $obj = new Amenity();
        $obj->name = $request->name;
        $obj->save();

        return redirect()->route('admin_amenity_index')->with('success','Amenity is Created Successfully');
    }

    public function edit($id)
    {
        $amenity = Amenity::where('id',$id)->first();
        return view('admin.amenity.edit',compact('amenity'));
    }
    
    public function edit_submit(Request $request, $id)
    {
        $obj = Amenity::where('id',$id)->first();
        
        $request->validate([
            'name' => 'required|unique:amenities,name,'.$id,
        ]);

        $obj->name = $request->name;
        $obj->save();

        return redirect()->route('admin_amenity_index')->with('success','Amenity is Updated Successfully');
    }

    public function delete($id)
    {
        $total = PackageAmenity::where('amenity_id',$id)->count();
        if($total>0)
        {
            return redirect()->back()->with('error','Amenity is Assigned to Package(s), So it can not be deleted');
        }

        $obj = Amenity::where('id',$id)->first();
        $obj->delete();
        return redirect()->route('admin_amenity_index')->with('success','Amenity is Deleted Successfully');
    }
}
