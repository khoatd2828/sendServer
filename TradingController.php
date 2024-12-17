<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class TradingController extends Controller
{
    public function streamData()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Tắt buffering nếu dùng Nginx

        while (true) {
            // Lấy ngày hiện tại
            $date = date('Y-m-d');
            $json_data = $this->get_data($date);

            // Kiểm tra dữ liệu trả về
            $data = $json_data['BuySellReply']['stockDatas'] ?? [];

            // Sắp xếp dữ liệu theo ngày giảm dần
            usort($data, function ($element1, $element2) {
                $datetime1 = strtotime($element1['date']);
                $datetime2 = strtotime($element2['date']);
                return $datetime2 - $datetime1;
            });

            // Tổ chức dữ liệu theo cấu trúc giống `today`
            $result = [];
            foreach ($data as $element) {
                $result[$element['ticker']][] = $element;
            }

            // Trả dữ liệu SSE với tên "today"
            echo "data: " . json_encode($result) . "\n\n";

            // Đẩy dữ liệu ngay lập tức
            ob_flush();
            flush();

            // Chờ 1 giây trước khi gửi tiếp
            sleep(1);
        }
    }



    public function get_data($date)
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
}
