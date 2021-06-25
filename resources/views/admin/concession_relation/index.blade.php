@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Concession Relations') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Concession Relations')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-check-square-o"></i>  {{__('Concession Relations')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [  'route' => 'admin:concession-types.create',  'new' => '',])
                        </li>

{{--                        <li class="list-inline-item">--}}
{{--                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:concession-types.deleteSelected',])--}}
{{--                            @endcomponent--}}
{{--                        </li>--}}

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="cities" class="table table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Concession Type') !!}</th>
                                <th scope="col">{!! __('Concession Item') !!}</th>
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
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Concession Type') !!}</th>
                                <th scope="col">{!! __('Concession Item') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($types as $index=>$type)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    <td>{!! $type->name !!}</td>
                                    <td class="text-danger">{{ __($type->concessionItem->name) }}</td>
                                    <td>
                                        @component('admin.buttons._edit_button',[
                                                    'id'=>$type->id,
                                                    'route' => 'admin:concession-relations.edit',
                                                     ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                                    'id'=> $type->id,
                                                    'route' => 'admin:concession-types.destroy',
                                                     ])
                                        @endcomponent
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                   'id' => $type->id,
                                                    'route' => 'admin:concession-types.deleteSelected',
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
