@extends('layouts.layout')

<div id="chart-div"></div>
<?= $lava->render('ScatterChart', 'YearSex', 'chart-div') ?>

@section('content')

    <div id="my-dash">
        <div id="chart">
        </div>
        <div id="control">
        </div>
    </div>
    <?= $lava->render('Dashboard', 'Donuts', 'my-dash'); ?> <br> <br> <br> <br>

    <table class="table table-bordered" id="users-table">
        <thead>
        <tr>
            <th>Id</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Bdate</th>
            <th>Sex</th>
            <th>Country</th>
        </tr>
        </thead>
    </table>
@stop

@push('scripts')
<script>
    $(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('test') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name'},
                { data: 'bdate', name: 'bdate' },
                { data: 'sex', name: 'sex' },
                { data: 'country', name: 'country' }
            ]
        });
    });
</script>
@endpush