@extends('admin.layouts.app')

@section('title')
<title>{{ __('Super Car') }} - {{__('Create Service')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

    
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin:services.index')}}"> {{__('Services')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Service')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

        <div class=" card box-content-wg-new bordered-all primary">
                    <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-gear"></i>{{__('Create Service')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h1>
                <div class="box-content">
                    <form method="post" action="{{route('admin:services.store')}}" class="form">
                        @csrf
                        @method('post')
                        @include('admin.services.form')
                    </form>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Services\CreateRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        function getTypesByBranch() {

            var branch_id = $("#branch_id").val();

            $.ajax({
                url: "{{ route('admin:services.types.by.branch')}}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{branch_id:branch_id},

                success: function (data) {

                    $('.js-example-basic-single').select2();

                    $(".removeToNewData").remove();

                    var option = new Option('', '{{__('Select Service Type')}}');

                    option.text = '{{__("Select Service Type")}}';
                    option.value = '';

                    $("#services_types_options").append(option);

                    $.each(data, function (key, modelName) {

                        var option = new Option(modelName, modelName);

                        option.text = modelName;
                        option.value = key;

                        $("#services_types_options").append(option);
                    });

                    $('#services_types_options option').addClass(function() {
                        return 'removeToNewData';
                    });
                }
            });


        }

    </script>

@endsection