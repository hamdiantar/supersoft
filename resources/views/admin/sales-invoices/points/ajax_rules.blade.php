<div class="form-group" id="points_rules_div">
    <label>{{__('Rules')}}</label>
    <select name="points_rule_id" class="form-control js-example-basic-single" id="point_rule_id" onchange="selectPointsRule()">
        <option value="" data-amount="0"> {{__('Select Rule')}}</option>
        @foreach($pointsRules as $pointsRule)
            <option value="{{$pointsRule->id}}" data-amount="{{$pointsRule->amount}}"> {{ $pointsRule->text }} </option>
        @endforeach
    </select>
</div>
