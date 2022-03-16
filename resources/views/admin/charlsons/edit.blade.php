@extends('admin.default')

@section('page-header')
    <a href="{{ route('ccis.patients') }}"> {{ trans('app.ccis') }} </a> <i class="ti-angle-right"></i> <a href="{{ route('ccis.patients.list', $data->patient_id) }}"> {{ $patientName }} </a> <i class="ti-angle-right"></i> {{trans('app.cci_for',['date'=>$data->diagnose_date])}}

@stop

@section('content')
    <form action="{{ route('cci.update', [$data->patient_id,$data->id]) }}" method="POST" class="form-horizontal edit_cci" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.charlsons.form')
        <button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.save_button') }}</button>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#diagnose_date').prop('disabled',true);

            fillInCci(<?php echo json_encode($data);?>, $('form.edit_cci'));

        });
    </script>
@endpush

