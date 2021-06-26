<div class="modal fade" id="part_types_{{$index}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content wg-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel-1">{{__('Types')}}</h4>
            </div>

            <div class="modal-body">

                <table id="" class="table table-striped table-bordered display" style="width:100%">
                    <thead>
                    <tr class="text-center-inputs">
                        <th scope="col">{!! __('Select') !!}</th>
                        <th scope="col">{!! __('Type') !!}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr class="text-center-inputs">
                        <th scope="col">{!! __('Select') !!}</th>
                        <th scope="col">{!! __('Type') !!}</th>
                    </tr>
                    </tfoot>
                    <tbody>

                    @if(!empty($partTypes))

                        @foreach($partTypes as $key=>$value)
                        <tr class="text-center-inputs">
                                <td>
                                    <div class="checkbox">
                                        <input type="checkbox" id="item_type_checkbox_{{$index}}_{{$key}}"
                                               onclick="selectItemType('{{$index}}', '{{$key}}')"
                                               {{isset($item) && in_array($key, $item->spareParts->pluck('id')->toArray()) ? 'checked':''}}
                                            {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                                        >
                                        <label for="item_type_checkbox_{{$index}}_{{$key}}"></label>
                                    </div>
                                </td>
                                <td>{!! $value !!}</td>
                            </tr>
                        @endforeach
                    @else
                    <tr class="text-center-inputs">
                            <td colspan="2">
                                <span>{{__('No Types')}}</span>
                            </td>
                        </tr>
                    @endif

                    </tbody>
                </table>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                    {{__('Close')}}
                </button>
            </div>
        </div>
    </div>
</div>
