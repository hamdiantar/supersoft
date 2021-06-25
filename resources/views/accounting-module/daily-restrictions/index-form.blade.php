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

<form id="filtration-form">
    <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
    <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
    <input type="hidden" name="sort_method" value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
    <input type="hidden" name="sort_by" value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
    <input type="hidden" name="invoker"/>

    <div class="form-group col-md-3">
        <label> {{ __('accounting-module.date-from') }} </label>
        <input type="date" name="date_from" class="form-control"
            value="{{ isset($_GET['date_from']) ? $_GET['date_from'] : '' }}"/>
    </div>
    <div class="form-group col-md-3">
        <label> {{ __('accounting-module.date-to') }} </label>
        <input type="date" name="date_to" class="form-control"
            value="{{ isset($_GET['date_to']) ? $_GET['date_to'] : '' }}"/>
    </div>
    <div class="form-group col-md-3">
        <label> {{ __('accounting-module.restriction-number') }} </label>
        <select class="form-control select2" name="id">
            <option value=""> {{ __('accounting-module.select-one') }} </option>
            @foreach($search_collection as $search_c)
                <option value="{{ $search_c->id }}"
                    {{ isset($_GET['id']) && $_GET['id'] == $search_c->id ? 'selected' : '' }}>
                    {{ $search_c->restriction_number }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label> {{ __('accounting-module.with-adverse') }} </label>
        <div class="form-check" style="display: flex;align-items: flex-end;">
            <input style="margin:0 10px" class="form-check-input" type="radio" name="with_adverse" id="yes" value="yes"
                {{ isset($_GET['with_adverse']) && $_GET['with_adverse'] == 'yes' ? 'checked' : '' }}>
            <label class="form-check-label" for="yes">
                {{ __('accounting-module.yes') }}
            </label>
            <input style="margin:0 10px" class="form-check-input" type="radio" name="with_adverse" id="no" value="no"
                {{ isset($_GET['with_adverse']) && $_GET['with_adverse'] == 'no' ? 'checked' : '' }}>
            <label class="form-check-label" for="no">
                {{ __('accounting-module.no') }}
            </label>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-md-4">
        <button onclick="invoke('search')" class="btn sr4-wg-btn waves-effect waves-light hvr-rectangle-out"> <i class=" fa fa-search "></i> {{ __('accounting-module.filter') }} </button>
        <a class="btn bc-wg-btn waves-effect waves-light hvr-rectangle-out" href="{{ route('daily-restrictions.index') }}">
        <i class=" fa fa-reply"></i>  {{ __('accounting-module.back') }}
        </a>
    </div>
    <div class="clearfix"></div>
</form>
</div>
</div>
</div>