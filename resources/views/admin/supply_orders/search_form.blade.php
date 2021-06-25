@if(filterSetting())
    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card top-search">
            <h4 class="box-title with-control">
                <i class="fa fa-search"></i>{{__('Search filters')}}
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                    <button type="button" class="control fa fa-times js__card_remove"></button>
                </span>
            </h4>

            <!-- /.box-title -->
            <div class="card-content js__card_content">
                <form action="{{route('admin:concession-types.index')}}" method="get" id="filtration-form">

                    <div class="list-inline margin-bottom-0 row">

                        @if(authIsSuperAdmin())
                            <div class="form-group col-md-4">
                                <label> {{ __('Branches') }} </label>
                                <select name="branch_id" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select branch')}}</option>
                                    @foreach($branches as $k=>$branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group col-md-4">
                            <label> {{ __('Types') }} </label>
                            <select name="type_id" class="form-control js-example-basic-single">
                                <option value="">{{__('Select type')}}</option>
                                @foreach($data['types'] as $k=>$type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>

{{--                        <div class="form-group col-md-4">--}}
{{--                            <label> {{ __('Concession Name') }} </label>--}}
{{--                            <input type="text" name="search" class="form-control" placeholder="{{__('Concession Name')}}">--}}
{{--                        </div>--}}

                        <div class="radio primary col-md-2" style="margin-top: 37px;">
                            <input type="radio" name="type" value="add" id="add">
                            <label for="add">{{__('Add Concession')}}</label>
                        </div>

                        <div class="radio primary col-md-2" style="margin-top: 37px;">
                            <input type="radio" name="type" id="withdrawal" value="withdrawal">
                            <label for="withdrawal">{{__('Withdrawal Concession')}}</label>
                        </div>

                        <div class="radio primary col-md-2" style="margin-top: 37px;">
                            <input type="radio" name="type" id="all" value="all">
                            <label for="all">{{__('All')}}</label>
                        </div>

                    </div>
                    <button type="submit" class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out">
                        <i class=" fa fa-search "></i> {{__('Search')}} </button>
                    <a href="{{route('admin:concession-types.index')}}"
                       class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out">
                        <i class=" fa fa-reply"></i> {{__('Back')}}
                    </a>

                </form>
            </div>
            <!-- /.card-content -->
        </div>
        <!-- /.box-content -->
    </div>
@endif
