@extends('accounting-module.common-printer-layout')

@section('table')
    <div class="table-responsive">
        <table class="table table-striped table-bordered wg-table-print" style="width:100%;margin-top: 10px !important">
            <thead>
                <tr>
                    @foreach($columns_keys as $key)
                        <th class="text-center"> {{ $header[$key] }} </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $collection->chunk(100 ,function($data) use ($columns_keys ,$custom_echo) {
                        foreach($data as $index=>$record) {
                            $custom_echo($columns_keys ,$record, $index);
                        }
                    });
                @endphp
            </tbody>
        </table>
    </div>
@endsection
