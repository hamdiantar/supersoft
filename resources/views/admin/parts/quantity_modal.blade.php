@foreach($parts as $part)

    <div class="modal fade" id="part_quantity_{{$part->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content wg-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Part Quantity')}}</h4>
                </div>
                <div class="modal-body">
                    <table id="" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                        <tr>

                            <th scope="col">{!! __('Store') !!}</th>
                            <th scope="col">{!! __('Quantity') !!}</th>
                            <th scope="col">{!! __('Recession date') !!}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>

                            <th scope="col">{!! __('Store') !!}</th>
                            <th scope="col">{!! __('Quantity') !!}</th>
                            <th scope="col">{!! __('Recession date') !!}</th>
                        </tr>
                        </tfoot>
                        <tbody>

                        @foreach($part->stores as $store)
                            <tr>

                                <td>{!! $store->name !!}</td>
                                <td>{!! $store->pivot->quantity !!}</td>
                                <td>{!! $store->pivot->updated_at !!}</td>

                            </tr>
                        @endforeach

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

@endforeach
