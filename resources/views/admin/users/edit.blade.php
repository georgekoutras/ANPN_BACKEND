@extends('admin.default')

@section('page-header')
    @if($loggedId == $data->id)
        {{ trans('app.edit_button').' '.$title }}
    @else
        <a href="{{ route('accounts.index') }}">
            @if($loggedRole == 'administrator')
                {{ trans('app.accounts') }}
            @elseif($loggedRole == 'doctor')
                {{ trans('app.patients') }}
            @endif
        </a> <i class="ti-angle-right"></i>  {{ trans('app.edit_button').' '.$title }}
    @endif

@stop

@section('content')
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @include('admin.users.change-password-layout')
            </div>
        </div>
    </div>
    <form action="{{ route('accounts.update', $data->id) }}" method="POST" class="form-horizontal edit_user" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @if($role == 'administrator')
            @include('admin.users.admin-form')
        @elseif($role == 'doctor')
            @include('admin.users.doctor-form')
        @elseif($role == 'patient')
            @include('admin.users.patient-form')
        @endif
        <button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.save_button') }}</button>
        <a class="btn btn-outline-info" data-toggle="modal" href="" data-target="#myModal">{{ trans('app.change_password') }}</a>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            <?php if($loggedRole == 'administrator'){ ?>
                $('#doctor_id').select2();
            <?php } ?>
            $('#email').prop('disabled',true);

            fillInAccount(<?php echo json_encode($data);?>, $('form.edit_user'), "<?php echo $loggedRole ?>");

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

            var createUserForm = $('form.store_user');
            $("form.store_user button[type=submit]").click(function() {
                $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
                $(this).attr("clicked", "true");
            });

            createUserForm.on('submit', function(e){

                if($('form.store_user').parsley().validate()){

                    if($('#password').val() !== $('#repeat_password').val()){
                        $('#repeat_password').parsley().removeError("error");
                        $('#repeat_password').parsley().addError("error", {message: "Ο κωδικός επαλήθευσης δεν ταιριάζει με τον πρώτο κωδικό.", updateClass: true});
                        e.preventDefault();
                    }else{
                        $('#repeat_password').parsley().removeError("error");
                    }
                }else{
                    e.preventDefault();
                }
            });

        });
    </script>
@endpush

