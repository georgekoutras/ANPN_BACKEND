@extends('admin.default')

@section('page-header')
    @if($my_reports)
        {{trans('app.daily_report_title',['date'=>$data->created_at])}}
    @else
        <a href="{{ route('daily_reports.patients') }}"> {{ trans('app.daily_reports') }} </a> <i class="ti-angle-right"></i> <a href="{{ route('daily_reports.patients.reports', $patientId) }}"> {{ $patientName }} </a> <i class="ti-angle-right"></i> {{trans('app.daily_report_title',['date'=>$data->created_at])}}
    @endif
@stop

@section('content')
    <form action="{{ route('daily_reports.store', $patientId) }}" method="POST" class="form-horizontal {{$formAction}}_daily_report" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.daily_reports.form')
        <a href="{{ route('daily_reports.patients.reports', $patientId) }}" class="btn btn-secondary btn-back"><i class="ti-arrow-left"></i> {{ trans('app.back') }}</a>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            fillInDailyReport(<?php echo json_encode($data);?>, $('form.view_daily_report'));

        });
    </script>
@endpush
