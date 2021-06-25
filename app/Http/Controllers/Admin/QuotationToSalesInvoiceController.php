<?php

namespace App\Http\Controllers\Admin;

use App\Models\Quotation;
use App\Models\SalesInvoice;
use App\Services\QuotationToSalesServices;
use App\Services\SampleSalesInvoiceServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QuotationToSalesInvoiceController extends Controller
{
    use QuotationToSalesServices;

    public function quotationToSalesInvoice(Request $request)
    {
        $this->validate($request, [

            'quotation_id' => 'required|integer|exists:quotations,id',
        ]);

        $quotation = Quotation::find($request['quotation_id']);

        $quotationTypes = array_column($quotation->types()->select('type')->get()->toArray(), 'type');

        if (count($quotationTypes) > 1 || !in_array('Part', $quotationTypes)) {

            return redirect()->back()->with(['message' => __('this Quotation can not transfer to sales invoice'), 'alert-type' => 'error']);
        }

        try {

            $quotationPartType = $quotation->types()->where('type', 'Part')->first();

            $quotationTypeItems = $quotationPartType->load('items')->items;

            $quotationItems = $this->prepareQuotationItems($quotationTypeItems, $quotation->branch_id);

            $validationMessages = $this->checkValidation($quotationItems);

            if (count($validationMessages)) {

                return redirect()->back()->with(['message' => $validationMessages[0], 'alert-type' => 'error']);
            }

            $invoice_data = $this->prepareInvoiceData($quotation, $quotationItems);

            $invoice_data['invoice_number'] = $this->generateInvoiceNumber($quotation->branch_id);

            DB::beginTransaction();

            $sales_invoice = SalesInvoice::create($invoice_data);

            $this->saveInvoiceItems($quotationItems, $sales_invoice->id, $quotation->branch_id);

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();

//            dd($e->getMessage());
            return redirect()->back()->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('words.sale-invoice-created'), 'alert-type' => 'success']);
    }
}
