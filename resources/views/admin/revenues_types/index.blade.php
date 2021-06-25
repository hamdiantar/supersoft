@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Revenues Types') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Revenues Types')}}</li>
            </ol>
        </nav>

        @if(filterSetting())
                <div class="col-xs-12">
                <div class="box-content card bordered-all js__card top-search">
					<h4 class="box-title with-control">
                    <i class="fa fa-search"></i>{{__('Search filters')}}
						<span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
						<!-- /.controls -->
					</h4>
					<!-- /.box-title -->
					<div class="card-content js__card_content">
                            <form>
                                <div class="list-inline margin-bottom-0 row">
                                    @if(authIsSuperAdmin())
                                    <div class="form-group col-md-12">
                                    <label> {{ __('Branch') }} </label>
                                            <select name="branch_id" class="form-control js-example-basic-single" id="branch_id">
                                                <option value="">{{__('Select Branch')}}</option>
                                                @foreach(\App\Models\Branch::all() as $branch)
                                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="form-group col-md-12">
                                    <label> {{ __('Revenue Type') }} </label>
                                        <select name="type" class="form-control js-example-basic-single" id="revenueType">
                                            <option value="">{{__('Select Revenue Type')}}</option>
                                            @foreach($revenueTypes as $type)
                                                <option value="{{$type->type}}">{{$type->type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                        <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:revenues_types.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

                            </form>
                        </div>
                        <!-- /.card-content -->
                    </div>
                    <!-- /.box-content -->
                </div>
                @endif

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                 <i class="fa fa-money"></i>  {{__('Revenues Types')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:revenues_types.create',
                      'new' => '',
                     ])
                       </li>
                     
                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:revenues_types.deleteSelected',
                                 ])
                                @endcomponent
                            </li>
                  
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="revenuesTypes" class="table table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col">{!! __('Revenue Type') !!}</th>
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
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col">{!! __('Name') !!}</th>
                        <th scope="col">{!! __('Created at') !!}</th>
                        <th scope="col">{!! __('Updated at') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">{!! __('Select') !!}</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($revenueTypes as $index=>$type)
                        <tr>
                            <td>{!! $index +1 !!}</td>
                            <td>{!! $type->type !!}</td>
                            <td>{!! $type->created_at->format('y-m-d h:i:s A') !!}</td>
                            <td>{!! $type->updated_at->format('y-m-d h:i:s A')!!}</td>
                            <td>
                                @if($type->is_seeder == 0)

                                @component('admin.buttons._edit_button',[
                                            'id'=>$type->id,
                                            'route' => 'admin:revenues_types.edit',
                                             ])
                                @endcomponent

                                @component('admin.buttons._delete_button',[
                                            'id'=> $type->id,
                                            'route' => 'admin:revenues_types.destroy',
                                             ])
                                @endcomponent
                                    @endif
                            </td>
                            <td>
                            @if($type->is_seeder == 0)
                            @component('admin.buttons._delete_selected',[
                                       'id' => $type->id,
                                       'route' => 'admin:revenues_types.deleteSelected',
                                        ])
                                @endcomponent
                                @endif
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
        invoke_datatable($('#revenuesTypes'))
        $('#branch_id').on('change', function () {
            $.ajax({
                url: "{{ route('admin:getRevenuesTypesByBranchID') }}?branch_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#revenueType').html(data.types);
                }
            });
        });
    </script>
@endsection
