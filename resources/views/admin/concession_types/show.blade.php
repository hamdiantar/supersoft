@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Part Info') }} </title>
@endsection
@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:parts.index')}}"> {{__('Parts management')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Part Info')}}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xs-12">
                <div class=" card box-content-wg-new bordered-all primary">
                    <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                        {{__('Part Info')}}
                        <span class="controls hidden-sm hidden-xs pull-left">

							<button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:45px;height:45px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                    </h4>


                    <div class="box-content">

                        <div class="card-content wg-for-dt">

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th scope="row">{{__('Name')}}</th>
                                                <td>{{$part->name}}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">{{__('Unit')}}</th>
                                                <td>{{optional($part->sparePartsUnit)->unit}}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">{{__('Quantity')}}</th>
                                                <td>{{$part->quantity}}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">{{__('Description')}}</th>
                                                <td>{{$part->description}}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">{{__('Status')}}</th>
                                                <td>{{$part->active}}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>

                                            <tr>
                                                <th>{{__('Store')}}</th>
                                                <th>{{__('Quantity')}}</th>
                                            </tr>

                                            @if($part->stores->count())

                                                @foreach($part->stores as $store)

                                                    <tr>
                                                        <td> {{$store->name}}</td>
                                                        <td> {{optional($store->pivot)->quantity}}</td>
                                                    </tr>

                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2"> {{__('Not Found')}}</td>
                                                </tr>
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>

                                            <tr>
                                                <th>{{__('Part Types')}}</th>
                                            </tr>

                                            @if($part->spareParts->count())
                                                @foreach($part->spareParts as $type)

                                                    <tr>
                                                        <td> {{$type->type}}</td>
                                                    </tr>

                                                @endforeach
                                            @else
                                                <tr>
                                                    <td> {{__('Not Found')}}</td>
                                                </tr>
                                            @endif


                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>

                                            <tr>
                                                <th>{{__('Part Taxes')}}</th>
                                            </tr>

                                            @if($part->taxes->count())
                                                @foreach($part->taxes as $tax)

                                                    <tr>
                                                        <td> {{$type->name}}</td>
                                                    </tr>

                                                @endforeach
                                            @else
                                                <tr>
                                                    <td> {{__('Not Found')}}</td>
                                                </tr>
                                            @endif


                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="col-md-6 img-wg">
                                        @include('admin.spare-parts.parts.image-preview', [
                                                                          'path' => 'storage/images/parts/',
                                                                         'image' => $part->img,
                                                                         ])
                                    </div>
                                </div>

                                @if($part->barcode)
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-xs-5"><label>
                                                    <div class="form-group">
                                                        <input type="number" id="barcode_qty" min="1"
                                                               class="form-control"
                                                               name="barcode_qty" value="1">
                                                    </div>

                                                    <button class="btn btn-primary" onclick="openWin('{{$part->id}}')">
                                                        {{__('Print Barcode')}}
                                                    </button>

                                                </label>
                                            </div>
                                            <!-- /.col-xs-5 -->
                                            <div class="col-xs-7">

                                            </div>
                                            <!-- /.col-xs-7 -->
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            @foreach($part->prices as $index => $price)
                                <div class="col-xs-12">
                                    <div class="box-content card bordered-all js__card top-search">

                                        <h4 class="box-title with-control remove-before">
                                            <i class="fa fa-search"></i>
                                            <span>{{optional($price->unit)->unit}}</span>
                                            <span class="controls">
                                            <button type="button" class="control fa fa-minus "
                                                    onclick="hideBody('{{$price->id}}')"></button>
                                            <button type="button" class="control fa fa-times js__card_remove"></button>
                                        </span>
                                        </h4>

                                        <div class="card-content " id="unit_form_body_{{$price->id}}"
                                             style="background-color: white">

                                            <div class="list-inline margin-bottom-0 row">
                                                @include('admin.parts.units.form_show')
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <a href="{{route('admin:parts.index')}}"
                               class="btn btn-danger waves-effect waves-light">
                                <i class=" fa fa-reply"></i> رجوع
                            </a>

                            <!-- /.row -->
                        </div>
                        <!-- /.card-content -->
                    </div>
                    <!-- /.box-content card -->
                </div>
            </div>


        </div>
    </div>



@endsection

@section('js')

    <script type="application/javascript" type="text/javascript">
        function openWin(id) {

            var qty = $("#barcode_qty").val();

            $.ajax({
                type: 'get',
                data: {id: id, qty: qty},
                url: "{{route('admin:parts.print.barcode')}}",
                success: function (data) {
                    var myWindow = window.open('', '', 'width=700,height=500');
                    myWindow.document.write(data);

                    myWindow.document.close();
//                    myWindow.focus();
//                    myWindow.print();
//                    myWindow.close();
//                    $(".msg").html(data);
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function hideBody(index) {

            $("#unit_form_body_" + index).slideToggle('slow');
        }

    </script>

@endsection
