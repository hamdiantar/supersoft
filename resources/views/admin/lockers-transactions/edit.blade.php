@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{__('Edit Locker Transaction')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin:lockers-transactions.index')}}">   {{__('Lockers Transactions')}}</a></li>
            <li class="breadcrumb-item">  {{__('Edit Locker Transaction')}} </li>
            </ol>
        </nav>

        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-money"></i>  {{__('Edit Locker Transaction')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">

                    <form method="post" action="{{route('admin:lockers-transactions.update',$lockers_transaction->id)}}" class="form"
                          enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        @include('admin.lockers-transactions.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-content -->

@endsection
@section('js-validation')
        {!! JsValidator::formRequest('App\Http\Requests\Admin\LockersTransactions\CreateLockersTransactionsRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')

        <script type="application/javascript">

            function getByBranch() {

                var id = $("#branch_id").val();

                $.ajax({
                    url: '{{route('admin:transaction.form.by.branch')}}',
                    type: "get",
                    data: {id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {

                        $("#data_by_branch").html(data);
                        $('.js-example-basic-single').select2();

                    }, error: function (jqXhr, json, errorThrown) {
                        swal("sorry!", 'sorry please try later', "error");
                    },
                });
            }

            function getBalance() {

                var locker_id = $("#locker_id").val();

                $.ajax({
                    url: "{{ route('admin:lockers.get.balance')}}",
                    method: 'GET',
                    data:{locker_id:locker_id},
                    success: function (data) {
                        $('#locker_balance').val(data.balance);
                    }
                });
            }

            function getAccountBalance() {

                var account_id = $("#account_id").val();

                $.ajax({
                    url: "{{ route('admin:accounts.get.balance')}}",
                    method: 'GET',
                    data:{account_id:account_id},
                    success: function (data) {
                        $('#account_balance').val(data.balance);
                    }
                });
            }

        </script>

@endsection