@extends('layouts.app-master')

@section('content')

<!-- CONTENT -->
<div class="lg:pl-[320px] p-8 pb-48 pt-[108px] lg:pt-8 bg-black min-h-screen h-full text-white overflow-auto">
    <div id="power">
        <?php if (isset($dash2)) {
            $item = $dash2;
        ?>
            <section class="pt-0">
                <div class="pl-4 mb-8 border-l-4 border-black border-white border-solid">
                    <h1 class="font-bold text-primary">{{ $item['name'] }}</h1>
                </div>
                <?php if (isset($item['image'])) { ?>
                    <div class="mb-8">
                        <div class="m-auto max-w-lg border border-solid px-14 border-primary w-fit rounded-xl">
                            <img src="{{ Storage::url($item['image']) }}" alt="chart" />
                        </div>
                    </div>
                <?php } ?>
                <?php if (isset($item['content'])) { ?>
                    <div class="bg-gray-900 text-gray-400 p-4 text-sm">
                        <?php echo nl2br($item['content']); ?>
                    </div>
                <?php } ?>
            </section>
            <section class="pt-10">
                <div class="pl-4 mb-8 border-l-4 border-black border-white border-solid">
                    <h1 class="font-bold text-primary">Emeralpha AI - Tổng hợp sức mạnh thị trường</h1>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-[140%] border-collapse border border-gray-700">
                        <thead>
                            <tr class="bg-gray-300 text-primary">
                                <th class="border border-gray-700 px-4 py-2 mt-5" style="white-space: normal;">Ngày</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: nowrap;">Sức mạnh<br>thị trường</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh Vnindex ngắn hạn</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh Vnindex trung hạn</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh Vnindex dài hạn</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh VN30</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh VN50</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh ngành ngân hàng</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh ngành chứng khoáng </th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh ngành BDS</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh ngành bán lẻ & tiêu dùng</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh ngành công nghệ & viễn thông</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh ngành dầu khí & hóa chất</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh ngành thép & xây dựng</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: normal;">Sức mạnh ngành xuất nhập khẩu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr class="text-gray-300 hover:bg-gray-700 text-center">
                                <!-- Ngày -->
                                <td class="border border-gray-700 px-4 py-2 text-yellow-300 whitespace-nowrap">{{ $item->date }}</td>

                                <!-- Sức mạnh thị trường -->
                                <td class="border border-gray-700 px-4 py-2 font-bold text-sm
                        {{ $item->market_strength == 'HƯNG PHẤN CAO TRÀO' ? 'text-green-500' : 
                           ($item->market_strength == 'LẠC QUAN KỲ VỌNG' ? 'text-yellow-500' : 
                           ($item->market_strength == 'TRUNG TÍNH' ? 'text-orange-500' : 'text-red-500')) }}">
                                    {{ $item->market_strength }}
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->CP_SMA20))
                                    {{ number_format($item->CP_SMA20, 2) }}%
                                    @else
                                    {{ $item->CP_SMA20 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->CP_SMA50))
                                    {{ number_format($item->CP_SMA50, 2) }}%
                                    @else
                                    {{ $item->CP_SMA50 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->CP_SMA200))
                                    {{ number_format($item->CP_SMA200, 2) }}%
                                    @else
                                    {{ $item->CP_SMA200 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->VN30_SMA20))
                                    {{ number_format($item->VN30_SMA20, 2) }}%
                                    @else
                                    {{ $item->VN30_SMA20 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->VN50_SMA20))
                                    {{ number_format($item->VN50_SMA20, 2) }}%
                                    @else
                                    {{ $item->VN50_SMA20 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->bank_SMA20))
                                    {{ number_format($item->bank_SMA20, 2) }}%
                                    @else
                                    {{ $item->bank_SMA20 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->securities_SMA20))
                                    {{ number_format($item->securities_SMA20, 2) }}%
                                    @else
                                    {{ $item->securities_SMA20 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->realEstate_SMA20))
                                    {{ number_format($item->realEstate_SMA20, 2) }}%
                                    @else
                                    {{ $item->realEstate_SMA20 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->retail_SMA20))
                                    {{ number_format($item->retail_SMA20, 2) }}%
                                    @else
                                    {{ $item->retail_SMA20 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->tech_SMA20))
                                    {{ number_format($item->tech_SMA20, 2) }}%
                                    @else
                                    {{ $item->tech_SMA20 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->chemical_SMA20))
                                    {{ number_format($item->chemical_SMA20, 2) }}%
                                    @else
                                    {{ $item->chemical_SMA20 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->steelConstruction_SMA20))
                                    {{ number_format($item->steelConstruction_SMA20, 2) }}%
                                    @else
                                    {{ $item->steelConstruction_SMA20 }}
                                    @endif
                                </td>

                                <td class="border border-gray-700 px-4 py-2">
                                    @if(is_numeric($item->importExport_SMA20))
                                    {{ number_format($item->importExport_SMA20, 2) }}%
                                    @else
                                    {{ $item->importExport_SMA20 }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="pt-10">
                <div class="pl-4 mb-8 border-l-4 border-black border-white border-solid">
                    <h1 class="font-bold text-primary">Emeralpha AI - Top 15 mã cổ phiếu </h1>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-700">
                        <thead>
                            <tr class="bg-gray-300 text-primary">
                                <th class="border border-gray-700 px-4 py-2" style="white-space: nowrap;">Mã</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: nowrap;">Sàn</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: nowrap;">Giá phiên hiện tại </th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: nowrap;">Tăng/ giảm giá phiên hiện tại</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: nowrap;">Khối lượng giao dịch trung bình 10 phiên</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: nowrap;">Khối lượng giao dịch đến hiện tại</th>
                                <th class="border border-gray-700 px-4 py-2" style="white-space: nowrap;">% Tăng so với trung bình 10 phiên</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datatop15 as $item)
                            <tr class="text-gray-300 hover:bg-gray-700 text-center">
                                <td class="border border-gray-700 px-4 py-2 text-red-300">{{ $item->ticker }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ $item->type ?? '' }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ number_format($item->close, 2) }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ number_format($item->price_change_percentage, 2) }}%</td>
                                <td class="border border-gray-700 px-4 py-2">{{ number_format($item->SMA10) }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ number_format($item->vol) }}</td>
                                <td class="border border-gray-700 px-4 py-2">{{ number_format($item->vol_to_SMA10_percentage, 2) }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

        <?php } ?>
    </div>
</div>
@endsection