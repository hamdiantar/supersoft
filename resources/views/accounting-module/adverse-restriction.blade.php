@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.adverse-restrictions') }} </title>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{ __('accounting-module.adverse-restrictions') }} </li>
        </ol>
    </nav>

<div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title with-control"><i class="ico fa fa-money"></i>
                    {{ __('accounting-module.adverse-restrictions') }}

                    <span class="controls hidden-sm hidden-xs">
							<button class="control text-primary">{{ __('words.save') }}<img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f1.png') }}"/></button>
							<button class="control text-info">{{ __('words.clear') }}<img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f2.png') }}"/></button>
							<button class="control text-danger">{{ __('words.cancel') }} <img class="img-fluid" style="width:60px;height:60px;margin-top:-20px" src="{{ asset('assets/images/f3.png') }}"/></button>

						</span>
                    </h4>


					<div class="card-content">

            <form action="{{ route('adverse-restrictions-save') }}" method="post">
                @csrf
                <div class="form-group col-md-4">
                    <label> {{ __('accounting-module.fiscal-years-index') }} </label>
                    <select name="fiscal_year" class="form-control select2" onchange="set_dates(event)">
                        <option value=""> {{ __('accounting-module.select-one') }} </option>
                        @php
                            $years->chunk(50 ,function ($__years) {
                                foreach($__years as $year) {
                                    $selected =  old('fiscal_year') == $year->id ? 'selected' : '';
                                    echo "
                                    <option value='$year->id'
                                        $selected
                                        data-start-date='$year->start_date'
                                        data-end-date='$year->end_date'>
                                        $year->name
                                    </option>
                                    ";
                                }
                            })
                        @endphp
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label> {{ __('accounting-module.date-from') }} </label>
                    <input type="date" name="date_from" class="form-control" value="{{ old('date_from') }}"/>
                </div>
                <div class="form-group col-md-4">
                    <label> {{ __('accounting-module.date-to') }} </label>
                    <input type="date" name="date_to" class="form-control" value="{{ old('date_to') }}"/>
                </div>
                <div class="clearfix"></div>
                {{-- <div class="col-md-4 form-group">
                    <label> {{ __('accounting-module.account-root') }} </label>
                    <select class="form-control" name="acc_root_id"
                        onchange="change_tree_options(event ,'{{ route('load-account-tree') }}' ,undefined)">
                        {!! $root_options !!}
                    </select>
                </div> --}}
                <div class="col-md-4 form-group">
                    <label> {{ __('accounting-module.account-middleware') }} </label>
                    <select class="form-control select2" name="acc_tree_id">
                        {!! $tree_options !!}
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label> {{ __('accounting-module.adverse-restriction-date') }} </label>
                    <input type="date" name="date" class="form-control" value="{{ old('date') ?: date('Y-m-d') }}"/>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <button class="btn btn-wg-show hvr-radial-out"
                        {{ user_can_access_accounting_module(NULL ,'adverse-restrictions' ,'add') ? '' : 'disabled' }}>
                        <i class="fa fa-save"></i> {{ __('accounting-module.save') }}
                    </button>
                </div>
                <div class="clearfix"></div>
            </form>
            </div>
        </div>
    </div>
@endsection

@section('accounting-scripts')
    {!! JsValidator::formRequest('App\Http\Requests\AccountingModule\AdverseRestrictionReq') !!}

    <script type="application/javascript">
        function set_dates(event) {
            var option = $(event.target).find('option:selected')
            $('input[name="date_from"]').val(option.data('start-date'))
            $('input[name="date_to"]').val(option.data('end-date'))
        }
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection