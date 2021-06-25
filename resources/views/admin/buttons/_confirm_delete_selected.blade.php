


{{--<a  data-remodal-target="deleteSelected" style="--}}
{{--    margin-bottom: 12px; border-radius: 5px"--}}
{{--   type="button"--}}
{{--   class="btn btn-icon btn-icon-left btn-delete-wg waves-effect waves-light hvr-bounce-to-left">--}}
{{--   {{__('Delete Now')}}--}}
{{--   <i class="ico fa fa-trash"></i>--}}
{{--</a>--}}
<button style="
    margin-bottom: 12px; border-radius: 5px" type="button" class="btn btn-icon btn-icon-left btn-delete-wg waves-effect waves-light hvr-bounce-to-left" onclick="confirmDeleteSelected('{{$route}}')">
    <i class="ico fa fa-trash"></i>  {{__('Delete Selected')}}
</button>
