<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('layouts.partials.head')

<body class="app">

  @include('admin.partials.spinner')

  <div class="peers ai-s fxw-nw flex-row" style="height: 80vh;">
    <div class="d-n@sm- peer peer-greed pos-r bgr-n bgpX-c bgpY-c bgsz-cv">
      <div class="pos-a centerXY login-screen rounded-circle">
        <img class="" src="/images/anapneo-01.png">
      </div>
    </div>
    <div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style='min-width: 320px;'>

        @yield('content')

{{--
        @include('flash-message')
--}}

    </div>

  </div>
  <div class="footer-logo d-flex justify-content-center">
      <img src="/images/EU_ERDF_gr-1024x219.jpg" alt="">
  </div>
  <!-- Global js content -->

  <!-- End of global js content-->

  <!-- Specific js content placeholder -->
  @stack('js')
  <!-- End of specific js content placeholder -->

</body>

</html>
