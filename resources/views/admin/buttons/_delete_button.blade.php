<button type="button" class="btn btn-wg-delete hvr-radial-out"  onclick="confirmDelete({{$id}})">
    <i class="fa fa-trash"></i>  {{__('Delete')}}
</button>

<form style="display: none" method="POST" id="confirmDelete{{$id}}" action={{route($route,[ 'id' => $id])}}>
    @method('DELETE')
    @csrf
</form>
