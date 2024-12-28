@extends('layouts.app-master')

@section('content')

<!-- CONTENT -->
<div class="lg:pl-[320px] p-8 lg:pb-48 pt-[108px] lg:pt-8 bg-black min-h-screen h-full text-white overflow-auto">
    <div id="reviews">
        <?php if ($copytrade) { ?>
            <section class="pt-0">
                <div class="pl-4 mb-[50px] border-l-4 border-black border-white border-solid">
                    <h1 class="font-bold text-primary">Tỷ trọng và phân bổ vốn</h1>
                </div>
                <?php if ($copytrades) { ?>
                    <h2 class="text-center text-xl mb-0 md:mb-8">EMERALPHA AI Value</h2>
                    <div class="flex flex-wrap lg:flex-nowrap items-start">
                        <!-- Chart Container -->
                        <div class="flex-grow">
                            <canvas id="myChart1" class="!w-full !h-[500px]"></canvas>
                        </div>

                        <!-- Legend Container -->
                        <div id="chartLegend" class="flex flex-row md:flex-col mr-[34%] lg:mt-[160px] md:mt-[-300px] gap-4 md:gap-0"></div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
                    <script>
                        const ctx1 = document.getElementById('myChart1').getContext('2d');
                        const data1 = {
                            labels: [
                                <?php foreach ($copytrades as $item) { ?> '<?php echo $item['name']; ?>',
                                <?php } ?>
                            ],
                            datasets: [{
                                label: 'Value',
                                data: [
                                    <?php foreach ($copytrades as $item) { ?>
                                        <?php echo (float)$item['content']; ?>,
                                    <?php } ?>
                                ],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)',
                                ],
                                borderColor: 'rgba(255, 255, 255, 1)',
                                borderWidth: 2,
                            }]
                        };

                        const config1 = {
                            type: 'pie',
                            data: data1,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false // Tắt chú thích mặc định
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                const dataset = tooltipItem.chart.data.datasets[0];
                                                const total = dataset.data.reduce((sum, value) => sum + value, 0);
                                                const currentValue = dataset.data[tooltipItem.dataIndex];
                                                const percentage = ((currentValue / total) * 100).toFixed(2);
                                                return tooltipItem.label + ': ' + percentage + '%';
                                            }
                                        }
                                    },
                                    datalabels: {
                                        color: '#fff',
                                        formatter: (value, ctx) => {
                                            const dataset = ctx.chart.data.datasets[0];
                                            const total = dataset.data.reduce((sum, val) => sum + val, 0);
                                            const percentage = ((value / total) * 100).toFixed(2);
                                            return `${percentage}%`;
                                        },
                                        anchor: 'center',
                                        align: 'center',
                                    }
                                },
                                layout: {
                                    padding: 20
                                }
                            },
                            plugins: [ChartDataLabels],
                        };

                        const myChart1 = new Chart(ctx1, config1);

                        // Tạo chú thích tùy chỉnh
                        const legendContainer = document.getElementById('chartLegend');
                        data1.datasets[0].backgroundColor.forEach((color, index) => {
                            const label = data1.labels[index];
                            const legendItem = document.createElement('div');
                            legendItem.classList.add('flex', 'items-center', 'space-x-2', 'text-gray-700', 'text-sm');
                            legendItem.innerHTML = `
                <span style="display:inline-block; width:12px; height:12px; background:${color}; margin-right:5px;"></span>
                <span>${label}</span>`;
                            legendContainer.appendChild(legendItem);
                        });
                    </script>
                <?php } else { ?>
                    <p class="text-gray-300">Không có dữ liệu để hiển thị.</p>
                <?php } ?>

                <hr class="mt-10">

                <?php if ($copytradesN) { ?>
                    <h2 class="text-center text-xl my-5 mb-0 md:mb-8">EMERALPHA AI Growth</h2>
                    <div class="flex flex-wrap lg:flex-nowrap items-start">
                        <!-- Chart Container -->
                        <div class="flex-grow">
                            <canvas id="myChart2" class="!w-full !h-[500px]"></canvas>
                        </div>

                        <!-- Legend Container -->
                        <div id="chartLegend2" class="flex flex-row md:flex-col mr-[32%] lg:mt-[160px] md:mt-[-300px] gap-4 md:gap-0"></div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
                    <script>
                        const ctx2 = document.getElementById('myChart2').getContext('2d');
                        const data2 = {
                            labels: [
                                <?php foreach ($copytradesN as $item) { ?> '<?php echo $item['name']; ?>',
                                <?php } ?>
                            ],
                            datasets: [{
                                label: 'Value',
                                data: [
                                    <?php foreach ($copytradesN as $item) { ?>
                                        <?php echo (float)$item['content']; ?>,
                                    <?php } ?>
                                ],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)',
                                ],
                                borderColor: 'rgba(255, 255, 255, 1)',
                                borderWidth: 2,
                            }]
                        };

                        const config2 = {
                            type: 'pie',
                            data: data2,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false // Tắt chú thích mặc định
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                const dataset = tooltipItem.chart.data.datasets[0];
                                                const total = dataset.data.reduce((sum, value) => sum + value, 0);
                                                const currentValue = dataset.data[tooltipItem.dataIndex];
                                                const percentage = ((currentValue / total) * 100).toFixed(2);
                                                return tooltipItem.label + ': ' + percentage + '%';
                                            }
                                        }
                                    },
                                    datalabels: {
                                        color: '#fff',
                                        formatter: (value, ctx) => {
                                            const dataset = ctx.chart.data.datasets[0];
                                            const total = dataset.data.reduce((sum, val) => sum + val, 0);
                                            const percentage = ((value / total) * 100).toFixed(2);
                                            return `${percentage}%`;
                                        },
                                        anchor: 'center',
                                        align: 'center',
                                    }
                                },
                                layout: {
                                    padding: 20
                                }
                            },
                            plugins: [ChartDataLabels],
                        };

                        const myChart2 = new Chart(ctx2, config2);

                        // Tạo chú thích tùy chỉnh
                        const legendContainer2 = document.getElementById('chartLegend2');
                        data2.datasets[0].backgroundColor.forEach((color, index) => {
                            const label = data2.labels[index];
                            const legendItem = document.createElement('div');
                            legendItem.classList.add('flex', 'items-center', 'space-x-2', 'text-gray-700', 'text-sm');
                            legendItem.innerHTML = `
                <span style="display:inline-block; width:12px; height:12px; background:${color}; margin-right:5px;"></span>
                <span>${label}</span>`;
                            legendContainer2.appendChild(legendItem);
                        });
                    </script>
                <?php } else { ?>
                    <p class="text-gray-300">Không có dữ liệu để hiển thị.</p>
                <?php } ?>

                <hr class="mt-10">

                <?php if ($copytradesP) { ?>
                    <h2 class="text-center text-xl my-5 mb-0 md:mb-8">EMERALPHA AI Momentum</h2>
                    <div class="flex flex-wrap lg:flex-nowrap items-start">
                        <!-- Chart Container -->
                        <div class="flex-grow">
                            <canvas id="myChart3" class="!w-full !h-[500px]"></canvas>
                        </div>

                        <!-- Legend Container -->
                        <div id="chartLegend3" class="flex flex-row md:flex-col mr-[32%] lg:mt-[160px] md:mt-[-300px] gap-4 md:gap-0"></div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
                    <script>
                        const ctx3 = document.getElementById('myChart3').getContext('2d');
                        const data3 = {
                            labels: [
                                <?php foreach ($copytradesP as $item) { ?> '<?php echo $item['name']; ?>',
                                <?php } ?>
                            ],
                            datasets: [{
                                label: 'Value',
                                data: [
                                    <?php foreach ($copytradesP as $item) { ?>
                                        <?php echo (float)$item['content']; ?>,
                                    <?php } ?>
                                ],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)',
                                ],
                                borderColor: 'rgba(255, 255, 255, 1)',
                                borderWidth: 2,
                            }]
                        };

                        const config3 = {
                            type: 'pie',
                            data: data3,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false // Tắt chú thích mặc định
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                const dataset = tooltipItem.chart.data.datasets[0];
                                                const total = dataset.data.reduce((sum, value) => sum + value, 0);
                                                const currentValue = dataset.data[tooltipItem.dataIndex];
                                                const percentage = ((currentValue / total) * 100).toFixed(2);
                                                return tooltipItem.label + ': ' + percentage + '%';
                                            }
                                        }
                                    },
                                    datalabels: {
                                        color: '#fff',
                                        formatter: (value, ctx) => {
                                            const dataset = ctx.chart.data.datasets[0];
                                            const total = dataset.data.reduce((sum, val) => sum + val, 0);
                                            const percentage = ((value / total) * 100).toFixed(2);
                                            return `${percentage}%`;
                                        },
                                        anchor: 'center',
                                        align: 'center',
                                    }
                                },
                                layout: {
                                    padding: 20
                                }
                            },
                            plugins: [ChartDataLabels],
                        };

                        const myChart3 = new Chart(ctx3, config3);

                        // Tạo chú thích tùy chỉnh
                        const legendContainer3 = document.getElementById('chartLegend3');
                        data3.datasets[0].backgroundColor.forEach((color, index) => {
                            const label = data3.labels[index];
                            const legendItem = document.createElement('div');
                            legendItem.classList.add('flex', 'items-center', 'space-x-2', 'text-gray-700', 'text-sm');
                            legendItem.innerHTML = `
                <span style="display:inline-block; width:12px; height:12px; background:${color}; margin-right:5px;"></span>
                <span>${label}</span>`;
                            legendContainer3.appendChild(legendItem);
                        });
                    </script>
                <?php } else { ?>
                    <p class="text-gray-300">Không có dữ liệu để hiển thị.</p>
                <?php } ?>
            </section>
        <?php } else { ?>
            <p class="text-gray-300">Không có thông tin về copytrade.</p>
        <?php } ?>
    </div>

    <div class="bg-gray-900 text-white mt-5 px-4 sm:px-8 lg:px-10 py-5">
        <header class="py-4">
            <div class="container mx-auto flex justify-between items-center">
                <a href="https://forms.office.com/pages/responsepage.aspx?id=DQSIkWdsW0yxEjajBLZtrQAAAAAAAAAAAAO__RWSf11UNU83V1kxNkQ0M0lHUURINktaSE9HTEZPVS4u&route=shorturl" target="_blank" rel="noopener noreferrer">
                    <button class="bg-[rgb(7,130,44)] hover:bg-[rgb(5,110,40)] text-white font-bold py-1 md:px-4 sm:py-3 px-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out">
                        Đăng ký <span class="text-lg font-extrabold text-yellow-400 ms-1">Emeralpha AI Copytrade</span>
                    </button>
                </a>
            </div>
        </header>

        <!-- Banner Section -->
        <div class="mt-5">
            <h1 class="text-2xl md:text-3xl md:text-4xl font-bold text-[rgb(7,130,44)] md:my-5">Cá Nhân Hóa Theo Phong Cách Đầu Tư</h1>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-10">
                <div class="flex flex-col col-span-1 lg:col-span-5 space-y-6 bg-gray-900 text-white p-6 rounded-lg shadow-lg">
                    <ul class="list-disc pl-5 space-y-4">
                        <li class="text-base sm:text-lg">
                            <span class="font-semibold text-green-400">Tận dụng ưu thế vượt trội:</span> Emeralpha AI Trading giúp đầu tư chứng khoán & quản trị danh mục đầu tư tự động trên chính tài khoản của NDT tại công ty chứng khoán hợp tác với Emeralpha Capital Management.
                        </li>
                        <li class="text-base sm:text-lg">
                            <span class="font-semibold text-green-400">Emeralpha AI Copytrade:</span> Sản phẩm quản lý đầu tư nhằm cá nhân hóa nhu cầu, phong cách đầu tư của nhà đầu tư theo tư duy quản trị của giám đốc quỹ đầu tư.
                        </li>
                    </ul>

                    <div class="flex justify-center mt-10 pb-5">
                        <img src="images/logo.png" alt="Logo" class="w-32 rounded-lg shadow-md">
                    </div>

                    <div>
                        <p class="text-lg font-semibold text-green-400 mb-3">Sản phẩm phù hợp với:</p>
                        <ul class="list-disc pl-5 space-y-4">
                            <li class="text-base sm:text-lg">Người bận rộn không có thời gian theo dõi, bám sát cơ hội trên thị trường hàng ngày.</li>
                            <li class="text-base sm:text-lg">NDT ít kinh nghiệm đầu tư, ít có khả năng phân tích và ra quyết định đem lại hiệu quả cao.</li>
                            <li class="text-base sm:text-lg">NDT mong muốn đạt được lợi nhuận tối ưu và quản trị rủi ro hiệu quả theo các giám đốc quỹ đầu tư.</li>
                        </ul>
                        <p class="text-base sm:text-lg mt-4">
                            <span class="font-semibold text-green-400">Qua thời gian:</span> Emeralpha AI Copytrade đem lại lợi nhuận tối ưu vượt trội và bền vững cho NDT.
                        </p>
                    </div>

                    <!-- Phần quản trị rủi ro -->
                    <div class="mt-6">
                        <p class="text-base sm:text-lg">
                            <span class="font-semibold text-green-400">Quản trị rủi ro và vận hành đầu tư:</span> Hệ thống Emeralpha AI quản lý chuyên sâu, tự động hạn chế toàn bộ các cổ phiếu rác, cổ phiếu lãi penny, cổ phiếu lãi tăng vốn ảo có chất lượng tài sản kém thông qua hệ thống đánh giá quản trị từ AI.
                        </p>
                    </div>
                </div>

                <img src="images/robotAI.jpg" alt="traders" class="col-span-1 lg:col-span-7 mx-auto rounded-lg w-full h-auto object-cover pt-[90px]">
            </div>
        </div>

        <!-- Features Section -->
        <div class="flex flex-wrap justify-center gap-16 py-8 bg-gray-700">
            <div class="bg-gray-800 lg:p-4 p-3 rounded-lg w-64 lg:w-72 text-center text-sm lg:h-48 h-[10rem] flex flex-col justify-center items-center">
                <h3 class="text-lg sm:text-xl lg:text-xl font-semibold text-[rgb(7,130,44)] mb-2">Linh hoạt phương thức đầu tư</h3>
                <p>Dễ dàng điều chỉnh danh mục đầu tư của bạn để phù hợp với chiến lược và nhu cầu tài chính cá nhân.</p>
            </div>
            <div class="bg-gray-800 lg:p-6 p-4 rounded-lg w-64 lg:w-72 text-center text-sm lg:h-48 h-[10rem] flex flex-col justify-center items-center">
                <h3 class="text-lg sm:text-xl lg:text-xl font-semibold text-[rgb(7,130,44)] mb-2">Bảo mật thông tin, Minh bạch thành quả</h3>
                <p>Thông tin đầu tư của bạn luôn được bảo vệ, kết quả rõ ràng và minh bạch.</p>
            </div>
            <div class="bg-gray-800 lg:p-6 p-4 rounded-lg w-64 lg:w-72 text-center text-sm lg:h-48 h-[10rem] flex flex-col justify-center items-center">
                <h3 class="text-lg sm:text-xl lg:text-xl font-semibold text-[rgb(7,130,44)] mb-2">Linh hoạt vốn và thời gian đầu tư</h3>
                <p>Tối ưu hóa lợi nhuận với chiến lược đầu tư linh hoạt và hiệu quả.</p>
            </div>
            <div class="bg-gray-800 lg:p-6 p-4 rounded-lg w-64 lg:w-72 text-center text-sm lg:h-48 h-[10rem] flex flex-col justify-center items-center">
                <h3 class="text-lg sm:text-xl lg:text-xl font-semibold text-[rgb(7,130,44)] mb-2">Quản trị khẩu vị rủi ro</h3>
                <p>Kiểm soát rủi ro đầu tư theo mức độ phù hợp với khẩu vị của bạn.</p>
            </div>
        </div>


        <!-- Steps Section -->
        <div class="bg-gray-800 py-12">
            <h2 class="text-center text-2xl sm:text-3xl font-semibold mb-6 lg:mx-0 mx-4">Tham gia EMERALPHA AI COPYTRADE như nào?</h2>
            <ul class="space-y-4 max-w-lg mx-auto">
                <li class="bg-gray-700 p-4 rounded-lg text-base sm:text-lg md:w-full w-64 mx-auto">1. NDT có sẵn hoặc mở mới tài khoản chứng khoán tại danh sách hơn 10 CTCK là đối tác của Emeralpha Capital Management.</li>
                <li class="bg-gray-700 p-4 rounded-lg text-base sm:text-lg md:w-full w-64 mx-auto">2. Khách hàng tham gia Emeralpha AI Copytrade thông qua việc đăng kí giao dịch với Emeralpha Capital Management.</li>
                <li class="bg-gray-700 p-4 rounded-lg text-base sm:text-lg md:w-full w-64 mx-auto">3. Kết nối hệ thống vận hành Emeralpha AI Copytrade.</li>
                <li class="bg-gray-700 p-4 rounded-lg text-base sm:text-lg md:w-full w-64 mx-auto">4. Khách hàng lựa chọn quản lý theo nhu cầu cá nhân: Vốn đầu tư, thời gian, lãi kì vọng…</li>
                <li class="bg-gray-700 p-4 rounded-lg text-base sm:text-lg md:w-full w-64 mx-auto">5. Vận hành quản lý đầu tư tự động từ Emeralpha AI Copytrade.</li>
            </ul>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-3d"></script>
@endsection