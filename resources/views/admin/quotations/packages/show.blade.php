<div class="remodal" data-remodal-id="m-3" role="dialog"
     aria-labelledby="modal1Title" aria-describedby="modal1Desc">

    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>

    <div class="remodal-content">
        <h2 id="modal1Title">{{__('services Details')}}</h2>

        <hr>

        <div class="form-group has-feedback col-sm-12">
            <table class="table table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th scope="col">{!! __('Name') !!}</th>
                    <th scope="col">{!! __('Type') !!}</th>
                    <th scope="col">{!! __('value') !!}</th>
                </tr>
                </thead>
                <tbody>
{{--                @foreach($taxes as $index=>$tax)--}}
{{--                    <tr>--}}
{{--                        <td>--}}

{{--                        </td>--}}
{{--                        <td>--}}

{{--                        </td>--}}
{{--                        <td>--}}

{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
                </tbody>
            </table>
        </div>

    </div>
</div>