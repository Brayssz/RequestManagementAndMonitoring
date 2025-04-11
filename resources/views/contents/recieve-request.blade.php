@extends('layouts.app-layout')

@section('title', 'Requests Management')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Requests Management</h4>
                    <h6>Manage your requests</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw"
                            class="feather-rotate-ccw"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>
            <div class="page-btn">
                <a class="btn btn-added add-request"><i data-feather="plus-circle" class="me-2"></i>Add New Request</a>
            </div>
        </div>
        <div class="card table-list-card">
            <div class="card-body pb-0">
                <div class="table-top table-top-two table-top-new d-flex">
                    <div class="search-set mb-0 d-flex w-100 justify-content-start">

                        <div class="search-input text-left">
                            <a href="" class="btn btn-searchset"><i data-feather="search"
                                    class="feather-search"></i></a>
                        </div>

                        <div class="row mt-sm-3 mt-xs-3 mt-lg-0 w-sm-100 flex-grow-1">
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <select class="select status_filter form-control">
                                        <option value="">Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table requests-table pb-3">
                        <thead>
                            <tr>
                                <th>DTS Tracker Number</th>
                                <th>DTS Date</th>
                                <th>SGOD Date Received</th>
                                <th>Requesting Office</th>
                                <th>Nature of Request</th>
                                <th>Amount</th>
                                <th>Utilize Funds</th>
                                <th>Fund Source</th>
                                <th>Allotment</th>
                                <th>Signed Chief Date</th>
                                <th>Date Transmitted</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
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

    @livewire('contents.receive-request')
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

            if ($('.requests-table').length > 0) {
                var table = $('.requests-table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    'pagingType': 'numbers',
                    "ordering": true,
                    "order": [
                        [0, 'desc']
                    ],
                    "language": {
                        search: ' ',
                        sLengthMenu: '_MENU_',
                        searchPlaceholder: "Search...",
                        info: "_START_ - _END_ of _TOTAL_ items",
                    },
                    "ajax": {
                        "url": "/recieve-requests",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function(d) {
                            d.status = $('.status_filter').val();
                        },
                        "dataSrc": "data"
                    },
                    "columns": [
                        { "data": "dts_tracker_number" },
                        { "data": "dts_date" },
                        { "data": "sgod_date_received" },
                        { 
                            "data": "requesting_office.name", 
                            "render": function(data, type, row) {
                                return data || 'N/A';
                            } 
                        },
                        { "data": "nature_of_request" },
                        { 
                            "data": "amount", 
                            "render": function(data, type, row) {
                                return data ? `₱ ${parseFloat(data).toLocaleString('en-US')}` : '₱ 0';
                            } 
                        },
                        { "data": "utilize_funds" },
                        { "data": "fund_source.name" },
                        { "data": "allotment.year" },
                        { "data": "signed_chief_date" },
                        { "data": "date_transmitted" },
                        { "data": "remarks" },
                        { "data": "status" },
                        { 
                            "data": null, 
                            "render": function(data, type, row) {
                                return `
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2 edit-request" data-requestid="${row.request_id}">
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                    </div>
                                `;
                            } 
                        }
                    ],
                    "createdRow": function(row, data, dataIndex) {
                        $(row).find('td').eq(13).addClass('action-table-data');
                    },
                    "initComplete": function(settings, json) {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                        feather.replace();

                        $('.status_filter').on('change', function() {
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
