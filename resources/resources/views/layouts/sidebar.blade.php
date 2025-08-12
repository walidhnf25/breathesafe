<!-- Sidebar -->
<aside class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    @if (auth()->check() && auth()->user()->role === 'Administrator')
    <a class="sidebar-brand d-flex align-items-center justify-content-center mt-3 mb-3" href="{{ url('/location') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/logo.png') }}" alt="BreatheSafe Logo" style="width: 100px; height: 100px;">
        </div>
    </a>
    @endif

    @if (auth()->check() && auth()->user()->role === 'User')
    <a class="sidebar-brand d-flex align-items-center justify-content-center mt-3 mb-3" href="{{ url('/live-stream') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/logo.png') }}" alt="BreatheSafe Logo" style="width: 100px; height: 100px;">
        </div>
    </a>
    @endif

    @php
        // Cek apakah route yang sedang aktif adalah 'location'
        $isActiveLocation  = request()->routeIs('location');
        $isActiveCamera = request()->routeIs('camera');
        $isActiveLiveStream = request()->routeIs('live-stream');
        $isActiveOriginalImage = request()->routeIs('original-image-location');
        $isActiveSegmentationImage = request()->routeIs('detection-image-location');
        $isNumberOfviolations = request()->routeIs('number-of-violations');
        $activeColor = '#00DEA3';
        $defaultColor = 'white';
    @endphp

    @if (auth()->check() && auth()->user()->role === 'Administrator')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('number-of-violations') }}"
        style="color: {{ $isNumberOfviolations  ? $activeColor : $defaultColor }};">
            <svg width="15" height="15" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M11 3.88779C11 4.44079 10.552 4.88779 10 4.88779C9.448 4.88779 9 4.44079 9 3.88779V1.88779C9 1.33479 9.448 0.887787 10 0.887787C10.552 0.887787 11 1.33479 11 1.88779V3.88779ZM11 14.3476C11 13.9906 11.191 13.6596 11.501 13.4806C12.426 12.9496 13 11.9556 13 10.8876C13 9.23359 11.654 7.88759 10 7.88759C8.346 7.88759 7 9.23359 7 10.8876C7 11.9556 7.574 12.9496 8.499 13.4806C8.809 13.6596 9 13.9906 9 14.3476L9 18.8876H11L11 14.3476ZM5 10.8876C5 8.13059 7.243 5.88759 10 5.88759C12.757 5.88759 15 8.13059 15 10.8876C15 12.4666 14.246 13.9496 13 14.8836L13 18.8876C13 19.9906 12.103 20.8876 11 20.8876H9C7.897 20.8876 7 19.9906 7 18.8876V14.8836C5.753 13.9496 5 12.4666 5 10.8876ZM19 9.88779H17C16.447 9.88779 16 10.3348 16 10.8878C16 11.4408 16.447 11.8878 17 11.8878H19C19.553 11.8878 20 11.4408 20 10.8878C20 10.3348 19.553 9.88779 19 9.88779ZM1 9.88779H3C3.552 9.88779 4 10.3348 4 10.8878C4 11.4408 3.552 11.8878 3 11.8878H1C0.448 11.8878 0 11.4408 0 10.8878C0 10.3348 0.448 9.88779 1 9.88779ZM5.6597 5.30579L4.2207 3.91579C3.8237 3.53279 3.1917 3.54479 2.8067 3.94179C2.4227 4.33779 2.4337 4.97179 2.8307 5.35579L4.2697 6.74479C4.4647 6.93279 4.7147 7.02579 4.9647 7.02579C5.2267 7.02579 5.4877 6.92279 5.6837 6.71979C6.0677 6.32379 6.0567 5.68979 5.6597 5.30579ZM15.7794 3.91749C16.1754 3.53449 16.8114 3.54549 17.1924 3.94149C17.5764 4.33749 17.5654 4.97149 17.1684 5.35449L15.7294 6.74449C15.5364 6.93149 15.2854 7.02549 15.0354 7.02549C14.7734 7.02549 14.5114 6.92249 14.3164 6.71949C13.9324 6.32349 13.9434 5.68949 14.3404 5.30649L15.7794 3.91749Z"
                fill="{{ $isNumberOfviolations  ? $activeColor : $defaultColor }}" />
            </svg> Dashboard
        </a>
    </li>
    @endif

    @if (auth()->check() && auth()->user()->role === 'User')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('number-of-violations') }}"
        style="color: {{ $isNumberOfviolations  ? $activeColor : $defaultColor }};">
            <svg width="15" height="15" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M11 3.88779C11 4.44079 10.552 4.88779 10 4.88779C9.448 4.88779 9 4.44079 9 3.88779V1.88779C9 1.33479 9.448 0.887787 10 0.887787C10.552 0.887787 11 1.33479 11 1.88779V3.88779ZM11 14.3476C11 13.9906 11.191 13.6596 11.501 13.4806C12.426 12.9496 13 11.9556 13 10.8876C13 9.23359 11.654 7.88759 10 7.88759C8.346 7.88759 7 9.23359 7 10.8876C7 11.9556 7.574 12.9496 8.499 13.4806C8.809 13.6596 9 13.9906 9 14.3476L9 18.8876H11L11 14.3476ZM5 10.8876C5 8.13059 7.243 5.88759 10 5.88759C12.757 5.88759 15 8.13059 15 10.8876C15 12.4666 14.246 13.9496 13 14.8836L13 18.8876C13 19.9906 12.103 20.8876 11 20.8876H9C7.897 20.8876 7 19.9906 7 18.8876V14.8836C5.753 13.9496 5 12.4666 5 10.8876ZM19 9.88779H17C16.447 9.88779 16 10.3348 16 10.8878C16 11.4408 16.447 11.8878 17 11.8878H19C19.553 11.8878 20 11.4408 20 10.8878C20 10.3348 19.553 9.88779 19 9.88779ZM1 9.88779H3C3.552 9.88779 4 10.3348 4 10.8878C4 11.4408 3.552 11.8878 3 11.8878H1C0.448 11.8878 0 11.4408 0 10.8878C0 10.3348 0.448 9.88779 1 9.88779ZM5.6597 5.30579L4.2207 3.91579C3.8237 3.53279 3.1917 3.54479 2.8067 3.94179C2.4227 4.33779 2.4337 4.97179 2.8307 5.35579L4.2697 6.74479C4.4647 6.93279 4.7147 7.02579 4.9647 7.02579C5.2267 7.02579 5.4877 6.92279 5.6837 6.71979C6.0677 6.32379 6.0567 5.68979 5.6597 5.30579ZM15.7794 3.91749C16.1754 3.53449 16.8114 3.54549 17.1924 3.94149C17.5764 4.33749 17.5654 4.97149 17.1684 5.35449L15.7294 6.74449C15.5364 6.93149 15.2854 7.02549 15.0354 7.02549C14.7734 7.02549 14.5114 6.92249 14.3164 6.71949C13.9324 6.32349 13.9434 5.68949 14.3404 5.30649L15.7794 3.91749Z"
                fill="{{ $isNumberOfviolations  ? $activeColor : $defaultColor }}" />
            </svg> Dashboard
        </a>
    </li>
    @endif

    @if (auth()->check() && auth()->user()->role === 'Administrator')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('location') }}" 
        style="color: {{ $isActiveLocation  ? $activeColor : $defaultColor }};">
            <svg width="15" height="15" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" 
                    d="M9.99976 0.887695C12.5808 0.887695 14.8548 2.5487 15.6748 4.9327C18.1138 5.2637 19.9998 7.35969 19.9998 9.8877C19.9998 11.1087 19.5558 12.2837 18.7498 13.1967C18.5518 13.4197 18.2768 13.5347 17.9998 13.5347C17.7648 13.5347 17.5288 13.4527 17.3378 13.2847C16.9248 12.9177 16.8848 12.2867 17.2508 11.8717C17.7338 11.3257 17.9998 10.6197 17.9998 9.8877C17.9998 8.2337 16.6538 6.8877 14.9998 6.8877H14.8998C14.4238 6.8877 14.0138 6.55169 13.9198 6.08469C13.5458 4.2327 11.8978 2.8877 9.99976 2.8877C8.10276 2.8877 6.45376 4.2327 6.08076 6.08469C5.98676 6.55169 5.57576 6.8877 5.09976 6.8877H4.99976C3.34576 6.8877 1.99976 8.2337 1.99976 9.8877C1.99976 10.6197 2.26576 11.3257 2.74976 11.8717C3.11476 12.2867 3.07576 12.9177 2.66176 13.2847C2.24776 13.6507 1.61576 13.6097 1.25076 13.1967C0.443756 12.2837 -0.000244141 11.1087 -0.000244141 9.8877C-0.000244141 7.35969 1.88576 5.2637 4.32476 4.9327C5.14576 2.5487 7.41976 0.887695 9.99976 0.887695ZM9.30496 9.1678C9.69896 8.7918 10.322 8.7948 10.707 9.18079L13.707 12.1808C14.098 12.5718 14.098 13.2038 13.707 13.5948C13.512 13.7898 13.256 13.8878 13 13.8878C12.744 13.8878 12.488 13.7898 12.293 13.5948L11 12.3018L11 17.8878C11 18.4408 10.552 18.8878 9.99996 18.8878C9.44796 18.8878 8.99996 18.4408 8.99996 17.8878L8.99996 12.2438L7.69496 13.5038C7.29796 13.8888 6.66496 13.8758 6.28096 13.4788C5.89696 13.0808 5.90796 12.4488 6.30496 12.0648L9.30496 9.1678Z" 
                    fill="{{ $isActiveLocation  ? $activeColor : $defaultColor }}" />
            </svg> Location
        </a>
    </li>
    @endif

    @if (auth()->check() && auth()->user()->role === 'Administrator')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('camera') }}"
        style="color: {{ $isActiveCamera  ? $activeColor : $defaultColor }};">
            <svg width="15" height="15" viewBox="0 0 16 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.714 6.88779C10.32 6.88779 10 6.50679 10 6.03779V2.86579L13.742 6.88779H10.714ZM11 16.8878H5C4.448 16.8878 4 16.4398 4 15.8878C4 15.3358 4.448 14.8878 5 14.8878L11 14.8878C11.553 14.8878 12 15.3358 12 15.8878C12 16.4398 11.553 16.8878 11 16.8878ZM5 10.8878L8 10.8878C8.552 10.8878 9 11.3358 9 11.8878C9 12.4398 8.552 12.8878 8 12.8878L5 12.8878C4.448 12.8878 4 12.4398 4 11.8878C4 11.3358 4.448 10.8878 5 10.8878ZM15.74 6.21579L11.296 1.21579C11.107 1.00679 10.838 0.887787 10.556 0.887787L2.556 0.887787C1.147 0.887787 0 2.00979 0 3.38779L0 18.3878C0 19.7658 1.147 20.8878 2.556 20.8878L13.444 20.8878C14.854 20.8878 16 19.7658 16 18.3878L16 6.88779C16 6.63879 15.907 6.39979 15.74 6.21579Z"
                fill="{{ $isActiveCamera  ? $activeColor : $defaultColor }}" />
            </svg> Camera
        </a>
    </li>
    @endif

    @if (auth()->check() && auth()->user()->role === 'Administrator')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('live-stream') }}"
        style="color: {{ $isActiveLiveStream  ? $activeColor : $defaultColor }};">
            <svg width="15" height="15" viewBox="0 0 16 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.714 6.88779C10.32 6.88779 10 6.50679 10 6.03779V2.86579L13.742 6.88779H10.714ZM11 16.8878H5C4.448 16.8878 4 16.4398 4 15.8878C4 15.3358 4.448 14.8878 5 14.8878L11 14.8878C11.553 14.8878 12 15.3358 12 15.8878C12 16.4398 11.553 16.8878 11 16.8878ZM5 10.8878L8 10.8878C8.552 10.8878 9 11.3358 9 11.8878C9 12.4398 8.552 12.8878 8 12.8878L5 12.8878C4.448 12.8878 4 12.4398 4 11.8878C4 11.3358 4.448 10.8878 5 10.8878ZM15.74 6.21579L11.296 1.21579C11.107 1.00679 10.838 0.887787 10.556 0.887787L2.556 0.887787C1.147 0.887787 0 2.00979 0 3.38779L0 18.3878C0 19.7658 1.147 20.8878 2.556 20.8878L13.444 20.8878C14.854 20.8878 16 19.7658 16 18.3878L16 6.88779C16 6.63879 15.907 6.39979 15.74 6.21579Z"
                fill="{{ $isActiveLiveStream  ? $activeColor : $defaultColor }}" />
            </svg> Live Stream Camera
        </a>
    </li>
    @endif

    @if (auth()->check() && auth()->user()->role === 'Administrator')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('detection-image-location') }}"
        style="color: {{ $isActiveSegmentationImage  ? $activeColor : $defaultColor }};">
            <svg width="15" height="15" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M18 15.8878C18 16.4388 17.551 16.8878 17 16.8878L15 16.8878V6.88779H17C17.551 6.88779 18 7.33679 18 7.88779L18 15.8878ZM2 15.8878L2 7.88779C2 7.33679 2.449 6.88779 3 6.88779H5L5 16.8878H3C2.449 16.8878 2 16.4388 2 15.8878ZM8 3.38779C8 3.11179 8.224 2.88779 8.5 2.88779L11.5 2.88779C11.776 2.88779 12 3.11179 12 3.38779V4.88779H8V3.38779ZM7 16.8878H13V6.88779L7 6.88779L7 16.8878ZM17 4.88779L14 4.88779V3.38779C14 2.00979 12.878 0.887787 11.5 0.887787L8.5 0.887787C7.122 0.887787 6 2.00979 6 3.38779L6 4.88779L3 4.88779C1.346 4.88779 0 6.23379 0 7.88779L0 15.8878C0 17.5418 1.346 18.8878 3 18.8878L17 18.8878C18.654 18.8878 20 17.5418 20 15.8878L20 7.88779C20 6.23379 18.654 4.88779 17 4.88779Z"
                fill="{{ $isActiveSegmentationImage  ? $activeColor : $defaultColor }}" />
            </svg> Smoke Detection View
        </a>
    </li>
    @endif

    @if (auth()->check() && auth()->user()->role === 'User')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('live-stream') }}"
        style="color: {{ $isActiveLiveStream  ? $activeColor : $defaultColor }};">
            <svg width="15" height="15" viewBox="0 0 16 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.714 6.88779C10.32 6.88779 10 6.50679 10 6.03779V2.86579L13.742 6.88779H10.714ZM11 16.8878H5C4.448 16.8878 4 16.4398 4 15.8878C4 15.3358 4.448 14.8878 5 14.8878L11 14.8878C11.553 14.8878 12 15.3358 12 15.8878C12 16.4398 11.553 16.8878 11 16.8878ZM5 10.8878L8 10.8878C8.552 10.8878 9 11.3358 9 11.8878C9 12.4398 8.552 12.8878 8 12.8878L5 12.8878C4.448 12.8878 4 12.4398 4 11.8878C4 11.3358 4.448 10.8878 5 10.8878ZM15.74 6.21579L11.296 1.21579C11.107 1.00679 10.838 0.887787 10.556 0.887787L2.556 0.887787C1.147 0.887787 0 2.00979 0 3.38779L0 18.3878C0 19.7658 1.147 20.8878 2.556 20.8878L13.444 20.8878C14.854 20.8878 16 19.7658 16 18.3878L16 6.88779C16 6.63879 15.907 6.39979 15.74 6.21579Z"
                fill="{{ $isActiveLiveStream  ? $activeColor : $defaultColor }}" />
            </svg> Live Stream Camera
        </a>
    </li>
    @endif

    @if (auth()->check() && auth()->user()->role === 'User')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('detection-image-location') }}"
        style="color: {{ $isActiveSegmentationImage  ? $activeColor : $defaultColor }};">
            <svg width="15" height="15" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M18 15.8878C18 16.4388 17.551 16.8878 17 16.8878L15 16.8878V6.88779H17C17.551 6.88779 18 7.33679 18 7.88779L18 15.8878ZM2 15.8878L2 7.88779C2 7.33679 2.449 6.88779 3 6.88779H5L5 16.8878H3C2.449 16.8878 2 16.4388 2 15.8878ZM8 3.38779C8 3.11179 8.224 2.88779 8.5 2.88779L11.5 2.88779C11.776 2.88779 12 3.11179 12 3.38779V4.88779H8V3.38779ZM7 16.8878H13V6.88779L7 6.88779L7 16.8878ZM17 4.88779L14 4.88779V3.38779C14 2.00979 12.878 0.887787 11.5 0.887787L8.5 0.887787C7.122 0.887787 6 2.00979 6 3.38779L6 4.88779L3 4.88779C1.346 4.88779 0 6.23379 0 7.88779L0 15.8878C0 17.5418 1.346 18.8878 3 18.8878L17 18.8878C18.654 18.8878 20 17.5418 20 15.8878L20 7.88779C20 6.23379 18.654 4.88779 17 4.88779Z"
                fill="{{ $isActiveSegmentationImage  ? $activeColor : $defaultColor }}" />
            </svg> Smoke Detection View
        </a>
    </li>
    @endif
</aside>