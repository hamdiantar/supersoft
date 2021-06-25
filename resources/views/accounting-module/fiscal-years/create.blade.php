@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.fiscal-years-index') }} </title>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('fiscal-years.index')}}"> {{ __('accounting-module.fiscal-years-index') }}</a></li>
            <li class="breadcrumb-item active"> {{__('accounting-module.fiscal-years-create')}}</li>
        </ol>
    </nav>

<div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title with-control"><i class="ico fa fa-money"></i>
                    {{__('accounting-module.fiscal-years-create')}}

                    <span class="controls hidden-sm hidden-xs">
							<button class="control text-primary">{{ __('words.save') }}<img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f1.png') }}"/></button>
							<button class="control text-info">{{ __('words.clear') }}<img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f2.png') }}"/></button>
							<button class="control text-danger">{{ __('words.cancel') }} <img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f3.png') }}"/></button>

						</span>
                    </h4>


					<div class="card-content">
        <form method="post" action="{{ route('fiscal-years.store') }}">
            @csrf
            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
            <div class="form-group col-md-6">
                <label> {{ __('accounting-module.name-ar') }} </label>
                <input class="form-control" name="name_ar" value="{{ old('name_ar') }}"/>
            </div>
            <div class="form-group col-md-6">
                <label> {{ __('accounting-module.name-en') }} </label>
                <input class="form-control" name="name_en" value="{{ old('name_en') }}"/>
            </div>
            <div class="form-group col-md-6">
                <label> {{ __('accounting-module.start-date') }} </label>
                <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}"/>
            </div>
            <div class="form-group col-md-6">
                <label> {{ __('accounting-module.end-date') }} </label>
                <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}"/>
            </div>
            <div class="form-group col-md-6">
                <div class="form-group">
                    <ul class="list-inline">
                        <li>
                            <div class="radio pink">
                                <input type="radio" name="status"
                                    value="1" {{ old('status') == 1 ? 'checked' : '' }}
                                    id="radio-9"><label for="radio-9">{{ __('accounting-module.status-1') }}</label></div>
                        </li>
                        <li>
                            <div class="radio info">
                                <input type="radio" name="status"
                                    value="0" {{ old('status') == 0 ? 'checked' : '' }}
                                    id="radio-8"><label for="radio-8">{{ __('accounting-module.status-0') }}</label></div>
                            <!-- /.radio -->
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-md-12">
                <button id="btn-save" class="btn hvr-rectangle-in saveAdd-wg-btn"><i class="ico ico-left fa fa-save"></i> {{ __('words.save') }} </button>
                <button id="btn-clear" type="button" onclick="accountingModuleClearFrom(event)"
                class="btn hvr-rectangle-in resetAdd-wg-btn"><i class="ico ico-left fa fa-trash"></i> {{ __('words.clear') }} </button>
                <button id="btn-cancel" type="button" onclick="accountingModuleCancelForm('{{ route('fiscal-years.index') }}')"
                class="btn hvr-rectangle-in closeAdd-wg-btn"><i class="ico ico-left fa fa-close"></i> {{ __('words.cancel') }} </button>
            </div>
        </form>
    </div>
    </div>
    </div>
@endsection

@section('accounting-scripts')
    {!! JsValidator::formRequest('App\Http\Requests\AccountingModule\FiscalYearReq') !!}
    @include('accounting-module.form-actions')
@endsection