<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Emeralpha Capital Management</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="{!! url('styles/base.css') !!}" />
  <link rel="stylesheet" href="{!! url('styles/custom.css') !!}" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#0A822C",
            ["primary-light"]: "rgba(30, 203, 79, 0.55)",
            white: "#fff",
          },
        },
      },
    };
  </script>
</head>

<body>

  @include('layouts.partials.navbar')
  @yield('content')
  @yield('scripts')
  @if(!Route::is('admin.*'))

  <a href="#" class="toast-top-right-open text-white fixed bottom-5 lg:bottom-auto lg:top-5 right-5 ms-auto -mx-1.5 -my-1.5 bg-slate-600 items-center justify-center flex-shrink-0 text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-slate-500 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">
    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 21">
      <path stroke="#c3c3c3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3.464V1.1m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175C17 15.4 17 16 16.462 16H3.538C3 16 3 15.4 3 14.807c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 10 3.464ZM1.866 8.832a8.458 8.458 0 0 1 2.252-5.714m14.016 5.714a8.458 8.458 0 0 0-2.252-5.714M6.54 16a3.48 3.48 0 0 0 6.92 0H6.54Z"></path>
    </svg>
    <span class="sr-only">Load icon</span>
  </a>
  <div id="toast-top-right" class="shadow-md fixed flex items-center w-full max-w-xs p-4 space-x-4 text-gray-300 bg-slate-800 divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow bottom-14 lg:bottom-auto lg:top-14 right-2 dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800" role="alert">
    <div class="flex w-full max-h-96 overflow-x-hidden overflow-y-auto ">
      <div class="w-full">
        <?php if ($announcement): ?>
          <div class="text-yellow !mb-4">*{{$announcement}}</div>
        <?php endif; ?>
        <?php if (count($settings['notice']) > 0) { ?>
          <?php
          $filteredNotice = [];
          foreach ($settings['notice'] as $item) {
            $eitem = $resultExcel[$item['ticker']][0];
            $ranking = $eitem[1];

            $liquidity = str_replace(',', '', $item['liquidity']);
            $liquidity = (int)$liquidity;

            if (in_array($ranking, ['4 - HẠN CHẾ ', '5 - RẤT HẠN CHẾ']) || $liquidity < 250000) {
              continue;
            }
            $filteredNotice[] = $item;
          }
          ?>
          <?php foreach ($filteredNotice as $i => $item) { ?>
            <?php $eitem = $resultExcel[$item['ticker']][0]; ?>
            <div class="flex w-full phu" data-ticker="<?php echo $item['ticker']; ?>" data-xephang="<?php echo $eitem[1]; ?>" data-thanhkhoan="<?php echo $item['liquidity']; ?>">
              <div class="inline-flex items-center justify-center flex-shrink-0 w-6 h-6 text-blue-500 bg-blue-100 rounded-md dark:text-blue-300 dark:bg-blue-900">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 1v5h-5M2 19v-5h5m10-4a8 8 0 0 1-14.947 3.97M1 10a8 8 0 0 1 14.947-3.97" />
                </svg>
                <span class="sr-only">Load icon</span>
              </div>
              <div class="ms-3 text-sm font-normal">
                <span class="mb-1 text-sm font-semibold text-white">{{ $item['ticker'] }}</span>
                <div class="mb-1 text-xs font-normal">
                  <span class="{{ ($item['signal'] == 'MUA') ? 'text-primary' : 'text-red' }}">{{ $item['signal'] }}</span>
                  {{ $item['recommendedPrice'] }} - {{ date('d/m/Y H:i:s', strtotime($item['date'])) }}
                </div>
                <div class="mb-2 text-xs font-normal flex gap-3">
                  <?php echo (isset($item['target1']) && $item['target1'] != '') ? '<span>T1: ' . $item['target1'] . '</span>' : ''; ?>
                  <?php echo (isset($item['target2']) && $item['target2'] != '') ? '<span>T2: ' . $item['target2'] . '</span>' : ''; ?>
                  <?php echo (isset($item['stoploss']) && $item['stoploss'] != '') ? '<span>Stoploss: ' . $item['stoploss'] . '</span>' : ''; ?>
                </div>
              </div>
            </div>
            <?php if ($i <= count($settings['notice'])) { ?>
              <hr class="opacity-25 pb-3 hr-separator">
            <?php } ?>
            <?php if ($i > count($settings['notice'])) {
              break;
            }; ?>
          <?php } ?>
        <?php } ?>
      </div>
      <button type="button" class="ms-auto -mx-1.5 -my-1.5 mr-[0.625rem] mt-[0.125rem] bg-slate-600 items-center justify-center flex-shrink-0 text-gray-400 hover:text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white bg-transparent hover:bg-transparent" data-dismiss-target="#toast-top-right" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
      </button>
    </div>
  </div>
  @endif
  <?php $options = $settings['options']; ?>
  <footer class="lg:pl-[320px] p-8 py-2 bg-zinc-900	lg:fixed bottom-0 w-full text-white overflow-auto">
    <div class="flex flex-col lg:flex-row gap-4 justify-start items-center text-grey text-sm">
      <div class="w-full lg:w-4/12">
        <div class="flex gap-4 flex-row align-center items-center">
          <img class="w-16" src="{!! url('images/logo.png') !!}">
          <div class="text-lg text-primary">
            <h1>Emeralpha Capital</h1>
            <h1>Management</h1>
          </div>
        </div>
      </div>
      <div class="w-full lg:w-4/12">
        <?php if (isset($options['website'])) { ?>
          <div>Website: <a href="http://<?php echo $options['website']['url']; ?>" class="text-white hover:text-primary" target="_blank"><?php echo $options['website']['url']; ?></a></div>
        <?php } ?>
        <?php if (isset($options['email'])) { ?>
          <div>Email: <a href="mailto:<?php echo $options['email']['url']; ?>" class="text-white hover:text-primary" target="_blank"><?php echo $options['email']['url']; ?></a></div>
        <?php } ?>
        <div>Phone:
          <?php if (isset($options['phone1']) && $options['phone1']['url'] != '') { ?>
            <a class="text-white hover:text-primary" href="tel:<?php echo $options['phone1']['url']; ?>" target="_blank"><?php echo $options['phone1']['url']; ?></a>
          <?php } ?>
          <?php if (isset($options['phone2']) && $options['phone2']['url'] != '') { ?>
            -
            <a class="text-white hover:text-primary" href="tel:<?php echo $options['phone2']['url']; ?>" target="_blank"><?php echo $options['phone2']['url']; ?></a>
          <?php } ?>
        </div>
      </div>
      <div class="w-full lg:w-4/12">
        <?php if (isset($options['socials'])) { ?>
          <div class="flex gap-5 lg:justify-end mb-3">
            <?php foreach ($options['socials'] as $i => $item) { ?>
              <a target="_blank" href="{!! nl2br(e($item['url'])) !!}">
                <img class="w-12 h-12 object-contain" src="{{ Storage::url($item['image']) }}">
              </a>
            <?php } ?>
          </div>
        <?php } ?>
        <?php if (isset($options['copyright'])) { ?>
          <div class="flex lg:justify-end text-xs"><?php echo $options['copyright']['url']; ?></div>
        <?php } ?>
      </div>
    </div>
  </footer>
</body>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
<script src="https://pagination.js.org/dist/2.6.0/pagination.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

<script src="{!! url('scripts/script.js') !!}"></script>
<script src="{!! url('scripts/about.js') !!}"></script>
<script src="{!! url('scripts/content.js') !!}"></script>

<script>
  $(".list-box-input").datepicker({
    dateFormat: "yy-mm-dd",
    maxDate: "-1d",
    beforeShowDay: $.datepicker.noWeekends
  });

  $('.list-box-input').on('change', function(e) {
    var $val = $(this).val();
    $('#spinner').removeClass('hidden'); // Hiển thị spinner
    $.ajax({
      url: "{{ url('/ajax') }}",
      type: 'POST',
      data: {
        date: $val
      },
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(result) {
        $('.list-box-id').html(result);
        $('#spinner').addClass('hidden'); // Ẩn spinner
        showBtn();
      },
      error: function(xhr, status, error) {
        $('#spinner').addClass('hidden'); // Ẩn spinner ngay cả khi lỗi
      }
    });
  });
</script>

</html>

<script>
  document.querySelectorAll('.phu').forEach(function(popupItem) {
    popupItem.addEventListener('click', function() {
      const ticker = this.getAttribute('data-ticker');

      const tableRow = document.querySelector(`tr[data-title="${ticker}"]`);

      if (tableRow) {
        tableRow.click();

        tableRow.scrollIntoView({
          behavior: "smooth",
          block: "center"
        });
        tableRow.classList.add('highlight');
        setTimeout(() => tableRow.classList.remove('highlight'), 1500);
      } else {
        console.log("Không tìm thấy hàng với ticker: " + ticker);
      }
    });
  });
</script>