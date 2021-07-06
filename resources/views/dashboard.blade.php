@extends('layouts.app')
@section('top-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

@section('content')
    <div class="container-fluid">
        <div class="card">
            <form id="searchForm">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="platform"><b>Platform</b></label>
                                <select id="platform" name="platform" class="form-control" style="width: 100%;">
                                    <option value="">All</option>
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->PlatformType }}">{{ $platform->PlatformType }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">

                    </div>

                    <div class="col-md-4">

                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card-body">
                            <span>
                                <input id="keyword" class="form-control" type="text" name="keyword" placeholder="Search By Keyword" style="width: 100%;"/>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card-body">
                            <span>
                                <i class="fa fa-calendar"></i>
                                <input id="startDate" class="form-control" type="text" name="start_date" placeholder="Start Date" style="width: 50%;"/>
                                To
                                <input id="endDate" class="form-control" type="text" name="end_date" placeholder="End Date" style="width: 50%;"/>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card-body">
                            <span>
                                <input id="startTime" class="form-control @error('start_time') is-invalid @enderror" type="text" name="start_time" placeholder="From Time" style="width: 50%;"/>
                                To
                                <input id="endTime" class="form-control @error('end_time') is-invalid @enderror" type="text" name="end_time" placeholder="To Date" style="width: 50%;"/>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-success" id="submit">Search</button>
                    </div>
                </div>
            </form>
            <div class="col-md-12">
                <div id="allData" class="table-responsive"></div>
            </div>
            <div class="col-md-12">
                <div id="allData2" class="table-responsive"></div>
            </div>
        </div>
    </div>
@endsection

@section('bottom-js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script type="text/javascript">
        $(document).ready(function(){
            $('#startDate').flatpickr({
                dateFormat: "d-M-Y",
            });
            $('#endDate').flatpickr({
                dateFormat: "d-M-Y",
            });

            $('#startTime').flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i:ss",
                time_24hr: true
            });

            $('#endTime').flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i:ss",
                time_24hr: true
            });

            $.ajax({
                url: "{{ route('allData') }}",
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('#allData').html(data);
                    $('#datatable').DataTable( {
                        "searching": false,
                        buttons: ["copy", "csv", "excel", "pdf", "print"]
                    });
                },
                error : function() {
                    alert("Data Not Found");
                }
            });


        });

        $(function()
        {
            $('#searchForm').submit(function (e)
            {
                $('#datatable').DataTable().destroy();
                if (!e.isDefaultPrevented())
                {
                    $.ajax({
                        url : "{{ url('search') }}",
                        type : "POST",
                        dataType: "JSON",
                        data: new FormData($("form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data)
                        {
                            $('#allData').html(data);
                            $('#datatable').DataTable( {
                                "searching": false,
                                buttons: ["copy", "csv", "excel", "pdf", "print"]
                            });
                        },
                        error : function(data)
                        {
                            alert(data.responseJSON.message);
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endsection
