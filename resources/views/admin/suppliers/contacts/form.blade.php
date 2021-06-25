<div class="col-md-12">
    <hr>
    <span style="color: white;font-size: 14px;background:#2980B9;padding:5px 10px;border-radius:3px"> {{__('Contacts')}} </span>
    <hr>
</div>

<div class="container">

    <div class="form_new_contact">

        @if(isset($supplier) && $supplier->contacts )
            @foreach($supplier->contacts as $contact)
                @php
                $index = $contact->id;
                @endphp
                <input type="hidden" name="contactsUpdate[{{$contact->id}}][contactId]" value="{{$contact->id}}">
                <div class="row " id="contact_{{$contact->id}}">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Name')}}</label>

                            <span class="asterisk" style="color: #ff1d47"> * </span>

                            <input type="text"  id="name_{{$contact->id}}"
                                   name="contactsUpdate[{{$contact->id}}][name]" value="{{$contact->name}}" class="form-control">

                        </div>
                        {{input_error($errors,'contactsUpdate.'.$contact->id.'.name')}}
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">

                            <label for="exampleInputEmail1">{{__('phone 1')}}</label>

                            <span class="asterisk" style="color: #ff1d47"> * </span>

                            <input type="text" id="phone_1_{{$contact->id}}"
                                   name="contactsUpdate[{{$contact->id}}][phone_1]" value="{{$contact->phone_1}}" class="form-control">

                        </div>
                        {{input_error($errors,'contactsUpdate.'.$contact->id.'.phone_1')}}
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('phone 2')}} :</label>
                            <input type="text" id="phone_2_{{$contact->id}}"
                                   name="contactsUpdate[{{$contact->id}}][phone_2]" value="{{$contact->phone_2}}"  class="form-control">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">

                            <label for="exampleInputPassword1"> {{__('Address')}} : </label>

                            <textarea id="address_{{$contact->id}}" class="form-control" style=" height: 45px;"
                                      name="contactsUpdate[{{$contact->id}}][address]">{{$contact->address}}</textarea>

                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">

{{--                            <button class="btn btn-sm btn-success" type="button" onclick="updateContact('{{$contact->id}}')"--}}
{{--                                    id="delete-div-" style="margin-top: 31px;" title="{{__('Update Contact')}}">--}}
{{--                                <li class="fa fa-check"></li>--}}
{{--                            </button>--}}

                            <button class="btn btn-sm btn-danger" type="button" onclick="destroyContact('{{$contact->id}}')"
                                    id="delete-div-" style="margin-top: 31px;" title="{{__('Delete Contact')}}">
                                <li class="fa fa-trash"></li>
                            </button>

                        </div>
                    </div>
                </div>

            @endforeach
        @endif
            @if($errors->has('contacts.*.name') || $errors->has('contacts.*.phone_1'))
                @include('admin.suppliers.contacts.ajax_form_new_contact', ['index' =>  $index ?? 4545])
            @endif

        <input type="hidden" value="0" id="contacts_count">

    </div>


</div>


<div class="col-md-12">
<button type="button" title="new price" onclick="newContact()"
                class="btn btn-sm btn-primary">
            <li class="fa fa-plus"></li>
        </button>
    <hr>
</div>
