<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierBankAccountController extends Controller
{
    public function newBankAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_account_count' => 'required|integer|min:0'
        ]);

        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }
        try {
            $index = $request['bank_account_count'] + 1;
            $view = view('admin.suppliers.bank_accounts.ajax_form_new_contact', compact('index'))->render();
        } catch (Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
        return response()->json(['view' => $view, 'index' => $index], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_id' => 'required|integer|exists:supplier_contacts,id',
            'phone_1' => 'required|string',
            'phone_2' => 'nullable|string',
            'address' => 'nullable|string',
            'name' => 'required|string|min:1|max:100',
        ]);

        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {
            $bankAccount = BankAccount::find($request['bankAccountId']);
            if (!$bankAccount) {
                return response()->json('sorry, Bank Account not valid', 400);
            }
            $bankAccount->bank_name = $request['bank_name'];
            $bankAccount->account_name = $request['account_name'];
            $bankAccount->branch = $request['branch'];
            $bankAccount->account_number = $request['account_number'];
            $bankAccount->iban = $request['iban'];
            $bankAccount->swift_code = $request['swift_code'];
            $bankAccount->save();
        } catch (Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
        return response()->json('saved successfully', 200);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bankAccountId' => 'required|integer|exists:bank_accounts,id'
        ]);
        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }
        try {
            $bankAccount = BankAccount::find($request['bankAccountId']);
            if (!$bankAccount) {
                return response()->json('sorry, contact not valid', 400);
            }
            $bankAccount->delete();
        } catch (Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
        return response()->json(['id' => $request['bankAccountId']], 200);
    }
}
