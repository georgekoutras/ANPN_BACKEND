@extends('admin.default')

@section('page-header')
    <a href="{{ route('treatments.patients') }}"> {{ trans('app.treatments') }} </a> <i class="ti-angle-right"></i> {{ $patientName }}
@endsection
@section('content')
    <div class="mB-20 d-inline-block">
        <a href="{{ route('treatments.create',$patiendId) }}" class="btn btn-primary">
            {{ trans('app.create_treatment') }}
        </a>
    </div>
    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>{{ trans('app.diagnose_date') }}</th>
                    <th>{{ trans('app.status') }}</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td><a href="{{ route('treatment.edit', [$item->patient_id,$item->id]) }}"> {{ $item->diagnose_date}} </a></td>
                        <td>
                            @if($item->status == 'baseline')
                                {{ trans('app.baseline') }}
                            @else
                                {{ trans('app.exacerbation') }}
                            @endif
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    {!! Form::open([
                                        'class'=>'edit',
                                        'url'  => route('treatment.edit', [$item->patient_id,$item->id]),
                                        'id'    => $item->id,
                                        'method' => 'GET',
                                        ])
                                    !!}

                                    <button  class="btn btn-edit btn-primary btn-sm" title="{{ trans('app.edit_title') }}"><i class="ti-pencil"></i></button>
                                    {!! Form::close() !!}

                                </li>
                                <li class="list-inline-item">
                                    {!! Form::open([
                                        'class'=>'delete',
                                        'url'  => route('treatment.destroy', [$item->patient_id,$item->id]),
                                        'id'    => $item->id,
                                        'method' => 'DELETE',
                                        ])
                                    !!}

                                    <button class="btn btn-danger btn-sm" title="{{ trans('app.delete_title') }}" data-msg="{{trans('app.delete_msg',['obj'=>"τη θεραπεία ",'name'=>$item->diagnose_date])}}"><i class="ti-trash"></i></button>

                                    {!! Form::close() !!}
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">

        $(document).ready(function(){
        });

    </script>
@endpush
