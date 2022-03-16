@extends('admin.default')

@section('page-header')
    <a href="{{ route('treatments.patients') }}"> {{ trans('app.treatments') }} </a> <i class="ti-angle-right"></i> <a href="{{ route('treatments.patients.list', $data->patient_id) }}"> {{ $patientName }} </a> <i class="ti-angle-right"></i> {{trans('app.treatment_for',['date'=>$data->diagnose_date])}}

@stop

@section('content')
    <form action="{{ route('treatment.update', [$data->patient_id,$data->id]) }}" method="POST" class="form-horizontal edit_treatment" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.treatments.form')
        <button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.save_button') }}</button>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#status').select2({minimumResultsForSearch:-1});
            $('#ltot_device').select2({minimumResultsForSearch:-1});
            $('#ventilation_device').select2({minimumResultsForSearch:-1});
            $('#diagnose_date').prop('disabled',true);
            $.datetimepicker.setLocale('el');

            $('#ltot_start_date').datetimepicker({
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
            $('#ventilation_start_date').datetimepicker({
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
            fillInTreatment(<?php echo json_encode($data);?>, $('form.edit_treatment'));

        });
    </script>
@endpush

