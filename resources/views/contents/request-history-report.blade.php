@extends('layouts.app-layout')

@section('title', 'Request History Report')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Request History Report</h4>
                    <h6>Overview of fund allocations and utilization</h6>
                </div>
            </div>
            <div class="page-btn">
                <a class="btn btn-added btn-generate"><i data-feather="plus-circle" class="me-2"></i>Generate/Print Report</a>
            </div>
        </div>
        <div class="card table-list-card">
            <div class="card-body pb-0">
                <div class="table-top table-top-two table-top-new d-flex">
                    <div class="search-set mb-0 d-flex w-100 justify-content-start">

                        <div class="row mt-sm-3 mt-xs-3 mt-lg-0 w-sm-100 flex-grow-1">

                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <select class="select office_filter form-control">
                                        <option value="">Filter by Requesting Office/School</option>
                                        @foreach ($offices_schools as $office_school)
                                            <option value="{{ $office_school->requesting_office_id }}">{{ $office_school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <select class="select month_filter form-control">
                                        <option value="">Filter by Month</option>
                                        @foreach (range(1, 12) as $month)
                                            <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <select class="select year_filter form-control">
                                        <option value="">Filter by Year</option>
                                        @foreach (range(date('Y'), date('Y') - 50) as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <select class="select fund_source_filter form-control">
                                        <option value="">Filter by Fund Source</option>
                                        @foreach ($fund_sources as $fund_source)
                                            <option value="{{ $fund_source->fund_source_id }}">{{ $fund_source->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <select class="select transmitted_filter form-control">
                                        <option value="">Filter by Transmitted Office</option>
                                        @foreach ($offices as $office)
                                            <option value="{{ $office->requesting_office_id }}">{{ $office->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table report-table pb-3 fs-14 table-bordered" style="border-color: #343a40;">
                        <thead>
                           
                            <tr>
                                <th style="background-color: #f0f0f0;">DTS Date</th>
                                <th style="background-color: #f0f0f0;">DTS Tracker No.</th>
                                <th style="background-color: #f0f0f0;">SGOD Date Received</th>
                                <th style="background-color: #f0f0f0;">Requesting School/Office</th>
                                <th style="background-color: #f0f0f0;">Requestor</th>
                                <th style="background-color: #f0f0f0;">Fund Source</th>
                                <th style="background-color: #f0f0f0;">Amount</th>
                                <th style="background-color: #f0f0f0;">Utilized Amount</th>
                                <th style="background-color: #f0f0f0;">Nature of Request</th>
                                <th style="background-color: #f0f0f0;">Date Transmitted</th>
                                <th style="background-color: #f0f0f0;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            @if (session('message'))
                toastr.success("{{ session('message') }}", "Success", {
                    closeButton: true,
                    progressBar: true,
                });
            @endif

            $('.btn-generate').on('click', function () {
                var year = $('.year_filter').val();
                var fund_source_id = $('.fund_source_filter').val();
                var month = $('.month_filter').val();
                window.open('/request-history-report-pdf?year=' + year + '&fund_source_id=' + fund_source_id + '&month=' + month, '_blank');
            });

            if ($('.report-table').length > 0) {
                var table = $('.report-table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    'pagingType': 'numbers',
                    "ordering": true,
                    "order": [
                        [0, 'desc']
                    ],
                    "columnDefs": [
                        { "orderable": false, "targets": '_all' } // Disable sorting for all columns
                    ],
                    "language": {
                        search: ' ',
                        sLengthMenu: '_MENU_',
                        searchPlaceholder: "Search...",
                        info: "_START_ - _END_ of _TOTAL_ items",
                    },
                    "ajax": {
                        "url": "/request-history-report",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function(d) {
                            d.month = $('.month_filter').val();
                            d.fund_source_id = $('.fund_source_filter').val();
                            d.requesting_office_id = $('.office_filter').val();
                            d.transmitted_office_id = $('.transmitted_filter').val();
                            d.year = $('.year_filter').val();
                        },
                        "dataSrc": "data"
                    },
                    "columns": [
                        { "data": "dts_date" },
                        { "data": "dts_tracker_number" },
                        { "data": "sgod_date_received" },
                        { "data": "requesting_office" },
                        { "data": "requestor" },
                        { "data": "fund_source" },
                        { 
                            "data": "amount",
                            "render": function(data) {
                                return `₱ ${parseFloat(data).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                            }
                        },
                        { 
                            "data": "utilize_amount",
                            "render": function(data) {
                                return `₱ ${parseFloat(data).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                            }
                        },
                        { "data": "nature_of_request" },
                        { 
                            "data": "date_transmitted",
                            "render": function(data) {
                                return data ? data : '-';
                            }
                        },
                        { 
                            "data": "remarks",
                            "render": function(data) {
                                return data ? data.replace(/\n/g, '<br>') : '-';
                            }
                        }
                    ],
                    "createdRow": function(row, data, dataIndex) {
                        $(row).find('td').eq(6).addClass('action-table-data');
                    },
                    "initComplete": function(settings, json) {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                        feather.replace();

                        $('.month_filter, .fund_source_filter, .year_filter, .office_filter, .transmitted_filter').on('change', function() {
                            table.draw();
                        });
                    },
                    "drawCallback": function(settings) {
                        feather.replace();
                    },
                });
            }
        });
    </script>
@endpush
