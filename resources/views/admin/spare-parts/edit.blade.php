@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{ __('Edit Spare Part') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:spare-parts.index')}}"> {{__('Spare parts')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Spare Part')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-check-square-o"></i>
                  {{__('Edit Spare Part')}}
                  <span class="controls hidden-sm hidden-xs pull-left">
                  <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>


                <div class="box-content">
                    <form  method="post" action="{{route('admin:spare-parts.update', ['id' => $sparePart->id])}}" class="form" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div class="row">

                        <div class="col-xs-12">
                            @if(authIsSuperAdmin())
                        <div class="col-md-12">
                                <div class="form-group has-feedback">
                            <label for="inputSymbolAR" class="control-label">{{__('Select Branch')}}</label>
                            <div class="input-group">
<span class="input-group-addon fa fa-file"></span>
                            <select name="branch_id" class="form-control  js-example-basic-single">
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option value="{{$branch->id}}" {{$sparePart->branch_id == $branch->id ? 'selected' : ''}}>{{$branch->name}}</option>
                                @endforeach
                            </select>
                            {{input_error($errors,'branch_id')}}
                        </div>
                                </div>
                                </div>
                            @endif
                        </div>

                            <div class="col-md-12">



                                <div class="col-md-6">
                                <div class="form-group">
                            <label for="inputNameAR" class="control-label">{{__('Type In arabic')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="type_ar" class="form-control" id="inputNameAR"
                                       value="{{$sparePart->type_ar}}" placeholder="{{__('Type In Arabic')}}">
                            </div>
                            {{input_error($errors,'type_ar')}}
                        </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-group has-feedback">
                            <label for="inputNameEN" class="control-label">{{__('Type In English')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                <input type="text" name="type_en" class="form-control" id="inputNameEN"
                                       value="{{$sparePart->type_en}}" placeholder="{{__('Type In English')}}">
                            </div>
                            {{input_error($errors,'type_en')}}
                        </div>

                                </div>
                                </div>

                                <div class="col-md-12">
                                <div class="col-md-6">
                                <div class="form-group has-feedback">
                            <label for="logo" class="control-label">{{__('Image')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-photo"></li></span>
                                <input type="file" name="image" class="form-control" id="image"
                                       value="{{$sparePart->image}}" placeholder="{{__('Image')}}" onchange="preview_image(event)">
                            </div>
                            @if($sparePart->image)
                            <div class="image-container handing ani wg image-format-hd" style="width: 185px;">
                            <a class="example-image-link" href="{{asset('storage/images/spare-parts/'.$sparePart->image)}}" data-lightbox="example-2">
                              <img class="example-image" style="width:185px; height: 185px" src="{{asset('storage/images/spare-parts/'.$sparePart->image)}}" id="output_image"/>

                            <div class="frame"></div>
                            </a>

</div>
                            <!-- <img style="max-width:300px; max-height: 130px" src="{{asset('storage/images/spare-parts/'.$sparePart->image)}}" id="output_image"/> -->
                            @else
                            <div class="image-container handing ani image-format-hd" style="width: 185px;">
                            <a class="example-image-link" href="{{asset('default-images/defualt.png')}}" data-lightbox="example-1">
<img  class="example-image" style="width:185px; height: 185px" src="{{asset('default-images/defualt.png')}}" id="output_image"/>

<div class="frame"></div>
</a>
</div>
                                <!-- <img style="max-width:300px; max-height: 130px" src="{{asset('default-images/defualt.png')}}" id="output_image"/> -->
                            @endif
                            {{input_error($errors,'image')}}
                        </div>
                                </div>




                        <div class="col-md-4">
                            <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="hidden"  name="status" value="0">
                                <input type="checkbox" {{$sparePart->status == 1 ? 'checked' : ''}} id="switch-1" name="status" value="1">
                                <label for="switch-1">{{__('Active')}}</label>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    </div>
    </div>

    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\SparePart\UpdateSparPartsRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
