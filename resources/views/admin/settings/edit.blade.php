@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{__('Global settings')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Global settings')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Global settings')}}
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

                    <form method="post" action="{{route('admin:settings.update')}}" class="form">
                        @csrf
                        @method('post')

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label for="date"
                                               class="control-label">{{__('Sales Status English')}}</label>
                                        <div class="switch success">
                                            <input type="checkbox" name="sales_invoice_status"
                                                   id="switch-1"
                                                {{ isset($setting) && $setting && $setting->sales_invoice_status == 1? 'checked':''}}>
                                            <label for="switch-1"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label for="date"
                                               class="control-label">{{__('Maintenance Status English')}}</label>
                                        <div class="switch success">
                                            <input type="checkbox" name="maintenance_status"
                                                   id="switch-2"
                                                {{ isset($setting) && $setting && $setting->maintenance_status == 1? 'checked':''}}
                                            >
                                            <label for="switch-2"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label for="date"
                                               class="control-label">{{__('Invoice Setting (advanced/simple)')}}</label>
                                        <div class="switch success">
                                            <input type="checkbox" name="invoice_setting" id="switch-4"
                                                {{ isset($setting) && $setting && $setting->invoice_setting == 1? 'checked':''}}>
                                            <label for="switch-4"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label for="date"
                                               class="control-label">{{__('Filter Setting (advanced/simple)')}}</label>
                                        <div class="switch success">
                                            <input type="checkbox" name="filter_setting" id="switch-5"
                                                {{ isset($setting) && $setting && $setting->filter_setting == 1? 'checked':''}}
                                            >
                                            <label for="switch-5"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <label for="date"
                                               class="control-label">{{__('Quotation terms status')}}</label>
                                        <div class="switch success">
                                            <input type="checkbox" name="quotation_terms_status" id="switch-6"
                                                {{ isset($setting) && $setting && $setting->quotation_terms_status == 1? 'checked':''}}>
                                            <label for="switch-6"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group col-md-6">
                                    <div class="form-group">

                                        <label for="date" class="control-label">
                                            {{__('Sell From (old/new) invoice')}}
                                        </label>

                                        <div class="radio success">
                                            <input type="radio" name="sell_from_invoice_status" id="sell_invoice_old"
                                                   value="old" {{!isset($setting) ? 'checked':''}} {{isset($setting) &&
                                                    $setting && $setting->sell_from_invoice_status == 'old'? 'checked':'' }} >
                                            <label for="sell_invoice_old">{{__('Old items')}}</label>
                                        </div>

                                        <div class="radio success">
                                            <input type="radio" name="sell_from_invoice_status" id="sell_invoice_new"
                                                   value="new" {{isset($setting) &&
                                                    $setting && $setting->sell_from_invoice_status == 'new'? 'checked':'' }}>
                                            <label for="sell_invoice_new">{{__('New items')}}</label>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group col-md-6">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Latitude')}}</label>

                                            <input type="text" name="lat" class="form-control"
                                                   value="{{isset($setting) ? $setting->lat : 0 }}">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group col-md-6">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('Longitude')}}</label>
                                            <input type="text" name="long" class="form-control"
                                                   value="{{isset($setting) ? $setting->long : 0 }}">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group col-md-6">
                                        <div class="form-group">
                                            <label for="date" class="control-label">{{__('kilo meter price')}}</label>
                                            <input type="text" name="kilo_meter_price" class="form-control"
                                                   value="{{isset($setting) ? $setting->kilo_meter_price : 0 }}">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="col-md-12">
                                    <div class="form-group col-md-12">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Sales Invoice Terms Arabic')}}</label>
                                            <textarea id="editor1" name="sales_invoice_terms_ar" cols="5" rows="5"
                                            >{{old('sales_invoice_terms_ar', isset($setting) && $setting ?
                                             $setting->sales_invoice_terms_ar:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group col-md-12">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Sales Invoice Terms English')}}</label>
                                            <textarea id="editor2" name="sales_invoice_terms_en" cols="5" rows="5"
                                            >{{old('sales_invoice_terms_en', isset($setting) && $setting ?
                                             $setting->sales_invoice_terms_en:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group col-md-12">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Maintenance Terms Arabic')}}</label>
                                            <textarea id="editor3" name="maintenance_terms_ar" cols="5" rows="5"
                                            >{{old('maintenance_terms_ar', isset($setting) && $setting ? $setting->maintenance_terms_ar:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group col-md-12">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Maintenance Terms English')}}</label>
                                            <textarea id="editor4" name="maintenance_terms_en" cols="5" rows="5"
                                            >{{old('maintenance_terms_en',
                                            isset($setting) && $setting ? $setting->maintenance_terms_en:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group col-md-12">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Quotation Terms Arabic')}}</label>
                                            <textarea id="editor6" name="quotation_terms_ar" cols="5" rows="5"
                                            >{{old('quotation_terms_ar',
                                            isset($setting) && $setting ? $setting->quotation_terms_ar:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group col-md-12">
                                        <div class="form-group">
                                            <label for="date"
                                                   class="control-label">{{__('Quotation Terms English')}}</label>
                                            <textarea id="editor5" name="quotation_terms_en" cols="5" rows="5"
                                            >{{old('quotation_terms_en',
                                            isset($setting) && $setting ? $setting->quotation_terms_en:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>


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

    <script type="application/javascript" src="{{asset('assets/ckeditor/ckeditor.js')}}"></script>
    <script type="application/javascript" src="{{asset('assets/ckeditor/samples/js/sample.js')}}"></script>

    <script type="application/javascript">
        CKEDITOR.replace('editor1', {});
        CKEDITOR.replace('editor2', {});
        CKEDITOR.replace('editor3', {});
        CKEDITOR.replace('editor4', {});
        CKEDITOR.replace('editor5', {});
        CKEDITOR.replace('editor6', {});
    </script>

@endsection


