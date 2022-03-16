@extends('admin.default')

@section('page-header')
    {{ trans('app.deaths') }} {{--<small>{{ trans('app.manage') }}</small>--}}
@endsection
@section('content')
    <div class="mB-20 d-inline-block">
        <a href="{{ route('deaths.create') }}" class="btn btn-primary">
            {{ trans('app.create_death') }}
        </a>
    </div>
    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>{{ trans('app.name') }}</th>
                    <th>{{ trans('app.date') }}</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td><a href="{{ route('death.edit', [$item->patient_id,$item->death_id]) }}"> {{ $item->first_name.' '.$item->last_name }}</a></td>
                        <td> {{ $item->date }} </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    {!! Form::open([
                                        'class'=>'edit',
                                        'url'  => route('death.edit', [$item->patient_id,$item->death_id]),
                                        'id'    => $item->id,
                                        'method' => 'GET',
                                        ])
                                    !!}

                                    <button  class="btn btn-edit btn-primary btn-sm" title="{{ trans('app.edit_title') }}"><i class="ti-pencil"></i></button>
                                    {!! Form::close() !!}

                                </li>
                                <li class="list-inline-item">
                                    {!! Form::open([
                                        'class'=>'delete',
                                        'url'  => route('death.destroy', [$item->patient_id,$item->death_id]),
                                        'id'    => $item->id,
                                        'method' => 'DELETE',
                                        ])
                                    !!}

                                    <button class="btn btn-danger btn-sm" title="{{ trans('app.delete_title') }}" data-msg="{{trans('app.delete_msg',['obj'=>"το θάνατο ",'name'=>$item->full_name])}}"><i class="ti-trash"></i></button>

                                    {!! Form::close() !!}
                                </li>
                            </ul>
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
