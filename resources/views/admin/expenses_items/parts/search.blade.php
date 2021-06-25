@if(filterSetting())
{{-- Start Search Conttent --}}
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
                    <div class="form-group col-md-6">
                    <label> {{ __('Expense Type') }} </label>
                        <select name="revenue_id" class="form-control js-example-basic-single" id="expenseTypes">
                            <option value="">{{__('Select Expense Type')}}</option>
                            @foreach(\App\Models\ExpensesType::withTrashed()->get() as $type)
                                <option value="{{$type->id}}">{{$type->type}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                    <label> {{ __('Expense Item') }} </label>
                        <select name="item" class="form-control js-example-basic-single" id="expenseItems">
                            <option value="">{{__('Select Expense Item')}}</option>
                            @foreach($expenseItems as $item)
                                <option value="{{$item->item}}">{{$item->item}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:expenses_items.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

            </form>
        </div>
        <!-- /.card-content -->
    </div>
    <!-- /.box-content -->
</div>
@endif

@section('js-ajax')
    <script type="application/javascript">
        $('#branch_id').on('change', function () {
            $.ajax({
                url: "{{ route('admin:getExpensesTypesByBranchID') }}?branch_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#expenseTypes').html(data.types);
                }
            });
        });

        $('#expenseTypes').on('change', function () {
            $.ajax({
                url: "{{ route('admin:expenseTypes.items') }}?expense_type_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#expenseItems').html(data.items);
                }
            });
        });
    </script>
@endsection
