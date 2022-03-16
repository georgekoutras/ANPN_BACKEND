@extends('admin.default')

@section('page-header')
    <a href="{{ route('ccis.patients') }}"> {{ trans('app.ccis') }} </a> <i class="ti-angle-right"></i> <a href="{{ route('ccis.patients.list', $patientId) }}"> {{ $patientName }} </a> <i class="ti-angle-right"></i> {{trans('app.create_cci')}}
@stop

@section('content')
    <form action="{{ route('cci.store', $patientId) }}" method="POST" class="form-horizontal store_cci" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.charlsons.form')
        <button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.add_button') }}</button>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
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
