<div>
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
                                    <span>${tracker.status.charAt(0).toUpperCase() + tracker.status.slice(1)}</span>
                                </td>
                                <td>${formatTrackerDate(tracker.received_at)}</td>
                                <td>${formatTrackerDate(tracker.released_at)}</td>
                            </tr>
                            <tr>
                                <td colspan="8" id="docCollapse${index}" class="collapse acc" data-parent="#documentTrackerAccordion">
                                    <p>${tracker.details || 'No details available.'}</p>
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
