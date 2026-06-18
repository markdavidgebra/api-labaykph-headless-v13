<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;

class AdminInquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::orderBy('created_at', 'desc')->get();
        return view('admin.inquiry.index', compact('inquiries'));
    }

    public function delete($id)
    {
        $obj = Inquiry::where('id', $id)->first();
        if ($obj) {
            $obj->delete();
        }
        return redirect()->route('admin_inquiry_index')->with('success', 'Inquiry deleted successfully.');
    }
}
