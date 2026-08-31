@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Members</h5>
                        <h3>{{ $totalMembers }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Householders</h5>
                        <h3>{{ $totalHouseholders }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Deceased</h5>
                        <h3>{{ $totalDeceased }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Divorced</h5>
                        <h3>{{ $totalDivorced }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mb-4">
            <!-- Community-wise Bar Chart -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Members by Community</h5>
                        <div id="communityChart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
            <!-- Trend Line Chart -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Recent Member Additions (Trend)</h5>
                        <div id="trendChart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Members Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Recently Added Members</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Community</th>
                                        <th>CNIC</th>
                                        <th>Added On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentMembers as $member)
                                        <tr>
                                            <td>{{ $member->name }}</td>
                                            <td>{{ $member->community->name }}</td>
                                            <td>{{ $member->cnic ?? 'N/A' }}</td>
                                            <td>{{ $member->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No recent members found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Google Charts Script Directly Here -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load Google Charts library
        google.charts.load('current', {
            packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Debug data in console
            const communitiesData = @json($communitiesData ?? []);
            const trendLabels = @json($labels ?? []);
            const trendData = @json($trendData ?? []);

            console.log('Communities Data:', communitiesData);
            console.log('Trend Labels:', trendLabels);
            console.log('Trend Data:', trendData);

            // Check if data exists
            if (!communitiesData.length || !trendLabels.length || !trendData.length) {
                console.warn('No data available to render charts.');
                document.getElementById('communityChart').innerHTML =
                    '<p class="text-center">No data available for Community Chart.</p>';
                document.getElementById('trendChart').innerHTML =
                    '<p class="text-center">No data available for Trend Chart.</p>';
                return;
            }

            // Community Bar Chart (ColumnChart with Enhanced Styling)
            var communityData = new google.visualization.DataTable();
            communityData.addColumn('string', 'Community');
            communityData.addColumn('number', 'Total Members');
            communityData.addColumn('number', 'Male Members');
            communityData.addColumn('number', 'Female Members');
            communityData.addColumn('number', 'Children');

            // Dynamically add rows based on available data with type conversion
            communitiesData.forEach(item => {
                communityData.addRow([
                    item.name || 'Unknown Community',
                    parseInt(item.count) || 0, // Ensure number type
                    parseInt(item.male_count) || 0, // Ensure number type
                    parseInt(item.female_count) || 0, // Ensure number type
                    parseInt(item.child_count) || 0 // Ensure number type
                ]);
            });

            var communityOptions = {
                title: 'Members by Community',
                height: 400,
                legend: {
                    position: 'top',
                    maxLines: 3,
                    textStyle: {
                        fontSize: 14
                    }
                },
                bar: {
                    groupWidth: '60%'
                },
                isStacked: false,
                hAxis: {
                    title: 'Community',
                    titleTextStyle: {
                        fontSize: 16,
                        bold: true
                    },
                    textStyle: {
                        fontSize: 12
                    }
                },
                vAxis: {
                    title: 'Number of Members',
                    titleTextStyle: {
                        fontSize: 16,
                        bold: true
                    },
                    textStyle: {
                        fontSize: 12
                    },
                    minValue: 0,
                    gridlines: {
                        color: '#e0e0e0'
                    }
                },
                colors: ['#1b9e77', '#d95f02', '#7570b3', '#e7298a'],
                chartArea: {
                    width: '70%',
                    height: '70%'
                },
                backgroundColor: {
                    fill: 'transparent'
                },
                titleTextStyle: {
                    fontSize: 18,
                    bold: true
                },
                animation: {
                    duration: 1000,
                    easing: 'out',
                    startup: true
                }
            };

            var communityChart = new google.visualization.ColumnChart(document.getElementById('communityChart'));
            communityChart.draw(communityData, communityOptions);

            // Trend Line Chart (LineChart with Enhanced Styling)
            var trendDataTable = new google.visualization.DataTable();
            trendDataTable.addColumn('string', 'Month');
            trendDataTable.addColumn('number', 'New Members');

            // Dynamically add rows based on available data with type conversion
            for (let i = 0; i < trendLabels.length; i++) {
                trendDataTable.addRow([
                    trendLabels[i] || 'Unknown Month',
                    parseInt(trendData[i]) || 0 // Ensure number type
                ]);
            }

            var trendOptions = {
                title: 'Recent Member Additions (Trend)',
                height: 400,
                legend: {
                    position: 'top',
                    textStyle: {
                        fontSize: 14
                    }
                },
                hAxis: {
                    title: 'Month',
                    titleTextStyle: {
                        fontSize: 16,
                        bold: true
                    },
                    textStyle: {
                        fontSize: 12
                    }
                },
                vAxis: {
                    title: 'New Members',
                    titleTextStyle: {
                        fontSize: 16,
                        bold: true
                    },
                    textStyle: {
                        fontSize: 12
                    },
                    minValue: 0,
                    gridlines: {
                        color: '#e0e0e0'
                    }
                },
                colors: ['#1b9e77'],
                chartArea: {
                    width: '70%',
                    height: '70%'
                },
                backgroundColor: {
                    fill: 'transparent'
                },
                titleTextStyle: {
                    fontSize: 18,
                    bold: true
                },
                curveType: 'function',
                pointSize: 5,
                dataOpacity: 0.8,
                animation: {
                    duration: 1000,
                    easing: 'out',
                    startup: true
                }
            };

            var trendChart = new google.visualization.LineChart(document.getElementById('trendChart'));
            trendChart.draw(trendDataTable, trendOptions);
        }
    </script>
@endsection
