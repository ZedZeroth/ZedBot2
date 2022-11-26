<div>
    {!! $chart->container() !!}
    {!! $chart->script() !!}
</div>

<script>
    window.livewire.on('chartUpdate', (chartId, labels, datasets) => {
    let chart = window[chartId].chart;

    chart.data.datasets.forEach((dataset, key) => {
        dataset.data = datasets[key];
    });

    chart.data.labels = labels;

    chart.update();
});
</script>