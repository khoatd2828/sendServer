@extends('layouts.app-master')

@section('content')

<!-- CONTENT -->
<div class="lg:pl-[320px] p-8 pb-48 pt-[108px] lg:pt-8 bg-black min-h-screen h-full text-white overflow-auto">
    <div id="trend">
        @if($data2)
        <section class="pt-0">
            <div class="pl-4 mb-4 border-l-4 border-black border-white border-solid">
                <h1 class="font-bold text-primary">Trạng thái thị trường</h1>
            </div>
            <div class="w-full">
                <div class="font-bold text-4xl w-[200px] leading-tight">{{ $latestData->trend }}</div>
            </div>
        </section>
        @endif

        <?php if (isset($dash1)) {
            $item = $dash1;
        ?>
            <section class="pt-16">
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
        <?php } ?>

        <section class="mt-12">
            <div class="pl-4 mb-4 border-l-4 border-black border-white border-solid">
                <h1 class="font-bold text-primary">Tổng hợp Xu Hướng</h1>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse border border-gray-400 text-white">
                    <thead>
                        <tr class="bg-gray-300 text-primary">
                            <th class="border border-gray-400 px-4 py-2">Mã</th>
                            <th class="border border-gray-400 px-4 py-2">Ngày</th>
                            <th class="border border-gray-400 px-4 py-2">Giá</th>
                            <th class="border border-gray-400 px-4 py-2">Xu Hướng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td class="border border-gray-400 px-4 py-2">{{ $row->ticker }}</td>
                            <td class="border border-gray-400 px-4 py-2">{{ $row->date }}</td>
                            <td class="border border-gray-400 px-4 py-2">{{ number_format($row->close, 2) }}</td>
                            <td class="border border-gray-400 px-4 py-2 font-bold
                                @if($row->trend == 'TĂNG') text-green-500 
                                @elseif($row->trend == 'GIẢM') text-red-500 
                                @else text-yellow-500 @endif">
                                {{ $row->trend }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection
