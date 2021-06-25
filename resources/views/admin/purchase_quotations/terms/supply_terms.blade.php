@foreach($items as $item)
    <div class="modal fade" id="terms_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="myModalLabel-1">{{__('Supply & payments')}}</h4>
                </div>

                <form action="{{route('admin:purchase.quotations.terms')}}" method="post">
                    @csrf

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12 margin-bottom-20">
                                <ul class="nav nav-tabs nav-justified" id="myTabs-justified" role="tablist">

                                    <li role="presentation" class="active">
                                        <a href="#home-{{$item->id}}-justified" id="home-tab-{{$item->id}}-justified"
                                           role="tab" data-toggle="tab" aria-controls="home"
                                           aria-expanded="true">
                                            {{__('Supply Terms')}}
                                        </a>
                                    </li>

                                    <li role="presentation">
                                        <a href="#profile-{{$item->id}}-justified" role="tab" id="profile-tab-{{$item->id}}-justified"
                                           data-toggle="tab" aria-controls="profile">
                                            {{__('Payment Terms')}}
                                        </a>
                                    </li>
                                </ul>

                                <!-- /.nav-tabs -->
                                <div class="tab-content" id="myTabContent-justified">

                                    <div class="tab-pane fade in active" role="tabpanel" id="home-{{$item->id}}-justified"
                                         aria-labelledby="home-tab-{{$item->id}}-justified">

                                        <table class="table table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th scope="col">{!! __('Check') !!}</th>
                                                <th scope="col">{!! __('Term') !!}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($supplyTerms as $term)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="terms[]" value="{{$term->id}}"
                                                            {{in_array($term->id, $item->terms->pluck('id')->toArray()) ? 'checked':''}}
                                                        >
                                                    </td>
                                                    <td>
                                                        <span>{{$term->term}}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade" role="tabpanel" id="profile-{{$item->id}}-justified"
                                         aria-labelledby="profile-tab-{{$item->id}}-justified">
                                        <table class="table table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th scope="col">{!! __('Check') !!}</th>
                                                <th scope="col">{!! __('Term') !!}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($paymentTerms as $term)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="terms[]" value="{{$term->id}}"
                                                        {{in_array($term->id, $item->terms->pluck('id')->toArray()) ? 'checked':''}}>
                                                    </td>
                                                    <td>
                                                        <span>{{$term->term}}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.tab-content -->
                            </div>

                            <input type="hidden" name="purchase_quotation_id"  value="{{$item->id}}">
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">
                            {{__('Save')}}
                        </button>

                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light"
                                data-dismiss="modal">
                            {{__('Close')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

