<div class="col-xs-12">
<div class="box-content box-content-wg card bordered-all blue-1 js__card">
        <h4 class="box-title bg-blue-1 with-control">
            {{__('Customer Data')}}
            <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
        </h4>
        <div class="card-content js__card_content" style="">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Address')}}</th>
                    <th>{{__('Phone')}}</th>
                    <th>{{__('Customer Type')}}</th>
                    <th>{{__('Cars Number Registered')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                        <td id="customerName"></td>
                        <td id="customerAddress"></td>
                        <td id="customerPhone"></td>
                        <td id="customerType"></td>
                        <td id="carsCount"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
