@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{__('Sms settings')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Sms settings')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-gear"></i>
                {{__('Sms settings')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img
                            class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                            src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>

						</span>
                </h1>
                <div class="box-content">

                    <form method="post" action="{{route('admin:sms.settings.update')}}" class="form fonts-size-wg">
                        @csrf
                        @method('post')


                        <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-user"></i>{{__('Customer Request')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content">

                        <div class="row">
                            {{-- csutomer request  --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <div class="switch success">
                                            <input type="checkbox" name="customer_request_status" id="switch-1"
                                                {{ isset($setting) && $setting && $setting->customer_request_status == 1? 'checked':''}}>
                                            <label for="switch-1"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('customer request (accept)')}}</label>
                                            <textarea class="form-control" style="resize: none;" name="customer_request_accept" cols="5" rows="5"
                                            >{{old('customer_request_accept', isset($setting) && $setting ?
                                             $setting->customer_request_accept:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('customer request (reject)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="customer_request_reject" name="customer_request_reject" cols="5" rows="5"
                                            >{{old('customer_request_reject', isset($setting) && $setting ?
                                             $setting->customer_request_reject:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>
  
                                </div>
                    </div>
                    </div>



                    <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-file-o"></i>{{__('Quotation Request')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content">
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="quotation_request_status" id="switch-quotation_request_status"
                                                {{ isset($setting) && $setting && $setting->quotation_request_status == 1? 'checked':''}}>
                                            <label for="switch-quotation_request_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Quotation request (pending)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="quotation_request_pending" name="quotation_request_pending" cols="5" rows="5"
                                            >{{old('quotation_request_pending', isset($setting) && $setting ?
                                             $setting->quotation_request_pending:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Quotation request (accept)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="quotation_request_accept" name="quotation_request_accept" cols="5" rows="5"
                                            >{{old('quotation_request_accept', isset($setting) && $setting ?
                                             $setting->quotation_request_accept:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Quotation request (reject)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="quotation_request_reject" name="quotation_request_reject" cols="5" rows="5"
                                            >{{old('quotation_request_reject', isset($setting) && $setting ?
                                             $setting->quotation_request_reject:'')}}</textarea>
                                             </div>
                                </div>
                         

                        </div>

                          </div>
                        </div>
                    </div>
                    

                    <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-file-text-o"></i>{{__('Sales Invoice')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content" >
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group col-md-12">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="sales_invoice_status" id="switch-sales_invoice_status"
                                                {{ isset($setting) && $setting && $setting->sales_invoice_status == 1? 'checked':''}}>
                                            <label for="switch-sales_invoice_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Sales Invoice (create)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_invoice_create" name="sales_invoice_create" cols="5" rows="5"
                                            >{{old('sales_invoice_create', isset($setting) && $setting ?
                                             $setting->sales_invoice_create:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Sales Invoice (edit)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_invoice_edit" name="sales_invoice_edit" cols="5" rows="5"
                                            >{{old('sales_invoice_edit', isset($setting) && $setting ?
                                             $setting->sales_invoice_edit:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Sales Invoice (delete)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_invoice_delete" name="sales_invoice_delete" cols="5" rows="5"
                                            >{{old('sales_invoice_delete', isset($setting) && $setting ?
                                             $setting->sales_invoice_delete:'')}}</textarea>
                                             </div>
                            </div>

                        </div>
                          </div>                        
                        </div>
                    </div> 

                            {{-- sales invoice return  --}}
                            <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-file-text-o"></i>{{__('Sales Invoice return')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content" >
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="sales_invoice_return_status" id="switch-sales_invoice_return_status"
                                                {{ isset($setting) && $setting && $setting->sales_invoice_return_status == 1? 'checked':''}}>
                                            <label for="switch-sales_invoice_return_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Sales Invoice return (create)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_invoice_return_create" name="sales_invoice_return_create" cols="5" rows="5"
                                            >{{old('sales_invoice_return_status', isset($setting) && $setting ?
                                             $setting->sales_invoice_return_create:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Sales Invoice return (edit)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_invoice_return_edit" name="sales_invoice_return_edit" cols="5" rows="5"
                                            >{{old('sales_invoice_return_edit', isset($setting) && $setting ?
                                             $setting->sales_invoice_return_edit:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Sales Invoice return (delete)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_invoice_return_delete" name="sales_invoice_return_delete" cols="5" rows="5"
                                            >{{old('sales_invoice_return_delete', isset($setting) && $setting ?
                                             $setting->sales_invoice_return_delete:'')}}</textarea>
                                             </div>
                                </div>

                        </div>
                          </div>                        
                        </div>
                    </div>

                            {{-- work card  --}}
                            <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-car"></i>{{__('Work Card')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content" >
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="work_card_send_status" id="switch-work_card_send_status"
                                                {{ isset($setting) && $setting && $setting->work_card_send_status == 1? 'checked':''}}>
                                            <label for="switch-work_card_send_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Work Card (create)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="work_card_create" name="work_card_create" cols="5" rows="5"
                                            >{{old('work_card_create', isset($setting) && $setting ?
                                             $setting->work_card_create:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Work Card (edit)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="work_card_edit" name="work_card_edit" cols="5" rows="5"
                                            >{{old('work_card_edit', isset($setting) && $setting ?
                                             $setting->work_card_edit:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Work Card (delete)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="work_card_delete" name="work_card_delete" cols="5" rows="5"
                                            >{{old('work_card_delete', isset($setting) && $setting ?
                                             $setting->work_card_delete:'')}}</textarea>
                                             </div>
                                </div>
                            </div>


                          </div>                        
                        </div>
                    </div>   


                            {{-- sales invoice payment  --}}
                            <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-money"></i>{{__('Sales Invoice Payment')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content" >
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group col-md-12">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="sales_invoice_payments_status" id="switch-sales_invoice_payments_status"
                                                {{ isset($setting) && $setting && $setting->sales_invoice_payments_status == 1? 'checked':''}}>
                                            <label for="switch-sales_invoice_payments_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Sales Invoice Payment (create)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_invoice_payments_create" name="sales_invoice_payments_create" cols="5" rows="5"
                                            >{{old('sales_invoice_payments_create', isset($setting) && $setting ?
                                             $setting->sales_invoice_payments_create:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Sales Invoice Payment (edit)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_invoice_payments_edit" name="sales_invoice_payments_edit" cols="5" rows="5"
                                            >{{old('sales_invoice_payments_edit', isset($setting) && $setting ?
                                             $setting->sales_invoice_payments_edit:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Sales Invoice Payment (delete)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_invoice_payments_delete" name="sales_invoice_payments_delete" cols="5" rows="5"
                                            >{{old('sales_invoice_payments_delete', isset($setting) && $setting ?
                                             $setting->sales_invoice_payments_delete:'')}}</textarea>
                                             </div>
                                </div>
                            </div>




                          </div>                        
                        </div>
                    </div>


                            {{-- sales invoice return payment  --}}
                            <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-money"></i>{{__('Sales Invoice return Payment')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content" >
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="sales_return_payments_status" id="switch-sales_return_payments_status"
                                                {{ isset($setting) && $setting && $setting->sales_return_payments_status == 1? 'checked':''}}>
                                            <label for="switch-sales_return_payments_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Sales Invoice return Payment (create)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_return_payments_create" name="sales_return_payments_create" cols="5" rows="5"
                                            >{{old('sales_return_payments_create', isset($setting) && $setting ?
                                             $setting->sales_return_payments_create:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Sales Invoice return Payment (edit)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_return_payments_edit" name="sales_return_payments_edit" cols="5" rows="5"
                                            >{{old('sales_return_payments_edit', isset($setting) && $setting ?
                                             $setting->sales_return_payments_edit:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Sales Invoice return Payment (delete)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="sales_return_payments_delete" name="sales_return_payments_delete" cols="5" rows="5"
                                            >{{old('sales_return_payments_delete', isset($setting) && $setting ?
                                             $setting->sales_return_payments_delete:'')}}</textarea>
                                             </div>
                                </div>
                            </div>



                          </div>                        
                        </div>
                    </div>


                            {{-- work card payment  --}}
                            <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-money"></i>{{__('Work Card Payment')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content" >
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="work_card_payments_status" id="switch-work_card_payments_status"
                                                {{ isset($setting) && $setting && $setting->work_card_payments_status == 1? 'checked':''}}>
                                            <label for="switch-work_card_payments_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Work Card Payment (create)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="work_card_payments_create" name="work_card_payments_create" cols="5" rows="5"
                                            >{{old('work_card_payments_create', isset($setting) && $setting ?
                                             $setting->work_card_payments_create:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Work Card Payment (edit)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="work_card_payments_edit" name="work_card_payments_edit" cols="5" rows="5"
                                            >{{old('work_card_payments_edit', isset($setting) && $setting ?
                                             $setting->work_card_payments_edit:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Work Card Payment (delete)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="work_card_payments_delete" name="work_card_payments_delete" cols="5" rows="5"
                                            >{{old('work_card_payments_delete', isset($setting) && $setting ?
                                             $setting->work_card_payments_delete:'')}}</textarea>
                                             </div>
                                </div>
                            </div>

     
                          </div>                        
                        </div>
                    </div>

                            {{-- work card status  --}}
                            <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-car"></i>{{__('Work Card Status')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content" >
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="work_card_status" id="switch-work_card_status"
                                                {{ isset($setting) && $setting && $setting->work_card_status == 1? 'checked':''}}>
                                            <label for="switch-work_card_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Work Card status (pending)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="work_card_status_pending" name="work_card_status_pending" cols="5" rows="5"
                                            >{{old('work_card_status_pending', isset($setting) && $setting ?
                                             $setting->work_card_status_pending:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Work Card status (processing)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="work_card_status_processing" name="work_card_status_processing" cols="5" rows="5"
                                            >{{old('work_card_status_processing', isset($setting) && $setting ?
                                             $setting->work_card_status_processing:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Work Card status (finished)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="work_card_status_finished" name="work_card_status_finished" cols="5" rows="5"
                                            >{{old('work_card_status_pending', isset($setting) && $setting ?
                                             $setting->work_card_status_finished:'')}}</textarea>
                                             </div>
                                </div>
                            </div>

                        

                          </div>                        
                        </div>
                    </div>


                            {{-- car follow up status  --}}
                            <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-car"></i>{{__('Car Follow Up Status')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content" >
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="car_follow_up_status" id="switch-car_follow_up_status"
                                                {{ isset($setting) && $setting && $setting->car_follow_up_status == 1? 'checked':''}}>
                                            <label for="switch-car_follow_up_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Car Follow Up Text')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="car_follow_up_remember" name="car_follow_up_remember" cols="5" rows="5"
                                            >{{old('car_follow_up_remember', isset($setting) && $setting ?
                                             $setting->car_follow_up_remember:'')}}</textarea>
                                             </div>
                                </div>
                            </div>

            
                          </div>                        
                        </div>
                    </div>


                            {{-- expenses status  --}}

                            <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-money"></i>{{__('Expenses Status')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content" >
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="expenses_status" id="switch-expenses_status"
                                                {{ isset($setting) && $setting && $setting->expenses_status == 1? 'checked':''}}>
                                            <label for="switch-expenses_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Expenses (create)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="expenses_create" name="expenses_create" cols="5" rows="5"
                                            >{{old('expenses_create', isset($setting) && $setting ?
                                             $setting->expenses_create:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Expenses (edit)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="expenses_edit" name="expenses_edit" cols="5" rows="5"
                                            >{{old('expenses_edit', isset($setting) && $setting ?
                                             $setting->expenses_edit:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Expenses (delete)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="expenses_delete" name="expenses_delete" cols="5" rows="5"
                                            >{{old('expenses_delete', isset($setting) && $setting ?
                                             $setting->expenses_delete:'')}}</textarea>
                                             </div>
                                </div>
                            </div>

            
                          </div>                        
                        </div>
                    </div>


                            {{-- revenue status  --}}
                          
                    <div class=" bordered-all js__card top-search top-search-margin">
                        <h4 style="color:white !important" class="box-title with-control title-wg text-white">
                        <i class="fa fa-money"></i>{{__('Revenue Status')}}
                            <span class="controls controls-wg"><button type="button" class="control fa fa-minus js__card_minus">
                                </button> <button type="button" class="control fa fa-times js__card_remove"></button>
                            </span>
                        </h4>
                        <div style="border:1px solid #CCC !important;padding:0 20px;display:none" class="card-content js__card_content" >
                          <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date" class="control-label"></label>
                                        <div class="switch success">
                                            <input type="checkbox" name="revenue_status" id="switch-revenue_status"
                                                {{ isset($setting) && $setting && $setting->revenue_status == 1? 'checked':''}}>
                                            <label for="switch-revenue_status"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Revenue (create)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="revenue_create" name="revenue_create" cols="5" rows="5"
                                            >{{old('revenue_create', isset($setting) && $setting ?
                                             $setting->revenue_create:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Revenue (edit)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="revenue_edit" name="revenue_edit" cols="5" rows="5"
                                            >{{old('revenue_edit', isset($setting) && $setting ?
                                             $setting->revenue_edit:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Revenue (delete)')}}</label>
                                            <textarea class="form-control" style="resize: none;" id="revenue_delete" name="revenue_delete" cols="5" rows="5"
                                            >{{old('revenue_delete', isset($setting) && $setting ?
                                             $setting->revenue_delete:'')}}</textarea>
                                             </div>
                                </div>
                            </div>

  

                          </div>                        
                        </div>
                    </div>


                    <div class="row">

                            <div class="col-md-12">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        @include('admin.buttons._save_buttons')
                                    </div>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->


@endsection

@section('js-validation')
    {{--    {!! JsValidator::formRequest('App\Http\Requests\Admin\WorkCards\CreateWorkCardRequest', '.form'); !!}--}}
    @include('admin.partial.sweet_alert_messages')
@endsection

@section('js')

    <script src="{{asset('assets/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('assets/ckeditor/samples/js/sample.js')}}"></script>
<!-- 
    <script>
        CKEDITOR.replace('customer_request_accept', {});
        CKEDITOR.replace('customer_request_reject', {});
        CKEDITOR.replace('quotation_request_accept', {});
        CKEDITOR.replace('quotation_request_reject', {});
        CKEDITOR.replace('quotation_request_pending', {});
        CKEDITOR.replace('sales_invoice_create', {});
        CKEDITOR.replace('sales_invoice_edit', {});
        CKEDITOR.replace('sales_invoice_delete', {});
        CKEDITOR.replace('sales_invoice_return_create', {});
        CKEDITOR.replace('sales_invoice_return_edit', {});
        CKEDITOR.replace('sales_invoice_return_delete', {});
        CKEDITOR.replace('work_card_create', {});
        CKEDITOR.replace('work_card_edit', {});
        CKEDITOR.replace('work_card_delete', {});
        CKEDITOR.replace('sales_invoice_payments_create', {});
        CKEDITOR.replace('sales_invoice_payments_edit', {});
        CKEDITOR.replace('sales_invoice_payments_delete', {});
        CKEDITOR.replace('sales_return_payments_create', {});
        CKEDITOR.replace('sales_return_payments_edit', {});
        CKEDITOR.replace('sales_return_payments_delete', {});
        CKEDITOR.replace('work_card_payments_create', {});
        CKEDITOR.replace('work_card_payments_edit', {});
        CKEDITOR.replace('work_card_payments_delete', {});
        CKEDITOR.replace('work_card_status_pending', {});
        CKEDITOR.replace('work_card_status_processing', {});
        CKEDITOR.replace('work_card_status_finished', {});
        CKEDITOR.replace('car_follow_up_remember', {});
        CKEDITOR.replace('expenses_create', {});
        CKEDITOR.replace('expenses_edit', {});
        CKEDITOR.replace('expenses_delete', {});
        CKEDITOR.replace('revenue_create', {});
        CKEDITOR.replace('revenue_edit', {});
        CKEDITOR.replace('revenue_delete', {});
    </script> -->

@endsection


