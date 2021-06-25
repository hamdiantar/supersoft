<a style="
    margin-bottom: 12px; border-radius: 5px"
   type="button" href="{{route($button_url ,isset($button_args) ? $button_args : [])}}"
   class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left">
    {{ $button_text }}
    <i class="ico fa fa-plus"></i>
</a>