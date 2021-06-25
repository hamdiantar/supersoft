@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{ __('Shifts') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Shifts')}}</li>
            </ol>
        </nav>

        @include('admin.shifts.parts.search')
        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-check-square-o"></i>  {{__('Shifts')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:shifts.create',
                      'new' => '',
                     ])
                       </li>
                
                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                    'route' => 'admin:shifts.deleteSelected',
                                     ])
                                @endcomponent
                            </li>
               
                    </ul>
                    <div class="clearfix"></div>


                <table id="shifts" class="table table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col">{!! __('Name') !!}</th>
                        <th scope="col">{!! __('Start From') !!}</th>
                        <th scope="col">{!! __('End At') !!}</th>
                        <th scope="col">{!! __('Created At') !!}</th>
                        <th scope="col">{!! __('Updated At') !!}</th>
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
                        <th scope="col">{!! __('Start From') !!}</th>
                        <th scope="col">{!! __('End At') !!}</th>
                        <th scope="col">{!! __('Created At') !!}</th>
                        <th scope="col">{!! __('Updated At') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">{!! __('Select') !!}</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($shifts as $index=>$shift)
                        <tr>
                            <td>{!! $index +1 !!}</td>
                            <td>{!! $shift->name !!}</td>
                            <td>
                            <span class="label label-primary wg-label"> 
                            {!! date("g:ia", strtotime($shift->start_from)) !!}
                            </span>
                            </td>
                            <td>
                            <span class="label label-primary wg-label"> 
                            {!! date("g:ia", strtotime($shift->end_from)) !!}
                            </span>
                            </td>
                            <td>{!! $shift->created_at!!}</td>
                            <td>{!! $shift->updated_at !!}</td>
                            <td>


                                @component('admin.buttons._edit_button',[
                                            'id'=>$shift->id,
                                            'route' => 'admin:shifts.edit',
                                             ])
                                @endcomponent

                                @component('admin.buttons._delete_button',[
                                            'id'=> $shift->id,
                                            'route' => 'admin:shifts.destroy',
                                             ])
                                @endcomponent

                            </td>
                            <td>
                            @component('admin.buttons._delete_selected',[
                                         'id' => $shift->id,
                                          'route' => 'admin:shifts.deleteSelected',
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
@endsection
@section('js')
    <script type="application/javascript">
        invoke_datatable($('#shifts'))
    </script>
@endsection
