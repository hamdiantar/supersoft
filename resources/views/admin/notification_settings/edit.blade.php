@extends('admin.layouts.app')

@section('title')
<title>{{ __('Super Car') }} - {{__('Notifications settings')}} </title>
@endsection

@section('content')
<div class="row small-spacing">

    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{__('Notification settings')}}</li>
        </ol>
    </nav>

    <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
            <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-gear"></i>
                {{__('Notification settings')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
                    <button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>

                </span>
            </h1>
            <div class="box-content">

                <form method="post" action="{{route('admin:notification.settings.update')}}" class="form">
                    @csrf
                    @method('post')
                  <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">الإسم</th>
                                <th scope="col">الحاله</th>
                                <th scope="col">الإسم</th>
                                <th scope="col">الحاله</th>
                                <th scope="col">الإسم</th>
                                <th scope="col">الحاله</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><label for="date" class="control-label">{{__('Customer Request')}}</label></td>
                                <td>
                                    <div class="switch success">
                                        <input type="checkbox" name="customer_request" id="switch-1" {{ isset($setting) && $setting && $setting->customer_request == 1? 'checked':''}}>
                                        <label for="switch-1"></label>
                                    </div>
                                </td>
                                <td><label for="date" class="control-label">{{__('Quotation Request')}}</label></td>
                                <td>
                                    <div class="switch success">
                                        <input type="checkbox" name="quotation_request" id="switch-2" {{ isset($setting) && $setting && $setting->quotation_request == 1? 'checked':''}}>
                                        <label for="switch-2"></label>
                                    </div>
                                </td>
                                <td><label for="date" class="control-label">{{__('Work Card Status To User')}}</label></td>
                                <td>
                                    <div class="switch success">
                                        <input type="checkbox" name="work_card_status_to_user" id="switch-4" {{ isset($setting) && $setting && $setting->work_card_status_to_user == 1? 'checked':''}}>
                                        <label for="switch-4"></label>
                                    </div>
                                </td>

                            </tr>
                            <tr>
                                <td><label for="date" class="control-label">{{__('Minimum Parts Request')}}</label></td>
                                <td>
                                    <div class="switch success">
                                        <input type="checkbox" name="minimum_parts_request" id="switch-5" {{ isset($setting) && $setting && $setting->minimum_parts_request == 1? 'checked':''}}>
                                        <label for="switch-5"></label>
                                    </div>
                                </td>
                                <td><label for="date" class="control-label">{{__('End Work Card For Employee')}}</label></td>
                                <td>
                                    <div class="switch success">
                                        <input type="checkbox" name="end_work_card_employee" id="switch-6" {{ isset($setting) && $setting && $setting->end_work_card_employee == 1? 'checked':''}}>
                                        <label for="switch-6"></label>
                                    </div>
                                </td>
                                <td><label for="date" class="control-label">{{__('End Residence For Employee')}}</label></td>
                                <td>
                                    <div class="switch success">
                                        <input type="checkbox" name="end_residence_employee" id="switch-7" {{ isset($setting) && $setting && $setting->end_residence_employee == 1? 'checked':''}}>
                                        <label for="switch-7"></label>
                                    </div>
                                </td>

                            </tr>
                            <tr>
                                <td><label for="date" class="control-label">{{__('End Medical Insurance For Employee')}}</label></td>
                                <td>
                                    <div class="switch success">
                                        <input type="checkbox" name="end_medical_insurance_employee" id="switch-8" {{ isset($setting) && $setting && $setting->end_medical_insurance_employee == 1? 'checked':''}}>
                                        <label for="switch-8"></label>
                                    </div>
                                </td>
                                <td><label for="date" class="control-label">{{__('Quotation Request Status')}}</label></td>
                                <td>
                                    <div class="switch success">
                                        <input type="checkbox" name="quotation_request_status" id="switch-9" {{ isset($setting) && $setting && $setting->quotation_request_status == 1? 'checked':''}}>
                                        <label for="switch-9"></label>
                                    </div>
                                </td>
                                <td><label for="date" class="control-label">{{__('Sales Invoice')}}</label></td>
                                <td>
                                    <div class="switch success">
                                        <input type="checkbox" name="sales_invoice" id="switch-10" {{ isset($setting) && $setting && $setting->sales_invoice == 1? 'checked':''}}>
                                        <label for="switch-10"></label>
                                    </div>
                                </td>

                            </tr>
                            <tr>
                            <td>
                                <label for="date" class="control-label">{{__('Return Sales Invoice')}}</label>
                                </td>
                                <td>
                                <div class="switch success">
                                        <input type="checkbox" name="return_sales_invoice" id="switch-11" {{ isset($setting) && $setting && $setting->return_sales_invoice == 1? 'checked':''}}>
                                        <label for="switch-11"></label>
                                    </div>
                                </td>
                                <td>
                                <label for="date" class="control-label">{{__('Work Card Status To Customer')}}</label>
                                </td>
                                <td>
                                <div class="switch success">
                                        <input type="checkbox" name="work_card_status_to_customer" id="switch-13" {{ isset($setting) && $setting && $setting->work_card_status_to_customer == 1? 'checked':''}}>
                                        <label for="switch-13"></label>
                                    </div>
                                </td>
                                <td>
                                <label for="date" class="control-label">{{__('Sales Invoice Payments')}}</label>
                                </td>
                                <td>
                                <div class="switch success">
                                        <input type="checkbox" name="sales_invoice_payments" id="switch-14" {{ isset($setting) && $setting && $setting->sales_invoice_payments == 1? 'checked':''}}>
                                        <label for="switch-14"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                               <td>
                               <label for="date" class="control-label">{{__('Return Sales Invoice Payments')}}</label>
                               </td>
                               <td>
                               <div class="switch success">
                                        <input type="checkbox" name="return_sales_invoice_payments" id="switch-20" {{ isset($setting) && $setting && $setting->return_sales_invoice_payments == 1? 'checked':''}}>
                                        <label for="switch-20"></label>
                                    </div>
                               </td>
                               <td>
                               <label for="date" class="control-label">{{__('Work Card Payments')}}</label>
                               </td>
                               <td>
                               <div class="switch success">
                                        <input type="checkbox" name="work_card_payments" id="switch-15" {{ isset($setting) && $setting && $setting->work_card_payments == 1? 'checked':''}}>
                                        <label for="switch-15"></label>
                                    </div>
                               </td>
                               <td>
                               <label for="date" class="control-label">{{__('Follow Up Cars')}}</label>
                               </td>
                               <td>
                               <div class="switch success">
                                        <input type="checkbox" name="follow_up_cars" id="switch-16" {{ isset($setting) && $setting && $setting->follow_up_cars == 1? 'checked':''}}>
                                        <label for="switch-16"></label>
                                    </div>
                               </td>                                                                                                                            
                            </tr>
                        </tbody>
                    </table>
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
{{-- {!! JsValidator::formRequest('App\Http\Requests\Admin\WorkCards\CreateWorkCardRequest', '.form'); !!}--}}
@include('admin.partial.sweet_alert_messages')
@endsection

@section('js')

<script type="application/javascript" src="{{asset('assets/ckeditor/ckeditor.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/ckeditor/samples/js/sample.js')}}"></script>

<script type="application/javascript">
    CKEDITOR.replace('editor1', {});
    CKEDITOR.replace('editor2', {});
    CKEDITOR.replace('editor3', {});
    CKEDITOR.replace('editor4', {});
</script>

@endsection