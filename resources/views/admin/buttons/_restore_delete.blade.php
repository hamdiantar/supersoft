<a class="btn btn-wg-edit hvr-radial-out" onclick="confirmRestoreData({{$id}})"  href="{{route($route,['id' => $id])}}">
    <i class="fa fa-arrow-circle-left"></i> {{__('Restore Delete')}}
</a>
<form style="display: none" method="POST" id="confirmRestoreData{{$id}}" action={{route($route,[ 'id' => $id])}}>
    @method('PUT')
    @csrf
</form>
