<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TermPrivacyItem;

class AdminTermPrivacyItemController extends Controller
{
    public function index()
    {
        $term_privacy_item = TermPrivacyItem::where('id',1)->first();
        return view('admin.term_privacy_item.index',compact('term_privacy_item'));
    }
    
    public function update(Request $request)
    {
        $obj = TermPrivacyItem::where('id',1)->first();
        $obj->term = $request->term;
        $obj->privacy = $request->privacy;
        $obj->save();

        return redirect()->back()->with('success','Term & Privacy Item is Updated Successfully');
    }
}
