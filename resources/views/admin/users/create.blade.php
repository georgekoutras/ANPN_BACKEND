@extends('admin.default')

@section('page-header')
    <a href="{{ route('accounts.index') }}">
        @if($loggedRole == 'administrator')
            {{ trans('app.accounts') }}
        @elseif($loggedRole == 'doctor')
            {{ trans('app.patients') }}
        @endif
    </a> <i class="ti-angle-right"></i>  {{ trans('app.add_button').' '.$title }} {{--<small>{{ trans('app.add_new_item') }}</small>--}}
@stop

@section('content')
    <form action="{{ route('accounts.store') }}" method="POST" class="form-horizontal store_user" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @if($role == 'administrator')
            @include('admin.users.admin-form')
        @elseif($role == 'doctor')
            @include('admin.users.doctor-form')
        @elseif($role == 'patient')
            @include('admin.users.patient-form')
        @endif
		<button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.add_button') }}</button>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.notification-enabled').on('click',function (e){
                if(this.value == '1'){
                    $('.notification-mode').removeAttr('hidden');
                }else{
                    $('.notification-mode').attr('hidden',true);
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
            <?php if($loggedRole == 'administrator'){ ?>
                initSelect2("#doctor_id", "Επιλέξτε ιατρό", selectDoctorData);
            <?php } ?>

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
            <?php if($loggedRole == 'administrator'){ ?>
            initSelect2("#doctor_id", "Επιλέξτε ιατρό", selectDoctorData);
            <?php } ?>

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
