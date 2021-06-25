@extends($layout_path)

@section('styles')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('table')
    <table class="table table-responsive table-bordered table-striped">
        <thead>
            <tr>
                @foreach($table_header as $head)
                    @php
                        $head_key = implode("-" ,explode("_" ,$head));
                        if (is_array($hidden_columns) && in_array($head_key ,$hidden_columns)) continue;
                    @endphp
                    <th> {{ __('accounting-module.'.$head_key) }} </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $collection->chunk(100 ,function ($data) use ($table_header ,$hidden_columns) {
                    foreach($data as $row) {
                        echo '<tr>';
                        foreach($table_header as $col) {
                            $head_key = implode("-" ,explode("_" ,$col));
                            if (is_array($hidden_columns) && in_array($head_key ,$hidden_columns)) continue;
                            echo '<td>'. $row->$col .'</td>';
                        }
                        echo '</tr>';
                    }
                })
            @endphp
        </tbody>
    </table>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-4 col-xs-4">

        <div class="bordered-div">
                <h3>
                {{ __('accounting-module.debit-amount') }}
                </h3>
                <input disabled="" value="{{ $total->total_debit }}" class="form-control">
            </div>   
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
    
            <div class="bordered-div">
                <h3>
                {{ __('accounting-module.credit-amount') }}
                </h3>
                <input disabled="" value="{{ $total->total_credit }}" class="form-control">
            </div>              
        
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

        <div class="bordered-div">
                <h3>
                {{ __('accounting-module.balance') }}
                </h3>
                <input disabled="" value="{{ $total->balance }}" class="form-control">
            </div>           
    </div>
@endsection