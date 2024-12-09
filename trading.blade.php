@extends('layouts.app-master')

@section('content')

<!-- CONTENT -->
<div class="lg:pl-[320px] p-8 pb-48 pt-[108px] lg:pt-8 bg-black min-h-screen h-full text-white overflow-auto">
    <div id="trading">
        <section class="pt-0">
            <div class="flex justify-between items-center pl-4 mb-4 border-l-4 border-black border-white border-solid">
                <h1 class="font-bold text-primary">Emeralpha AI Top Khuyến Nghị</h1>
                <!-- <div class=" flex mr-[32px] hidden">
                    <select id="filter" name="filter" onchange="filterData()" class="focus:bg-black focus:outline-none focus:ring-0 bg-black text-[rgb(10,130,44)] font-bold border text-sm rounded-md w-full p-2">
                        <option value="filtered">Không khuyến nghị CP HẠN CHẾ và RẤT HẠN CHẾ</option>
                        <option value="all">Tất cả</option>
                    </select>
                </div> -->
            </div>
            <div class="w-full">
                <!-- TABLE -->
                <div class="list-box max-w-full">
                    <?php
                    if (count($data) > 0) { ?>
                        <?php
                        $dataFiltered = [];

                        foreach ($data as $key => $group) {
                            $filteredGroup = [];
                            foreach ($group as $item) {
                                $ranking = $resultExcel[$key][0][1];
                                $liquidity = str_replace(',', '', $item['liquidity']);
                                $liquidity = (int)$liquidity;

                                if ($ranking === '4 - HẠN CHẾ ' || $ranking === '5 - RẤT HẠN CHẾ' || $liquidity < 250000) {
                                    continue;
                                }

                                $filteredGroup[] = $item;
                            }

                            if (!empty($filteredGroup)) {
                                $dataFiltered[$key] = $filteredGroup;
                            }
                        } ?>
                        <div class="p-2 lg:p-4 border border-[#3F3F3F] border-solid rounded-[26px] max-w-full overflow-auto">
                            <table class="table-fix js-table-1 font-light text-white text-xs w-full table-auto ">
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
                                            <div class="py-3 px-1">Tỷ trọng của <br> Tỷ trọng tối đa (%)</div>
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
                                    $countData = count($dataFiltered);

                                    foreach ($dataFiltered as $key => $group) {
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
                                            <tr
                                                data-title="<?php echo $key; ?>"
                                                data-num="<?php echo $num; ?>"
                                                data-xephang="<?php echo $eitem[1]; ?>"
                                                data-loai="<?php echo $eitem[2]; ?>"
                                                data-luandiem="<?php echo $eitem[7]; ?>"
                                                data-title="<?php echo $key; ?>"
                                                data-signal="<?php echo $item['signal']; ?>"
                                                data-giamua="<?php echo $item['recommendedPrice']; ?>"
                                                data-ngaymua="<?php echo date('d/m/Y', strtotime($item['date'])); ?>"
                                                data-color="<?php echo $class; ?>"
                                                data-lai="<?php echo $item['provisionalInterest']; ?>"
                                                data-target1="<?php echo $item['target1']; ?>"
                                                data-target2="<?php echo $item['target2']; ?>"
                                                data-sucmanhgia="<?php echo $eitem[4]; ?>"
                                                data-roe="<?php echo round($eitem[5], 1); ?>"
                                                data-eps="<?php echo round($eitem[6], 1); ?>"
                                                data-tytrongtoida="<?php echo $eitem[3]; ?>"
                                                data-thanhkhoan="<?php echo $item['liquidity']; ?>"
                                                data-stoploss="<?php echo $item['stoploss']; ?>"
                                                data-recommend="<?php echo $item['recommendations']; ?>"
                                                class="table-fix-modal-btn hover:opacity-70 hover:cursor-pointer relative tr-page-<?php echo $page; ?> tr-count-<?php echo ($i === 1) ? $count : ''; ?> font-light text-xs text-center__ text-white">
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
                                                    <div class="py-3 text-center pb-1 px-1 border-solid border-b border-b-neutral-700"></div>
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
                        <div class="lg:flex-row flex flex-col items-end lg:justify-between mt-4 lg:items-center">
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

        <section class="pt-0 mt-5">
            <div class="flex justify-between items-center pl-4 mb-4 border-l-4 border-black border-white border-solid">
                <h1 class="font-bold text-primary">BEST PERFORMANCE - TOP</h1>
            </div>
            <div class="w-full">
                <!-- TABLE -->
                <div class="list-box max-w-full">
                    <?php
                    if (count($dataN) > 0) {
                        $filteredData = [];
                        foreach ($dataN as $key => $group) {
                            foreach ($group as $item) {
                                $item['provisionalInterest'] = (float)$item['provisionalInterest']; 
                                $filteredData[] = array_merge($item, ['group' => $key]); 
                            }
                        }
                        // Sắp xếp theo lãi tạm tính (giảm dần)
                        usort($filteredData, function ($a, $b) {
                            return $b['provisionalInterest'] <=> $a['provisionalInterest'];
                        });

                        foreach ($filteredData as &$item) {
                            $item['provisionalInterest'] = ($item['provisionalInterest'] >= 0 ? '+' : '') . $item['provisionalInterest'] . '%';
                        }
                        unset($item); 
                        
                        $restructuredData = [];
                        foreach ($filteredData as $item) {
                            $ticker = $item['ticker']; 
                            unset($item['group']); 

                            if (!isset($restructuredData[$ticker])) {
                                $restructuredData[$ticker] = [];
                            }

                            $restructuredData[$ticker][] = $item; 
                        }
                    ?>
                        <div class="p-2 lg:p-4 border border-[#3F3F3F] border-solid rounded-[26px] max-w-full overflow-auto">
                            <table class="table-fix js-table-1 font-light text-white text-xs w-full table-auto ">
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
                                            <div class="py-3 px-1">Tỷ trọng (%) của Tỷ trọng tối đa</div>
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
                                    $num = 0;
                                    $limitedData = array_slice($restructuredData, 0, 15, true);
                                    $countData = count($limitedData);

                                    foreach ($limitedData as $key => $group) {
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
                                            <tr
                                                data-title="<?php echo $key; ?>"
                                                data-num="<?php echo $num; ?>"
                                                data-xephang="<?php echo $eitem[1]; ?>"
                                                data-loai="<?php echo $eitem[2]; ?>"
                                                data-luandiem="<?php echo $eitem[7]; ?>"
                                                data-title="<?php echo $key; ?>"
                                                data-signal="<?php echo $item['signal']; ?>"
                                                data-giamua="<?php echo $item['recommendedPrice']; ?>"
                                                data-ngaymua="<?php echo date('d/m/Y', strtotime($item['date'])); ?>"
                                                data-color="<?php echo $class; ?>"
                                                data-lai="<?php echo $item['provisionalInterest']; ?>"
                                                data-target1="<?php echo $item['target1']; ?>"
                                                data-target2="<?php echo $item['target2']; ?>"
                                                data-sucmanhgia="<?php echo $eitem[4]; ?>"
                                                data-roe="<?php echo round($eitem[5], 1); ?>"
                                                data-eps="<?php echo round($eitem[6], 1); ?>"
                                                data-tytrongtoida="<?php echo $eitem[3]; ?>"
                                                data-thanhkhoan="<?php echo $item['liquidity']; ?>"
                                                data-stoploss="<?php echo $item['stoploss']; ?>"
                                                data-recommend="<?php echo $item['recommendations']; ?>"
                                                class="table-fix-modal-btn hover:opacity-70 hover:cursor-pointer relative tr-page-<?php echo $page; ?> tr-count-<?php echo ($i === 1) ? $count : ''; ?> font-light text-xs text-center__ text-white">
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
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        Không tìm thấy dữ liệu
                    <?php } ?>
                </div>
            </div>
        </section>

        <?php if (isset($dash5)) {
            $item = $dash5;
        ?>
            <section class="pt-16">
                <div class="pl-4 mb-8 border-l-4 border-black border-white border-solid">
                    <h1 class="font-bold text-primary">{{ $item['name'] }}</h1>
                </div>
                <?php if (isset($item['image'])) {
                    $pdf = $item['image'];
                    $pdf = explode('/', $pdf); ?>
                    <div>
                        <div class="m-auto border border-solid py-1 px-14 border-primary w-fit rounded-xl">
                            <a href="{{ Storage::url($item['image']) }}" target="_blank" alt="" class="w-full h-full object-contains flex items-center"><img class="mr-2" width="20px" src="{!! url('images/pdf.png') !!}"> {{ $item['name'] }}</a>
                        </div>
                    </div>
                <?php } ?>

            </section>
        <?php } ?>

        <?php if (isset($dash4)) {
            $item = $dash4;
        ?>
            <section class="pt-16">
                <div class="pl-4 mb-8 border-l-4 border-black border-white border-solid">
                    <h1 class="font-bold text-primary">{{ $item['name'] }}</h1>
                </div>
                <?php if (isset($item['image'])) {
                    $images = json_decode($dash4['image']); ?>
                    <div class="flex flex-wrap flex-row justify-content">
                        <?php foreach ($images as $image) { ?>
                            <?php if (isset($image)) { ?>
                                <div class="w-1/3 px-2">
                                    <div class="border overflow-hidden border-solid border-primary w-full rounded-xl">
                                        <img class="max-w-full mx-auto" src="{{ Storage::url($image) }}" alt="chart" />
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            </section>
        <?php } ?>


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

<!-- <script>
    document.addEventListener("DOMContentLoaded", () => {
        const filterSelect = document.getElementsByClassName("filter-class")[0]; 
        filterSelect.value = "filtered";
        filterData();  
        console.log("Initial filter set to 'filtered'");

        const paginationButtons = document.querySelectorAll(".js-pagination-btnn");
        paginationButtons.forEach(button => {
            button.addEventListener("click", () => {
                console.log("Pagination button clicked");
                setTimeout(() => {
                    filterData();
                }, 100);
            });
        });
    });

    function filterData() {
        const filterValue = document.getElementsByClassName("filter-class")[0].value; 
        console.log("Current filter value:", filterValue);  
        const rows = document.querySelectorAll("tbody tr");
        rows.forEach(row => {
            const basicRating = row.getAttribute("data-xephang");
            const liquidityString = row.getAttribute("data-thanhkhoan");
            const liquidity = parseInt(liquidityString.replace(/,/g, ''), 10);
            row.style.display = "";  

            console.log("Row data:", {
                basicRating: basicRating,
                liquidity: liquidity
            }); 

            if (filterValue === "filtered") {
                if (basicRating === "4 - HẠN CHẾ " || basicRating === "5 - RẤT HẠN CHẾ") {
                    row.style.display = "none";
                    console.log(`Hiding row with basic rating: ${basicRating}`);
                }
                if (liquidity < 250000) {
                    row.style.display = "none";
                    console.log(`Hiding row due to low liquidity: ${liquidity}`);
                }
            }
        });
    }
</script> -->

<!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const socket = new WebSocket('http://emeralphaaii.local:8080');

        socket.addEventListener('message', function(event) {
            // Parse lần đầu tiên
            let data = JSON.parse(event.data);

            // Kiểm tra nếu dữ liệu vẫn là chuỗi sau lần parse đầu tiên
            if (typeof data === "string") {
                // Nếu vẫn là chuỗi, parse lần thứ hai để chuyển thành mảng
                data = JSON.parse(data);
            }

            // Duyệt qua từng mục dữ liệu
            data.forEach(item => {
                const rows = document.querySelectorAll(`tr[data-title="${item.ticker}"]`);

                rows.forEach((row, index) => {
                    // Điền dữ liệu vào các thẻ <div> rỗng trong mỗi ô của hàng
                    let div = row.querySelectorAll('.border-table-middle')[0]?.querySelector('div');
                    if (div) div.innerText = item.signal;

                    div = row.querySelectorAll('.border-table-middle')[1]?.querySelector('div');
                    if (div) div.innerText = item.recommendedPrice;

                    div = row.querySelectorAll('.border-table-middle')[2]?.querySelector('div');
                    if (div) div.innerText = item.currentPrice;

                    div = row.querySelector('.text-\\[0\\.6rem\\].border-table-middle div');
                    if (div) div.innerText = new Date(item.date).toLocaleString();

                    div = row.querySelectorAll('.border-table-middle')[4]?.querySelector('div');
                    if (div) div.innerText = item.transactionTime;

                    // Giữ nguyên lớp và thêm padding cho target1, nếu không có dữ liệu thì điền &nbsp;
                    div = row.querySelectorAll('.border-table-middle')[5]?.querySelector('div > .bg-primary');
                    if (div) {
                        if (item.target1) {
                            div.innerText = item.target1;
                        } else {
                            div.innerHTML = "&nbsp;";
                        }
                        div.classList.add('py-1', 'px-2');
                    }

                    // Giữ nguyên lớp và thêm padding cho target2, nếu không có dữ liệu thì điền &nbsp;
                    div = row.querySelectorAll('.border-table-middle')[6]?.querySelector('div > .bg-primary');
                    if (div) {
                        if (item.target2) {
                            div.innerText = item.target2;
                        } else {
                            div.innerHTML = "&nbsp;";
                        }
                        div.classList.add('py-1', 'px-2');
                    }

                    div = row.querySelectorAll('.border-table-middle')[7]?.querySelector('div');
                    if (div) div.innerText = item.stoploss;

                    div = row.querySelectorAll('.border-table-middle')[8]?.querySelector('div');
                    if (div) div.innerText = item.density;

                    div = row.querySelectorAll('.border-table-middle')[9]?.querySelector('div');
                    if (div) {
                        div.innerText = item.provisionalInterest;

                        // Đảm bảo màu sắc của thẻ được thay đổi chính xác dựa trên giá trị provisionalInterest
                        div.classList.remove('text-red', 'text-primary', 'text-yellow');
                        if (parseFloat(item.provisionalInterest) > 0) {
                            div.classList.add('text-primary');
                        } else if (parseFloat(item.provisionalInterest) === 0) {
                            div.classList.add('text-yellow');
                        } else {
                            div.classList.add('text-red');
                        }
                    }

                    div = row.querySelectorAll('.border-table-middle')[10]?.querySelector('div');
                    if (div) div.innerText = item.liquidity;

                    // Điền dữ liệu vào phần recommendations
                    div = row.querySelectorAll('.border-table-middle')[11]?.querySelector('div:first-child');
                    if (div) div.innerText = item.recommendations;
                });
            });
        });

        socket.addEventListener('open', () => {
            console.log('WebSocket đã được kết nối');
        });

        socket.addEventListener('close', () => {
            console.log('WebSocket đã bị đóng');
        });

        socket.addEventListener('error', (error) => {
            console.error('Lỗi WebSocket:', error);
        });
    });
</script> -->
@endsection