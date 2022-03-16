@extends('admin.default')

@section('page-header')
    <a href="{{ route('deaths.patients') }}"> {{ trans('app.deaths') }} </a> <i class="ti-angle-right"></i> {{trans('app.create_death')}}
@stop

@section('content')
    <form action="{{ route('death.store') }}" method="POST" class="form-horizontal store_death" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.deaths.form')
        <button type="submit" class="btn btn-primary btn-save-user">{{ trans('app.add_button') }}</button>

    </form>

@stop

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $.datetimepicker.setLocale('el');
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

            var selectPatientData = {
                escapeMarkup: function (text) { return text; },
                minimumInputLength: -1,
                ajax:{
                    url: '{{ route('patients') }}',
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
            initSelect2("#patient_id", "Επιλέξτε ασθενή", selectPatientData);
        });

       /* var createDeathForm = $('form.store_death');
        $("form.store_death button[type=submit]").click(function() {
            $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
            $(this).attr("clicked", "true");
        });

        createDeathForm.on('submit', function(e) {
            e.preventDefault();
                var timestamp = Date.parse($('#date').val());
                var minTs = Date.parse($('#date').data("maxdate"));
                console.log(timestamp);
                console.log(minTs);
                if(isNaN(timestamp) || timestamp > minTs){
                    $('#date').parsley().addError('maxdate-error', {message: "Η ημερομηνία δεν μπορεί να είναι μεταγενέστερη της <?php echo date('d/m/y')?>"});
                }else{
                    $('#date').parsley().removeError('maxdate-error');
                }
        });*/
    </script>
@endpush
