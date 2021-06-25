<a style="
    margin-bottom: 12px; border-radius: 5px"
   type="button" href="{{route($route ,isset($custom_args) ? $custom_args : [])}}"
   class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left">
    {{__('Create')}} {{__((string)$new)}} {{__('New')}}
    <i class="ico fa fa-plus"></i>

</a>
