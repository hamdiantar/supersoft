<tfoot style="background-color: #ada3a361">
<tr>
    <th scope="col" colspan="12" style="background-color: #EBEBEB !important">
        <div class="form-group col-md-12">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Package')}}</label> -->
            <select class="form-control select2 js-example-basic-single"
                    id="package_footer_id" onchange="getPackageDetails( $('#package_footer_id').val())">
                <option value="" >{{ __('Select Package') }}</option>
                @foreach($packages as $package)
                    <option value="{{$package->id}}">{{$package->name}}</option>
                @endforeach
            </select>
        </div>
    </th>
</tr>
</tfoot>

