<header class="fixed lg:hidden bg-black flex justify-between w-full align-center z-50">
  <div class="p-2 px-8 text-sm text-primary">
    <div class="flex gap-2 flex-row align-center items-center">
      <img class="w-14 lg:w-16" src="{!! url('images/logo.png') !!}">
      <div>
        <h1>Emeralpha Capital</h1>
        <h1>Management</h1>
      </div>
    </div>
  </div>
  <button id="nav-hamburger" type="button" class="ml-6 w-20 h-20 p-2 text-sm text-gray-500 rounded-lg">
    <svg class="w-10 h-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
    </svg>
  </button>
</header>
<nav id="navbar" class="w-[300px] h-full block fixed left-0 top-0 z-20 bg-black border-r border-solid border-[#9E9E9E] z-50">
  <div class="navbar-scroll mx-auto overflow-auto h-full">
    <div id="nav-menu" class="w-full">
      <div class="p-8 px-4 text-lg text-primary">
        <div class="flex gap-2 flex-row align-center items-center">
          <img class="w-16" src="{!! url('images/logo.png') !!}">
          <div>
            <h1>Emeralpha Capital</h1>
            <h1>Management</h1>
          </div>
        </div>
      </div>
      <div class="p-4 px-4 flex flex-row align-center text-[#9E9E9E]" id="avarta">
        <div>
          <div class="mb-2">Hello 👋</div>
          <div class="text-lg text-white">{{auth()->user()->username}}</div>
        </div>
      </div>

      <ul class="flex p-4 py-2 flex-col font-medium text-[#9E9E9E]">
        <li>
          <a href="javascript:;" class="flex flex-row p-3 py-2 items-center align-center hover:bg-primary-light @if(in_array(Request::url(), [url('/'), url('/ai-trading'), url('/ai-trading-history'), url('/user-manual'), url('/stock-evaluation'), url('/private-consultation'), url('/strategic-hexagram'), url('/ai-copytrade'), url('/psychology-power'), url('/market-trends')])) {{ 'bg-primary' }} @endif" aria-controls="dropdown-dashboard" data-collapse-toggle="dropdown-dashboard">
            <img class="w-[22px] height-[22px]" src="{!! url('images/menu-dashboard.svg') !!}" alt="" />
            <span class="block w-full px-3 py-2 rounded md:bg-transparent">Dashboard</span>
            <svg class="w-3 h-3" aria-hidden="@if(in_array(Request::url(), [url('/'), url('/ai-trading')])) {{ 'true' }} @else {{ 'false' }} @endif" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
            </svg>
          </a>
          <ul id="dropdown-dashboard" class="list-disc pl-11 @if(in_array(Request::url(), [url('/'), url('/ai-trading'), url('/ai-trading-history'), url('/user-manual'), url('/stock-evaluation'), url('/private-consultation'), url('/strategic-hexagram'), url('/ai-copytrade'), url('/psychology-power'), url('/market-trends')])) {{ 'hidden__' }} @else {{ 'hidden' }} @endif py-2 space-y-2">
            <li>
              <a href="{{ url('/ai-trading') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/ai-trading') === Request::url()) {{ 'text-primary' }} @endif">EMERALPHA AI TRADING SYSTEM</a>
            </li>
            <li>
              <a href="{{ url('/stock-lockup') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/stock-lockup') === Request::url()) {{ 'text-primary' }} @endif">EMERALPHA AI Tầm soát cổ phiếu</a>
            </li>
            <li>
              <a href="{{ url('/market-trends') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/market-trends') === Request::url()) {{ 'text-primary' }} @endif">Xu hướng thị trường & <br> Tỷ trọng danh mục</a>
            </li>
            <li>
              <a href="{{ url('/psychology-power') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/psychology-power') === Request::url()) {{ 'text-primary' }} @endif">Sức mạnh thị trường & Tâm lý thị trường</a>
            </li>
            <li>
              <a href="{{ url('/strategic-hexagram') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/strategic-hexagram') === Request::url()) {{ 'text-primary' }} @endif">EMERALPHA AI Gieo quẻ chiến lược</a>
            </li>
            <li>
              <a href="{{ url('/ai-copytrade') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/ai-copytrade') === Request::url()) {{ 'text-primary' }} @endif">EMERALPHA AI COPYTRADE</a>
            </li>
            <li>
              <a href="{{ url('/private-consultation') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/private-consultation') === Request::url()) {{ 'text-primary' }} @endif">Nâng cấp Tư vấn riêng 1:1 cùng chuyên gia</a>
            </li>
            <li>
              <a href="{{ url('/ai-trading-history') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/ai-trading-history') === Request::url()) {{ 'text-primary' }} @endif">HISTORY EMERALPHA AI</a>
            </li>
            <li>
              <a href="{{ url('/stock-evaluation') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/stock-evaluation') === Request::url()) {{ 'text-primary' }} @endif">EMERALPHA AI Đánh giá chuyên sâu cổ phiếu</a>
            </li>
            <li>
              <a href="{{ url('/user-manual') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/user-manual') === Request::url()) {{ 'text-primary' }} @endif">Hướng dẫn sử dụng</a>
            </li>
          </ul>
        </li>
        <li class="flex flex-row p-4 py-2 align-center hover:bg-primary-light @if(url('/about') === Request::url()) {{ 'bg-primary' }} @endif">
          <img class="w-[22px] height-[22px]" src="{!! url('images/menu-intro.svg') !!}" alt="dashboard" />
          <a href="{{ url('/about') }}" class="block w-full px-3 py-2 rounded md:bg-transparent" aria-current="page">Giới thiệu</a>
        </li>
        @if(auth()->user()->group_id === 1)
        <li class="p-4">
          <hr class="opacity-50">
        </li>
        <li>
          <a href="javascript:;" class="flex flex-row p-3 py-2 items-center align-center hover:bg-primary-light @if(in_array(Request::url(), [url('/admin'), url('/admin/ai-trading'), url('/admin/user-manual'), url('/admin/stock-evaluation'), url('/admin/private-consultation'), url('/admin/strategic-hexagram'), url('/admin/psychology-power'), url('/admin/market-trends')])) {{ 'bg-primary' }} @endif" aria-controls="dropdown-dashboard-admin" data-collapse-toggle="dropdown-dashboard-admin">
            <img class="w-[22px] height-[22px]" src="{!! url('images/menu-pen.svg') !!}" alt="" />
            <span class="block w-full px-3 py-2 rounded md:bg-transparent">Quản lý Dashboard</span>
            <svg class="w-3 h-3" aria-hidden="@if(in_array(Request::url(), [url('/admin/'), url('/admin/ai-trading')])) {{ 'true' }} @else {{ 'false' }} @endif" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
            </svg>
          </a>
          <ul id="dropdown-dashboard-admin" class="list-disc pl-11 @if(in_array(Request::url(), [url('/admin/'), url('/admin/ai-trading'), url('/admin/user-manual'), url('/admin/stock-evaluation'), url('/admin/private-consultation'), url('/admin/strategic-hexagram'), url('/admin/psychology-power'), url('/admin/market-trends')])) {{ 'hidden__' }} @else {{ 'hidden' }} @endif py-2 space-y-2">
            <li>
              <a href="{{ url('/admin/ai-trading') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/admin/ai-trading') === Request::url()) {{ 'text-primary' }} @endif">EMERALPHA AI TRADING SYSTEM</a>
            </li>
            <li>
              <a href="{{ url('/admin/market-trends') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/admin/market-trends') === Request::url()) {{ 'text-primary' }} @endif">Xu hướng thị trường & <br> Tỷ trọng danh mục</a>
            </li>
            <li>
              <a href="{{ url('/admin/psychology-power') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/admin/psychology-power') === Request::url()) {{ 'text-primary' }} @endif">Sức mạnh thị trường & Tâm lý thị trường</a>
            </li>
            <li>
              <a href="{{ url('/admin/strategic-hexagram') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/admin/strategic-hexagram') === Request::url()) {{ 'text-primary' }} @endif">EMERALPHA AI Gieo quẻ chiến lược</a>
            </li>
            <li>
              <a href="{{ url('/admin/ai-copytrade') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/admin/ai-copytrade') === Request::url()) {{ 'text-primary' }} @endif">EMERALPHA AI COPYTRADE</a>
            </li>
            <li>
              <a href="{{ url('/admin/private-consultation') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/admin/private-consultation') === Request::url()) {{ 'text-primary' }} @endif">Nâng cấp Tư vấn riêng 1:1 cùng chuyên gia</a>
            </li>
            <li>
              <a href="{{ url('/admin/stock-evaluation') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/admin/stock-evaluation') === Request::url()) {{ 'text-primary' }} @endif">EMERALPHA AI Đánh giá chuyên sâu cổ phiếu</a>
            </li>
            <li>
              <a href="{{ url('/admin/user-manual') }}" class="flex items-center w-full p-2 text-[#9E9E9E] transition duration-75 rounded-lg pl-0 group hover:text-primary @if(url('/admin/user-manual') === Request::url()) {{ 'text-primary' }} @endif">Hướng dẫn sử dụng</a>
            </li>
          </ul>
        </li>

        <li class="flex flex-row p-4 py-2 align-center hover:bg-primary-light @if(str_contains(Request::url(), url('/edit-account'))) {{ 'bg-primary' }} @endif">
          <img class="w-[22px] height-[22px]" src="{!! url('images/menu-pen.svg') !!}" alt="dashboard" />
          <a href="{{ url('/edit-account') }}" class="block w-full px-3 py-2 rounded md:bg-transparent" aria-current="page">Quản lý tài khoản</a>
        </li>
        <li class="flex flex-row p-4 py-2 align-center hover:bg-primary-light @if(url('/edit-about') === Request::url()) {{ 'bg-primary' }} @endif">
          <img class="w-[22px] height-[22px]" src="{!! url('images/menu-pen.svg') !!}" alt="dashboard" />
          <a href="{{ url('/edit-about') }}" class="block w-full px-3 py-2 rounded md:bg-transparent" aria-current="page">Quản lý giới thiệu</a>
        </li>
        <li class="flex flex-row p-4 py-2 align-center hover:bg-primary-light @if(url('/edit-setting') === Request::url()) {{ 'bg-primary' }} @endif">
          <img class="w-[22px] height-[22px]" src="{!! url('images/menu-pen.svg') !!}" alt="dashboard" />
          <a href="{{ url('/edit-setting') }}" class="block w-full px-3 py-2 rounded md:bg-transparent" aria-current="page">Tùy chỉnh trang</a>
        </li>
        @endif
        <li class="flex flex-row p-4 py-2 align-center hover:bg-primary-light">
          <img class="w-[22px] height-[22px]" src="{!! url('images/menu-logout.svg') !!}" alt="dashboard" />
          <a href="{{ route('logout.perform') }}" class="block w-full px-3 py-2 rounded md:bg-transparent" aria-current="page">Thoát</a>
        </li>

      </ul>

    </div>
  </div>
</nav>