<div>
    <style>
        .doc-details {
            margin-bottom: 12px;
        }

        .movement-timeline {
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
            max-width: 520px;
        }

        .movement-timeline-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #374151;
            margin-bottom: 16px;
        }

        .tl-item {
            position: relative;
            display: grid;
            grid-template-columns: 48px 1fr;
            gap: 14px;
            padding-bottom: 22px;
        }

        /* connecting line */
        .tl-item::before {
            content: '';
            position: absolute;
            left: 23px;
            top: 34px;
            bottom: -4px;
            width: 2px;
            background: #e5e7eb;
        }

        .tl-item--last {
            padding-bottom: 0;
        }

        .tl-item--last::before {
            display: none;
        }

        .tl-marker {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .tl-month {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .05em;
            color: #9ca3af;
        }

        .tl-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
            z-index: 1;
        }

        .tl-card {
            background: #fff;
            border: 1px solid #eef0f3;
            border-radius: 14px;
            padding: 12px 14px;
            box-shadow: 0 4px 14px rgba(17, 24, 39, .06);
        }

        .tl-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 6px;
        }

        .tl-time {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .tl-action {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #fff;
            padding: 3px 9px;
            border-radius: 999px;
        }

        .tl-route {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .tl-route .bi {
            font-size: 11px;
            color: #9ca3af;
            margin: 0 5px;
        }

        .tl-user {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .tl-user .bi {
            font-size: 11px;
            margin-right: 3px;
        }

        .tl-notes {
            font-size: 12px;
            color: #4b5563;
            margin-top: 6px;
            padding-top: 6px;
            border-top: 1px dashed #eef0f3;
        }
    </style>

    @push('scripts')
        <script>
            $(document).ready(function() {
                getDocumentTracker();
            });

            $("#documentTrackerSearchInput").on("keyup", function() {
                const searchQuery = $(this).val();
                getDocumentTracker(1, searchQuery);
            });

            const formatTrackerDate = function(value) {
                if (!value) {
                    return 'N/A';
                }

                const date = new Date(value.replace(' ', 'T'));

                if (isNaN(date.getTime())) {
                    return 'N/A';
                }

                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            };

            const statusLabels = {
                transmitted: 'Forwarded',
                returned: 'Returned',
                received: 'Received',
                pending: 'Pending'
            };

            const formatStatusLabel = function(status) {
                if (!status) {
                    return 'N/A';
                }
                return statusLabels[status] || (status.charAt(0).toUpperCase() + status.slice(1));
            };

            const formatMovementDate = function(value) {
                if (!value) {
                    return 'N/A';
                }

                const date = new Date(value.replace(' ', 'T'));

                if (isNaN(date.getTime())) {
                    return 'N/A';
                }

                return date.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };

            const getDocumentTracker = function(page = 1, searchQuery = '') {
                @this.call('getDocumentTrackers', page, searchQuery).then(response => {
                    const data = response.original.data;
                    const pagination = response.original;

                    const $tableBody = $('#documentTrackerAccordion tbody');
                    $tableBody.empty();

                    if (!data || data.length === 0) {
                        $tableBody.append('<tr><td colspan="8" class="text-center">No document trackers found.</td></tr>');
                        return;
                    }

                    const movementDateParts = function(value) {
                        if (!value) {
                            return { month: '', day: '', time: 'N/A' };
                        }

                        const date = new Date(value.replace(' ', 'T'));

                        if (isNaN(date.getTime())) {
                            return { month: '', day: '', time: 'N/A' };
                        }

                        return {
                            month: date.toLocaleDateString('en-US', { month: 'short' }).toUpperCase(),
                            day: date.toLocaleDateString('en-US', { day: 'numeric' }),
                            time: date.toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit'
                            })
                        };
                    };

                    const buildMovementLogs = function(logs) {
                        if (!logs || logs.length === 0) {
                            return '<p class="text-muted mb-0">No movement logs recorded yet.</p>';
                        }

                        let html = '<div class="movement-timeline">';
                        html += '<h6 class="movement-timeline-title">Movement Logs</h6>';

                        logs.forEach((log, i) => {
                            const actionLabel = log.action ? formatStatusLabel(log.action) : 'Moved';
                            const actionColor = log.action === 'transmitted' ? '#22c55e' :
                                log.action === 'returned' ? '#ef4444' :
                                log.action === 'received' ? '#3b82f6' : '#643bc6';
                            const badge = movementDateParts(log.created_at);
                            const isLast = i === logs.length - 1;

                            const routeHtml = log.action === 'received' ?
                                `<i class="bi bi-box-arrow-in-down"></i> Received at ${log.to_office}` :
                                `${log.from_office} <i class="bi bi-arrow-right"></i> ${log.to_office}`;

                            html += `
                                <div class="tl-item ${isLast ? 'tl-item--last' : ''}">
                                    <div class="tl-marker">
                                        <span class="tl-month">${badge.month}</span>
                                        <span class="tl-badge" style="background-color: ${actionColor};">${badge.day}</span>
                                    </div>
                                    <div class="tl-card">
                                        <div class="tl-card-top">
                                            <span class="tl-time">${badge.time}</span>
                                            <span class="tl-action" style="background-color: ${actionColor};">${actionLabel}</span>
                                        </div>
                                        <div class="tl-route">
                                            ${routeHtml}
                                        </div>
                                        <div class="tl-user"><i class="bi bi-person-fill"></i> ${log.user}</div>
                                        ${log.notes ? `<div class="tl-notes">${log.notes}</div>` : ''}
                                    </div>
                                </div>
                            `;
                        });

                        html += '</div>';
                        return html;
                    };

                    data.forEach((tracker, index) => {
                        const row = `
                            <tr data-bs-toggle="collapse" data-bs-target="#docCollapse${index}"
                                aria-expanded="false" aria-controls="docCollapse${index}">
                                <th scope="row">
                                    <i class="bi ${
                                        tracker.status === 'pending' ? 'bi-hourglass-split' : 
                                        tracker.status === 'transmitted' ? 'bi-send-check' : 
                                        tracker.status === 'returned' ? 'bi-arrow-return-left' : 'bi-file-earmark-fill'
                                    }" style="color: ${
                                        tracker.status === 'pending' ? '#FFA500' : 
                                        tracker.status === 'transmitted' ? '#32CD32' : 
                                        tracker.status === 'returned' ? '#FF0000' : '#643bc6'
                                    }; font-size: 22px;"></i>
                                </th>
                                <td>${tracker.current_office_name}</td>
                                <td>${tracker.tracking_number}</td>
                                <td>${tracker.requestor_name || 'N/A'}</td>
                                <td>${tracker.document_type || 'N/A'}</td>
                                <td>
                                    <span>${formatStatusLabel(tracker.status)}</span>
                                </td>
                                <td>${formatTrackerDate(tracker.received_at)}</td>
                                <td>${formatTrackerDate(tracker.released_at)}</td>
                            </tr>
                            <tr>
                                <td colspan="8" id="docCollapse${index}" class="collapse acc" data-parent="#documentTrackerAccordion">
                                    <p class="doc-details">${tracker.details || 'No details available.'}</p>
                                    ${buildMovementLogs(tracker.movement_logs)}
                                </td>
                            </tr>
                        `;

                        $tableBody.append(row);
                    });

                    const $paginationContainer = $('#documentTrackerPaginationContainer');
                    $paginationContainer.empty();

                    let paginationHTML = `
                        <div class="pagination p1">
                            <ul>
                    `;

                    const maxPagesToShow = 5;
                    const startPage = Math.max(1, pagination.current_page - Math.floor(maxPagesToShow / 2));
                    const endPage = Math.min(pagination.last_page, startPage + maxPagesToShow - 1);

                    if (pagination.prev_page_url) {
                        paginationHTML += `
                            <a href="javascript:void(0);" onclick="getDocumentTracker(${pagination.current_page - 1}, '${searchQuery}')">
                                <li><</li>
                            </a>
                        `;
                    }

                    if (startPage > 1) {
                        paginationHTML += `
                            <a href="javascript:void(0);" onclick="getDocumentTracker(1, '${searchQuery}')">
                                <li>1</li>
                            </a>
                            ${startPage > 2 ? '<li>...</li>' : ''}
                        `;
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        if (i === pagination.current_page) {
                            paginationHTML += `
                                <a class="is-active" href="javascript:void(0);">
                                    <li>${i}</li>
                                </a>
                            `;
                        } else {
                            paginationHTML += `
                                <a href="javascript:void(0);" onclick="getDocumentTracker(${i}, '${searchQuery}')">
                                    <li>${i}</li>
                                </a>
                            `;
                        }
                    }

                    if (endPage < pagination.last_page) {
                        paginationHTML += `
                            ${endPage < pagination.last_page - 1 ? '<li>...</li>' : ''}
                            <a href="javascript:void(0);" onclick="getDocumentTracker(${pagination.last_page}, '${searchQuery}')">
                                <li>${pagination.last_page}</li>
                            </a>
                        `;
                    }

                    if (pagination.next_page_url) {
                        paginationHTML += `
                            <a href="javascript:void(0);" onclick="getDocumentTracker(${pagination.current_page + 1}, '${searchQuery}')">
                                <li>></li>
                            </a>
                        `;
                    }

                    paginationHTML += `
                            </ul>
                        </div>
                    `;

                    $paginationContainer.append(paginationHTML);
                }).catch(error => {
                    console.error(error);
                });
            }
        </script>
    @endpush
</div>
