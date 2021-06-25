<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CustomerContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerContactsController extends Controller
{
    public function newContact(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'contacts_count' => 'required|integer|min:0'
        ]);

        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {
            $index = $request['contacts_count'] + 1;

            $view = view('admin.customers.contacts.ajax_form_new_contact', compact('index'))->render();
        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }

        return response()->json(['view' => $view, 'index' => $index], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'contact_id' => 'required|integer|exists:customer_contacts,id',
            'phone_1' => 'required|string',
            'phone_2' => 'nullable|string',
            'address' => 'nullable|string',
            'name' => 'required|string|min:1|max:100',
        ]);

        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {
            $contact = CustomerContact::find($request['contact_id']);

            if (!$contact) {
                return response()->json('sorry, contact not valid', 400);
            }

            $contact->phone_1 = $request['phone_1'];
            $contact->phone_2 = $request['phone_2'];
            $contact->address = $request['address'];
            $contact->name = $request['name'];

            $contact->save();
        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }

        return response()->json('saved successfully', 200);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'contact_id' => 'required|integer|exists:customer_contacts,id'
        ]);

        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {
            $contact = CustomerContact::find($request['contact_id']);

            if (!$contact) {
                return response()->json('sorry, contact not valid', 400);
            }

            $contact->delete();
        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }

        return response()->json(['id' => $request['contact_id']], 200);
    }
}
