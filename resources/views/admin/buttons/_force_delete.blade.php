<a onclick="confirmForceDelete({{$id}})" class="btn btn-wg-delete hvr-radial-out" href="{{route($route,['id' => $id])}}">
    <i class="fa fa-trash"></i>  {{__('Force Delete')}}
</a>
<form style="display: none" method="POST" id="confirmForceDelete{{$id}}" action={{route($route,[ 'id' => $id])}}>
    @method('DELETE')
    @csrf
</form>
