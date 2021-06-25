<form action="{{ $action }}" method="post" id="cost-centers-form" onsubmit="return cost_centers_form_submit(event)">
    @csrf
    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
    <div class="modal-header">
        <div class="col-md-12">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"> {{ $title }} </h4>
        </div>
    </div>
    <div class="modal-body">
        @if($model)
            <input type="hidden" name="id" value="{{ $model->id }}"/>
        @else
            <input type="hidden" name="cost_centers_id" value="{{ $id }}"/>
            <input type="hidden" name="tree_level" value="{{ $tree_level }}"/>
        @endif
        <div class="form-group col-md-6">
            <label> {{ __('accounting-module.name-ar') }} </label>
            <input name="name_ar" class="form-control" value="{{ $model ? $model->name_ar : '' }}"/>
        </div>
        <div class="form-group col-md-6">
            <label> {{ __('accounting-module.name-en') }} </label>
            <input name="name_en" class="form-control" value="{{ $model ? $model->name_en : '' }}"/>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="modal-footer">
        <div class="col-md-12">
            <button class="btn btn-primary"> {{ __('accounting-module.save') }} </button>
            <button type="button" class="btn btn-default" data-dismiss="modal"> {{ __('accounting-module.close') }} </button>
        </div>
    </div>
</form>