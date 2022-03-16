@extends('admin.default')

@section('page-header')
    @if($role == 'administrator')
        {{ trans('app.accounts') }}
    @elseif($role == 'doctor')
        {{ trans('app.patients') }}
    @endif
@endsection

@section('content')
    @if($role == 'administrator')
        <div class="mB-20 d-inline-block">
            <a href="{{ route('accounts.create','administrator') }}" class="btn btn-success">
                {{ trans('app.add_button_admin') }}
            </a>
        </div>
        <div class="mB-20 d-inline-block">
            <a href="{{ route('accounts.create','doctor') }}" class="btn btn-info">
                {{ trans('app.add_button_doctor') }}
            </a>
        </div>
    @endif

    <div class="mB-20 d-inline-block">
        <a href="{{ route('accounts.create','patient') }}" class="btn btn-primary">
            {{ trans('app.add_button_patient') }}
        </a>
    </div>

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
                            <td><a href="{{ route('accounts.edit', $item->id) }}"> {{ $item->first_name.' '.$item->last_name }} </a></td>
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
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        {!! Form::open([
                                            'class'=>'edit',
                                            'url'  => route('accounts.edit', $item->id),
                                            'id'    => $item->id,
                                            'method' => 'GET',
                                            ])
                                        !!}

                                            <button  class="btn btn-edit btn-primary btn-sm" title="{{ trans('app.edit_title') }}"><i class="ti-pencil"></i></button>
                                        {!! Form::close() !!}

                                    </li>
                                    @if(auth()->user()->id !== $item->id)
                                    <li class="list-inline-item">
                                        {!! Form::open([
                                            'class'=>'delete',
                                            'url'  => route('accounts.destroy', $item->id),
                                            'id'    => $item->id,
                                            'method' => 'DELETE',
                                            ])
                                        !!}

                                            <button class="btn btn-danger btn-sm" title="{{ trans('app.delete_title') }}" data-msg="{{trans('app.delete_msg',['obj'=>"το χρήστη",'name'=>$item->first_name.' '.$item->last_name])}}"><i class="ti-trash"></i></button>

                                        {!! Form::close() !!}
                                    </li>
                                    @endif
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

                if($(this).text() != "") {
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
            } );

            function removeTags(txt){
                var rex = /(<([^>]+)>)/ig;
                return txt.replace(rex , "");

            }
        });

    </script>
@endpush
