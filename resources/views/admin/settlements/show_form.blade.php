<div class="row">
<div class="col-xs-12">

<div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

    @if(authIsSuperAdmin())

    <div class="col-md-12">
<table class="table table-bordered">
  <tbody>
    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Branches')}}</th>
                <td>{{optional($settlement->branch)->name}}</td>
                </tbody>
</table>
</div>
@endif

<div class="col-md-6">
<table class="table table-bordered">
  <tbody>
    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Number')}}</th>
                <td>{{old('number', isset($settlement)? $settlement->special_number :'')}}</td>
                </tbody>
</table>
</div>

<div class="col-md-6">
<table class="table table-bordered">
  <tbody>
    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Date')}}</th>
                <td>{{old('date', isset($settlement)? $settlement->date : \Carbon\Carbon::now()->format('Y-m-d'))}}</td>
            </tbody>
            </table>
            {{input_error($errors,'date')}}
            </tbody>
</table>
</div>

<div class="col-md-6">
               <table class="table table-bordered">
               <tbody>
               <th style="width:50%;background:#ddd !important;color:black !important">{{__('Time')}}</th>
                <td>{{old('time', isset($settlement)? $settlement->time : \Carbon\Carbon::now()->format('H:i:s'))}}</td>
            </tbody>
            </table>
            {{input_error($errors,'time')}}

            </tbody>
</table>
</div>

<div class="col-md-6">
               <table class="table table-bordered">
               <tbody>
               <th style="width:50%;background:#ddd !important;color:black !important">{{__('Username')}}</th>
                <td>{{isset($settlement) ? optional($settlement->user)->name : auth()->user()->name}}</td>
            </tbody>
            </table>
            {{input_error($errors,'user')}}

            </div>


            <div class="col-md-12">
               <table class="table table-bordered">
               <tbody>
               <th style="width:50%;background:rgb(210, 244, 246) !important;color:black !important">{{__('settlement type')}}</th>
               <td>

               <div style="display:flex;align-items:center">
<input style="margin:0 5px" type="radio" name="type" value="positive" id="positive" disabled
                {{ !isset($settlement) ? 'checked':'' }}
                {{isset($settlement) && $settlement->type == 'positive' ? 'checked':''}} >
            {{__('Positive')}}
           
                   <input style="margin:0 5px" type="radio" name="type" id="negative" value="negative" disabled
                {{isset($settlement) && $settlement->type == 'negative' ? 'checked':''}} >
            {{__('Negative')}}
          
            </div>
                   </td>
                   </tbody>
               </table>
               </div>
    
               </div>
    </div>

    </div>


    <div class="table-responsive center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:10px 5px;padding:15px 15px 0">

<table class="table table-responsive table-hover table-bordered table-striped remove-disabled text-center-inputs">
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
            @if(isset($settlement))

                @foreach ($settlement->items as $index => $item)
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
                            <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }}  </span>
                        </td>

                        <td>

                        <span>
                        {{optional($item->partPrice->unit)->unit}}
                        </span>

                        </td>

                        <td>
                        <span id="price_segments_part_{{$index}}">
                        {{optional($item->partPriceSegment)->name}}
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

                        <td>
                        <span class="text-danger">
                        {{isset($item) ? ($item->price * $item->quantity) : 0}}
                        </span>
 
                        </td>

                        <td>

                        <span class="label wg-label" style="background:#60B28E !important"> 
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
    <td style="background:#FFC5D7">{{isset($settlement) ? $totalQuantity : 0}}</td>
    </tbody>
        </table>


        <table class="table table-bordered">
        <tbody>
            <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Price')}}</th>
            <td style="background:#F9EFB7">{{isset($settlement) ? $settlement->total : 0}}</td>
            </tbody>
        </table>

        <table class="table table-bordered">
        <tbody>
            <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{__('Description')}}</th>
            <td style="background:#D2F4F6">{{old('description', isset($settlement)? $settlement->description :'')}}</td>
</tbody>
{{input_error($errors,'description')}}

</tbody>
        </table>

    </div>

