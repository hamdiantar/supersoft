@foreach($parts as $part)

    <div class="modal fade modal-bg-wg" id="part_taxes_{{$part->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Part Taxes')}}</h4>
                </div>

                <form action="{{route('admin:parts.taxes.save')}}" method="post">
                    @csrf

                    <div class="modal-body">
                        <table id="" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                            <tr>

                                <th scope="col">{!! __('Select') !!}</th>
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('Tax Type') !!}</th>
                                <th scope="col">{!! __('Tax /Fee Value') !!}</th>

                            </tr>
                            </thead>
                            <tfoot>
                            <tr>

                                <th scope="col">{!! __('Select') !!}</th>
                                <th scope="col">{!! __('Name') !!}</th>
                                <th scope="col">{!! __('Tax Type') !!}</th>
                                <th scope="col">{!! __('Tax /Fee Value') !!}</th>

                            </tr>
                            </tfoot>
                            <tbody>

                            @foreach($taxes as $tax)
                                <tr>
                                    <td>
                                        <div class="switch success">
                                            <input type="checkbox" class="part_taxable" id="part-{{$part->id}}-tax-{{ $tax->id }}"
                                                   name="taxes[]" value="{{$tax->id}}"
                                                {{in_array($tax->id, $part->taxes->pluck('id')->toArray()) ? 'checked':''}}>
                                            <label for="part-{{$part->id}}-tax-{{ $tax->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{!! $tax->name !!}</td>
                                    <td>
                            @if($tax->tax_type === 'amount')
                                    <span class="label label-primary wg-label"> {{ __('Amount') }} </span>
                                    @else
                                    <span class="label label-danger wg-label"> {{ __('Percentage') }} </span>
                            @endif
                            </td>
                                    <td>{!! $tax->value !!}</td>
                                </tr>
                            @endforeach

                            <input type="hidden" name="part_id" value="{{$part->id}}">

                            </tbody>
                        </table>

                        <div class="row">

                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label for="inputPhone" class="control-label">{{__('Reviewable')}}</label>
                                <div class="switch primary">
                                    <input type="checkbox" id="reviewable-{{$part->id}}" name="reviewable" {{isset($part) && $part->reviewable? 'checked':''}}>
                                    <label for="reviewable-{{$part->id}}">{{__('Active')}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label for="inputPhone" class="control-label">{{__('Taxable')}}</label>
                                <div class="switch primary">
                                    <input type="checkbox" id="taxable-{{$part->id}}" name="taxable" onchange="partTaxable('{{$part->id}}')"
                                        {{isset($part) && $part->taxable? 'checked':''}}>
                                    <label for="taxable-{{$part->id}}">{{__('Active')}}</label>
                                </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">
                            {{__('Save')}}
                        </button>

                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                            {{__('Close')}}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endforeach
