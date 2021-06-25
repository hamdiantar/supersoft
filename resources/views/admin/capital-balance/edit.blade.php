@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('words.edit-capital-balance') }} </title>
@endsection


@section('content')
<div class="row small-spacing">
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin:capital-balance.index') }}"> {{__('words.capital-balance')}}</a></li>
            <li class="breadcrumb-item active"> {{__('words.edit-capital-balance')}}</li>
        </ol>
    </nav>
    <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
            <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-money"></i>  {{__('words.edit-capital-balance')}}
            <span class="controls hidden-sm hidden-xs pull-left">
            <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
        </h4>
            <div class="box-content">
                <form method="post" action="{{ route('admin:capital-balance.update' ,['capital_balance' => $capitalBalance]) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> {{ __('Branch') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-text"></i></span>                                               
                                        <select class="form-control select2" name="branch_id">
                                            <option value=""> {{ __('Select Branch') }} </option>
                                            @foreach($branches as $branch)
                                                <option {{ $capitalBalance->branch_id == $branch->id ? 'selected' : '' }}
                                                    value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> {{ __('words.capital-balance') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>  
                                        <input class="form-control" name="balance" value="{{ $capitalBalance->balance }}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="form-group">
                                @include('admin.buttons._save_buttons')
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    {!! JsValidator::formRequest('App\Http\Requests\CapitalBalanceRequest'); !!}
    <script type="application/javascript">
        $(document).ready(function() {
            $(".select2").select2()
        })
    </script>
@stop
