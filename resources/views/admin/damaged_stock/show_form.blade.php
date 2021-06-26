<div class="row">
<div class="col-xs-12">

<div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

    @if(authIsSuperAdmin())

    <div class="col-md-12">
<table class="table table-bordered">
  <tbody>
    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Branch')}}</th>
                   <td>{{optional($damagedStock->branch)->name}}</td>
                   </tbody>
</table>
</div>
@endif

<div class="col-md-4">
<table class="table table-bordered">
  <tbody>
    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Number')}}</th>
                   <td>{{old('number', isset($damagedStock)? $damagedStock->special_number :'')}}</td>
                   </tbody>
</table>
</div>

<div class="col-md-4">
<table class="table table-bordered">
  <tbody>
    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Date')}}</th>
                   <td>{{old('date', isset($damagedStock)? $damagedStock->date : \Carbon\Carbon::now()->format('Y-m-d'))}}</td>
                   </tbody>
</table>
</div>

<div class="col-md-4">
               <table class="table table-bordered">
               <tbody>
               <th style="width:50%;background:#ddd !important;color:black !important">{{__('Time')}}</th>
                   <td>{{old('time', isset($damagedStock)? $damagedStock->time : \Carbon\Carbon::now()->format('H:i:s'))}}</td>
               </tbody>
               </table>
               </div>


               <div class="col-md-4">
               <table class="table table-bordered">
               <tbody>
               <th style="width:50%;background:#ddd !important;color:black !important">{{__('damage type')}}</th>
               <td>
               @if($damagedStock->type == 'natural' )
                                        <span class="label label-primary wg-label"> {{__('Natural')}} </span>
                                        @else
                                        <span class="label label-danger wg-label"> {{__('un_natural')}} </span>
                @endif

                   </td>
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
                <th width="2%">#</th>
                <th width="16%"> {{ __('Name') }} </th>
                <th width="10%"> {{ __('Unit Quantity') }} </th>
                <th width="12%"> {{ __('Unit') }} </th>
                <th width="13%"> {{ __('Price Segments') }} </th>
                <th width="5%"> {{ __('Quantity') }} </th>
                <th width="5%"> {{ __('Price') }} </th>
                <th width="5%"> {{ __('Total') }} </th>
                <th width="5%"> {{ __('Store') }} </th>
            </tr>
            </thead>
            <tbody id="parts_data">
            @if(isset($damagedStock))

                @foreach ($damagedStock->items as $index => $item)
                    @php
                        $index +=1;
                        $part = $item->part;
                    @endphp
                    <tr id="tr_part_{{$index}}">

                        <td>
                            <span>{{$index}}</span>
                        </td>

                        <td>
                        <span> 
                        {{$part->name}}
                        </span>
            
                        </td>
                        <td class="inline-flex-span">
                        <span id="unit_quantity_{{$index}}"> 
                        {{isset($item) && $item->partPrice ? $item->partPrice->quantity : $part->first_price_quantity}}
                        </span>

                            <span class="part-unit-span">  {{ $part->sparePartsUnit->unit }}  </span>


                        </td>
                        <td>
                        <span> 
                        {{optional($item->partPrice->unit)->unit}}
                        </span>
                        </td>

                        <td>
                        <span class="price-span" id="price_segments_part_{{$index}}">
                                    {{ $item->partPriceSegment ? $item->partPriceSegment->name : __('Not determined')}}
                        </span>
                        </td>

                        <td>
                        <span> 
                        {{isset($item) ? $item->quantity : 0}}
                        </span>

                        </td>

                        <td>
                        <span> 
                        {{isset($item) ? $item->price : 0}}
                        </span>
                        </td>

                        <td style="background:#FBFAD4 !important">
                        <span> 
                        {{isset($item) ? ($item->price * $item->quantity) : 0}}
                        </span>
                        </td>

                        <td>
                        <span id="stores" class="label wg-label" style="background:#60B28E !important"> 
                        {{optional($item->store)->name}}
                        </span>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th width="2%">#</th>
                <th width="16%"> {{ __('Name') }} </th>
                <th width="10%"> {{ __('Unit Quantity') }} </th>
                <th width="12%"> {{ __('Unit') }} </th>
                <th width="13%"> {{ __('Price Segments') }} </th>
                <th width="5%"> {{ __('Quantity') }} </th>
                <th width="5%"> {{ __('Price') }} </th>
                <th width="5%"> {{ __('Total') }} </th>
                <th width="5%"> {{ __('Store') }} </th>
            </tr>
            </tfoot>
        </table>
    </div>
   

    <div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{__('Total quantity')}}</th>
            <td style="background:#FFC5D7">{{isset($damagedStock) ? $totalQuantity : 0}}</td>
        </tbody>
        </table>

        <table class="table table-bordered">
        <tbody>
            <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Price')}}</th>
            <td style="background:#F9EFB7">{{isset($damagedStock) ? $damagedStock->total : 0}}</td>
        </tbody>
        </table>

        <table class="table table-bordered">
        <tbody>
            <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{__('Description')}}</th>
            <td style="background:#D2F4F6">{{old('description', isset($damagedStock)? $damagedStock->description :'')}}</td>
        </tbody>
        </table>

    </div>


    <div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <div class="col-md-12" id="employees_percent"
         style="{{isset($damagedStock) && $damagedStock->type == 'un_natural' ? '':'display: none;' }}">
        <div class="form-group">
            <label for="inputDescription" class="control-label">{{__('Employees')}}</label>


            <div id="employees_data">

                @if(isset($damagedStock))

                    @foreach($damagedStock->employees as $damaged_employee)

                        <div class="col-md-12">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputDescription" class="control-label">{{__('Name')}}</label>
                                    <div class="input-group">
                                        <input type="text" disabled class="form-control"
                                               value="{{$damaged_employee->name}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputDescription" class="control-label">{{__('Percent')}}</label>
                                    <div class="input-group">

                                        <input type="text" disabled class="form-control"
                                               value="{{$damaged_employee->pivot->percent}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputDescription" class="control-label">{{__('Total Amount')}}</label>
                                    <div class="input-group">
                                        <input type="text" disabled class="form-control"
                                               value="{{$damaged_employee->pivot->amount}}">
                                    </div>
                                </div>
                            </div>
                            </div>
                
          
                    @endforeach
                   
                @endif
                </div>
                </div>


 
