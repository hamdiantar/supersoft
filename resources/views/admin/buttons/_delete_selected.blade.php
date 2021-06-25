<form action="{{route($route)}}" method="post" id="deleteSelected">
@csrf
<div class="checkbox danger">
        <input type="checkbox" name="ids[]" value="{{$id}}" id="checkbox-{{$id}}">
        <label for="checkbox-{{$id}}"></label>
    </div>
</form>
{{--<div class="remodal" data-remodal-id="deleteSelected" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">--}}
{{--    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>--}}
{{--    <div class="remodal-content">--}}
{{--        <h2 id="modal1Title">{{__('Are you sure want to delete ?')}}</h2>--}}
{{--    </div>--}}
{{--    <button type="button" form="deleteSelected" onclick="force_delete_selected('{{ route($route) }}')"--}}
{{--        class="remodal-confirm">{!! __('Delete') !!}</button>--}}
{{--    <button data-remodal-action="cancel" class="remodal-cancel">{{__('Close')}}</button>--}}
{{--</div>--}}
