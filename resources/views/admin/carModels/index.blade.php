@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{ __('Cars Models') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Cars Models')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-check-square-o"></i>  {{__('Cars Models')}}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:carModels.create',
                      'new' => '',
                     ])
                       </li>
                 
                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:carModels.deleteSelected',
                                 ])
                                @endcomponent
                            </li>
             
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="cities" class="table table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col">{!! __('Model Name') !!}</th>
                        <th scope="col">{!! __('Car Company') !!}</th>
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
                        <th scope="col">{!! __('Model Name') !!}</th>
                        <th scope="col">{!! __('Car Company') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">{!! __('Select') !!}</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($carModels as $index=>$carModel)
                        <tr>
                            <td>{!! $index +1 !!}</td>
                            <td>{!! $carModel->name !!}</td>
                            <td class="text-primary">{!! optional($carModel->company)->name !!}</td>
                            <td>


                                @component('admin.buttons._edit_button',[
                                            'id'=>$carModel->id,
                                            'route' => 'admin:carModels.edit',
                                             ])
                                @endcomponent

                                @component('admin.buttons._delete_button',[
                                            'id'=> $carModel->id,
                                            'route' => 'admin:carModels.destroy',
                                             ])
                                @endcomponent
                            </td>
                            <td>
                            @component('admin.buttons._delete_selected',[
                                       'id' => $carModel->id,
                                        'route' => 'admin:carModels.deleteSelected',
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
        invoke_datatable($('#cities'))
    </script>
@endsection
