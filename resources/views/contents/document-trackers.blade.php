@extends('layouts.app-layout')

@section('title', 'Document Trackers Management')

@section('content')

    <style>
        /* Keep the cell a normal table-cell so it shares row height and baseline
           with the other columns. */
        table tbody td.action-menu-cell {
            vertical-align: middle;
            white-space: nowrap;
        }

        .action-menu-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: 1px solid rgba(145, 158, 171, 0.3);
            border-radius: 8px;
            background-color: #ffffff;
            color: #637381;
            transition: background-color .15s ease, border-color .15s ease, color .15s ease;
        }

        .action-menu-toggle:hover,
        .action-menu-toggle[aria-expanded="true"] {
            background-color: #f1effd;
            border-color: #643bc6;
            color: #643bc6;
        }

        .action-menu-toggle svg {
            width: 18px;
            height: 18px;
        }

        .action-menu-list {
            min-width: 200px;
            padding: 6px;
            border: 1px solid #e7e7e7;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(16, 42, 94, .14);
        }

        /* The theme borders every <a> inside an action cell; reset that here so the
           menu reads as one list instead of a stack of boxed buttons. */
        .action-menu-list .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 8px 10px;
            border: 0;
            border-radius: 6px;
            background-color: transparent;
            font-size: 14px;
            line-height: 1.4;
            color: #2d3748;
        }

        .action-menu-list .dropdown-item:hover,
        .action-menu-list .dropdown-item:focus {
            background-color: #f6f5fe;
            color: #643bc6;
        }

        .action-menu-list .dropdown-item.text-danger:hover,
        .action-menu-list .dropdown-item.text-danger:focus {
            background-color: #fdf1f1;
            color: #dc3545;
        }

        .action-menu-list .dropdown-item svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            color: inherit;
        }

        .action-menu-list .dropdown-divider {
            margin: 6px 2px;
            border-top-color: #ececf1;
        }
    </style>

    <div class="content mx-3">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Document Trackers Management</h4>
                    <h6>Manage your document trackers</h6>
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
                <a class="btn btn-added add-document-tracker"><i data-feather="plus-circle" class="me-2"></i>Add New
                    Document Tracker</a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-folder-open" style="color: #643bc6; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="counters" data-count="{{ $totalDocumentTrackers }}">{{ $totalDocumentTrackers }}</span></h5>
                        <h6>Total Document Trackers</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash1 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-hourglass-half" style="color: #ffc107; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="counters" data-count="{{ $totalPendingDocumentTrackers }}">{{ $totalPendingDocumentTrackers }}</span></h5>
                        <h6>Pending Document Trackers</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash2 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-paper-plane" style="color: #007bff; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="counters" data-count="{{ $totalForwardedDocumentTrackers }}">{{ $totalForwardedDocumentTrackers }}</span></h5>
                        <h6>Forwarded Document Trackers</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash3 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-check-circle" style="color: #28a745; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="counters" data-count="{{ $totalCompletedDocumentTrackers }}">{{ $totalCompletedDocumentTrackers }}</span></h5>
                        <h6>Completed Document Trackers</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="card table-list-card">
            <div class="card-body pb-0">
                <div class="table-top table-top-two table-top-new d-flex">
                    <div class="search-set mb-0 d-flex w-100 justify-content-start">

                        <div class="search-input text-left">
                            <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                            <input type="text" class="form-control document-tracker-search ms-2" placeholder="Search...">
                        </div>

                        <div class="row mt-sm-3 mt-xs-3 mt-lg-0 w-sm-100 flex-grow-1">
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <select class="select status_filter form-control">
                                        <option value="">Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="received">Received</option>
                                        <option value="transmitted">Forwarded</option>
                                        <option value="returned">Returned</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table document-trackers-table pb-3 fs-14">
                        <thead>
                            <tr>
                                <th>Tracking Number</th>
                                <th>Requesting Office</th>
                                <th>Current Office</th>
                                <th>Document Type</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Received At</th>
                                <th>Released At</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @livewire('contents.document-tracker-management')

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

            // received_at / released_at are UTC instants serialized with an offset.
            // Render them in the division's timezone rather than the viewer's.
            const DISPLAY_TIMEZONE = @json(config('app.display_timezone'));

            const formatDisplayDate = (value) => {
                if (!value) {
                    return 'N/A';
                }

                const date = new Date(value);

                if (isNaN(date.getTime())) {
                    return 'N/A';
                }

                return date.toLocaleDateString('en-US', {
                    timeZone: DISPLAY_TIMEZONE,
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            };

            // The table scrolls horizontally, so .table-responsive (overflow-x: auto)
            // would clip a normally-positioned menu. Popper's fixed strategy takes the
            // menu out of that clipping context.
            const initActionMenus = () => {
                document.querySelectorAll('.action-menu-toggle').forEach((toggle) => {
                    bootstrap.Dropdown.getOrCreateInstance(toggle, {
                        popperConfig: (defaultConfig) => ({
                            ...defaultConfig,
                            strategy: 'fixed'
                        })
                    });
                });
            };

            if ($('.document-trackers-table').length > 0) {
                var table = $('.document-trackers-table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bFilter": false,
                    "sDom": 'Btlpi',
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
                        "url": "/document-trackers",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function(d) {
                            d.search_value = $('.document-tracker-search').val();
                            d.status = $('.status_filter').val();
                        },
                        "dataSrc": "data"
                    },
                    "columns": [
                        {
                            "data": "tracking_number"
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                // Fall back to the requestor name when no office is linked (external requests).
                                const office = row.requesting_office;
                                const name = office?.name || row.requestor_name || 'N/A';
                                const subLabel = office?.type ?
                                    office.type.charAt(0).toUpperCase() + office.type.slice(1) :
                                    (row.requestor_name ? 'External' : '');
                                return `
                                    <div class="userimgname">
                                        <div>
                                            <a href="javascript:void(0);">${name}</a>
                                            <span class="emp-team text-muted">${subLabel}</span>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                const officeName = row.current_office?.name || 'N/A';
                                return `
                                    <div class="userimgname">
                                        <div>
                                            <a href="javascript:void(0);">${officeName}</a>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        {
                            "data": "document_type"
                        },
                        {
                            "data": "details",
                            "render": function(data) {
                                return `<div style="max-width: 420px; min-width: 420px; white-space: normal; word-wrap: break-word; word-break: break-word;">${data || 'N/A'}</div>`;
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                if (row.status === 'pending') {
                                    return `<span class="badge badge-linewarning">Pending</span>`;
                                } else if (row.status === 'received') {
                                    return `<span class="badge badge-linesuccess">Received</span>`;
                                } else if (row.status === 'transmitted') {
                                    return `<span class="badge badge-lineinfo">Forwarded</span>`;
                                } else if (row.status === 'returned') {
                                    return `<span class="badge badge-linedanger">Returned</span>`;
                                } else if (row.status === 'completed') {
                                    return `<span class="badge badge-linesuccess">Completed</span>`;
                                }
                                return `<span class="badge badge-lineinfo">Unknown</span>`;
                            }
                        },
                        {
                            "data": "received_at",
                            "render": function(data) {
                                return formatDisplayDate(data);
                            }
                        },
                        {
                            "data": "released_at",
                            "render": function(data) {
                                return formatDisplayDate(data);
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                // A completed document is terminal — it can no longer be
                                // forwarded, returned, or completed again, so that whole
                                // group (and its divider) drops out of the menu.
                                const routingGroup = row.status === 'completed' ? '' : `
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item transmit-document-tracker" href="javascript:void(0);" data-documenttrackerid="${row.id}">
                                                    <i data-feather="send"></i><span>Forward</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item return-document-tracker" href="javascript:void(0);" data-documenttrackerid="${row.id}">
                                                    <i data-feather="corner-up-left"></i><span>Return</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item complete-document-tracker" href="javascript:void(0);" data-documenttrackerid="${row.id}">
                                                    <i data-feather="check-circle"></i><span>Mark as Completed</span>
                                                </a>
                                            </li>`;

                                return `
                                    <div class="dropdown action-menu">
                                        <a href="javascript:void(0);" class="action-menu-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end action-menu-list">
                                            <li>
                                                <a class="dropdown-item print-document-tracker" href="/document-tracker-slip-pdf/${row.id}" target="_blank">
                                                    <i data-feather="printer"></i><span>Print Slip</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item edit-document-tracker" href="javascript:void(0);" data-documenttrackerid="${row.id}">
                                                    <i data-feather="edit"></i><span>Edit</span>
                                                </a>
                                            </li>
                                            ${routingGroup}
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger delete-document-tracker" href="javascript:void(0);" data-documenttrackerid="${row.id}">
                                                    <i data-feather="trash-2"></i><span>Delete</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                `;
                            }
                        }
                    ],
                    "createdRow": function(row, data, dataIndex) {
                        // Deliberately NOT .action-table-data — that theme class sets
                        // display:flex on the td (which drops it out of the table row's
                        // box model) and borders every <a> inside it, including menu items.
                        $(row).find('td').eq(8).addClass('action-menu-cell');
                    },
                    "initComplete": function(settings, json) {
                        feather.replace();

                        $(document).off('keyup.docTracker', '.document-tracker-search')
                            .on('keyup.docTracker', '.document-tracker-search', function() {
                                showLoader();
                                table.draw();
                            });

                        $(document).off('change.docTracker', '.status_filter')
                            .on('change.docTracker', '.status_filter', function() {
                                showLoader();
                                table.ajax.reload(null, false);
                            });

                        // A fixed-positioned menu does not follow the table as it scrolls,
                        // so close it instead of letting it drift away from its row.
                        $('.table-responsive').off('scroll.docTracker').on('scroll.docTracker', function() {
                            document.querySelectorAll('.action-menu-toggle[aria-expanded="true"]')
                                .forEach((toggle) => bootstrap.Dropdown.getInstance(toggle)?.hide());
                        });

                        initActionMenus();
                    },
                    "drawCallback": function(settings) {
                        hideLoader();
                        feather.replace();
                        initActionMenus();
                    },
                    "preDrawCallback": function(settings) {
                        showLoader();
                    },
                });
            }
        });
    </script>
@endpush