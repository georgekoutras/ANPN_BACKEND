<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@include('layouts.partials.head')

<body class="app">

    @include('admin.partials.spinner')

    <div>
        <!-- #Left Sidebar ==================== -->
        @include('admin.partials.sidebar')

        <!-- #Main ============================ -->
        <div class="page-container">
            <!-- ### $Topbar ### -->
            @include('admin.partials.topbar')

            <!-- ### $App Screen Content ### -->
            <main class='main-content'> <!-- bgc-grey-100 -->
                <div id='mainContent'>
                    <div class="container-fluid">

                        <h4 class="c-grey-900 mT-10 mB-30">@yield('page-header')</h4>

                        @include('admin.partials.messages')
                        @yield('content')

                    </div>
                </div>
            </main>

            <!-- ### $App Screen Footer ### -->
            <footer class="footer-row ta-c lh-0 fsz-sm c-grey-600 d-flex flex-column ">
<!--                <span style="margin-bottom: 20px;">Copyright © {{ date('Y') }} Developed By
                    <a href="https://openit.gr" target='_blank' title="OpenIT">OpenIT</a>. All rights
                    reserved.
                </span>-->
                <div class="footer-logo d-flex justify-content-center">
                    <img src="/images/EU_ERDF_gr-1024x219.jpg" alt="">
                </div>
            </footer>

        </div>
    </div>

    <script src="{{ mix('/js/app.js') }}"></script>

    <!-- Global js content -->

    <!-- End of global js content-->

    <!-- Specific js content placeholder -->
    @stack('js')
    <!-- End of specific js content placeholder -->

</body>

</html>
