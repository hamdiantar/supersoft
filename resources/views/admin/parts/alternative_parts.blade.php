<a data-remodal-target="m-{{$part->id}}"
   title="alternative parts" style="color:black">
   {!! $part->name !!}
</a>

<div class="remodal" data-remodal-id="m-{{$part->id}}" role="dialog"
     aria-labelledby="modal1Title" aria-describedby="modal1Desc">

    <div class="remodal-content">
        <!-- <div class="card box-content-wg-new bordered-all primary"> -->
            <h4 class="box-title with-control" style="text-align: initial">
                {{__('Alternative Parts')}}
            </h4>
<div class="table-responsive">
            <table id="" class="table table-striped table-bordered display" style="width:98%;margin: 0 auto">
                <thead>
                <tr>

                    <th scope="col">{!! __('Name') !!}</th>
                    <th scope="col">{!! __('Type') !!}</th>
                    <th scope="col">{!! __('Quantity') !!}</th>
                    <th scope="col">{!! __('Status') !!}</th>

                </tr>
                </thead>
                <tfoot>
                <tr>

                    <th scope="col">{!! __('Name') !!}</th>
                    <th scope="col">{!! __('Type') !!}</th>
                    <th scope="col">{!! __('Quantity') !!}</th>
                    <th scope="col">{!! __('Status') !!}</th>

                </tr>
                </tfoot>
                <tbody>

                @foreach($part->alternative as $item)
                    <tr>

                        <td>{!! $item->name !!}</td>
                        <td>
                            <span class="label label-primary wg-label">
                                {{$item->spareParts->first() ? $item->spareParts->first()->type : '---'}}
                            </span>
                        </td>

                        <td>{!! $item->quantity !!}</td>

                        @if ($item->status)
                            <td>
                                <div class="switch success">
                                    <input
                                        disabled
                                        type="checkbox"
                                        {{$item->status == 1 ? 'checked' : ''}}
                                        id="switch-{{ $item->id }}">
                                    <label for="part-{{ $item->id }}"></label>
                                </div>

                            </td>
                        @else
                            <td>
                                <div class="switch success">
                                    <input
                                        disabled
                                        type="checkbox"
                                        {{$item->status == 1 ? 'checked' : ''}}
                                        id="switch-{{ $item->id }}">
                                    <label for="part-{{ $item->id }}"></label>
                                </div>

                            </td>
                    @endif

                    </tr>
                @endforeach

                </tbody>
            </table>
            </div>
<hr>
            <button style="margin-bottom:15px" data-remodal-action="cancel" type="button" class="btn btn-danger waves-effect waves-light" onclick="clearSelectedType()">
                <i class='fa fa-close'></i>
                {{ __('Close') }}
            </button>
        </div>
    </div>
</div>
