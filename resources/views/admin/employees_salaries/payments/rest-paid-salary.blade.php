<div class="col-xs-12">
    <div class="box-content card bordered-all js__card top-search">
        <h4 class="box-title with-control">
        <i class="fa fa-dollar"></i>  {{__('Employee Salary Payment Status')}}
        </h4>
 <div class="card-content js__card_conten">
            <table class="table table-responsive table-bordered table-striped">
                <thead>
                    <tr>
                        <th> {{ __("Net Salary") }} </th>
                        <th> {{ __("Paid Amount") }} </th>
                        <th> {{ __("Rest Amount") }} </th>
                    </tr>
                <thead>
                <tbody>
                    <tr>
                        <td style="color:black"> {{ $salary->employee_data['net_salary'] }} </td>
                        <td style="color:black"> {{ $salary->paid_amount }} </td>
                        <td style="color:black"> {{ $salary->rest_amount }} </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>