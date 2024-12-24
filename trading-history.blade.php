@extends('layouts.app-master')

@section('content')

<!-- CONTENT -->
<div class="lg:pl-[320px] p-8 pb-48 pt-[108px] lg:pt-8 bg-black min-h-screen h-full text-white overflow-auto">
    <div id="trading">
        <section class="pt-16">
            <div class="flex justify-between items-center pl-4 mb-4 border-l-4 border-black border-white border-solid">
                <h1 class="font-bold text-primary">EMERALPHA AI STOCK HISTORY</h1>
                <div class="flex items-center space-x-2">
                    <input type="text" id="searchInput" placeholder="Tìm kiếm Cổ phần..." class="border border-gray-300 rounded-md p-1 h-8 text-sm text-black" />
                    <input class="max-w-32 lg:max-w-40 list-box-input p-2 bg-[#2B2B2B] rounded-md text-[#fff] text-sm placeholder-[#585858]" name="date" type="text" value="{{ $dateN }}" placeholder="Select date" max="{{ $dateN }}">
                    <div id="spinner" class="hidden h-6 w-6 border-4 border-t-transparent border-white rounded-full animate-spin">o</div>
                </div>
            </div>
            <div class="flex justify-center items-center bg-gray-900 rounded-full p-1 mb-5 max-w-[600px] mx-auto">
                <button class="filterS flex-1 px-4 py-2 text-center border-r text-white rounded-l-full hover:bg-gray-800 focus:outline-none" data-filter="BÁN">BÁN</button>
                <button class="filterS flex-1 px-4 py-2 text-center border-r text-white border-gray-600  hover:bg-gray-800 focus:outline-none" data-filter="NAM_GIU">NẮM GIỮ</button>
                <button class="filterS flex-1 px-4 py-2 text-center border-r text-white rounded-r-full border-gray-600 hover:bg-gray-800 focus:outline-none" data-filter="MUA">MUA</button>
            </div>

            <div class="w-full">
                <!-- TABLE -->
                <div class="list-box list-box-id max-w-full">
                    <?php if (count($dataN) > 0) { ?>
                        <div class="p-4 border border-[#3F3F3F] border-solid rounded-[26px] max-w-full overflow-auto">
                            <table class="table-fix js-table-1 font-light text-white text-xs w-full table-auto">
                                <thead>
                                    <tr class="font-light text-xs text-left text-white">
                                        <th class="min-w-[30px] border-table-left">
                                            <div class="py-3 px-1">Cổ phiếu</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">Tín hiệu</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">Giá khuyến nghị</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">Giá hiện tại</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">Ngày</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">T+</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">Target1</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">Target2</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">Stoploss</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">Tỷ trọng (%)</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">Lãi tạm tính</div>
                                        </th>
                                        <th class="min-w-[30px] border-table-middle">
                                            <div class="py-3 px-1">Thanh khoản 10 phiên</div>
                                        </th>
                                        <th class="min-w-[400px] border-table-middle">
                                            <div class="py-3 text-center pb-1 px-1 border-solid border-b border-b-neutral-700">Khuyến nghị hiện tại</div>
                                            <div class="flex pt-1 pb-3 text-[0.6rem]">
                                                <div class="px-1 w-[10%]">Xếp hạng cơ bản</div>
                                                <div class="px-1 w-[30%]">Loại cổ phiếu</div>
                                                <div class="px-1 w-[30%]">Tỷ trọng tối đa</div>
                                                <div class="px-1 w-[10%]">Sức mạnh giá (%)</div>
                                                <div class="px-1 w-[10%]">ROE (%)</div>
                                                <div class="px-1 w-[10%]">EPS</div>
                                            </div>
                                        </th>
                                        <th class="min-w-[26px] border-table-right"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $k = 0;
                                    $num = 0;
                                    $page = 1;
                                    $countData = count($dataN);
                                    foreach ($dataN as $key => $group) {
                                        $k++;
                                        $count = count($group);
                                        foreach ($group as $i => $item) {
                                            $num++; ?>
                                            <?php
                                            $class = 'text-red';
                                            if ((float)$item['provisionalInterest'] > 0) {
                                                $class = 'text-primary';
                                            } elseif ((float)$item['provisionalInterest'] == 0) {
                                                $class = 'text-yellow';
                                            } ?>
                                            <?php $eitem = $resultExcel[$key][0]; ?>
                                            <tr data-num=<?php echo $num; ?> data-xephang="<?php echo $eitem[1]; ?>" data-tytrongtoida="<?php echo $eitem[3]; ?>" data-loai="<?php echo $eitem[2]; ?>" data-luandiem="<?php echo $eitem[7]; ?>" data-title="{{ $key }}" data-signal="{{ $item['signal'] }}" data-giamua="{{ $item['recommendedPrice'] }}" data-ngaymua="{{ date('d/m/Y', strtotime($item['date'])) }}" data-color="{{ $class }}" data-lai="{{ $item['provisionalInterest'] }}" data-target1="{{ $item['target1'] }}" data-target2="{{ $item['target2'] }}" data-recommend="{{$item['recommendations']}}" class="table-fix-modal-btn hover:opacity-70 hover:cursor-pointer relative tr-page-{{ $page }} tr-count-{{ ($i===1) ? $count:'' }} font-light text-xs text-center__ text-white">
                                                <?php if ($i === 0) { ?>
                                                    <td rowspan="{{ $count }}" class="min-w-fit border-table-left">
                                                        <div class="py-3 px-1">{{ $key }}</div>
                                                    </td>
                                                <?php } ?>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="py-3 px-1">{{ $item['signal'] }}</div>
                                                </td>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="py-3 px-1">{{ $item['recommendedPrice'] }}</div>
                                                </td>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="py-3 px-1">{{ $item['currentPrice'] }}</div>
                                                </td>
                                                <td class="min-w-fit text-[0.6rem] border-table-middle">
                                                    <div class="py-3 px-1">{{ date('d/m/Y H:i:s', strtotime($item['date'])) }}</div>
                                                </td>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="py-3 px-1">{{ $item['transactionTime'] }}</div>
                                                </td>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="py-1 px-2">
                                                        <div class="p-1 rounded-lg bg-primary text-center">&nbsp;{{ $item['target1'] }}&nbsp;</div>
                                                    </div>
                                                </td>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="py-1 px-2">
                                                        <div class="p-1 rounded-lg bg-primary text-center">&nbsp;{{ $item['target2'] }}&nbsp;</div>
                                                    </div>
                                                </td>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="py-3 px-1">{{ $item['stoploss'] }}</div>
                                                </td>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="px-4 py-2">{{ $item['density'] }}</div>
                                                </td>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="py-3 px-1 {{ $class }}">{{ $item['provisionalInterest'] }}</div>
                                                </td>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="py-3 px-1">{{ $item['liquidity'] }}</div>
                                                </td>
                                                <td class="min-w-fit border-table-middle">
                                                    <div class="py-3 text-center pb-1 px-1 border-solid border-b border-b-neutral-700">{{ $item['recommendations'] }}</div>
                                                    <?php if (isset($resultExcel[$key][0])) { ?>
                                                        <?php $eitem = $resultExcel[$key][0]; ?>
                                                        <div class="flex pt-1 pb-3 text-[0.6rem]">
                                                            <div class="px-1 w-[10%]">{{ $eitem[1] }}<?php if ($eitem[1] == "1 - RẤT ƯU TIÊN") echo '<span class="text-yellow-500 ml-1">★</span>'; ?></div>
                                                            <div class="px-1 w-[30%]">{{ $eitem[2] }}</div>
                                                            <div class="px-1 w-[30%]">{{ $eitem[3] }}</div>
                                                            <div class="px-1 w-[10%]">{{ $eitem[4] }}</div>
                                                            <div class="px-1 w-[10%]">{{ round($eitem[5],1) }}</div>
                                                            <div class="px-1 w-[10%]">{{ round($eitem[6],1) }}</div>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <?php if ($i === 0) { ?>
                                                    <td rowspan="{{ $count }}" class="min-w-fit border-table-right"></td>
                                                <?php } ?>
                                            </tr>
                                    <?php }
                                        if ($k % 10 == 0 && $k < $countData) {
                                            $page++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- PAGINATION -->
                        <div class="lg:flex-row flex flex-col items-end lg:justify-between mt-4 lg:items-center pagination">
                            <div class="mb-4 lg:mb-0 text-sm font-light">
                                Hiển thị <span class="data-num-show font-bold">1-10</span> trong
                                <span class="font-bold">{{ $num }}</span> dòng dữ liệu
                            </div>
                            <div class="js-pagination-1 flex flex-row gap-4 align-center">
                                <button data-page="1" class="js-pagination-btn p-2 border border-white border-solid rounded-xl hover:bg-primary-light">
                                    <img src="{!! url('images/ic_chevron.svg') !!}" alt="" />
                                </button>
                                <div data-current="1" class="list-page border border-white border-solid rounded-xl">
                                    <?php for ($i = 1; $i <= $page; $i++) { ?>
                                        <button data-page="{{ $i }}" class="{{ ($i==1)? 'bg-primary':'' }} js-pagination-btn in-list hidden lg:inline px-6 py-2 hover:bg-primary-light rounded-xl">{{ $i }}</button>
                                    <?php } ?>
                                </div>
                                <button data-page="{{ $page }}" class="js-pagination-btn p-2 border border-white border-solid rounded-xl hover:bg-primary-light">
                                    <img src="{{ url('images/ic_chevron_right.svg') }}" alt="" />
                                </button>
                            </div>
                        </div>
                    <?php } else { ?>
                        Không tìm thấy dữ liệu
                    <?php } ?>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="table-fix-modal">
    <div class="modal-body bg-black__ bg-white text-white__">
        <h4 class="text-2xl font-bold text-primary mb-2">EMERALPHA CAPITAL MANAGEMENT</h4>
        <h4 class="font-bold text-3xl leading-tight modal-title">Title</h4>
        <hr class="my-4 opacity-50__">
        <div class="flex gap-4 text-gray-600">
            <div class="w-1/2">
                <div class="text-xl__">Giá <span class="modal-signal lowercase"></span>: <span class="modal-giamua text-white__"></span></div>
                <div class="text-xl__">Ngày <span class="modal-signal lowercase"></span>: <span class="modal-ngaymua text-white__"></span></div>
                <div class="text-xl__">Lãi tạm tính: <span class="modal-lai text-white__"></span></div>
                <div class="text-xl__">Sức mạnh giá: <span class="modal-sucmanhgia text-white__"></span></div>
                <div class="text-xl__">ROE: <span class="modal-roe text-white__"></span></div>
                <div class="text-xl__">EPS: <span class="modal-eps text-white__"></span></div>
            </div>
            <div class="w-1/2">
                <div>Target 1: <span class="modal-target1 text-white__"></span></div>
                <div>Target2: <span class="modal-target2 text-white__"></span></div>
                <div>Tỷ trọng tối đa: <span class="modal-tytrongtoida text-white__"></span></div>
                <div>Thanh khoản trung bình 10 phiên: <span class="modal-thanhkhoan text-white__"></span></div>
                <div>Stop loss : <span class="modal-stoploss text-white__"></span></div>
            </div>
        </div>
        <hr class="my-4 opacity-50__">
        <div class="text-gray-600">Xếp hạng cơ bản: <span class="modal-xephang text-white__"></span></div>
        <div class="text-gray-600">Loại cổ phiếu: <span class="modal-loai text-gray-500 text-white__"></span></div>
        <div class="text-gray-600">Luận điểm đầu tư từ AI: <span class="block modal-luandiem text-white__ text-gray-500 italic"></span></div>
    </div>
</div>

<script>
    document.querySelectorAll('.flex-1').forEach(button => {
        button.addEventListener('click', function() {
            const filterType = this.getAttribute('data-filter');
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const signal = row.getAttribute('data-recommend');
                const title = row.getAttribute('data-title');
                let showRow = false;

                const relatedRows = Array.from(rows).filter(r => r.getAttribute('data-title') === title);

                relatedRows.forEach(r => {
                    const relatedSignal = r.getAttribute('data-recommend');
                    if (filterType === 'MUA' && relatedSignal === 'MUA') {
                        showRow = true;
                    } else if (filterType === 'NAM_GIU' && relatedSignal === 'NẮM GIỮ') {
                        showRow = true;
                    } else if (filterType === 'BÁN' && relatedSignal === 'BÁN') {
                        showRow = true;
                    }
                });

                if (showRow) {
                    relatedRows.forEach(r => r.style.display = '');
                } else {
                    relatedRows.forEach(r => r.style.display = 'none');
                }
            });

            document.querySelectorAll('.flex-1').forEach(btn => {
                btn.classList.remove('text-green-500', 'text-white');
                btn.classList.add('text-white');
            });

            this.classList.add('text-green-500');
        });
    });
</script>

<script>
    const searchInput = document.getElementById('searchInput');
    const pagination = document.querySelector('.pagination');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.table-fix-modal-btn');

        let hasVisibleRows = false;

        rows.forEach(row => {
            const title = row.getAttribute('data-title').toLowerCase();
            if (title.includes(searchTerm)) {
                row.style.display = '';
                hasVisibleRows = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (searchTerm) {
            pagination.style.display = 'none';
        } else {
            pagination.style.display = hasVisibleRows ? '' : 'none';
        }
    });
</script>
@endsection