@extends('admin.default')

@section('page-header')
    @if($my_notifications)
        {{ trans('app.my_notifications') }}
    @else
        <a href="{{ route('notifications.accounts') }}"> {{ trans('app.notifications') }} </a> <i class="ti-angle-right"></i>
        {{ $accountName }}
    @endif
@endsection
@section('content')
    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>{{ trans('app.date') }}</th>
                    <th>{{ trans('app.text') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($items as $item)
                    <tr>
<!--                        <td><a href=""> {{ $item->created_at}} </a></td>-->
                        <td> <b>{{ $item->created_at}}</b> </td>
                        <td> {{ $item->notification_message}} </td>

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
        });

    </script>
@endpush
