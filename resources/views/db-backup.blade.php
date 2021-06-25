@extends('admin.layouts.app')

@section('title')
    <title>{{ __('words.db-backup') }} </title>
@endsection

@section('content')
    <div class="col-xs-12">
    <div class=" card box-content-wg-new bordered-all primary">
            <h4 class="box-title  with-control"><i class="ico fa fa-database"></i>
            {{ __('words.db-backup') }}
</h4>
            <div class="box-content card-content">
                <a class="btn btn-icon btn-icon-left btn-delete-wg waves-effect waves-light hvr-bounce-to-left" href="{{ $link }}" download target="_blank"> 
                {{ __('words.download-here') }} 
                <i class="ico fa fa-download"></i>

                </a>
            </div>
    </div>
    </div>

@stop
