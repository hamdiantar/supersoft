@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('words.capital-balance') }} </title>
@endsection

@section('content')
<div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('words.capital-balance') }}</li>
            </ol>
        </nav>

@if (authIsSuperAdmin())
        <div class="col-xs-12">
    <div class="box-content card bordered-all js__card top-search">
        <h4 class="box-title with-control">
        <i class="fa fa-search"></i>  {{__('Search filters')}}
            <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
        </h4>
        <div class="card-content js__card_content">
                <form>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label> {{ __('Branches') }} </label>
                            <select class="form-control select2" name="branch">
                                <option value=""> {{ __('Select Branch') }} </option>
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option {{ isset($_GET['branch']) && $_GET['branch'] == $branch->id ? 'selected' : '' }}
                                        value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{ route('admin:capital-balance.index') }}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

                </form>
            </div>
        </div>
        </div>
        @endif



        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                 <i class="fa fa-money"></i>   {{ __('words.capital-balance') }}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                <li class="list-inline-item">
                    @include('admin.buttons.add-new', [
                        'route' => 'admin:capital-balance.create',
                        'new' => __(''),
                    ])
                </li>
            </ul>
            <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="datatable-with-btns" class="table table-striped table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col"> {{ __('Branch') }} </th>
                            <th scope="col"> {{ __('words.capital-balance') }} </th>
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col"> {{ __('Branch') }} </th>
                            <th scope="col"> {{ __('words.capital-balance') }} </th>
                            <th scope="col">{!! __('Created at') !!}</th>
                            <th scope="col">{!! __('Updated at') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($balances as $balance)
                            <tr>
                                <td> {{ optional($balance->branch)->name }} </td>
                                <td class="text-danger"> {{ $balance->balance }} </td>
                                <td> {{ $balance->created_at }} </td>
                                <td> {{ $balance->updated_at }} </td>
                                <td>
                                    @if($balance->editable())
                                        @component('admin.buttons._edit_button',[
                                            'id' =>  $balance,
                                            'route' => 'admin:capital-balance.edit',
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
@stop

@section('js')
    <script type="application/javascript">
        $(document).ready(function () {
            invoke_datatable($('#datatable-with-btns'))
            $(".select2").select2()
        })
    </script>
@stop
