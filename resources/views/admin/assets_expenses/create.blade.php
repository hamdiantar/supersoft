@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Create Assets Expenses') }} </title>
@endsection

@section('content')
        <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:assets_expenses.index')}}"> {{__('Assets Expenses')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Expenses Items')}}</li>
            </ol>
        </nav>
            <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-money"></i>{{__('Create Assets Expenses')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">

                    <form  method="post" action="{{route('admin:assets_expenses.store')}}" class="form">
                        @csrf
                        @method('post')
                        @include('admin.assets_expenses.form')
                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
                </div>
            </div>
            <!-- /.col-xs-12 -->
        </div>
        <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\AssetExpenseRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        function checkBranchValidation() {
            let branch_id = $('#branch_id').find(":selected").val();
            let isSuperAdmin = '{{authIsSuperAdmin()}}';
            if (!isSuperAdmin) {
                return true;
            }
            return !!branch_id;
        }
        function changeBranch () {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:assets_expenses.create')}}" + "?branch_id=" + branch_id ;
        }

        $('#assetsGroups').on('change', function () {
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
            $.ajax({
                url: "{{ route('admin:assets_expenses.getAssetsByAssetGroup') }}?asset_group_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#assetsOptions').html(data.assets);
                }
            });
        });

        $('#assetsOptions').on('change', function () {
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
            let assetsGroups = $('#assetsGroups').find(":selected").val();
            if (!assetsGroups) {
                swal({text: '{{__('sorry, please select assets Groups')}}', icon: "error"});
                return false;
            }

            if (checkIfAssetExists( $(this).val())) {
                swal({text: '{{__('sorry, you have already add this asset before')}}', icon: "warning"});
                return false;
            }
            let branch_id = $('#branch_id').find(":selected").val();
            $.ajax({
                url: "{{ route('admin:assets_expenses.getItemsByAssetId') }}?asset_id=" + $(this).val(),
                method: 'get',
                data : {
                    asset_id: $(this).val(),
                    branch_id: branch_id,
                    _token: '{{csrf_token()}}',
                },
                success: function (data) {
                    $('#items_data').append(data.items);
                    $('.js-example-basic-single').select2();
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        });

        function removeItem(index) {
            swal({
                title: "Delete Item",
                text: "Are you sure want to delete this item ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {
                if (willDelete) {
                    $('#item_' + index).remove();
                    addPriceToTotal()
                }
            });
        }

        function addPriceToTotal() {
            var total = 0;
            $(".priceItem").each(function(index, item){
               total = (parseInt(total) + parseInt($(this).val())).toFixed();
            })
            $('#total_price').val(total);
            $('#total_price_hidden').val(total);
        }

        function checkIfAssetExists(index) {
            var ids = [];
            $('.assetExist').each(function () {
               ids.push($(this).val())
            })
            return ids.includes(index);
        }
    </script>
@endsection
