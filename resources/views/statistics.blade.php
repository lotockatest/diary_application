@extends('layouts.dashboard')

@section('content')
<!--<div class="min-h-screen flex justify-center items-start bg-gray-100 py-10">-->
<div class="w-full max-w-screen-2xl min-h-[100vh] bg-white rounded-3xl shadow-2xl p-12">
    <!-- Page title, back button, filters (timespan selection) -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-purple-600">Statistics</h2>
        <form method="GET" class="flex items-center gap-2">
            <select name="time_span" class="border rounded px-2 py-1">
                <option value="week" {{ $timeSpan == 'week' ? 'selected' : '' }}>Current Week</option>
                <option value="month" {{ $timeSpan == 'month' ? 'selected' : '' }}>Current Month</option>
                <option value="year" {{ $timeSpan == 'year' ? 'selected' : '' }}>Current Year</option>
                <option value="custom" {{ $timeSpan == 'custom' ? 'selected' : '' }}>Custom</option>
            </select>
            <!-- Range for custom dates -->
            <input type="date" name="start_date" value="{{ $start->format('Y-m-d') }}" class="border rounded px-2 py-1">
            <input type="date" name="end_date" value="{{ $end->format('Y-m-d') }}" class="border rounded px-2 py-1">
            <!-- Button for submitting filters -->
            <button type="submit" class="px-3 py-1 bg-purple-600 text-white rounded hover:bg-purple-700">Apply</button>
        </form>
        <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 ml-2">Back</a>
    </div>
    <!-- Hidden element used for storing data in JSON format -->
    <div id="statisticsData"
     data-moods='@json($moodsCount)'
     data-routines='@json($routinesCount)'
     data-activities='@json($activitiesCount)'>
    </div>
    <!-- Container for all charts -->
    <div class="grid grid-cols-1 gap-6">
        <div>
            <canvas id="moodsChart"></canvas>
        </div>
        <div>
            <canvas id="routinesChart"></canvas>
        </div>
        <div>
            <canvas id="activitiesChart"></canvas>
        </div>
    </div>

<!--</div>-->
</div>
<!-- Using Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const statsEl = document.getElementById('statisticsData');
    //Parse Blade data
    const moodsData = JSON.parse(statsEl.dataset.moods);
    const routinesData = JSON.parse(statsEl.dataset.routines);
    const activitiesData = JSON.parse(statsEl.dataset.activities);
    //Create bar chart function
    function createBarChart(ctx, data, label) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    label: label,
                    data: Object.values(data),
                    backgroundColor: 'rgba(147, 51, 234, 0.6)',
                    borderColor: 'rgba(147, 51, 234, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: label }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
    //Create all charts
    createBarChart(document.getElementById('moodsChart'), moodsData, 'Moods');
    createBarChart(document.getElementById('routinesChart'), routinesData, 'Routines');
    createBarChart(document.getElementById('activitiesChart'), activitiesData, 'Activities');
});
</script>
@endsection