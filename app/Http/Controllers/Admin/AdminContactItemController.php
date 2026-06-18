<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactItem;
use App\Models\ContactOffice;

class AdminContactItemController extends Controller
{
    public function index()
    {
        $contact_item = ContactItem::where('id',1)->first();
        $contact_offices = ContactOffice::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.contact_item.index', compact('contact_item', 'contact_offices'));
    }

    public function update(Request $request)
    {
        $obj = ContactItem::where('id',1)->first();
        if ($obj) {
            $obj->map_code = $request->map_code;
            $obj->save();
        }
        return redirect()->back()->with('success', 'Contact Item is Updated Successfully');
    }

    public function office_create()
    {
        return view('admin.contact_item.office_create');
    }

    public function office_create_submit(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $maxOrder = ContactOffice::max('sort_order') ?? 0;
        ContactOffice::create([
            'name' => $request->name,
            'address' => $request->address,
            'landline' => $request->landline,
            'globe' => $request->globe,
            'smart' => $request->smart,
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()->route('admin_contact_item_index')->with('success', 'Office added successfully.');
    }

    public function office_edit($id)
    {
        $office = ContactOffice::findOrFail($id);
        return view('admin.contact_item.office_edit', compact('office'));
    }

    public function office_edit_submit(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $office = ContactOffice::findOrFail($id);
        $office->update([
            'name' => $request->name,
            'address' => $request->address,
            'landline' => $request->landline,
            'globe' => $request->globe,
            'smart' => $request->smart,
        ]);

        return redirect()->route('admin_contact_item_index')->with('success', 'Office updated successfully.');
    }

    public function office_delete($id)
    {
        ContactOffice::findOrFail($id)->delete();
        return redirect()->route('admin_contact_item_index')->with('success', 'Office deleted successfully.');
    }
}
