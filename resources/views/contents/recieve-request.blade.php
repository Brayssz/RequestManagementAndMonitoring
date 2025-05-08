@extends('layouts.app-layout')

@section('title', 'Requests Management')

@section('content')

    <div class="content mx-3">
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
                                        <option value="transmitted">Transmitted</option>
                                        <option value="returned">Returned</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table requests-table pb-3 fs-14">
                        <thead>
                            <tr>
                                <th>DTS Tracker Number</th>
                                <th>DTS Date</th>
                                <th>Requesting Office</th>
                                <th>Nature of Request</th>
                                <th>Amount</th>
                                <th>Fund Source</th>
                                <th>Allotment Year</th>
                                <th>Status</th>
                                <th>Signed Chief</th>
                                <th>Transmitted Office</th>
                                <th>Remarks</th>
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
    @livewire('contents.transmit-request')

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
                        "url": "/receive-requests",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function(d) {
                            d.status = $('.status_filter').val();
                        },
                        "dataSrc": "data"
                    },
                    "columns": [{
                            "data": "dts_tracker_number"
                        },
                        {
                            "data": "dts_date",
                            "render": function(data, type, row) {
                                return data ? moment(data).format('MMMM D, YYYY') : 'N/A';
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                const requestingOfficeName = row.requesting_office?.name || 'N/A';
                                const sgodDateReceived = row.sgod_date_received ? moment(row.sgod_date_received).format('MMMM D, YYYY') : 'N/A';
                                return `
                                    <div class="userimgname">
                                        <div>
                                            <a href="javascript:void(0);">${requestingOfficeName}</a>
                                            <span class="emp-team text-muted">${sgodDateReceived}</span>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        {
                            "data": "nature_of_request"
                        },
                        {
                            "data": "amount",
                            "render": function(data, type, row) {
                                return data ? `₱ ${parseFloat(data).toLocaleString('en-US')}` :
                                    '₱ 0';
                            }
                        },
                        {
                            "data": "fund_source.name"
                        },
                        {
                            "data": "allotment_year"
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                if (row.status === "pending") {
                                    return `<span class="badge badge-linewarning">Pending</span>`;
                                } else if (row.status === "transmitted") {
                                    return `<span class="badge badge-linesuccess">Transmitted</span>`;
                                } else if (row.status === "returned") {
                                    return `<span class="badge badge-lineinfo">Returned</span>`;
                                }
                                return `<span class="badge badge-lineinfo">Unknown</span>`;
                            }
                        },
                        {
                            "data": "signed_chief_date",
                            "render": function(data, type, row) {
                                return data ? moment(data).format('MMMM D, YYYY') : 'Not yet transmitted | Signed';
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                const transmittedOfficeName = row.transmitted_office?.name ||
                                    'Not yet transmitted';
                                const transmittedDate = row.date_transmitted ?
                                    moment(row.date_transmitted).format('MMMM D, YYYY') :
                                    'Not yet transmitted';

                                return `
                                    <div class="userimgname">
                                        <div>
                                            <a href="javascript:void(0);">${transmittedOfficeName}</a>
                                            <span class="emp-team text-muted">${transmittedDate}</span>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        {
                            "data": "remarks",
                            "render": function(data, type, row) {
                                return data || 'Not yet transmitted';
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                return `
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2 edit-request" data-requestid="${row.request_id}">
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                        <a class="me-2 p-2 delete-request" data-requestid="${row.request_id}">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                        <a class="me-2 p-2 return-request" data-requestid="${row.request_id}">
                                            <i data-feather="corner-up-left" class="feather-corner-up-left"></i>
                                        </a>
                                        <a class="me-2 p-2 transmit-request" data-requestid="${row.request_id}">
                                            <i data-feather="send" class="feather-send"></i>
                                        </a>
                                    </div>
                                `;
                            }
                        }
                    ],
                    "createdRow": function(row, data, dataIndex) {
                        $(row).find('td').eq(11).addClass('action-table-data');
                    },
                    "initComplete": function(settings, json) {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                        feather.replace();

                        $('.status_filter').on('change', function() {
                            table.draw();
                        });

                        initTippy();
                    },
                    "drawCallback": function(settings) {
                        feather.replace();
                        initTippy();
                    },
                });
            }

            const initTippy = () => {
                tippy('.edit-request', {
                    content: "Edit Request",
                });
                tippy('.transmit-request', {
                    content: "Transmit Request",
                });

                tippy('.return-request', {
                    content: "Return Request",
                });
                tippy('.delete-request', {
                    content: "Delete Request",
                });
            };

        });
    </script>
@endpush
