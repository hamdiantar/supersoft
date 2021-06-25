@extends('admin.layouts.app')
@include('admin.employees_data.parts.rating')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Employee Salary Payments') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{url('admin/employees_salaries')}}"> {{__('Employees Salaries')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Employee Salary Payments')}}</li>
            </ol>
        </nav>

        @include('admin.employees_salaries.payments.rest-paid-salary' ,['salary' => $salary])

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <span class="ribbon-bottom ribbon-b-r">
                <span> <i class="fa fa-money"></i>   {{ __('Employee Salary Payments') }}</span>
                 </span>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        @if($salary->rest_amount != 0)
                            <li class="list-inline-item">
                                @include('admin.buttons.add-new', [
                                    'route' => 'admin:employee-salary-payments.create',
                                    'new' => 'Employee Salary Payment',
                                    'custom_args' => ['salary_id' => $salary->id]
                                ])
                            </li>
                        @endif
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:employee-salary-payments.deleteSelected',
                             ])
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="employeeData" class="table table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('Employee Name') !!}</th>
                                <th scope="col">{!! __('Paid Amount') !!}</th>
                                <th scope="col">{!! __('Deportation Method') !!}</th>
                                <th scope="col">{!! __('Deportation') !!}</th>
                                <th scope="col">{!! __('Payment Type') !!}</th>
                                <th scope="col">{!! __('Date') !!}</th>
                                <th scope="col">{!! __('Created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
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
                                <th scope="col">{!! __('Employee Name') !!}</th>
                                <th scope="col">{!! __('Paid Amount') !!}</th>
                                <th scope="col">{!! __('Deportation Method') !!}</th>
                                <th scope="col">{!! __('Deportation') !!}</th>
                                <th scope="col">{!! __('Payment Type') !!}</th>
                                <th scope="col">{!! __('Date') !!}</th>
                                <th scope="col">{!! __('Created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($salary->payments as $payment)
                                <tr>
                                    <td> {{ $payment->receiver }} </td>
                                    <td class="text-danger"> {{ number_format($payment->cost, 2) }} </td>
                                    <td>
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$payment->deportation) }}
                                     </span>
                                     </td>
                                    <td>
                                    <span class="label label-danger wg-label">
                                        @if($payment->deportation == 'safe' && $payment->locker)
                                            {{ $payment->locker->name }}
                                        @endif
                                        @if($payment->deportation == 'bank' && $payment->bank)
                                            {{ $payment->bank->name }}
                                        @endif
                                        </span>
                                    </td>

                                    <td>
                                    <span class="label label-primary wg-label">
                                         {{ __("words.".$payment->payment_type) }}
                                     </span>
                                    </td>
                                    <td> {{ $payment->date }} </td>
                                    <td>{!! $payment->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>{!! $payment->updated_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>

                                    @component('admin.employees_salaries.payments.show', [
                                            'id' => $payment->id,
                                            'expense' => $payment,
                                            'branch' => $salary->employee->branch
                                        ])
                                        @endcomponent

                                        @component('admin.buttons._edit_button',[
                                            'id'=> $payment->id,
                                            'route' => 'admin:employee-salary-payments.edit',
                                        ])
                                        @endcomponent

                                        @component('admin.buttons._delete_button',[
                                            'id'=> $payment->id,
                                            'route' => 'admin:employee-salary-payments.destroy',
                                        ])
                                        @endcomponent

                                    </td>
                                    <td>
                                    @component('admin.buttons._delete_selected',[
                                            'id' =>  $payment->id,
                                            'route' => 'admin:employee-salary-payments.deleteSelected',
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
        invoke_datatable($('#employeeData'))
    </script>
@endsection
