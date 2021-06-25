<div class="modal fade modal-bg-wg" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
 
                <h4 class="modal-title" id="myModalLabel-1">{{__('Library')}}</h4>
            </div>
            <div class="modal-body">


                <div class="row">
                    <form action="{{$url}}" method="post"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="form-group col-md-10">
                            <label>{{__('files')}}</label>
                            <input type="file" name="files[]" class="form-control" multiple id="files">
                            <input type="hidden" name="item_id" value="" id="library_item_id">
                        </div>

                        <div class="form-group col-md-1">
                            <button type="button" class="btn btn-primary" onclick="uploadFiles()" style="margin-top: 28px;">{{__('save')}}</button>
                        </div>

                        <div class="form-group col-md-1" id="upload_loader" style="display: none;">
                            <img src="{{asset('default-images/loading.gif')}}" title="loader"
                                 style="width: 34px;height: 39px;margin-top: 27px;">
                        </div>

                    </form>
                </div>

                <hr>

                <div id="files_area" class="row" style="text-align: center">


                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                    {{__('Close')}}
                </button>
            </div>
        </div>
    </div>
</div>
