@extends('admin.default')

@section('page-header')
    @if($my_reports)
        {{ trans('app.daily_reports') }}
    @else
        <a href="{{ route('daily_reports.patients') }}"> {{ trans('app.daily_reports') }} </a> <i class="ti-angle-right"></i> {{ $patientName }}
    @endif
@endsection
@section('content')
    @if(!$hasToday)
        <div class="mB-20 d-inline-block">
            <a href="{{ route('daily_reports.create',$patientId) }}" class="btn btn-primary">
                {{ trans('app.add_daily_report') }}
            </a>
        </div>
    @endif
    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>{{ trans('app.date') }}</th>
                    @if($role == 'administrator')
                        <th></th>
                    @endif
                </tr>
                </thead>

                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td><a href="{{ route('daily_reports.patients.report', [$item->patient_id,$item->id]) }}"> {{ $item->created_at}} </a></td>
                        @if($role == 'administrator')
                            <td>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        {!! Form::open([
                                            'class'=>'delete',
                                            'url'  => route('daily_reports.destroy', [$item->patient_id,$item->id]),
                                            'id'    => $item->id,
                                            'method' => 'DELETE',
                                            ])
                                        !!}

                                        <button class="btn btn-danger btn-sm" title="{{ trans('app.delete_title') }}" data-msg="{{trans('app.delete_msg',['obj'=>"την ημερήσια αναφορά ",'name'=>$item->created_at])}}"><i class="ti-trash"></i></button>

                                        {!! Form::close() !!}
                                    </li>
                                </ul>
                            </td>
                        @endif
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
