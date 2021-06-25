@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{ __('Companies') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Companies')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-check-square-o"></i>  {{__('Companies')}}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:companies.create',
                      'new' => '',
                     ])
                       </li>
                 
                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:companies.deleteSelected',
                                 ])
                                @endcomponent
                            </li>
                 
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="companies" class="table table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
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
                        <th scope="col">{!! __('Name') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">{!! __('Select') !!}</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($companies as $index=>$company)
                        <tr>
                            <td>{!! $index +1 !!}</td>
                            <td>{!! $company->name !!}</td>
                            <td>
                                @component('admin.buttons._edit_button',[
                                            'id'=>$company->id,
                                            'route' => 'admin:companies.edit',
                                             ])
                                @endcomponent
                                @component('admin.buttons._delete_button',[
                                            'id'=> $company->id,
                                            'route' => 'admin:companies.destroy',
                                             ])
                                @endcomponent
                            </td>
                            <td>
                            @component('admin.buttons._delete_selected',[
                                       'id' => $company->id,
                                        'route' => 'admin:companies.deleteSelected',
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
        invoke_datatable($('#companies'))
    </script>
@endsection
