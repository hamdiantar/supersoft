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
                <form action="{{route('admin:concessions.index')}}" method="get" id="filtration-form">
                    <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                    <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                    <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'asc' }}"/>
                    <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                    <div class="list-inline margin-bottom-0 row">
                        @if(authIsSuperAdmin())

                            <div class="form-group col-md-4">
                                <label> {{ __('Branches') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-file"></span>
                                    {!! drawSelect2ByAjax('branch_id','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Branch'),request()->branch_id) !!}
                                </div>
                            </div>
                        @endif
                        <div class="form-group col-md-4">
                            <label> {{ __('UserName') }} </label>
                            {!! drawSelect2ByAjax('user_id','User','name','name',__('Select Name'), request()->name) !!}
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Date From') }} </label>
                            <input type="date" name="date_from" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Date To') }} </label>
                            <input type="date" name="date_to" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Status') }} </label>
                            <select class="form-control js-example-basic-single" name="status">
                                <option value="">{{__('Select Status')}}</option>
                                <option value="pending">{{__('Pending')}}</option>
                                <option value="accepted">{{__('Accepted')}}</option>
                                <option value="rejected">{{__('Rejected')}}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Execution Status') }} </label>
                            <select class="form-control js-example-basic-single" name="execution_status">
                                <option value="">{{__('Select Status')}}</option>
                                <option value="pending">{{__('Pending')}}</option>
                                <option value="late">{{__('Late')}}</option>
                                <option value="finished">{{__('Finished')}}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Concession Type') }} </label>
                            <div class="input-group" id="concession_types">

                                <select class="form-control js-example-basic-single" id="concession_type_id"
                                        name="concession_type_id" onchange="getConcessionItems()">
                                    <option value="">{{__('Select Type')}}</option>
                                    @foreach($concessionTypes as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                        <label> {{ __('Items') }} </label>
                            <select name="concession_id" class="form-control js-example-basic-single concessions_numbers">
                                <option value class="remove_concession_for_new">{{__('Select')}}</option>
                                @foreach($additionalData['concessions'] as $concession)
                                    <option value="{{$concession->id}}" class="remove_concession_for_new">{{$concession->number}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                           
                            <label> {{ __('Doc. number') }} </label>
                            <div class="input-group" id="concession_items">
                                <select class="form-control js-example-basic-single " name="item_id" id>
                                    <option value="">{{__('Select')}}</option>
                                </select>
                            </div>

                            <input type="hidden" name="model_name" id="model_name">
                        </div>

                        <div class="radio primary col-md-2" style="margin-top: 37px;">
                            <input type="radio" name="type" value="add" id="add" onclick="showSelectedTypes('add')">
                            <label for="add">{{__('Add Concession')}}</label>
                        </div>

                        <div class="radio primary col-md-2" style="margin-top: 37px;">
                            <input type="radio" name="type" id="withdrawal" value="withdrawal"
                                   onclick="showSelectedTypes('withdrawal')">
                            <label for="withdrawal">{{__('Withdrawal Concession')}}</label>
                        </div>

                        <div class="radio primary col-md-2" style="margin-top: 37px;">
                            <input type="radio" name="type" id="all" value="all" checked
                                   onclick="showSelectedTypes('all')">
                            <label for="all">{{__('All')}}</label>
                        </div>

                    </div>
                    <button type="submit" class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out">
                        <i class=" fa fa-search "></i> {{__('Search')}} </button>
                    <a href="{{route('admin:concessions.index')}}"
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
