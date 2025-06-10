@extends('layouts.app-layout')

@section('title', 'Dashboard')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-users" style="color: #ffc107; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $totalRequestors }}">{{ $totalRequestors }}</span>
                        </h5>
                        <h6>Total Requestors</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash1 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-book" style="color: #28C76F; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $totalOffices }}">{{ $totalOffices }}</span>
                        </h5>
                        <h6>Total Offices</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash2 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-school" style="color: #007bff; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $totalSchools }}">{{ $totalSchools }}</span>
                        </h5>
                        <h6>Total Schools</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash3 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-book-reader" style="color: #dc3545; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $totalFundSources }}">{{ $totalFundSources }}</span>
                        </h5>
                        <h6>Total Fund Sources</h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-undo" style="color: #ffc107; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters"
                                data-count="{{ $totalPendingRequests }}">{{ $totalPendingRequests }}</span>
                        </h5>
                        <h6>Total Pending Requests</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-exchange-alt" style="color: #d4ed00; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters"
                                data-count="{{ $totalTransmittedRequests }}">{{ $totalTransmittedRequests }}</span>
                        </h5>
                        <h6>Total Transmitted Requests</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash3 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-clock" style="color: #dc3545; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters"
                                data-count="{{ $totalReturnedRequests }}">{{ $totalReturnedRequests }}</span>
                        </h5>
                        <h6>Total Returned Requests</h6>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-6 col-sm-12 col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Monthly Request</h5>
                        <div class="graph-sets">
                            <ul class="mb-0">
                                <li>
                                    <span>Pending</span>
                                </li>
                                <li>
                                    <span>Transmitted</span>
                                </li>
                                <li>
                                    <span>Returned</span>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="card-body">
                        <div id="application_charts"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-12 col-12 d-flex">
                <div class="card flex-fill default-cover mb-4" style="max-height: 450px;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Pending Requests</h4>
                        <div class="view-all-link">
                            <a href="{{ route('receive-requests') }}" class="view-all d-flex align-items-center">
                                View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right"
                                        class="feather-16"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview" style="max-height: 350px; overflow-y: auto;">
                            @if ($pendingRequest->isEmpty())
                                <div class="text-center mt-4">
                                    <i class="fa fa-calendar-times" style="font-size: 2rem; color: #dc3545;"></i>
                                    <p class="mt-4">No pending requests available.</p>
                                </div>
                            @else
                                  <table class="table table-responsive dashboard-recent-products">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>School/Office</th>
                                            <th>Fund Source</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $colors = [
                                                'A' => 'bg-primary',
                                                'B' => 'bg-success',
                                                'C' => 'bg-danger',
                                                'D' => 'bg-warning',
                                                'E' => 'bg-info',
                                                'F' => 'bg-dark',
                                                'G' => 'bg-secondary',
                                            ];
                                        @endphp
                                        @foreach ($pendingRequest as $index => $request)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    {{ $request->requestingOffice->name ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    @php
                                                        $fundSourceName = $request->fundSource->name ?? 'N/A';
                                                        $firstLetter = strtoupper(substr($fundSourceName, 0, 1));
                                                        $bgColor = $colors[$firstLetter] ?? 'bg-secondary';
                                                        $allotmentYear = $request->allotment_year ?? 'N/A';
                                                    @endphp
                                                    <span class="avatar {{ $bgColor }} avatar-rounded"
                                                        style="height: 2.65rem;">
                                                        <span class="avatar-title">{{ $firstLetter }}</span>
                                                    </span>
                                                    <a>{{ $fundSourceName . "(". $allotmentYear. ")" }}</a>
                                                </td>
                                                <td>&#8369;{{ number_format($request->amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xl-5 col-sm-12 col-12 d-flex">

            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            if ($("#application_charts").length > 0) {
                $.ajax({
                    url: "/monthly-request", // Laravel route
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        var maxPending = Math.max(...response.pending.map(Number));
                        var maxTransmitted = Math.max(...response.transmitted.map(Number));
                        var maxReturned = Math.max(...response.returned.map(Number));
                        var maxY = Math.max(maxPending, maxTransmitted, maxReturned);

                        var options = {
                            series: [
                                {
                                    name: "Pending",
                                    data: response.pending.map(Number),
                                },
                                {
                                    name: "Transmitted",
                                    data: response.transmitted.map(Number),
                                },
                                {
                                    name: "Returned",
                                    data: response.returned.map(Number),
                                },
                            ],
                            colors: ["#28C76F", "#007bff", "#EA5455"],
                            chart: {
                                type: "bar",
                                height: 320,
                                zoom: {
                                    enabled: true
                                },
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    borderRadius: 4,
                                    columnWidth: "50%",
                                },
                            },
                            dataLabels: {
                                enabled: false
                            },
                            yaxis: {
                                min: 0,
                                max: maxY,
                                tickAmount: 5
                            },
                            xaxis: {
                                categories: [
                                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                ],
                            },
                            legend: {
                                show: false
                            },
                            fill: {
                                opacity: 1
                            },
                        };

                        var chart = new ApexCharts(
                            document.querySelector("#application_charts"),
                            options
                        );
                        chart.render();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            }
        });
    </script>
@endpush
