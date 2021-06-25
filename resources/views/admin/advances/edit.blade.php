@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Employee Advance') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:advances.index')}}"> {{__('Employees Advances')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Employee Advance')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-money"></i>  {{__('Edit Employee Advance')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">
                    <form method="post" action="{{route('admin:advances.update', ['id' => $advances->id])}}" class="form">
                        @csrf
                        @method('put')
                        <div class="row">
                        @if (authIsSuperAdmin())
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> {{__('Branch')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <input type="text" readonly value="{{$branchName}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            <div class="col-md-12">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="alert alert-info" role="alert" style="font-size: 20px; text-align: center; color: red">
                                            {{__('The Max Advance For This Employee is')}}
                                            <a id="value_of_max_advance" class="alert-link">{{$max_advance}}</a>
                                            {{__(',And The Rest Amount Is')}}
                                            <a id="value_of_rest_advance" class="alert-link">{{$restFromMaxAdvance}}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Employee Name')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="text" readonly value="{{$empName}}" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('The Amount')}} <i class="req-star" style="color: red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input type="text" class="form-control" name="amount" value="{{$advances->amount}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('The Rest Amount')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input id="employee-rest-amount" readonly class="form-control" value="{{$total}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Operation Type')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                        <input type="text" readonly value="{{$advances->operation}}" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{__('Method Of Deportation')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <input type="text" readonly value="{{__("words.".$advances->deportation)}}" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{__('Deportation')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <input type="text" readonly value="{{$deporationName}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{__('Date')}} <i class="req-star" style="color: red">*</i></label>
                                <input type="date" class="form-control time" name="date"
                                       value="{{$advances->date}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('accounting-module.cost-center') }} </label>
                                <select name="cost_center_id" class="form-control select2">
                                    @php
                                        $rec = $advances->getMyReceipt();
                                    @endphp
                                    {!!
                                        \App\AccountingModule\Controllers\CostCenterCont::build_centers_options(
                                            $rec ? $rec->cost_center_id : NULL ,NULL ,1 ,true ,$advances->branch_id
                                        )
                                    !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{__('Notes')}}</label>
                                <div class="input-group">
                                    <textarea class="form-control" name="notes" placeholder="{{__('Notes')}}">
                                        {{$advances->notes}}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        </div>


                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    @include('admin.buttons._save_buttons')

                                    <input type="hidden" name="save_type" id="save_type">

                                    <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn"
                                            onclick="saveAndPrint('save_and_print_receipt')">
                                        <i class="ico ico-left fa fa-print"></i>

                                        {{__('Save and print Receipt')}}
                                    </button>

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
    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">


        function saveAndPrint(type) {

            $("#save_type").val(type);
        }

    </script>
@endsection
