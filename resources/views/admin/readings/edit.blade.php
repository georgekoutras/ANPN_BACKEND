@extends('admin.default')

@section('page-header')
    <a href="{{ route('readings.patients') }}"> {{ trans('app.readings') }} </a> <i class="ti-angle-right"></i> <a href="{{ route('readings.patients.list', $data->patient_id) }}"> {{ $patientName }} </a> <i class="ti-angle-right"></i> {{trans('app.reading_for',['date'=>$data->diagnose_date])}}

@stop

@section('content')
    <form action="{{ route('reading.update', [$data->patient_id,$data->id]) }}" method="POST" class="form-horizontal edit_reading" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.readings.form')
        <button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.save_button') }}</button>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#status').select2({minimumResultsForSearch:-1});
            $('#smoker').select2({minimumResultsForSearch:-1,placeholder: "Επιλέξτε.."});
            $('#mmrc').select2({minimumResultsForSearch:-1,placeholder: "Επιλέξτε.."});
            $('#diagnose_date').prop('disabled',true);

            fillInReading(<?php echo json_encode($data);?>, $('form.edit_reading'));

        });
    </script>
@endpush

