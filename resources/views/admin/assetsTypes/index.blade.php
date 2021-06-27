@extends('admin.layouts.app')
@section('title')
<title>{{ __('assetsType') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">  {{__('assetsType')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-money"></i>   {{__('Assets Type')}}
                 </h4>


                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">

                       @include('admin.buttons.add-new', [
                  'route' => 'admin:assetsType.create',
                      'new' => '',
                     ])
                       </li>

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                        'route' => 'admin:assetsType.deleteSelected',
                                         ])
                                @endcomponent
                            </li>

                    </ul>
                    <div class="clearfix"></div>

                    <div class="table-responsive">
                <table id="assetsTypes" class="table table-bordered wg-table-print table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col"> {{ __('Branch') }} </th>
                        <th scope="col">{!! __('Name') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">
                        <div class="checkbox danger">
                                <input type="checkbox"  id="select-all">
                                <label for="select-all"></label>
                            </div>{!! __('Select') !!}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col"> {{ __('Branch') }} </th>
                        <th scope="col">{!! __('Name') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">{!! __('Select') !!}</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($assetsTypes as $index=>$assetType)
                        <tr>
                            <td>{!! $loop->iteration !!}</td>
                            <td> {{ optional($assetType->branch)->name }} </td>
                            <td>{!! $assetType->name !!}</td>
                            <td>


                                            @component('admin.buttons._edit_button',[
                                            'id'=>$assetType->id,
                                            'route' => 'admin:assetsType.edit',
                                             ])
                                @endcomponent
                                            @component('admin.buttons._delete_button',[
                                            'id'=> $assetType->id,
                                            'route' => 'admin:assetsType.destroy',
                                             ])
                                @endcomponent

                            </td>
                            <td>
                            @component('admin.buttons._delete_selected',[
                                      'id' => $assetType->id,
                                       'route' => 'admin:assetsType.deleteSelected',
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
@endsection
@section('js')
    <script type="application/javascript">
        invoke_datatable($('#assetsTypes'))
    </script>
@endsection
