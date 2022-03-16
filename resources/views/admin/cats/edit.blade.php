@extends('admin.default')

@section('page-header')
    <a href="{{ route('cats.patients') }}"> {{ trans('app.cats') }} </a> <i class="ti-angle-right"></i> <a href="{{ route('cats.patients.list', $data->patient_id) }}"> {{ $patientName }} </a> <i class="ti-angle-right"></i> {{trans('app.cat_for',['date'=>$data->diagnose_date])}}

@stop

@section('content')
    <form action="{{ route('cat.update', [$data->patient_id,$data->id]) }}" method="POST" class="form-horizontal edit_cat" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.cats.form')
        <button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.save_button') }}</button>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#status').select2({minimumResultsForSearch:-1});
            $('#diagnose_date').prop('disabled',true);

            fillInCat(<?php echo json_encode($data);?>, $('form.edit_cat'));

        });
    </script>
@endpush

