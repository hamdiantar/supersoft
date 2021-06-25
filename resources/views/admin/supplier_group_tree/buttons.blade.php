<button style="margin-bottom: 12px; border-radius: 5px"
    class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left"
    onclick="open_modal('create')"
    {{ auth()->user()->can('create_spareParts') ? '' : 'disabled' }}>
    <i class="ico fa fa-plus"></i>
    {{ __('words.create-supplier-group') }}
</button>

<button style="margin-bottom: 12px; border-radius: 5px"
    class="btn btn-icon btn-icon-left  waves-effect waves-light hvr-bounce-to-left resetAdd-wg-btn"
    onclick="open_modal('edit')"
    {{ auth()->user()->can('update_spareParts') ? '' : 'disabled' }}>
    <i class="ico fa fa-edit"></i>
    {{ __('words.edit-supplier-group') }}
</button>

<button style="margin-bottom: 12px; border-radius: 5px"
    class="btn btn-icon btn-icon-left btn-delete-wg waves-effect waves-light hvr-bounce-to-left"
    onclick="open_modal('delete')"
    {{ auth()->user()->can('delete_spareParts') ? '' : 'disabled' }}>
    <i class="ico fa fa-trash-o"></i>
    {{ __('words.delete-supplier-group') }}
</button>
