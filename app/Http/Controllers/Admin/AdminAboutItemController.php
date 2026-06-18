<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutItem;

class AdminAboutItemController extends Controller
{
    public function index()
    {
        $about_item = AboutItem::firstOrCreate(
            ['id' => 1],
            ['feature_status' => 'Show']
        );
        return view('admin.about_item.index', compact('about_item'));
    }
    
    public function update(Request $request)
    {
        $obj = AboutItem::firstOrCreate(
            ['id' => 1],
            ['feature_status' => 'Show']
        );
        $obj->feature_status = $request->feature_status;
        $obj->save();

        return redirect()->back()->with('success','About Item is Updated Successfully');
    }
}
