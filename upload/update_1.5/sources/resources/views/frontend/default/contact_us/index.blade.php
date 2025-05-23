@extends('layouts.default')
@push('title', get_phrase('Contact us'))
@push('meta')@endpush
@push('css')@endpush
@section('content')
    <!------------------- Breadcum Area Start  ------>
    <section class="breadcum-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="eNtry-breadcum">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ get_phrase('Home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('Contact us') }}</li>
                            </ol>
                        </nav>
                        <h3 class="g-title">{{ get_phrase('Contact Us') }}</h3>
                    </div>
                </div>
            </div>
            <p class="showing-text mt-15">{{ get_phrase("We're always here to help you.") }}</p>
        </div>
    </section>
    <!------------------- Breadcum Area End  --------->

    <div class="eNtery-item">
        <div class="container">
            @php
                $contact_info = get_frontend_settings('contact_info');
                if ($contact_info) {
                    $contact_info = json_decode($contact_info, true);
                } else {
                    $contact_info = ['email' => '', 'phone' => '', 'address' => '', 'office_hours' => '', 'location' => ''];
                }
            @endphp
            <div class="row justify-content-center mt-25">
                <div class="col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="ol-card p-4 card contact-card Ecard g-card c-card">
                        <div class="contact-icon">
                            <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M43.124 23C43.124 28.3375 41.0037 33.4564 37.2295 37.2305C33.4554 41.0047 28.3365 43.125 22.999 43.125C17.6615 43.125 12.5427 41.0047 8.7685 37.2305C4.99433 33.4564 2.87402 28.3375 2.87402 23C2.87402 20.357 3.39462 17.7399 4.40609 15.2982C5.41755 12.8564 6.90008 10.6378 8.76901 8.76897C10.6379 6.90017 12.8567 5.4178 15.2985 4.40651C17.7404 3.39522 20.3575 2.87481 23.0005 2.875C28.3379 2.875 33.4568 4.99531 37.231 8.76948C41.0052 12.5436 43.124 17.6625 43.124 23Z" fill="url(#paint0_linear_295_2553)"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12.1107 12.0779C12.92 10.1114 13.9392 8.38925 15.1093 6.99631C17.313 4.37 20.0601 2.875 22.9997 2.875C25.9394 2.875 28.6865 4.37 30.8902 6.99631C32.0603 8.38925 33.0795 10.1114 33.8888 12.0779C35.1236 11.4813 36.2233 10.7841 37.1548 10.0007C37.3906 9.80519 37.6134 9.6025 37.8247 9.3955C38.4759 10.1042 39.0767 10.8603 39.6216 11.6552C39.4232 11.8421 39.2176 12.0247 39.0034 12.2029C37.829 13.1891 36.4202 14.0688 34.8289 14.8048C35.5391 17.3017 35.9372 20.0761 35.9372 23C35.9372 25.9239 35.5391 28.6982 34.8289 31.1952C36.4202 31.9312 37.829 32.8109 39.0034 33.7971C39.2176 33.9753 39.4232 34.1579 39.6216 34.3447C39.077 35.1397 38.4765 35.8948 37.8247 36.6045C37.6098 36.3936 37.3864 36.1917 37.1548 35.9993C36.1564 35.1743 35.0593 34.4765 33.8888 33.9221C33.0795 35.8886 32.0603 37.6107 30.8902 39.0037C28.6865 41.63 25.9394 43.125 22.9997 43.125C20.0601 43.125 17.313 41.63 15.1093 39.0037C13.9392 37.6107 12.92 35.8886 12.1107 33.9221C10.8759 34.5187 9.77618 35.2159 8.84468 35.9993C8.61313 36.1917 8.38965 36.3936 8.1748 36.6045C7.52301 35.8948 6.92254 35.1397 6.37793 34.3447C6.5763 34.1579 6.78187 33.9753 6.99606 33.7971C8.17049 32.8109 9.57924 31.9312 11.1706 31.1952C10.4255 28.5275 10.0526 25.7697 10.0622 23C10.0622 20.0761 10.4604 17.3017 11.1706 14.8048C9.57924 14.0688 8.17049 13.1891 6.99606 12.2029C6.78444 12.0268 6.5783 11.8441 6.37793 11.6552C6.92274 10.8603 7.52362 10.1042 8.1748 9.3955C8.38612 9.6025 8.60893 9.80519 8.84468 10.0007C9.77618 10.7841 10.8759 11.4813 12.1107 12.0779ZM21.5622 31.6595V40.0689C19.9867 39.6693 18.5464 38.6285 17.3116 37.1551C16.3182 35.9734 15.4601 34.5187 14.7729 32.8613C16.9739 32.1661 19.2564 31.7621 21.5622 31.6595ZM24.4372 31.6595C26.8724 31.7788 29.1709 32.1986 31.2266 32.8613C30.5394 34.5187 29.6812 35.9734 28.6879 37.1551C27.4531 38.6285 26.0127 39.6693 24.4372 40.0689V31.6595ZM32.1451 15.8527C32.7374 18.0378 33.0622 20.4571 33.0622 23C33.0622 25.5429 32.7374 27.9622 32.1451 30.1473C29.6485 29.3494 27.056 28.8901 24.4372 28.7816V17.2184C27.056 17.1099 29.6485 16.6506 32.1451 15.8527ZM13.8544 15.8527C16.1889 16.6117 18.7965 17.0933 21.5622 17.2184V28.7816C18.9434 28.8901 16.351 29.3494 13.8544 30.1473C13.2358 27.8155 12.9274 25.4124 12.9372 23C12.9372 20.4571 13.2621 18.0378 13.8544 15.8527ZM21.5622 14.3405C19.2564 14.2379 16.9739 13.8339 14.7729 13.1388C15.4601 11.4813 16.3182 10.0266 17.3116 8.84494C18.5464 7.3715 19.9867 6.33075 21.5622 5.93112V14.3405ZM24.4372 14.3405V5.93112C26.0127 6.33075 27.4531 7.3715 28.6879 8.84494C29.6812 10.0266 30.5394 11.4813 31.2266 13.1388C29.1709 13.8014 26.8724 14.2212 24.4372 14.3405Z"
                                    fill="url(#paint1_linear_295_2553)"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M31.0371 4.54688C35.7665 6.61633 39.5251 10.4166 41.5423 15.1686C40.894 20.7791 38.0664 27.3126 33.0625 27.3126C27.3125 27.3126 24.4375 18.6876 24.4375 12.7464C24.4375 8.77312 27.2521 5.43813 31.0371 4.54688Z" fill="black" fill-opacity="0.1"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M34.5 25.875C28.7488 25.875 25.875 17.25 25.875 11.3091C25.875 6.6539 29.739 2.875 34.5 2.875C39.261 2.875 43.125 6.6539 43.125 11.3091C43.125 17.25 40.2512 25.875 34.5 25.875Z" fill="url(#paint2_linear_295_2553)"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M37.375 12.9375C37.375 14.0812 36.9206 15.1781 36.1119 15.9869C35.3031 16.7956 34.2062 17.25 33.0625 17.25C31.9188 17.25 30.8219 16.7956 30.0131 15.9869C29.2044 15.1781 28.75 14.0812 28.75 12.9375C28.75 11.7938 29.2044 10.6969 30.0131 9.8881C30.8219 9.07935 31.9188 8.625 33.0625 8.625C34.2062 8.625 35.3031 9.07935 36.1119 9.8881C36.9206 10.6969 37.375 11.7938 37.375 12.9375Z" fill="black" fill-opacity="0.11"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M38.8125 11.5C38.8125 12.6437 38.3581 13.7407 37.5494 14.5494C36.7406 15.3582 35.6437 15.8125 34.5 15.8125C33.3563 15.8125 32.2594 15.3582 31.4506 14.5494C30.6419 13.7407 30.1875 12.6437 30.1875 11.5C30.1875 10.3563 30.6419 9.25935 31.4506 8.4506C32.2594 7.64185 33.3563 7.1875 34.5 7.1875C35.6437 7.1875 36.7406 7.64185 37.5494 8.4506C38.3581 9.25935 38.8125 10.3563 38.8125 11.5Z" fill="url(#paint3_linear_295_2553)"></path>
                                <defs>
                                    <linearGradient id="paint0_linear_295_2553" x1="24.438" y1="43.125" x2="23.0005" y2="4.31264" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#1AAFFA"></stop>
                                        <stop offset="1" stop-color="#66D9FF"></stop>
                                    </linearGradient>
                                    <linearGradient id="paint1_linear_295_2553" x1="22.9997" y1="41.6875" x2="22.9997" y2="5.75" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#66D9FF"></stop>
                                        <stop offset="1" stop-color="white"></stop>
                                    </linearGradient>
                                    <linearGradient id="paint2_linear_295_2553" x1="34.5" y1="24.4375" x2="34.5" y2="4.3125" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#62E8AF"></stop>
                                        <stop offset="1" stop-color="#A1FFD8"></stop>
                                    </linearGradient>
                                    <linearGradient id="paint3_linear_295_2553" x1="34.5" y1="15.8125" x2="34.5" y2="7.1875" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#A1FFD8"></stop>
                                        <stop offset="1" stop-color="white"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <h4 class="g-title">{{ get_phrase('Our Address') }}</h4>
                        <p>{{ get_phrase('Our location') }}</p>
                        <a href="#">{{ $contact_info['address'] }}</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="ol-card p-4 card contact-card Ecard g-card c-card">
                        <div class="contact-icon">
                            <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_295_2563)">
                                    <path
                                        d="M29.469 13.4286L41.9105 3.75421C41.2956 3.18255 40.4861 2.86645 39.6465 2.87015H16.2512C15.4103 2.87108 14.6002 3.18642 13.9799 3.75421L26.3496 13.3783C26.7736 13.7664 27.325 13.9859 27.8998 13.9951C28.4745 14.0044 29.0327 13.8029 29.469 13.4286ZM42.7443 4.92577C42.4255 5.18715 31.7534 13.4582 31.1077 13.9676C31.785 14.4481 42.0774 21.6827 42.4424 21.9458C42.8153 21.3911 43.0154 20.7382 43.0174 20.0698C42.9082 19.3754 43.2995 5.15245 42.7443 4.92577ZM16.2296 15.2255L17.013 19.4373L24.7827 13.9676C24.0896 13.4264 13.5169 5.21026 13.1462 4.91859C12.9676 5.33672 12.8747 5.78643 12.873 6.24109V11.7611C13.7106 11.9458 14.4754 12.3727 15.0722 12.9886C15.669 13.6046 16.0715 14.3825 16.2296 15.2255ZM25.9902 14.8805L17.2718 21.0114C17.3202 21.8558 17.1257 22.6964 16.7112 23.4336C16.75 23.467 39.5857 23.4246 39.6465 23.4408C40.2573 23.4435 40.8569 23.2769 41.3787 22.9593C40.9876 22.6753 30.6834 15.4299 29.9074 14.8805C29.3198 15.2469 28.6413 15.4411 27.9488 15.4411C27.2564 15.4411 26.5778 15.2469 25.9902 14.8805Z"
                                        fill="url(#paint0_linear_295_2563)"></path>
                                    <path
                                        d="M33.1415 34.2103L33.1415 39.3349C33.1359 39.8686 33.016 40.3949 32.79 40.8784C32.5641 41.3619 32.2371 41.7914 31.8313 42.138C31.4255 42.4846 30.9501 42.7403 30.4372 42.8878C29.9243 43.0354 29.3857 43.0714 28.8577 42.9934C7.20179 39.5434 3.56492 23.6662 3.01155 17.0035C2.97131 16.4947 3.03645 15.9831 3.20288 15.5007C3.36932 15.0182 3.63348 14.5753 3.97885 14.1996C4.32422 13.8238 4.74338 13.5234 5.21013 13.317C5.67688 13.1106 6.18119 13.0027 6.69153 13H11.9528C12.6417 12.9994 13.3092 13.2398 13.8395 13.6795C14.3698 14.1193 14.7296 14.7307 14.8565 15.4078L15.834 20.6547C15.9085 21.054 15.8998 21.4644 15.8082 21.8602C15.7167 22.256 15.5444 22.6286 15.3022 22.9547L13.6706 25.1613C13.3944 25.536 13.2401 25.9866 13.2285 26.452C13.217 26.9174 13.3487 27.3751 13.6059 27.7631C14.7862 29.6262 16.3523 31.2144 18.1987 32.4206C18.6059 32.6791 19.0852 32.8005 19.5663 32.7668C20.0475 32.7332 20.5052 32.5464 20.8725 32.2338L22.3603 30.9688C22.6872 30.6912 23.0707 30.4882 23.484 30.374C23.8974 30.2598 24.3307 30.2371 24.7537 30.3075L30.6762 31.2994C31.3658 31.4129 31.9926 31.7681 32.4443 32.3014C32.8959 32.8347 33.1431 33.5114 33.1415 34.2103Z"
                                        fill="url(#paint1_linear_295_2563)"></path>
                                </g>
                                <defs>
                                    <linearGradient id="paint0_linear_295_2563" x1="2.94888" y1="23" x2="43.0508" y2="23" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#FF81FF"></stop>
                                        <stop offset="1" stop-color="#A93AFF"></stop>
                                    </linearGradient>
                                    <linearGradient id="paint1_linear_295_2563" x1="5.51179" y1="35.1973" x2="30.1388" y2="15.2501" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#18AEFA"></stop>
                                        <stop offset="1" stop-color="#83E1FF"></stop>
                                    </linearGradient>
                                    <clipPath id="clip0_295_2563">
                                        <rect width="46" height="46" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </svg>

                        </div>
                        <h4 class="g-title">{{ get_phrase('Contact Info') }}</h4>
                        <p>{{ get_phrase('Open a chat or give us call at') }}</p>
                        <a href="#">{{ $contact_info['phone'] }}</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="ol-card p-4 card contact-card Ecard g-card c-card">
                        <div class="contact-icon">
                            <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.1875 37.1939C5.95323 36.8747 4.85995 36.1545 4.07938 35.1466C3.29881 34.1386 2.87517 32.8999 2.875 31.625V15.8125C2.875 14.2875 3.4808 12.825 4.55914 11.7466C5.63747 10.6683 7.10001 10.0625 8.625 10.0625H25.875C27.4 10.0625 28.8625 10.6683 29.9409 11.7466C31.0192 12.825 31.625 14.2875 31.625 15.8125V31.625C31.6252 32.3801 31.4765 33.1278 31.1875 33.8254C30.8984 34.523 30.4745 35.1567 29.9402 35.6903C29.4067 36.2245 28.773 36.6484 28.0754 36.9375C27.3778 37.2265 26.6301 37.3752 25.875 37.375H16.3171L9.52344 42.8102C9.31199 42.9795 9.05701 43.0856 8.78789 43.1163C8.51876 43.147 8.24644 43.101 8.0023 42.9837C7.75816 42.8663 7.55215 42.6824 7.40799 42.4531C7.26383 42.2238 7.1874 41.9584 7.1875 41.6875V37.1939Z"
                                    fill="url(#paint0_linear_295_2545)"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M38.8125 30.0064V34.5C38.8132 34.7711 38.7371 35.0369 38.593 35.2665C38.449 35.4962 38.2429 35.6804 37.9985 35.7979C37.7542 35.9153 37.4816 35.9612 37.2122 35.9302C36.9429 35.8992 36.6878 35.7926 36.4766 35.6227L29.6829 30.1875H14.375C12.85 30.1875 11.3875 29.5817 10.3091 28.5034C9.2308 27.425 8.625 25.9625 8.625 24.4375V8.625C8.625 7.10001 9.2308 5.63747 10.3091 4.55914C11.3875 3.4808 12.85 2.875 14.375 2.875H37.375C38.9 2.875 40.3625 3.4808 41.4409 4.55914C42.5192 5.63747 43.125 7.10001 43.125 8.625V24.4375C43.1248 25.7124 42.7012 26.9511 41.9206 27.9591C41.14 28.967 40.0468 29.6872 38.8125 30.0064Z" fill="url(#paint1_linear_295_2545)"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M24.4375 15.8125C24.0599 15.8126 23.6859 15.8871 23.3371 16.0317C22.9882 16.1763 22.6712 16.3882 22.4043 16.6553C22.1373 16.9224 21.9256 17.2395 21.7811 17.5884C21.6367 17.9373 21.5624 18.3113 21.5625 18.6889C21.5626 19.0666 21.6371 19.4405 21.7817 19.7894C21.9263 20.1382 22.1382 20.4552 22.4053 20.7222C22.6724 20.9891 22.9895 21.2009 23.3384 21.3453C23.6873 21.4897 24.0613 21.564 24.4389 21.5639C25.2016 21.5637 25.933 21.2606 26.4722 20.7212C27.0113 20.1817 27.3141 19.4502 27.3139 18.6875C27.3137 17.9248 27.0106 17.1934 26.4711 16.6543C25.9317 16.1151 25.2002 15.8123 24.4375 15.8125ZM15.8125 15.8125C15.4349 15.8126 15.0609 15.8871 14.7121 16.0317C14.3632 16.1763 14.0462 16.3882 13.7793 16.6553C13.5123 16.9224 13.3006 17.2395 13.1561 17.5884C13.0117 17.9373 12.9374 18.3113 12.9375 18.6889C12.9376 19.0666 13.0121 19.4405 13.1567 19.7894C13.3013 20.1382 13.5132 20.4552 13.7803 20.7222C14.0474 20.9891 14.3645 21.2009 14.7134 21.3453C15.0623 21.4897 15.4363 21.564 15.8139 21.5639C16.5766 21.5637 17.308 21.2606 17.8472 20.7212C18.3863 20.1817 18.6891 19.4502 18.6889 18.6875C18.6887 17.9248 18.3856 17.1934 17.8462 16.6543C17.3067 16.1151 16.5752 15.8123 15.8125 15.8125ZM33.0625 15.8125C32.6849 15.8126 32.3109 15.8871 31.9621 16.0317C31.6132 16.1763 31.2962 16.3882 31.0293 16.6553C30.7623 16.9224 30.5506 17.2395 30.4061 17.5884C30.2617 17.9373 30.1874 18.3113 30.1875 18.6889C30.1876 19.0666 30.2621 19.4405 30.4067 19.7894C30.5513 20.1382 30.7632 20.4552 31.0303 20.7222C31.2974 20.9891 31.6145 21.2009 31.9634 21.3453C32.3123 21.4897 32.6863 21.564 33.0639 21.5639C33.8266 21.5637 34.558 21.2606 35.0972 20.7212C35.6363 20.1817 35.9391 19.4502 35.9389 18.6875C35.9387 17.9248 35.6356 17.1934 35.0961 16.6543C34.5567 16.1151 33.8252 15.8123 33.0625 15.8125Z"
                                    fill="black" fill-opacity="0.1"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M23 17.25C23 18.0125 23.3029 18.7438 23.8421 19.2829C24.3812 19.8221 25.1125 20.125 25.875 20.125C26.6375 20.125 27.3688 19.8221 27.9079 19.2829C28.4471 18.7438 28.75 18.0125 28.75 17.25C28.75 16.4875 28.4471 15.7562 27.9079 15.2171C27.3688 14.6779 26.6375 14.375 25.875 14.375C25.1125 14.375 24.3812 14.6779 23.8421 15.2171C23.3029 15.7562 23 16.4875 23 17.25Z" fill="url(#paint2_linear_295_2545)"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.375 17.25C14.375 18.0125 14.6779 18.7438 15.2171 19.2829C15.7562 19.8221 16.4875 20.125 17.25 20.125C18.0125 20.125 18.7438 19.8221 19.2829 19.2829C19.8221 18.7438 20.125 18.0125 20.125 17.25C20.125 16.4875 19.8221 15.7562 19.2829 15.2171C18.7438 14.6779 18.0125 14.375 17.25 14.375C16.4875 14.375 15.7562 14.6779 15.2171 15.2171C14.6779 15.7562 14.375 16.4875 14.375 17.25Z" fill="url(#paint3_linear_295_2545)"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M31.625 17.25C31.625 18.0125 31.9279 18.7438 32.4671 19.2829C33.0062 19.8221 33.7375 20.125 34.5 20.125C35.2625 20.125 35.9938 19.8221 36.5329 19.2829C37.0721 18.7438 37.375 18.0125 37.375 17.25C37.375 16.4875 37.0721 15.7562 36.5329 15.2171C35.9938 14.6779 35.2625 14.375 34.5 14.375C33.7375 14.375 33.0062 14.6779 32.4671 15.2171C31.9279 15.7562 31.625 16.4875 31.625 17.25Z" fill="url(#paint4_linear_295_2545)"></path>
                                <defs>
                                    <linearGradient id="paint0_linear_295_2545" x1="5.75" y1="34.5" x2="30.1875" y2="12.9375" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#7F3AF9"></stop>
                                        <stop offset="1" stop-color="#B560FF"></stop>
                                    </linearGradient>
                                    <linearGradient id="paint1_linear_295_2545" x1="11.5" y1="27.3125" x2="38.8125" y2="4.3125" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#18AEFA"></stop>
                                        <stop offset="1" stop-color="#83E1FF"></stop>
                                    </linearGradient>
                                    <linearGradient id="paint2_linear_295_2545" x1="24.4375" y1="20.125" x2="27.3125" y2="15.8125" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#82E3FF"></stop>
                                        <stop offset="1" stop-color="white"></stop>
                                    </linearGradient>
                                    <linearGradient id="paint3_linear_295_2545" x1="15.8125" y1="20.125" x2="18.6875" y2="15.8125" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#82E3FF"></stop>
                                        <stop offset="1" stop-color="white"></stop>
                                    </linearGradient>
                                    <linearGradient id="paint4_linear_295_2545" x1="33.0625" y1="20.125" x2="35.9375" y2="15.8125" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#82E3FF"></stop>
                                        <stop offset="1" stop-color="white"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>

                        </div>
                        <h4 class="g-title">{{ get_phrase('Contact Email') }}</h4>
                        <p>{{ get_phrase('Send your message') }}</p>
                        <a href="#">{{ $contact_info['email'] }}</a>
                    </div>
                </div>
            </div>
            <div class="row section-padding">
                <div class="col-lg-5">
                    <div class="conatact-map">
                        <iframe class="border-0" src="https://maps.google.com/maps?q={{ $contact_info['location'] }}&hl=es&z=14&amp;output=embed" width="600" height="450" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-left">
                        <h4 class="g-title">{{ get_phrase('Send Message') }}</h4>
                        <form action="{{ route('contact.store') }}" method="post" class="global-form mt-25" id="global-form">@csrf
                            <div class="form-group">
                                <label for="name" class="form-label">{{ get_phrase('Name') }}</label>
                                <input type="text" name="name" class="form-control @error('name') border border-danger @enderror" placeholder="Your Name">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">{{ get_phrase('Email') }}</label>
                                <input type="email" name="email" class="form-control @error('email') border border-danger @enderror" placeholder="Your Email">
                            </div>
                            <div class="form-group">
                                <label for="phone" class="form-label">{{ get_phrase('Phone') }}</label>
                                <input type="tel" name="phone" class="form-control @error('phone') border border-danger @enderror" placeholder="Your phone">
                            </div>
                            <div class="form-group">
                                <label for="address" class="form-label">{{ get_phrase('Address') }}</label>
                                <input type="text" name="address" class="form-control @error('address') border border-danger @enderror" placeholder="Your address">
                            </div>
                            <div class="form-group">
                                <label for="message" class="form-label">{{ get_phrase('Message') }}</label>
                                <textarea name="message" cols="30" rows="10" class="form-control @error('message') border border-danger @enderror" placeholder="Your message here ..."></textarea>
                            </div>

                            @if(get_frontend_settings('recaptcha_status'))
                                <button class="mt-20 g-recaptcha" data-sitekey="{{ get_frontend_settings('recaptcha_sitekey') }}" data-callback='onContactSubmit' data-action='submit'>{{ get_phrase('Send Message') }}</button>
                            @else
                                <button type="submit" class="mt-20">{{ get_phrase('Send Message') }}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script>
        "use strict";

        function onContactSubmit(token) {
            document.getElementById("global-form").submit();
        }
        
    </script>
@endpush
