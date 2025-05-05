@extends('layouts.app-layout')

@section('title', 'Monthly Summary Report')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Monthly Summary Report</h4>
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
                                    <select class="select year_filter form-control">
                                        <option value="">Filter by Allotment Year</option>
                                        @foreach (range(date('Y'), date('Y') - 50) as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
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
                                    <select class="select office_filter form-control">
                                        <option value="">Filter by Requesting Office/School</option>
                                        @foreach ($offices_schools as $office_school)
                                            <option value="{{ $office_school->requesting_office_id }}">{{ $office_school->name }}</option>
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
                                <th style="background-color: #f0f0f0;" rowspan="2">School Name</th>
                                <th style="background-color: #f0f0f0;" rowspan="2">Fund Source</th>
                                <th style="background-color: #f0f0f0;" rowspan="2">Allotment Year</th>
                                <th style="background-color: #f0f0f0;" colspan="3">Quarter 1</th>
                                <th style="background-color: #f0f0f0;" colspan="3">Quarter 2</th>
                                <th style="background-color: #f0f0f0;" colspan="3">Quarter 3</th>
                                <th style="background-color: #f0f0f0;" colspan="3">Quarter 4</th>
                                <th style="background-color: #f0f0f0;" rowspan="2">Total Amount</th>
                            </tr>
                            <tr>
                                <th style="background-color: #f0f0f0;">January</th>
                                <th style="background-color: #f0f0f0;">February</th>
                                <th style="background-color: #f0f0f0;">March</th>
                                <th style="background-color: #f0f0f0;">April</th>
                                <th style="background-color: #f0f0f0;">May</th>
                                <th style="background-color: #f0f0f0;">June</th>
                                <th style="background-color: #f0f0f0;">July</th>
                                <th style="background-color: #f0f0f0;">August</th>
                                <th style="background-color: #f0f0f0;">September</th>
                                <th style="background-color: #f0f0f0;">October</th>
                                <th style="background-color: #f0f0f0;">November</th>
                                <th style="background-color: #f0f0f0;">December</th>
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
                window.open('/summary-report-pdf?year=' + year + '&fund_source_id=' + fund_source_id, '_blank');
            });

            if ($('.report-table').length > 0) {
                var table = $('.report-table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bFilter": false, // Disable the search input
                    "sDom": 'Btlpi', // Remove the search input from the DOM
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
                        "url": "/summary-report",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function(d) {
                            d.year = $('.year_filter').val();
                            d.fund_source_id = $('.fund_source_filter').val();
                            d.requesting_office_id = $('.office_filter').val();
                        },
                        "dataSrc": "data"
                    },
                    "columns": [
                        {
                            "data": "school_name"
                        },
                        {
                            "data": "fund_source"
                        },
                        {
                            "data": "year"
                        },
                        {
                            "data": "monthly_request_amount.January",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.January ? `₱ ${parseFloat(row.monthly_request_amount.January).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.February",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.February ? `₱ ${parseFloat(row.monthly_request_amount.February).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.March",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.March ? `₱ ${parseFloat(row.monthly_request_amount.March).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.April",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.April ? `₱ ${parseFloat(row.monthly_request_amount.April).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.May",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.May ? `₱ ${parseFloat(row.monthly_request_amount.May).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.June",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.June ? `₱ ${parseFloat(row.monthly_request_amount.June).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.July",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.July ? `₱ ${parseFloat(row.monthly_request_amount.July).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.August",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.August ? `₱ ${parseFloat(row.monthly_request_amount.August).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.September",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.September ? `₱ ${parseFloat(row.monthly_request_amount.September).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.October",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.October ? `₱ ${parseFloat(row.monthly_request_amount.October).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.November",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.November ? `₱ ${parseFloat(row.monthly_request_amount.November).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "monthly_request_amount.December",
                            "render": function(data, type, row) {
                                return row.monthly_request_amount && row.monthly_request_amount.December ? `₱ ${parseFloat(row.monthly_request_amount.December).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '-';
                            }
                        },
                        {
                            "data": "total_amount",
                            "render": function(data) {
                                return `₱ ${parseFloat(data).toLocaleString('en-US')}`;
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

                        $('.year_filter, .fund_source_filter, .office_filter').on('change', function() {
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
