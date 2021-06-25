<button type="button" class="btn btn-info-wg hvr-radial-out"  onclick="confirmAddToArchive({{$id}})">
    <i class="fa fa-archive"></i>  {{__('Add To Archive')}}
</button>

<form style="display: none" method="POST" id="archiveData{{$id}}" action={{route($route,[ 'id' => $id])}}>
    @method('DELETE')
    @csrf
</form>
