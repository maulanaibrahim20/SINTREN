@extends('index')
@section('title', 'Grafik Harga Pangan | Pangan')
@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <!-- breadcrumb -->
        <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item1 active">Grafik Stok Pangan</li>
    </ol><!-- End breadcrumb -->
</div>
<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Grafik Stok Pangan</h4>
            </div>
            <div class="card-body">
                <canvas id="chartBar1"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
	var ctx1 = document.getElementById('chartBar1').getContext('2d');
	new Chart(ctx1, {
		type: 'bar',
		data: {
			labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
			datasets: [{
				label: '# of Votes',
				data: [14, 12, 34, 25, 24, 20],
				backgroundColor: '#467fcf'
			}]
		},
		options: {
			maintainAspectRatio: false,
			responsive: true,
			barPercentage: 0.5,
			plugins: {
				legend: {
					display: false,
					labels: {
						display: false
					}
				},
				tooltip: {
					enabled: true
				}
			},
			scales: {
				x: {
					ticks: {
						beginAtZero: true,
						fontSize: 10,
						fontColor: "rgba(180, 183, 197, 0.4)",
					},
					title: {
						display: false,
						text: 'Months',
					},
					grid: {
						display: true,
						color: 'rgba(180, 183, 197, 0.4)																																					',
						drawBorder: false,
					},
				},
				y: {
					ticks: {
						beginAtZero: true,
						fontSize: 10,
						fontColor: "rgba(180, 183, 197, 0.4)",
						stepSize: 10,
						min: 0,
						max: 80
					},
					title: {
						display: false,
						text: 'Revenue',
					},
					grid: {
						display: true,
						color: 'rgba(180, 183, 197, 0.4)',
						drawBorder: false,
					},
				}
			},
		}
	});
</script>
@endsection
