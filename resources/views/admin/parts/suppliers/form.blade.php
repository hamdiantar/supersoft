<div class="col-md-12">
    <hr>
    <span style="color: white;font-size: 14px;background:#2980B9;padding:5px 10px;border-radius:3px"> {{__('Main supplier')}} </span>
    <hr>
</div>
<div class="container">
    <div class="form_new_supplier">
        @if(isset($part) && $part->suppliers_ids )
            @foreach($part->suppliers_ids as $index=>$supplierPart)
                <div class="row supplier-{{$index}}">

                    <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label for="inputSymbolAR" class="control-label">{{__('Select Supplier')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <select name="suppliers_ids[]" class="form-control js-example-basic-single"
                                        onchange="getSupplierById('{{$index}}')" id="suppliers_ids{{$index}}">
                                    <option>{{__('Select Supplier')}}</option>
                                    @foreach($suppliers as $supplierData)
                                        <option value="{{$supplierData->id}}"
                                            {{ $supplierPart->id === $supplierData->id ? 'selected':''}}>{{$supplierData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{input_error($errors,'suppliers_ids')}}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">

                            <label for="exampleInputEmail1">{{__('phone')}}</label>
                            <input type="text" readonly name="contacts[{{$index}}][phone_1]" id="phone{{$index}}"
                                   value="{{$supplierPart->phone_1 .' | '. $supplierPart->phone_2}}" class="form-control">

                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Address')}}</label>
                            <input type="text" readonly min="0" name="contacts[{{$index}}][phone_2]"
                                   value="{{$supplierPart->address}}" id="address{{$index}}" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn btn-sm btn-danger" type="button"
                                    onclick="deleteSupplier('{{$index}}')"
                                    id="delete-div-" style="margin-top: 31px;">
                                <li class="fa fa-trash"></li>
                            </button>
                        </div>
                    </div>
                </div>

            @endforeach
        @endif
        <input type="hidden" value="{{isset($index) ? $index + 1 : 1}}" id="supplier_count">
    </div>
</div>


<div class="col-md-12">
    <button type="button" title="new price" onclick="newSupplier()"
            class="btn btn-sm btn-primary">
        <li class="fa fa-plus"></li>
    </button>
    <hr>
</div>


@section('js-validation')
    <script type="text/javascript">
        function newSupplier() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let supplier_count = $("#supplier_count").val();
            let branchId = $("#branch_id").val();
            @if(authIsSuperAdmin())
            if (!is_numeric(branchId)) {
                swal({text: '{{__('please select the branch first')}}', icon: "warning"})
                return false;
            }
            @endif
            $.ajax({
                type: 'post',
                url: '{{route('admin:parts.new.supplier')}}',
                data: {
                    _token: CSRF_TOKEN,
                    supplier_count: supplier_count,
                    branchId: branchId,
                },
                success: function (data) {
                    $("#supplier_count").val(data.index);
                    $("#suppliers_ids" + data.index).select2()
                    $(".form_new_supplier").append(data.view);
                    $("#suppliers_ids" + data.index).select2()
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function deleteSupplier(index) {
            swal({
                title: "{{__('Delete')}}",
                text: "{{__('Are you sure want to Delete?')}}",
                type: "success",
                buttons: {
                    confirm: {
                        text: "{{__('Ok')}}",
                    },
                    cancel: {
                        text: "{{__('Cancel')}}",
                        visible: true,
                    }
                }
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $(".supplier-" + index).remove();
                }
            });
        }

        function getSupplierById(index) {
            let id = $('#suppliers_ids' + index).val();
            $.ajax({
                type: 'get',
                url: "{{ route('admin:parts.getBYId.supplier') }}?supplier_id=" + id,
                success: function (data) {
                    if (data.status) {
                        swal({text: "{{__('Please Select Valid Supplier')}}", icon: "error"})
                    }
                    $("#phone" + index).val(data.phone)
                    $("#address" + index).val(data.address)
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }
    </script>
@endsection
