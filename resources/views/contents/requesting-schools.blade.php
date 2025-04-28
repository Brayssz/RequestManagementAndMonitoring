@extends('layouts.app-layout')

@section('title', 'Requesting Schools Management')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Requesting Schools</h4>
                    <h6>Manage your requesting schools</h6>
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
                <a class="btn btn-added add-office"><i data-feather="plus-circle" class="me-2"></i>Add New
                    School</a>
            </div>
        </div>
        <!-- /requesting offices list -->
        <div class="card table-list-card">
            <div class="card-body pb-0">
                <div class="table-top table-top-two table-top-new d-flex ">
                    <div class="search-set mb-0 d-flex w-100 justify-content-start">

                        <div class="search-input text-left">
                            <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                        </div>

                        <div class="row mt-sm-3 mt-xs-3 mt-lg-0 w-sm-100 flex-grow-1">
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group ">
                                    <select class="select status_filter form-control">
                                        <option value="">Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Deactivated</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table office-table pb-3">
                        <thead>
                            <tr>
                                <th>School Name</th>
                                <th>Requestor</th>
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
    @livewire('contents.requesting-schools-management')

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

            if ($('.office-table').length > 0) {
                var table = $('.office-table').DataTable({
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
                        "url": "/requesting-offices",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function (d) {
                            d.status = $('.status_filter').val();
                            d.type = 'school';
                        },
                        "dataSrc": "data"
                    },
                    "columns": [
                        {
                            "data": "name",
                            "render": function (data, type, row) {
                                return `<a href="javascript:void(0);">${data}</a>`;
                            }
                        },
                        {
                            "data": "requestor_obj.name",
                            "render": function (data, type, row) {
                                return data ? `<a href="javascript:void(0);">${data}</a>` : 'N/A';
                            }
                        },
                        {
                            "data": null,
                            "render": function (data, type, row) {
                                return row.status === "active" ?
                                    `<span class="badge badge-linesuccess">Active</span>` :
                                    `<span class="badge badge-linedanger">Deactivated</span>`;
                            }
                        },
                        {
                            "data": null,
                            "render": function (data, type, row) {
                                return `
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2 edit-office" data-officeid="${row.requesting_office_id}">
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                    </div>
                                `;
                            }
                        }
                    ],
                    "createdRow": function (row, data, dataIndex) {
                        $(row).find('td').eq(3).addClass('action-table-data');
                    },
                    "initComplete": function (settings, json) {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                        feather.replace();
                        hideLoader();

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
                tippy('.edit-office', {
                    content: "Edit School",
                });
               
            };

        });
    </script>
@endpush
