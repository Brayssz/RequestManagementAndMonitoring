<div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                getRequest();
            });

            $("#searchInput").on("keyup", function() {
                const searchQuery = $(this).val();
                getRequest(1, searchQuery);
            });

            const getRequest = function(page = 1, searchQuery = '') {
                @this.call('getRequest', page, searchQuery).then(response => {
                    const data = response.original.data;
                    const pagination = response.original;

                    const $tableBody = $('#accordion tbody');
                    $tableBody.empty();

                    if (!data || data.length === 0) {
                        $tableBody.append('<tr><td colspan="9" class="text-center">No requests found.</td></tr>');
                        return;
                    }

                    data.forEach((request, index) => {
                        const row = `
                            <tr data-bs-toggle="collapse" data-bs-target="#collapse${index}"
                                aria-expanded="false" aria-controls="collapse${index}">
                                <th scope="row">
                                    <i class="bi ${
                                        request.status === 'pending' ? 'bi-hourglass-split' : 
                                        request.status === 'transmitted' ? 'bi-send-check' : 
                                        request.status === 'returned' ? 'bi-arrow-return-left' : 'bi-file-earmark-fill'
                                    }" style="color: ${
                                        request.status === 'pending' ? '#FFA500' : 
                                        request.status === 'transmitted' ? '#32CD32' : 
                                        request.status === 'returned' ? '#FF0000' : '#643bc6'
                                    }; font-size: 22px;">
                                    </i>
                                </th>
                                <td>${request.requesting_office.name}</td>
                                <td>${request.dts_tracker_number}</td>
                                <td>&#8369;${request.amount}</td>
                                <td>${request.nature_of_request}</td>
                                <td>${request.allotment_year} - ${request.fund_source.name}</td>
                                <td>
                                    <span>
                                        ${request.status.charAt(0).toUpperCase() + request.status.slice(1)}
                                    </span>
                                </td>
                                <td>
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="9" id="collapse${index}" class="collapse acc"
                                    data-parent="#accordion">
                                    <p>${request.timeline}</p>
                                </td>
                            </tr>
                        `;

                        $tableBody.append(row);
                    });

                    const $paginationContainer = $('#paginationContainer');
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
                            <a href="javascript:void(0);" onclick="getRequest(${pagination.current_page - 1}, '${searchQuery}')">
                                <li><</li>
                            </a>
                        `;
                    }

                    if (startPage > 1) {
                        paginationHTML += `
                            <a href="javascript:void(0);" onclick="getRequest(1, '${searchQuery}')">
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
                                <a href="javascript:void(0);" onclick="getRequest(${i}, '${searchQuery}')">
                                    <li>${i}</li>
                                </a>
                            `;
                        }
                    }

                    if (endPage < pagination.last_page) {
                        paginationHTML += `
                            ${endPage < pagination.last_page - 1 ? '<li>...</li>' : ''}
                            <a href="javascript:void(0);" onclick="getRequest(${pagination.last_page}, '${searchQuery}')">
                                <li>${pagination.last_page}</li>
                            </a>
                        `;
                    }

                    if (pagination.next_page_url) {
                        paginationHTML += `
                            <a href="javascript:void(0);" onclick="getRequest(${pagination.current_page + 1}, '${searchQuery}')">
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
                    console.error(error); // Handle any errors here
                });
            }
        </script>
    @endpush
</div>
