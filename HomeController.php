<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Dashboard;
use App\Models\About;
use App\Imports\DashboardImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {

            $date = date('Y-m-d');

            // $options = array(
            //     'http' => array(
            //         'method' => 'POST',
            //         'header' => "Content-type: application/json\r\n",
            //         'content' => '{"BuySellRequest": {"date":"' . $date . '"}}',
            //     )
            // );

            // $context  = stream_context_create($options);

            // $json = file_get_contents($url, false, $context);
            // $json_data = json_decode($json, true);
            // if (isset($json_data['BuySellReply']['stockDatas'])) {
            //     $data = $json_data['BuySellReply']['stockDatas'];
            //     $result = [];
            //     foreach ($data as $element) {
            //         $result[$element['ticker']][] = $element;
            //     }
            //     $data = $result;
            // } else {
            //     $data = [];
            // }

            $json_data = $this->get_data($date);

            $data = $json_data['BuySellReply']['stockDatas'];

            usort(
                $data,
                function ($element1, $element2) {
                    $datetime1 = strtotime($element1['date']);
                    $datetime2 = strtotime($element2['date']);
                    return $datetime2 - $datetime1;
                }
            );

            $dataNotice = $data;

            $result = [];
            foreach ($data as $element) {
                $result[$element['ticker']][] = $element;
            }
            $data = $result;
            $dateN = $json_data['BuySellReply']['date'];

            // Num 2

            $url2 = "https://stocktraders.vn/service/data/getStockMarket";

            $options2 = array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-type: application/json\r\n",
                    'content' => '{"StockMarketRequest": {"date":"2024-07-12"}}',
                )
            );

            $context2  = stream_context_create($options2);

            $json2 = file_get_contents($url2, false, $context2);
            $json_data2 = json_decode($json2, true);

            $data2 = $json_data2['StockMarketReply']['keyVal'];


            $dateN = $json_data['BuySellReply']['date'];
            $dateN = date('Y-m-d', strtotime('-1 days', strtotime($dateN)));

            $json_dataN = $this->get_data($dateN);
            $dataN = $json_dataN['BuySellReply']['stockDatas'];

            usort(
                $dataN,
                function ($element1, $element2) {
                    $datetime1 = strtotime($element1['date']);
                    $datetime2 = strtotime($element2['date']);
                    return $datetime2 - $datetime1;
                }
            );

            $resultN = [];
            foreach ($dataN as $elementN) {
                $resultN[$elementN['ticker']][] = $elementN;
            }
            $dataN = $resultN;
            $dateN = $json_dataN['BuySellReply']['date'];
            // $dateN = date('Y-m-d', strtotime('-1 days', strtotime($dateN)));

            $images = Dashboard::all();

            return view('home.index', compact('data', 'dataNotice', 'dataN', 'dateN', 'data2', 'images'));
        } else {
            return redirect('login');
        }
    }

    public function trading()
    {
        $date = date('Y-m-d');

        $json_data = $this->get_data($date);

        $data = $json_data['BuySellReply']['stockDatas'];

        usort(
            $data,
            function ($element1, $element2) {
                $datetime1 = strtotime($element1['date']);
                $datetime2 = strtotime($element2['date']);
                return $datetime2 - $datetime1;
            }
        );

        $dataNotice = $data;

        $result = [];
        foreach ($data as $element) {
            $result[$element['ticker']][] = $element;
        }
        $data = $result;
        $dateN = $json_data['BuySellReply']['date'];

        $dateN = $json_data['BuySellReply']['date'];
        $dateN = date('Y-m-d', strtotime('-1 days', strtotime($dateN)));

        $json_dataN = $this->get_data($dateN);
        $dataN = $json_dataN['BuySellReply']['stockDatas'];

        usort(
            $dataN,
            function ($element1, $element2) {
                $datetime1 = strtotime($element1['date']);
                $datetime2 = strtotime($element2['date']);
                return $datetime2 - $datetime1;
            }
        );

        $resultN = [];
        foreach ($dataN as $elementN) {
            $resultN[$elementN['ticker']][] = $elementN;
        }
        $dataN = $resultN;
        $dateN = $json_dataN['BuySellReply']['date'];
        // $dateN = date('Y-m-d', strtotime('-1 days', strtotime($dateN)));

        $dash5 = Dashboard::find(5);
        $dash4 = Dashboard::find(4);
        $dash6 = Dashboard::find(6);

        $excel = Excel::toArray(new DashboardImport, $dash6['image']);
        $excel = $excel[0];
        unset($excel[0]);
        unset($excel[1]);
        $resultExcel = [];
        foreach ($excel as $excelItem) {
            $resultExcel[$excelItem[0]][] = $excelItem;
        }
        return view('home.trading', compact('data', 'dataN', 'dateN', 'dash5', 'dash4', 'resultExcel'));
    }

    public function tradingHistory()
    {
        $date = date('Y-m-d');

        $json_data = $this->get_data($date);

        $data = $json_data['BuySellReply']['stockDatas'];

        $dataP = $json_data['BuySellReply'];

        usort(
            $data,
            function ($element1, $element2) {
                $datetime1 = strtotime($element1['date']);
                $datetime2 = strtotime($element2['date']);
                return $datetime2 - $datetime1;
            }
        );

        $dataNotice = $data;

        $result = [];
        foreach ($data as $element) {
            $result[$element['ticker']][] = $element;
        }
        $data = $result;
        $dateN = $json_data['BuySellReply']['date'];

        $dateN = $json_data['BuySellReply']['date'];
        $dateN = date('Y-m-d', strtotime('-1 days', strtotime($dateN)));

        $json_dataN = $this->get_data($dateN);
        $dataN = $json_dataN['BuySellReply']['stockDatas'];

        usort(
            $dataN,
            function ($element1, $element2) {
                $datetime1 = strtotime($element1['date']);
                $datetime2 = strtotime($element2['date']);
                return $datetime2 - $datetime1;
            }
        );

        $resultN = [];
        foreach ($dataN as $elementN) {
            $resultN[$elementN['ticker']][] = $elementN;
        }
        $dataN = $resultN;
        $dateN = $json_dataN['BuySellReply']['date'];

        $dash5 = Dashboard::find(5);
        $dash4 = Dashboard::find(4);
        $dash6 = Dashboard::find(6);

        $excel = Excel::toArray(new DashboardImport, $dash6['image']);
        $excel = $excel[0];
        unset($excel[0]);
        unset($excel[1]);
        $resultExcel = [];
        foreach ($excel as $excelItem) {
            $resultExcel[$excelItem[0]][] = $excelItem;
        }

        return view('home.trading-history', compact('data', 'dataN', 'dateN', 'dash5', 'dash4', 'resultExcel'));
    }

    public function trends()
    {
        $url2 = "https://stocktraders.vn/service/data/getStockMarket";
        $options2 = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json\r\n",
                'content' => '{"StockMarketRequest": {"date":"2024-12-18"}}',
            )
        );
        $context2  = stream_context_create($options2);
        $json2 = file_get_contents($url2, false, $context2);
        $json_data2 = json_decode($json2, true);
        $data2 = $json_data2['StockMarketReply']['keyVal'];
        $dataP = $json_data2['StockMarketReply'];

        $dash1 = Dashboard::find(1);

        $data = DB::table('uptrends')
            ->where('ticker', 'VNINDEX')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        $data = $data->map(function ($row) {
            if ($row->close > $row->sma20 && $row->ema6 > $row->sma20) {
                $row->trend = "UPTREND";
            } elseif ($row->close < $row->sma20 && $row->ema6 < $row->sma20) {
                $row->trend = "DOWNTREND";
            } else {
                $row->trend = "SIDEWAY";
            }
            return $row;
        });

        $latestData = $data->first();

        return view('home.trends', compact('data2', 'dash1', 'dataP', 'data', 'latestData'));
    }

    public function power()
    {
        $dash2 = Dashboard::find(2);

        $data = DB::table('Daily_Percentages_on_SMA')
            ->orderBy('date', 'desc')
            ->limit(8)
            ->get();

        $data = $data->map(function ($item) {
            if ($item->{'CP_SMA20'} >= 68) {
                $item->market_strength = 'HƯNG PHẤN';
            } elseif ($item->{'CP_SMA20'} >= 55) {
                $item->market_strength = 'TRUNG TÍNH';
            } elseif ($item->{'CP_SMA20'} >= 40) {
                $item->market_strength = 'HOANG MANG';
            } else {
                $item->market_strength = 'HOẢNG SỢ';
            }
            return $item;
        });

        $datatop15 = DB::table('TOP15')
            ->orderBy('vol', 'desc')
            ->limit(15)
            ->get();

        return view('home.power', compact('dash2', 'data', 'datatop15'));
    }

    public function hexagram()
    {
        $dash3 = Dashboard::find(3);
        return view('home.hexagram', compact('dash3'));
    }
    public function consultation()
    {
        $dash7 = Dashboard::find(7);
        return view('home.consultation', compact('dash7'));
    }
    public function evaluation()
    {
        $evaluation = About::find(4);
        $evaluations = About::where('parent', 4)->get();
        return view('home.evaluation', compact('evaluation', 'evaluations'));
    }
    public function copytrade()
    {
        $copytrade = About::find(5);
        $copytrades = About::where('parent', 5)->get();

        $copytradeN = About::find(6);
        $copytradesN = About::where('parent', 6)->get();

        $copytradeP = About::find(7);
        $copytradesP = About::where('parent', 7)->get();

        $dash6 = Dashboard::find(6);

        // Đọc dữ liệu từ tệp Excel
        $excel = Excel::toArray(new DashboardImport, $dash6['image']);
        $excel = $excel[0]; // Lấy dữ liệu từ sheet đầu tiên

        // Loại bỏ các hàng không cần thiết (nếu cần)
        unset($excel[0], $excel[1]); // Loại bỏ hàng tiêu đề

        // Xử lý dữ liệu Excel
        $resultExcel = [];
        foreach ($excel as $excelItem) {
            $resultExcel[$excelItem[0]][] = $excelItem;
        }
        return view('home.copytrade', compact('resultExcel', 'copytrade', 'copytrades', 'copytradeN', 'copytradesN', 'copytradeP', 'copytradesP'));
    }
    public function manual()
    {
        $dash8 = Dashboard::find(8);
        $dash9 = Dashboard::find(9);
        return view('home.manual', compact('dash8', 'dash9'));
    }

    public function lookup()
    {
        $date = date('Y-m-d');

        $json_data = $this->get_data($date);

        $data = $json_data['BuySellReply']['stockDatas'];

        usort(
            $data,
            function ($element1, $element2) {
                $datetime1 = strtotime($element1['date']);
                $datetime2 = strtotime($element2['date']);
                return $datetime2 - $datetime1;
            }
        );

        $dataNotice = $data;

        $result = [];
        foreach ($data as $element) {
            $result[$element['ticker']][] = $element;
        }
        $data = $result;
        $dateN = $json_data['BuySellReply']['date'];

        $dateN = $json_data['BuySellReply']['date'];
        $dateN = date('Y-m-d', strtotime('-1 days', strtotime($dateN)));

        $json_dataN = $this->get_data($dateN);
        $dataN = $json_dataN['BuySellReply']['stockDatas'];

        usort(
            $dataN,
            function ($element1, $element2) {
                $datetime1 = strtotime($element1['date']);
                $datetime2 = strtotime($element2['date']);
                return $datetime2 - $datetime1;
            }
        );

        $resultN = [];
        foreach ($dataN as $elementN) {
            $resultN[$elementN['ticker']][] = $elementN;
        }
        $dataN = $resultN;
        $dateN = $json_dataN['BuySellReply']['date'];
        // $dateN = date('Y-m-d', strtotime('-1 days', strtotime($dateN)));

        $dash5 = Dashboard::find(5);
        $dash4 = Dashboard::find(4);
        $dash6 = Dashboard::find(6);

        $excel = Excel::toArray(new DashboardImport, $dash6['image']);
        $excel = $excel[0];
        unset($excel[0]);
        unset($excel[1]);
        $resultExcel = [];
        foreach ($excel as $excelItem) {
            $resultExcel[$excelItem[0]][] = $excelItem;
        }

        $dataH = $this->getLast40TradingSessions($date);

        return view('home.lookup', compact('data', 'dataN', 'dataH', 'dateN', 'dash5', 'dash4', 'resultExcel'));
    }

    function get_data($date)
    {
        $url = "https://stocktraders.vn/service/data/getBuySell";
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json\r\n",
                'content' => '{"BuySellRequest": {"date":"' . $date . '"}}',
            )
        );

        $context  = stream_context_create($options);
        $json = file_get_contents($url, false, $context);
        $json_data = json_decode($json, true);

        if (isset($json_data['BuySellReply']['stockDatas'])) {
            return $json_data;
        } else {
            $date = date('Y-m-d', strtotime('-1 days', strtotime($date)));
            return $this->get_data($date);
        }
    }

    public function ajax()
    {
        if (Auth::check()) {
            
            $date = request()->input('date');

            if (!$date) {
                return response()->json(['error' => 'Ngày không hợp lệ'], 400);
            }

            $url = "https://stocktraders.vn/service/data/getBuySell";

            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-type: application/json\r\n",
                    'content' => '{"BuySellRequest": {"date":"' . $date . '"}}',
                )
            );

            $context  = stream_context_create($options);

            $json = file_get_contents($url, false, $context);
            $json_data = json_decode($json, true);

            if (isset($json_data['BuySellReply']['stockDatas'])) {
                $data = $json_data['BuySellReply']['stockDatas'];
                usort(
                    $data,
                    function ($element1, $element2) {
                        $datetime1 = strtotime($element1['date']);
                        $datetime2 = strtotime($element2['date']);
                        return $datetime2 - $datetime1;
                    }
                );
                $result = [];
                foreach ($data as $element) {
                    $result[$element['ticker']][] = $element;
                }
                $data = $result;
            } else {
                $data = [];
            }
            $dash6 = Dashboard::find(6);

            $excel = Excel::toArray(new DashboardImport, $dash6['image']);
            $excel = $excel[0];
            unset($excel[0]);
            unset($excel[1]);
            $resultExcel = [];
            foreach ($excel as $excelItem) {
                $resultExcel[$excelItem[0]][] = $excelItem;
            }

            ob_start();  ?>
            <?php if (count($data) > 0) { ?>
                <div class="p-4 border border-[#3F3F3F] border-solid rounded-[26px] lg:max-w-full max-w-fit overflow-auto">
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
                                    <div class="py-3 px-1">Tỷ trọng</div>
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
                            $countData = count($data);
                            foreach ($data as $key => $group) {
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
                                        data-num="<?php echo $num; ?>"
                                        data-xephang="<?php echo $eitem[1]; ?>"
                                        data-loai="<?php echo $eitem[2]; ?>"
                                        data-tytrongtoidanav="<?php echo $eitem[3]; ?>"
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
                                        data-tytrongtoida="<?php echo $item['density']; ?>"
                                        data-thanhkhoan="<?php echo $item['liquidity']; ?>"
                                        data-stoploss="<?php echo $item['stoploss']; ?>"
                                        class="table-fix-modal-btn hover:opacity-70 hover:cursor-pointer relative tr-page-<?php echo $page; ?> tr-count-<?php echo ($i === 1) ? $count : ''; ?> font-light text-xs text-center__ text-white">
                                        <?php if ($i === 0) { ?>
                                            <td rowspan="<?php echo $count; ?>" class="min-w-fit border-table-left">
                                                <div class="py-3 px-1"><?php echo $key; ?></div>
                                            </td>
                                        <?php } ?>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="py-3 px-1"><?php echo $item['signal']; ?></div>
                                        </td>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="py-3 px-1"><?php echo $item['recommendedPrice']; ?></div>
                                        </td>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="py-3 px-1"><?php echo $item['currentPrice']; ?></div>
                                        </td>
                                        <td class="min-w-fit text-[0.6rem] border-table-middle">
                                            <div class="py-3 px-1"><?php echo date('d/m/Y H:i:s', strtotime($item['date'])); ?></div>
                                        </td>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="py-3 px-1"><?php echo $item['transactionTime']; ?></div>
                                        </td>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="py-1 px-2">
                                                <div class="p-1 rounded-lg bg-primary text-center">&nbsp;<?php echo $item['target1']; ?>&nbsp;</div>
                                            </div>
                                        </td>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="py-1 px-2">
                                                <div class="p-1 rounded-lg bg-primary text-center">&nbsp;<?php echo $item['target2']; ?>&nbsp;</div>
                                            </div>
                                        </td>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="py-3 px-1"><?php echo $item['stoploss']; ?></div>
                                        </td>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="px-4 py-2"><?php echo $item['density']; ?></div>
                                        </td>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="py-3 px-1 <?php echo $class; ?>"><?php echo $item['provisionalInterest']; ?></div>
                                        </td>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="py-3 px-1"><?php echo $item['liquidity']; ?></div>
                                        </td>
                                        <td class="min-w-fit border-table-middle">
                                            <div class="py-3 text-center pb-1 px-1 border-solid border-b border-b-neutral-700"><?php echo $item['recommendations']; ?></div>
                                            <?php if (isset($resultExcel[$key][0])) { ?>
                                                <?php $eitem = $resultExcel[$key][0]; ?>
                                                <div class="flex pt-1 pb-3 text-[0.6rem]">
                                                    <div class="px-1 w-[10%]"><?php echo $eitem[1]; ?></div>
                                                    <div class="px-1 w-[30%]"><?php echo $eitem[2]; ?></div>
                                                    <div class="px-1 w-[30%]"><?php echo $eitem[3]; ?></div>
                                                    <div class="px-1 w-[10%]"><?php echo $eitem[4]; ?></div>
                                                    <div class="px-1 w-[10%]"><?php echo round($eitem[5], 1); ?></div>
                                                    <div class="px-1 w-[10%]"><?php echo round($eitem[6], 1); ?></div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <?php if ($i === 0) { ?>
                                            <td rowspan="<?php echo $count; ?>" class="min-w-fit border-table-right"></td>
                                        <?php } ?>
                                    </tr>
                            <?php }
                                if ($k % 5 == 0 && $k < $countData) {
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
                        <span class="font-bold"><?php echo $num; ?></span> dòng dữ liệu
                    </div>
                    <div class="js-pagination-1 flex flex-row gap-4 align-center">
                        <button data-page="1" class="js-pagination-btn p-2 border border-white border-solid rounded-xl hover:bg-primary-light">
                            <img src="<?php echo url('images/ic_chevron.svg'); ?>" alt="" />
                        </button>
                        <div data-current="1" class="list-page border border-white border-solid rounded-xl">
                            <?php for ($i = 1; $i <= $page; $i++) { ?>
                                <button data-page="<?php echo $i; ?>" class="<?php echo ($i == 1) ? 'bg-primary' : ''; ?> js-pagination-btn in-list hidden lg:inline px-6 py-2 hover:bg-primary-light rounded-xl"><?php echo $i; ?></button>
                            <?php } ?>
                        </div>
                        <button data-page="<?php echo $page; ?>" class="js-pagination-btn p-2 border border-white border-solid rounded-xl hover:bg-primary-light">
                            <img src="<?php echo url('images/ic_chevron_right.svg'); ?>" alt="" />
                        </button>
                    </div>
                </div>
            <?php } else { ?>
                Không tìm thấy dữ liệu
            <?php } ?>
<?php $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }

    public function about()
    {
        if (Auth::check()) {
            $how = About::find(1);
            $speak = About::find(2);
            $testi = About::find(3);
            $hows = About::where('parent', 1)->get();
            $speaks = About::where('parent', 2)->get();
            $testis = About::where('parent', 3)->get();
            return view('home.about', compact('how', 'speak', 'hows', 'speaks', 'testi', 'testis'));
        } else {
            return redirect('login');
        }
    }
}
