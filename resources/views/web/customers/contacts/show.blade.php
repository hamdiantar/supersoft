<div class="box-content box-content-wg card bordered-all blue-1 js__card">
    <h4 class="box-title bg-blue-1 with-control text-center">
        {{__('Contacts')}}
        <span style="float: right;"> </span>
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
                <th>{{__('Phone 1')}}</th>
                <th>{{__('Phone 2')}}</th>
                <th>{{__('address')}}</th>
                <th>{{__('Created Date')}}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($customer->contacts as $contact)
                <tr>
                    <td>{{$contact->name}}</td>
                    <td>{{$contact->phone_1}}</td>
                    <td>{{$contact->phone_2}}</td>
                    <td>{{\Illuminate\Support\Str::limit($contact->address, 50)}}</td>
                    <td>{{$contact->created_at}}</td>
                </tr>
            @endforeach

        </table>
    </div>
</div>

