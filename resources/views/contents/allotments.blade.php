@extends('layouts.app-layout')

@section('title', 'Annual Allotments')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Annual Allotments</h4>
                    <h6>Manage your annual allotments</h6>
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
                <a class="btn btn-added add-allotment"><i data-feather="plus-circle" class="me-2"></i>Add New
                    Annual Allotment</a>
            </div>
        </div>
        <!-- /allotments list -->
        <div class="card table-list-card">
            <div class="card-body pb-0">
                <div class="table-top table-top-two table-top-new d-flex">
                    <div class="search-set mb-0 d-flex w-100 justify-content-start">

                        <div class="search-input text-left">
                            <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                        </div>

                        <div class="row mt-sm-3 mt-xs-3 mt-lg-0 w-sm-100 flex-grow-1">
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <select class="select year_filter form-control">
                                        <option value="">Year</option>
                                        @foreach (range(date('Y'), date('Y') - 50) as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                          
                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table allotments-table pb-3">
                        <thead>
                            <tr>
                                <th>Office | School</th>
                                <th>Assigned Personnel</th>
                                <th>Year</th>
                                <th>Fund Source</th>
                                <th>Amount</th>
                                <th>Balance</th>
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

    @livewire('contents.allotment-management')
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            @if (session('message'))
                toastr.success("{{ session('message') }}", "Success", {
                    closeButton: true,
                    progressBar: true,
                });
            @endif

            if ($('.allotments-table').length > 0) {
                var table = $('.allotments-table').DataTable({
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
                        "url": "/allotments",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function (d) {
                            d.year = $('.year_filter').val();
                            d.status = $('.status_filter').val();
                        },
                        "dataSrc": "data"
                    },
                    "columns": [
                        { 
                            "data": "requesting_office.name",
                            "render": function (data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        { 
                            "data": "requesting_office",
                            "render": function (data, type, row) {
                                const requestorName = data && data.requestor_obj && data.requestor_obj.name ? data.requestor_obj.name : 'N/A';
                                const requestorPosition = data && data.requestor_obj && data.requestor_obj.position 
                                    ? (data.requestor_obj.position === 'holder' ? 'Program Holder' : data.requestor_obj.position) 
                                    : 'N/A';
                                return `
                                    <div class="userimgname">
                                        <div>
                                            <a href="javascript:void(0);">${requestorName}</a>
                                            <span class="emp-team text-muted">${requestorPosition}</span>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        { "data": "year" },
                        { 
                            "data": "fund_source.name",
                            "render": function (data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        { 
                            "data": "amount",
                            "render": function (data, type, row) {
                                if (data) {
                                    const formattedAmount = `₱ ${parseFloat(data).toLocaleString('en-US')}`;
                                    const amountInWords = numberToWords.toWords(parseFloat(data)).replace(/,/g, '') + ' pesos';
                                    return `
                                        <div class="userimgname">
                                            <div>
                                                <a href="javascript:void(0);">${formattedAmount}</a>
                                                <span class="emp-team text-muted">${amountInWords}</span>
                                            </div>
                                        </div>
                                    `;
                                }
                                return `
                                    <div class="userimgname">
                                        <div>
                                            <a href="javascript:void(0);">₱ 0</a>
                                            <span class="emp-team">zero pesos</span>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        { 
                            "data": "balance",
                            "render": function (data, type, row) {
                                if (data) {
                                    const formattedBalance = `₱ ${parseFloat(data).toLocaleString('en-US')}`;
                                    const balanceInWords = numberToWords.toWords(parseFloat(data)).replace(/,/g, '') + ' pesos';
                                    return `
                                        <div class="userimgname">
                                            <div>
                                                <a href="javascript:void(0);">${formattedBalance}</a>
                                                <span class="emp-team text-muted">${balanceInWords}</span>
                                            </div>
                                        </div>
                                    `;
                                }
                                return `
                                    <div class="userimgname">
                                        <div>
                                            <a href="javascript:void(0);">₱ 0</a>
                                            <span class="emp-team">zero pesos</span>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        {
                            "data": null,
                            "render": function (data, type, row) {
                                return `
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2 edit-allotment" data-allotmentid="${row.allotment_id}">
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                    </div>
                                `;
                            }
                        }
                    ],
                    "createdRow": function (row, data, dataIndex) {
                        $(row).find('td').eq(6).addClass('action-table-data');
                    },
                    "initComplete": function (settings, json) {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                        feather.replace();

                        $('.year_filter').on('change', function () {
                            table.draw();
                        });

                        $('.status_filter').on('change', function () {
                            table.draw();
                        });
                        initTippy();
                    },
                    "drawCallback": function (settings) {
                        feather.replace();
                        initTippy();
                    },
                });
            }

            const initTippy = () => {
                tippy('.edit-allotment', {
                    content: "Edit Allotment",
                });
               
            };

        });
    </script>
@endpush
