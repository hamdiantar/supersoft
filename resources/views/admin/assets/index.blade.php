@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('words.assets') }} </title>
@endsection

@section('content')
<div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('words.assets') }}</li>
            </ol>
        </nav>




        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-cubes"></i>   {{ __('words.assets') }}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                <li class="list-inline-item">
                    @include('admin.buttons.add-new', [
                        'route' => 'admin:assets.create',
                        'new' => __(''),
                    ])
                </li>
                        <li class="list-inline-item">

                                @component('admin.buttons._confirm_delete_selected',[
                                    'route' => 'admin:assets.delete_selected',
                                    ])
                                @endcomponent



                        </li>
            </ul>
            <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="datatable-with-btns" class="table table-striped table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th scope="col"> {{ __('Branch') }} </th>
                            <th scope="col"> {{ __('Name') }} </th>
                            <!-- <th scope="col"> {{ __('type') }} </th> -->
                            <th scope="col"> {{ __('group') }} </th>
                            <th scope="col"> {{ __('status') }} </th>
                            <th scope="col"> {{ __('consumtion rate') }} </th>
                            <th scope="col"> {{ __('details') }} </th>
                            <th scope="col"> {{ __('asset age') }} </th>
                            <th scope="col"> {{ __('purchase date') }} </th>
                            <th scope="col"> {{ __('work date') }} </th>
                            <th scope="col"> {{ __('purchase cost') }} </th>
                            <th scope="col"> {{ __('previous consumtion') }} </th>
                            <th scope="col"> {{ __('current consumtion') }} </th>
                            <th scope="col"> {{ __('total current consumtion') }} </th>
                            <th scope="col"> {{ __('book value') }} </th>
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">
                                <div class="checkbox danger">
                                    <input type="checkbox"  id="select-all">
                                    <label for="select-all"></label>
                                </div>{!! __('Select') !!}
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                        <th scope="col"> {{ __('Branch') }} </th>
                            <th scope="col"> {{ __('Name') }} </th>
                            <!-- <th scope="col"> {{ __('type') }} </th> -->
                            <th scope="col"> {{ __('group') }} </th>
                            <th scope="col"> {{ __('status') }} </th>
                            <th scope="col"> {{ __('consumtion rate') }} </th>
                            <th scope="col"> {{ __('details') }} </th>
                            <th scope="col"> {{ __('asset age') }} </th>
                            <th scope="col"> {{ __('purchase date') }} </th>
                            <th scope="col"> {{ __('work date') }} </th>
                            <th scope="col"> {{ __('purchase cost') }} </th>
                            <th scope="col"> {{ __('previous consumtion') }} </th>
                            <th scope="col"> {{ __('current consumtion') }} </th>
                            <th scope="col"> {{ __('total current consumtion') }} </th>
                            <th scope="col"> {{ __('book value') }} </th>
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($assets as $asset)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td> {{ $asset->branch->name }} </td>
                                <td> {{ $asset->name }} </td>

                                <!-- <td > {{ $asset->type }} </td> -->
                                <td > {{ $asset->group->name }} </td>

                                <td>
                                    @if($asset->asset_status == 1)
                                        {{ __('continues') }}
                                    @elseif($asset->asset_status == 2)
                                        {{ __('sell') }}
                                    @else
                                        {{ __('ignore') }}
                                    @endif
                                </td>
                                <td> {{ $asset->annual_consumtion_rate }} </td>
                                <td> {{ $asset->asset_details }} </td>
                                <td> {{ $asset->asset_age }} </td>
                                <td> {{ $asset->purchase_date }} </td>
                                <td> {{ $asset->date_of_work }} </td>
                                <td> {{ $asset->purchase_cost }} </td>
                                <td> {{ $asset->past_consumtion }} </td>
                                <td> {{ $asset->current_consumtion }} </td>
                                <td> {{ $asset->total_current_consumtion }} </td>
                                <td> {{ $asset->book_value }} </td>
                                <td> {{ $asset->created_at }} </td>
                                <td> {{ $asset->updated_at }} </td>
                                <td>
                                <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>

                                    </button>
                                        <ul class="dropdown-menu">
                                            <li>

                                            @component('admin.buttons._edit_button',[
                                            'id'=>$asset->id,
                                            'route' => 'admin:assets.edit',
                                             ])
                                @endcomponent

                                            </li>
                                            <li>

                                            @component('admin.buttons._delete_button',[
                                            'id'=> $asset->id,
                                            'route' => 'admin:assets.destroy',
                                             ])
                                @endcomponent

                                            </li>

                                            <li>

                                            @component('admin.buttons._show_with_text_button',[
                                            'id'=> $asset->id,
                                            'route' => 'admin:assetsEmployees.index',
                                            'text' => __('employees'),
                                             ])
                                @endcomponent

                                            </li>

                                            <li>

                                            @component('admin.buttons._show_with_text_button',[
                                            'id'=> $asset->id,
                                            'route' => 'admin:assetsInsurances.index',
                                            'text' => __('insurances'),
                                             ])
                                @endcomponent

                                            </li>
                                            <li>

                                            @component('admin.buttons._show_with_text_button',[
                                            'id'=> $asset->id,
                                            'route' => 'admin:assetsLicenses.index',
                                            'text' => __('licenses'),
                                             ])
                                @endcomponent

                                            </li>

                                            <li>

                                            @component('admin.buttons._show_with_text_button',[
                                            'id'=> $asset->id,
                                            'route' => 'admin:assetsExaminations.index',
                                            'text' => __('examinations'),
                                             ])
                                @endcomponent

                                            </li>
                                            <li>

                                            <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                                               data-toggle="modal"

                                               onclick="getPrintData({{$asset->id}})"
                                               data-target="#boostrapModal" title="{{__('print')}}">
                                                <i class="fa fa-print"></i> {{__('Print')}}
                                            </a>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    @component('admin.buttons._delete_selected',[
                                        'id' =>  $asset->id,
                                        'route' => 'admin:assets.deleteSelected',
                                    ])
                                    @endcomponent
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@stop

@section('js')
    <script type="application/javascript">
        $(document).ready(function () {
            invoke_datatable($('#datatable-with-btns'))
            $(".select2").select2()
        })

        function printAsset() {
            var element_id = 'asset_to_print', page_title = document.title
            print_element(element_id, page_title)
        }
        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:assets.show') }}?asset_id=" + id,
                method: 'GET',
                success: function (data) {

                    $("#assetDatatoPrint").html(data.view)
                }
            });
        }
    </script>
@stop

@section('modals')

<div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <!-- <h4 class="modal-title" id="myModalLabel-1">{{__('Concession')}}</h4> -->
                </div>

                <div class="modal-body" id="assetDatatoPrint">


                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printAsset()" id="print_sales_invoice">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"><i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>
@endsection
