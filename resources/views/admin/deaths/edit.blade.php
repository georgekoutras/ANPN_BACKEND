@extends('admin.default')

@section('page-header')
    <a href="{{ route('deaths.patients') }}"> {{ trans('app.deaths') }} </a>  <i class="ti-angle-right"></i> {{trans('app.death_for',['patient'=>$patientName])}}

@stop

@section('content')
    <form action="{{ route('death.update', [$data->patient_id,$data->id]) }}" method="POST" class="form-horizontal edit_death" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.deaths.form')
        <button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.save_button') }}</button>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $.datetimepicker.setLocale('el');
            $('#patient_id').select2();
            $('#patient_id').prop('disabled',true);
            $('#date').datetimepicker({
                i18n:{
                    el:{
                        months:[
                            'Ιανουάριος','Φεβρουάριος','Μάρτιος','Απρίλιος',
                            'Μαΐος','Ιούνιος','Ιούλιος','Αύγουστος',
                            'Σεπτέμβριος','Οκτώβριος','Νοέμβριος','Δεκέμριος',
                        ],
                        dayOfWeek:[
                            "Κυρ.","Δευ.", "Τρι.", "Τετ.", "Πεμ.",
                            "Παρ.", "Σαβ.",
                        ]
                    }
                },
                format:'d/m/y',
                timepicker:false,
                maxDate: '0',
                scrollMonth : false

            });
            fillInDeath(<?php echo json_encode($data);?>,<?php echo $patientId;?>,"<?php echo $patientName;?>", $('form.edit_death'));

        });
    </script>
@endpush

