@extends('admin.layouts.app')
@section('title')
    <title>{{__('User Activities')}}</title>
@endsection
@section('content')

    <div class="row small-spacing">


        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:users.index')}}"> {{__('Points Rule')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Points Rules')}}</li>
            </ol>
        </nav>


        <div class="col-xs-12 search-tb">
            <div class="box-content card bordered-all js__card top-search">
                <h4 class="box-title with-control">
                    <i class="fa fa-user"></i>{{__('Rule Info')}}
                    <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                    <!-- /.controls -->
                </h4>
                <!-- /.box-title -->
                <div class="card-content js__card_content">

                    <div class="card-content">
                        <div class="row">

                            <!-- /.col-md-6 -->
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody>
                                    <th>{{__('Points')}}</th>
                                    <td>{{$pointsRule->points}}</td>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody>
                                    <th>{{__('Amount')}}</th>
                                    <td>{{$pointsRule->amount}}</td>
                                    </tbody>
                                </table>
                                <!-- /.row -->
                            </div>

                            <!-- /.col-md-6 -->
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody>
                                    <th>{{__('Text Arabic')}}</th>
                                    <td>{{$pointsRule->text_ar}}</td>
                                    </tbody>
                                </table>

                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody>
                                    <th>{{__('Text English')}}</th>
                                    <td>{{$pointsRule->text_en}}</td>
                                    </tbody>
                                </table>

                            </div>

                            <div class="col-md-6">

                                <table class="table table-bordered">
                                    <tbody>
                                    <th>{{__('Branch')}}</th>
                                    <td>{{optional($pointsRule->branch)->name}}</td>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">

                                <table class="table table-bordered">
                                    <tbody>
                                    <th>{{__('Created At')}}</th>
                                    <td>{{$pointsRule->created_at}}</td>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">

                                <table class="table table-bordered">
                                    <tbody>
                                    <th>{{__('Updated At')}}</th>
                                    <td>{{$pointsRule->updated_at}}</td>
                                    </tbody>
                                </table>
                            </div>

                            <!-- /.col-md-6 -->
                        </div>
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
    <script type="application/javascript">
        invoke_datatable($('#activity'))
    </script>
@endsection
