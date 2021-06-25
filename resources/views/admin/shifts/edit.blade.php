@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{ __('Edit Shift') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:shifts.index')}}"> {{__('Shifts')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Shift')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-check-square-o"></i>
                  {{__('Edit Shift')}}
                  <span class="controls hidden-sm hidden-xs pull-left">
                  <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">
                    <form  method="post" action="{{route('admin:shifts.update', ['id' => $shift->id])}}" class="form">
                        @csrf
                        @method('put')
                        @if(authIsSuperAdmin())
                        <div class="form-group has-feedback col-sm-12">
                            <label for="branch" class="control-label">{{__('Select Branch')}}</label>
                            <div class="input-group">
                            <span class="input-group-addon fa fa-file"></span>
                            <select name="branch_id" class="form-control   js-example-basic-single" id="branch">
                                <option value="">{{__('Select Branch')}}</option>
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option value="{{$branch->id}}" {{$shift->branch_id == $branch->id ? 'selected' : ''}}>{{$branch->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'branch_id')}}
                        </div>
                        </div>
                        @endif
                        <div class="form-group has-feedback col-lg-4">
                            <label for="inputNameAR" class="control-label">{{__('Name in Arabic')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text"></li></span>
                                <input type="text" name="name_ar" class="form-control" id="inputNameAR"
                                       placeholder="{{__('Name in Arabic')}}" value="{{$shift->name_ar}}">
                            </div>
                            {{input_error($errors,'name_ar')}}
                        </div>

                        <div class="form-group has-feedback col-sm-4">
                            <label for="inputNameEN" class="control-label">{{__('Name in English')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text"></li></span>
                                <input type="text" name="name_en" class="form-control" id="inputNameEN"
                                       placeholder="{{__('Name in English')}}" value="{{$shift->name_ar}}">
                            </div>
                            {{input_error($errors,'name_en')}}
                        </div>

                        <div class="form-group has-feedback col-sm-2">
                            <label for="inputNameEN" class="control-label">{{__('Start From')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                <input type="time" name="start_from"  class="form-control" value="{{$shift->start_from}}">
                            </div>
                            {{input_error($errors,'start_from')}}
                        </div>


                        <div class="form-group has-feedback col-sm-2">
                            <label for="inputNameEN" class="control-label">{{__('End At')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                <input type="time" name="end_from" class="form-control" value="{{$shift->end_from}}">                            </div>
                            {{input_error($errors,'end_from')}}
                        </div>

                        <div class="form-group has-feedback col-sm-12">
                            <label for="inputNameEN" class="control-label">{{__('Choose Days')}}</label>
                            <div class="checkbox">
                            <input type="checkbox"  id="select-all">
                            <label for="select-all"> {{__('Select All')}}</label>
                        </div>
                            <ul class="list-inline">
                                <li>
                                    <input type="hidden"  name="Saturday" VALUE="0">
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="checkbox" id="switch-1" name="Saturday" VALUE="1"
                                        {{$shift->Saturday == 1 ? 'checked'  : ''}}>
                                        <label for="switch-1">{{__('Saturday')}}</label>
                                    </div>
                                </li>

                                <li>
                                    <input type="hidden"  name="sunday" VALUE="0">
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="checkbox" id="switch-2" name="sunday" VALUE="1"
                                            {{$shift->sunday == 1 ? 'checked'  : ''}}>
                                        <label for="switch-2">{{__('Sunday')}}</label>
                                    </div>
                                </li>

                                <li>
                                    <input type="hidden"  name="monday" VALUE="0">
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="checkbox" id="switch-3" name="monday" VALUE="1"
                                            {{$shift->monday == 1 ? 'checked'  : ''}}>
                                        <label for="switch-3">{{__('Monday')}}</label>
                                    </div>
                                </li>

                                <li>
                                    <input type="hidden"  name="tuesday" VALUE="0">
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="checkbox" id="switch-4" name="tuesday" VALUE="1"
                                            {{$shift->tuesday == 1 ? 'checked'  : ''}}>
                                        <label for="switch-4">{{__('Tuesday')}}</label>
                                    </div>
                                </li>

                                <li>
                                    <input type="hidden"  name="wednesday" VALUE="0">
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="checkbox" id="switch-5" name="wednesday" VALUE="1"
                                            {{$shift->wednesday == 1 ? 'checked'  : ''}}>
                                        <label for="switch-5">{{__('Wednesday')}}</label>
                                    </div>
                                </li>

                                <li>
                                    <input type="hidden"  name="thursday" VALUE="0">
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="checkbox" id="switch-6" name="thursday" VALUE="1"
                                            {{$shift->thursday == 1 ? 'checked'  : ''}}>
                                        <label for="switch-6">{{__('Thursday')}}</label>
                                    </div>
                                </li>

                                <li>
                                    <input type="hidden"  name="friday" VALUE="0">
                                    <div class="switch primary" style="margin-top: 15px">
                                        <input type="checkbox" id="switch-7" name="friday" VALUE="1"
                                            {{$shift->friday == 1 ? 'checked'  : ''}}>
                                        <label for="switch-7">{{__('Friday')}}</label>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Shift\ShiftRequest', '.form'); !!}
    <script type="application/javascript">
        $("#start_from, #end_at").flatpickr({
            enableTime: true,
            noCalendar: true,
            time_24hr: true
        });
    </script>
    @include('admin.partial.sweet_alert_messages')
@endsection
