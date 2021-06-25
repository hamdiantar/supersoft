@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Concessions') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Concessions')}}</li>
            </ol>
        </nav>

        @include('admin.concessions.search_form')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-check-square-o"></i> {{__('Concessions')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_restore_selected',[
                          'route' => 'admin:concessions.deleteSelected',
                           ])
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <div class="clearfix"></div>
                        <table id="test_data" class="table table-bordered" style="width:100%">
                            <thead>
                            <th scope="col" class="text-center column-index">#</th>
                            <th scope="col" class="text-center column-number">{!! __('Concession number') !!}</th>
                            <th scope="col" class="text-center column-type">{!! __('Type') !!}</th>
                            <th scope="col" class="text-center column-user">{!! __('User') !!}</th>
                            <th scope="col" class="text-center column-status">{!! __('Status') !!}</th>
                            <th scope="col"
                                class="text-center column-concession-type">{!! __('Concession Type') !!}</th>
                            <th scope="col" class="text-center column-date">{!! __('Date') !!}</th>
                            <th scope="col" class="text-center column-execution">{!! __('Execution Status') !!}</th>
                            <th scope="col" class="text-center column-execution">{!! __('Item Number') !!}</th>
                            <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                            <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col" class="text-center column-options">{!! __('Options') !!}</th>
                            <th scope="col">

                                <div class="checkbox danger">
                                    <input type="checkbox" id="select-all">
                                    <label for="select-all"></label>
                                </div>{!! __('Select') !!}</th>
                            </thead>

                            <tfoot>
                            <tr>
                                <th scope="col" class="text-center column-index">#</th>
                                <th scope="col" class="text-center column-number">{!! __('Concession number') !!}</th>
                                <th scope="col" class="text-center column-type">{!! __('Type') !!}</th>
                                <th scope="col" class="text-center column-user">{!! __('User') !!}</th>
                                <th scope="col" class="text-center column-status">{!! __('Status') !!}</th>
                                <th scope="col"
                                    class="text-center column-concession-type">{!! __('Concession Type') !!}</th>
                                <th scope="col" class="text-center column-date">{!! __('Date') !!}</th>
                                <th scope="col" class="text-center column-execution">{!! __('Execution Status') !!}</th>
                                <th scope="col" class="text-center column-execution">{!! __('Item Number') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col" class="text-center column-options">{!! __('Options') !!}</th>
                                <th scope="col" class="text-center column-select-all">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>

                            @if($concessions->count())


                                @foreach($concessions as $index=>$concession)
                                    <tr>

                                        <td class="text-center column-number">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="text-center column-number">
                                            {{ $concession->type == 'add' ? $concession->add_number : $concession->withdrawal_number }}
                                        </td>
                                        <td class="text-center column-type">
                                            {{ __($concession->type . ' concession') }}
                                        </td>

                                        <td class="text-center column-user">
                                            {{ optional($concession->user)->name }}
                                        </td>

                                        <td class="text-center column-status">{{__($concession->status) }}</td>
                                        <td class="text-center column-concession-type">
                                            {{ optional($concession->concessionType)->name }}
                                        </td>

                                        <td class="text-center column-date">{{ $concession->date }}</td>
                                        <td class="text-center column-date">
                                            {{ $concession->concessionExecution ? __($concession->concessionExecution->status) : '---' }}
                                        </td>

                                        <td class="text-center column-date">{{ optional($concession->concessionable)->number }}</td>

                                        <td class="text-center column-created-at">{{ $concession->created_at }}</td>
                                        <td class="text-center column-created-at">{{ $concession->updated_at }}</td>
                                        <td class="text-center column-options">
                                            @component('admin.buttons._restore_delete',[
                                                      'id'=>$concession->id,
                                                      'route' => 'admin:concessions.restore_delete',
                                                      'tooltip' => __('Force Delete '.$concession['name']),
                                                       ])
                                            @endcomponent
                                        </td>
                                        <td>
                                            @component('admin.buttons._delete_selected',[
                                                       'id' => $concession->id,
                                                        'route' => 'admin:concessions.deleteSelected',
                                                        ])
                                            @endcomponent
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10"><span>{{__('NO DATA FOUND')}}</span></td>
                                </tr>
                            @endif
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
        invoke_datatable($('#test_data'));
    </script>
@endsection
