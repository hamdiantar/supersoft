@extends('admin.layouts.app')

@section('title')
    <title>{{__('opening-balance.show')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('opening-balance.index')}}"> {{__('opening-balance.index-title')}}</a></li>
                <li class="breadcrumb-item">  {{__('opening-balance.show')}} </li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-gears"></i> {{__('opening-balance.show')}} 
                                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
                   				    </span>
                </h4>

                <div class="box-content">

                    <form method="post">
                        @csrf
                        @method('post')

                        @if(authIsSuperAdmin())
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group has-feedback">
                                    <label for="inputSymbolAR" class="control-label">{{__('Branch')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon fa fa-file"></span>
                                        <input type="text" class="form-control" disabled
                                               value="{{optional($openingBalance)->branch->name}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label> {{ __('opening-balance.serial-number') }} </label>
                                <div class="input-group">
                               <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                                <input class="form-control" readonly name="serial_number"
                                       value="{{ $openingBalance->number }}"/>
                            </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label> {{ __('opening-balance.operation-date') }} </label>
                                <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input class="form-control" type="date" name="operation_date" disabled
                                       value="{{$openingBalance->operation_date}}"/>
                            </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label> {{ __('opening-balance.operation-time') }} </label>
                                <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                <input class="form-control" type="time" name="operation_time" disabled
                                       value="{{ $openingBalance->operation_time}}"/>
                            </div>
                            </div>
                        </div>


                        <table class="table table-responsive table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="13%"> {{ __('opening-balance.part') }} </th>
                                <th width="5%"> {{ __('opening-balance.default-quantity') }} </th>
                                <th width="12%"> {{ __('opening-balance.units') }} </th>
                                <th width="13%"> {{ __('opening-balance.price-segment') }} </th>
                                <th width="13%"> {{ __('opening-balance.quantity') }} </th>
                                <th width="13%"> {{ __('opening-balance.buy-price') }} </th>
                                <th width="13%"> {{ __('opening-balance.total') }} </th>
                                <th width="13%"> {{ __('opening-balance.store') }} </th>
                            </tr>
                            </thead>
                            <tbody id="opening-balance-items">

                            @foreach($openingBalance->items as $item)

                                <tr>

                                    <td>
                                        <input class="form-control" readonly value="{{ $item->part->name }}"/>
                                    </td>

                                    <td>
                                        <input class="form-control" data-row-input="defualt-qnty" disabled
                                               value="{{ $item->default_unit_quantity }}"/>
                                               <span class="part-unit-span"> {{ $item->part->sparePartsUnit->unit }}  </span>
                                    </td>

                                    <td>
                                        <input class="form-control" disabled value="{{ optional($item->partPrice->unit)->unit }}"/>
                                    </td>
                                    <td>
                                        <input class="form-control" disabled value="{{optional($item->partPriceSegment)->name}}"/>
                                    </td>
                                    <td>
                                        <input class="form-control" data-row-input="qnty" disabled
                                               value="{{ $item->quantity }}"/>
                                    </td>
                                    <td>
                                        <input class="form-control" data-row-input="buy-price" value="{{ $item->price }}" disabled/>
                                    </td>
                                    <td>
                                        <input class="form-control" data-row-input="total" readonly value="{{ $item->price * $item->quantity }}"/>
                                    </td>

                                    <td>
                                        <input class="form-control" disabled value="{{ $item->store->name }}"/>
                                    </td>
                                </tr>

                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th> {{ __('opening-balance.part') }} </th>
                                <th> {{ __('opening-balance.default-quantity') }} </th>
                                <th> {{ __('opening-balance.units') }} </th>
                                <th> {{ __('opening-balance.price-segment') }} </th>
                                <th> {{ __('opening-balance.quantity') }} </th>
                                <th> {{ __('opening-balance.buy-price') }} </th>
                                <th> {{ __('opening-balance.total') }} </th>
                                <th> {{ __('opening-balance.store') }} </th>
                            </tr>
                            </tfoot>
                        </table>


                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label> {{ __('opening-balance.total') }} </label>
                                <div class="input-group">
                                <span class="input-group-addon fa fa-money"></span>
                                <input class="form-control" readonly value="{{$openingBalance->total_money}}"/>
                            </div>
                            </div>

                            <div class="form-group col-md-8">
                                <label> {{ __('opening-balance.notes') }} </label>
                                <textarea class="form-control" name="notes" disabled
                                          rows="4">{{$openingBalance->notes}}</textarea>
                            </div>

                        </div>
                        <a href="{{route('opening-balance.index')}}"
                               class="btn btn-danger waves-effect waves-light">
                                <i class=" fa fa-reply"></i> {{__('Back')}}
                            </a>
                    </form>
                </div>
            </div>


        </div>
    </div>
@endsection

{{--@section('accounting-module-modal-area')--}}

{{--    <div id="loader-modal" class="modal fade" role="dialog">--}}
{{--        <div class="modal-dialog">--}}
{{--            <div class="modal-content" id="form-modal" style="height: 150px">--}}
{{--                <h3 class="text-center" style="margin-top: 70px"> {{ __('opening-balance.data-loading') }} </h3>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--@endsection--}}

@section('js-validation')

@endsection
