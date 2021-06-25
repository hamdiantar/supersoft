@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{ __('Spare parts') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Spare parts')}}</li>
            </ol>
        </nav>
        @include('admin.spare-parts.parts.search')

        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-check-square-o"></i>  {{__('Spare parts')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:spare-parts.create',
                      'new' => '',
                     ])
                       </li>
         
                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                              'route' => 'admin:spare-parts.deleteSelected',
                               ])
                                @endcomponent
                            </li>
                  
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="spare-parts" class="table table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col">{!! __('Main Type') !!}</th>
                        <th scope="col" style="width:15%">{!! __('Image') !!}</th>
                        <th scope="col">{!! __('Status') !!}</th>
                        <th scope="col">{!! __('Created At') !!}</th>
                        <th scope="col">{!! __('Updated at') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col"><div class="checkbox danger">
                                <input type="checkbox"  id="select-all">
                                <label for="select-all"></label>
                            </div>{!! __('Select') !!}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col">{!! __('Main Type') !!}</th>
                        <th scope="col">{!! __('Image') !!}</th>
                        <th scope="col">{!! __('Status') !!}</th>
                        <th scope="col">{!! __('Created At') !!}</th>
                        <th scope="col">{!! __('Updated at') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">{!! __('Select') !!}</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($sparePart as $index=>$part)
                        <tr>
                            <td>{!! $index +1 !!}</td>
                            <td>{!! $part->type !!}</td>
                            @include('admin.spare-parts.parts.image-preview', [
    'path' => 'storage/images/spare-parts/',
    'image' => $part->image,
    ])
                         <td>
                         <div class="switch success">
                                            <input
                                                disabled
                                                type="checkbox"
                                                {{$part->status == 1 ? 'checked' : ''}}
                                                id="switch-{{ $part->id }}">
                                            <label for="part-{{ $part->id }}"></label>
                                        </div>
                         </td>
                            <td>{!! $part->created_at->format('y-m-d h:i:s A') !!}</td>
                            <td>{!! $part->updated_at->format('y-m-d h:i:s A') !!}</td>
                            <td>


                                @component('admin.buttons._edit_button',[
                                            'id'=>$part->id,
                                            'route' => 'admin:spare-parts.edit',
                                             ])
                                @endcomponent

                                @component('admin.buttons._delete_button',[
                                            'id'=> $part->id,
                                            'route' => 'admin:spare-parts.destroy',
                                             ])
                                @endcomponent
                            </td>
                            <td>
                            @component('admin.buttons._delete_selected',[
                                     'id' => $part->id,
                                     'route' => 'admin:spare-parts.deleteSelected',
                                      ])
                                @endcomponent
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="application/javascript">
        invoke_datatable($('#spare-parts'))
    </script>
@endsection
