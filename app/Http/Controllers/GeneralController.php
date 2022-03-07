<?php

namespace App\Http\Controllers;

use App\Models\contact;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
     public function AddContacts(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                "name" => "required",
                "email" => "required",
                "location" => "required",
                "phone" => "required",
                "imageurl" => "required",
            ]);

            if ($request->type == 'edit') {
                $saved = contact::where('id', $request->id)->update($request->except(['type', 'id', 'created_at', 'updated_at']));
                if ($saved) {
                    return response()->json($request->except(['created_at', 'updated_at']));
                }
            } else {
                $all_contacts = $request->all();
                // dd($all_contacts);
                $save_contacts = contact::create($all_contacts);
                if ($request->wantsJson()) {
                    return response()->json($save_contacts);
                } elseif ($save_contacts) {
                    return back()->with("message", "Contact successfully saved.");
                }
            }

        }
    }

    public function Contact()
    {
        $contact = contact::get();
        return response()->json($contact);
    }


    public function deleteContact(Request $request, $id)
    {
        $delete_contact = contact::find($id)->delete();
        
        if($delete_contact){
            // $contact = contact::get();
            return response()->json(["message" => "Contact  Deleted Successfully", "data" => $delete_contact]);
        }
    }


    public function deleteAll(Request $request)
    {
        $arrays = $request->all();
        return response()->json($arrays);
        $delete_contact = contact::whereIn('id', $arrays)->delete();

        if($delete_contact){

            return response()->json(["message" => "Contact  Deleted Successfully", "data" => $delete_contact]);
        }
    }

}
