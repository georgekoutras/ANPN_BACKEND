@extends('admin.default')

@section('page-header')
    {{ trans('app.notifications') }} {{--<small>{{ trans('app.manage') }}</small>--}}
@endsection
@section('content')
    @if($loggedRole !== 'administrator')
        <div class="mB-20 d-inline-block">
            <a href="{{ route('notifications.accounts.list', $loggedId) }}" class="btn btn-success">
                {{ trans('app.my_notifications') }}
            </a>
        </div>
    @endif
    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>{{ trans('app.name') }}</th>
                    <th>{{ trans('app.email') }}</th>
                    <th>{{ trans('app.type') }}</th>
                    <th></th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th>{{ trans('app.name') }}</th>
                    <th>{{ trans('app.email') }}</th>
                    <th>{{ trans('app.type') }}</th>
                    <th></th>
                </tr>
                </tfoot>

                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td><a href="{{ route('notifications.accounts.list', $item->id) }}"> {{ $item->first_name.' '.$item->last_name }} </a></td>
                        <td> {{ $item->email }} </td>
                        <td>
                            @if($item->role == 'administrator')
                                {{ 'Διαχειριστής' }}
                            @elseif($item->role == 'doctor')
                                {{ 'Ιατρός' }}
                            @else
                                {{ 'Ασθενής' }}
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-edit btn-primary btn-sm" href="{{ route('notifications.accounts.list', $item->id) }}" title="{{ trans('app.notifications') }}"> <i class="ti-tag"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function(){
            var table = $('#dataTable').DataTable();

            $("#dataTable tfoot th").each( function ( i ) {

                if($(this).text() != "" && $(this).text() != "Ρόλος") {
                    var select = $('<select><option value="">Αναζήτηση..</option></select>')
                        .appendTo($(this).empty())
                        .on('change', function () {
                            var val = $(this).val();

                            table.column(i)
                                .search(val ? '^' + $(this).val() + '$' : val, true, false)
                                .draw();
                        });

                    table.column(i).data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + removeTags(d) + '">' + removeTags(d) + '</option>')
                    });
                    select.select2();

                }

                if($(this).text() == "Ρόλος") {
                    $(this).text("");
                }
            } );

            function removeTags(txt){
                var rex = /(<([^>]+)>)/ig;
                return txt.replace(rex , "");

            }
        });

    </script>
@endpush
