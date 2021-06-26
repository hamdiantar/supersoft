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
                        href="{{route('admin:opening-balance.index')}}"> {{__('opening-balance.index-title')}}</a></li>
                <li class="breadcrumb-item">  {{__('opening-balance.show')}} </li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class="card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-gears"></i> {{__('opening-balance.show')}}
                        <span class="controls hidden-sm hidden-xs pull-left">

							<button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">

                    <form method="post">
                        @csrf
                        @method('post')

                    
                            <div class="row">
                                <div class="col-xs-12">

<div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

@if(authIsSuperAdmin())
<div class="col-md-12">
<table class="table table-bordered">
  <tbody>
    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Branch')}}</th>
    <td>{{optional($openingBalance)->branch->name}}</td>
  </tbody>
</table>
</div>
@endif

<div class="col-md-4">
<table class="table table-bordered">
  <tbody>
    <th style="width:50%;background:#ddd !important;color:black !important">{{ __('opening-balance.serial-number') }}</th>
    <td>{{ $openingBalance->number }}</td>
  </tbody>
</table>
</div>


<div class="col-md-4">
<table class="table table-bordered">
  <tbody>
    <th style="width:50%;background:#ddd !important;color:black !important">{{ __('opening-balance.operation-date') }}</th>
    <td>{{$openingBalance->operation_date}}</td>
  </tbody>
</table>
</div>

<div class="col-md-4">
<table class="table table-bordered">
  <tbody>
    <th style="width:50%;background:#ddd !important;color:black !important">{{ __('opening-balance.operation-time') }}</th>
    <td>{{ $openingBalance->operation_time}}</td>
  </tbody>
</table>
</div>

</div>
</div>
</div>



                        <div class="table-responsive center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:10px 5px;padding:15px 15px 0">

                        <table class="table table-responsive table-hover table-bordered remove-disabled text-center-inputs">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="16%"> {{ __('Name') }} </th>
                                <th width="10%"> {{ __('opening-balance.default-quantity') }} </th>
                                <th width="10%"> {{ __('opening-balance.units') }} </th>
                                <th width="13%"> {{ __('opening-balance.price-segment') }} </th>
                                <th width="10%"> {{ __('opening-balance.quantity') }} </th>
                                <th width="13%"> {{ __('opening-balance.buy-price') }} </th>
                                <th width="13%"> {{ __('opening-balance.total') }} </th>
                                <th width="13%"> {{ __('opening-balance.store') }} </th>
                            </tr>
                            </thead>
                            <tbody id="opening-balance-items">

                            @foreach($openingBalance->items as $index=>$item)

                                <tr>

                                    <td>
                                        <span>{{$index+1}}</span>
                                    </td>

                                    <td>
                                        <span style="width:180px">{{ $item->part->name }}</span>
                                    </td>

                                    <td class="inline-flex-span">
                                        <span> {{isset($item) && $item->partPrice ? $item->partPrice->quantity : $part->first_price_quantity}} </span>
                                        <span class="part-unit-span"> {{ $item->part->sparePartsUnit->unit }}  </span>
                                    </td>

                                    <td>
                                    <span>
                                    {{ optional($item->partPrice->unit)->unit }}
                                    </span>
                                    </td>

                                    <td>
                                    <span class="price-span">
                                    {{ $item->partPriceSegment ? $item->partPriceSegment->name : __('Not determined')}}
                                    </span>
                                    </td>

                                    <td>
                                    <span>
                                    {{ $item->quantity }}
                                    </span>
                                    </td>

                                    <td>
                                    <span>
                                    {{ $item->price }}
                                    </span>
                                    </td>

                                    <td style="background:#FBFAD4 !important">
                                    <span>
                                    {{ $item->price * $item->quantity }}
                                    </span>
                                    </td>

                                    <td>
                                    <span class="label wg-label" style="background:#60B28E !important">
                                       {{ $item->store->name }}
                                    </span>
                                    </td>
                                </tr>

                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th width="2%">#</th>
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
                        </div>

 <div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{ __('opening-balance.quantity') }}</th>
    <td style="background:#FFC5D7">{{$openingBalance->items->sum('quantity')}}</td>
  </tbody>
</table>

<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{ __('opening-balance.total') }}</th>
    <td style="background:#F9EFB7">{{$openingBalance->total_money}}</td>
  </tbody>
</table>

<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{ __('opening-balance.notes') }}</th>
    <td style="background:#D2F4F6">{{$openingBalance->notes}}</td>
  </tbody>
</table>

                        </div>
                        
                        <a href="{{route('admin:opening-balance.index')}}"
                           class="btn btn-danger waves-effect waves-light">
                            <i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>
                    </form>
                </div>
            </div>


        </div>
    </div>
@endsection

