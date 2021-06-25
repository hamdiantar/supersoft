<div id="part_unit_div_{{$index}}">
    <div class="col-xs-11">
        <div class="box-content card bordered-all js__card top-search">

            <h4 class="box-title with-control remove-before">
                <i class="fa fa-clone"></i>
                <span  id="default_unit_title_{{$index}}">{{isset($price) ? optional($price->unit)->unit : __('Default Unit')}}</span>
                <span class="controls">
                <button type="button" class="control fa fa-plus " onclick="hideBody({{$index}})"></button>
                <button type="button" class="control fa fa-times js__card_remove"></button>
            </span>

                <!-- /.controls -->
            </h4>
            <!-- /.box-title -->

            <div class="card-content " id="unit_form_body_{{$index}}" style="background-color: white">

                <div class="list-inline margin-bottom-0 row">

                    @include('admin.parts.units.form', ['index'=> $index])

                </div>

            </div>
            <!-- /.card-content -->
        </div>
    </div>

    {{--  DELETE UNIT BUTTON  --}}
    @if($index != 1)
        <div class="col-xs-1">
            <div class="form-group">
                <button class="btn btn-sm btn-danger" type="button"
                        onclick="deleteUnit('{{$index}}'); {{isset($price) ? 'deleteOldUnit('.$price->id.','.$index.')' : ''}}"
                        id="delete-div-" style="margin-top: 31px;">
                    <li class="fa fa-trash"></li>
                </button>
            </div>
        </div>
    @endif

    {{-- UPDATE UNIT BUTTON  --}}
    @if(isset($price))
        {{--        <div class="col-xs-1">--}}
        {{--            <div class="form-group">--}}
        {{--                <button class="btn btn-sm btn-success" type="button" onclick="updateUnit({{$price->id}}, '{{$index}}')" style="margin-top: 31px;">--}}
        {{--                    <li class="fa fa-check"></li>--}}
        {{--                </button>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    @endif

</div>
