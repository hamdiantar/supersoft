@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Show Supply Term') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:supply-terms.index')}}"> {{__('Supply & payments')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Show Supply Term')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-o"></i>
                    {{__('Show Supply Term')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                          {{__('Save')}}
                          <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                               src="{{asset('assets/images/f1.png')}}">
                        </button>
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}">
                        </button>

                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                                {{__('Back')}}
                                <img class="img-fluid"
                                     style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                     src="{{asset('assets/images/f3.png')}}">
                            </button>
						</span>
                </h4>

                <div class="box-content">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                                    <div class="input-group">

                                        <span class="input-group-addon fa fa-file"></span>

                                        <input type="text" class="form-control"
                                               value="{{optional($supplyTerm->branch)->name}}" disabled>

                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">



                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputDescription" class="control-label">{{__('Term Ar')}}</label>
                                    <div class="input-group">
                                        <textarea name="term_ar" class="form-control" rows="4" cols="150" disabled
                                        >{{old('term_ar', isset($supplyTerm)? $supplyTerm->term_ar :'')}}</textarea>
                                    </div>
                                    {{input_error($errors,'term_ar')}}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputDescription" class="control-label">{{__('Term En')}}</label>
                                    <div class="input-group">
                                        <textarea name="term_en" class="form-control" rows="4" cols="150" disabled
                                        >{{old('term_en', isset($supplyTerm)? $supplyTerm->term_en :'')}}</textarea>
                                    </div>
                                    {{input_error($errors,'term_en')}}
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                <label for="inputStore" class="control-label">{{__('Type')}}</label>
                                @if($supplyTerm->type == 'supply')
                                            <span class="label label-primary wg-label"> {{__('Supply')}} </span>
                                        @else
                                            <span class="label label-warning wg-label"> {{__('Payment')}} </span>
                                        @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                <label for="inputStore" class="control-label">{{__('Status')}}</label>
                                @if($supplyTerm->status)
                                            <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                        @else
                                            <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                        @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                <label for="inputStore" class="control-label">{{__('Purchase Quotation')}}</label>

                                @if($supplyTerm->for_purchase_quotation)
                                            <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                        @else
                                            <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                        @endif
                                </div>
                            </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>

    <!-- /.row small-spacing -->
@endsection

@section('js-validation')

    @include('admin.partial.sweet_alert_messages')

@endsection
