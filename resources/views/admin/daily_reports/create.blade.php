@extends('admin.default')

@section('page-header')
    @if($my_reports)
        {{trans('app.add_daily_report')}}
    @else
        <a href="{{ route('daily_reports.patients') }}"> {{ trans('app.daily_reports') }} </a> <i class="ti-angle-right"></i> <a href="{{ route('daily_reports.patients.reports', $patientId) }}"> {{ $patientName }} </a> <i class="ti-angle-right"></i> {{trans('app.add_daily_report')}}
    @endif
@stop

@section('content')
    <form action="{{ route('daily_reports.store', $patientId) }}" method="POST" class="form-horizontal store_user" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.daily_reports.form')
        <button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.add_button') }}</button>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.q1').on('click',function (e){
                if(this.value == '1'){
                    $('.q1a-group').removeAttr('hidden');
                    $('.q1b-group').removeAttr('hidden');
                    $('.q1c-group').removeAttr('hidden');

                }else{
                    $('.q1a-group').attr('hidden',true);
                    $('.q1b-group').attr('hidden',true);
                    $('.q1c-group').attr('hidden',true);
                }
            });

            $('.q3').on('click',function (e){
                if(this.value == '1'){
                    $('.q3a-group').removeAttr('hidden');
                    $('.q3b-group').removeAttr('hidden');
                    $('.q3c-group').removeAttr('hidden');

                }else{
                    $('.q3a-group').attr('hidden',true);
                    $('.q3b-group').attr('hidden',true);
                    $('.q3c-group').attr('hidden',true);
                }
            });
            $('#sex').select2({minimumResultsForSearch:-1});
            var selectDoctorData = {
                escapeMarkup: function (text) { return text; },
                minimumInputLength: -1,
                ajax:{
                    url: '{{ route('doctors') }}',
                    data: function (params){
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function (data){
                        var res = [];
                        $.each(data, function( key, value ) {
                            var object ={
                                id: value['id'],
                                text: value['full_name']
                            };
                            res.push(object);
                        });
                        return { results: res };
                    }
                }
            };
            initSelect2("#doctor_id", "Επιλέξτε ιατρό", selectDoctorData);

            $.datetimepicker.setLocale('el');
            $('#birth_date').datetimepicker({
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
            $('#first_diagnose_date').datetimepicker({
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
