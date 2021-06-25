

<div class="col-xs-12 ui-sortable-handle">
<div class="box-content card bordered-all js__card top-search card-closed">
					<h4 class="box-title bg-secondary with-control">
                    <i class="ico fa fa-search"></i>
                    {{ __('words.filtration') }}
						<span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
						<!-- /.controls -->
					</h4>
					<!-- /.box-title -->
					<div class="card-content js__card_content">

<form>
    @if(authIsSuperAdmin())
        <div class="form-group col-md-12">
            <label> {{ __('Branch') }} </label>
            <select name="branch_id" class="form-control" onchange="$('#filtration-form').submit()">
                <option value=""> {{ __('accounting-module.select-one') }} </option>
                <option {{ isset($_GET['branch_id']) && $_GET['branch_id'] == '' ? 'selected' : '' }}
                    value=""> {{ __('All') }} </option>
                @php
                $name_key = app()->getLocale() == 'ar' ? 'name_ar' : 'name_en';
                    \App\Models\Branch::select('name_ar' ,'name_en' ,'id')
                    ->orderBy('id' ,'asc')->chunk(50 ,function ($__branches) use ($name_key) {
                        foreach($__branches as $__b) {
                            $name = $__b->$name_key;
                            $selected = isset($_GET['branch_id']) && $_GET['branch_id'] == $__b->id ? 'selected' : '';
                            echo "<option $selected value='$__b->id'>$name</option>";
                        }
                    });
                @endphp
            </select>
        </div>
    @endif
    <div class="form-group col-md-6">
        <label> {{ __('accounting-module.date-from') }} </label>
        <input type="date" name="date_from" class="form-control"
            value="{{ isset($_GET['date_from']) ? $_GET['date_from'] : '' }}"/>
    </div>
    <div class="form-group col-md-6">
        <label> {{ __('accounting-module.date-to') }} </label>
        <input type="date" name="date_to" class="form-control"
            value="{{ isset($_GET['date_to']) ? $_GET['date_to'] : '' }}"/>
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-md-4">
        <button class="btn sr4-wg-btn waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-search "></i>  {{ __('accounting-module.filter') }} </button>
        <a class="btn bc-wg-btn waves-effect waves-light hvr-rectangle-out" href="{{ route('trading-account-index') }}">
        <i class=" fa fa-reply"></i> {{ __('accounting-module.back') }}
        </a>
    </div>
    <div class="clearfix"></div>
</form>

</div>
</div>
</div>