@extends('admin.default')

@section('page-header')
    <a href="{{ route('ccqs.patients') }}"> {{ trans('app.ccqs') }} </a> <i class="ti-angle-right"></i> <a href="{{ route('ccqs.patients.list', $patientId) }}"> {{ $patientName }} </a> <i class="ti-angle-right"></i> {{trans('app.create_ccq')}}
@stop

@section('content')
    <form action="{{ route('ccq.store', $patientId) }}" method="POST" class="form-horizontal store_ccq" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.ccqs.form')
        <button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.add_button') }}</button>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#status').select2({minimumResultsForSearch:-1});
            $.datetimepicker.setLocale('el');
            $('#diagnose_date').datetimepicker({
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
        });
    </script>
@endpush
