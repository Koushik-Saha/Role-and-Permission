@extends('layouts/contentLayoutMaster')

@section('title', 'swiper')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/swiper.min.css')) }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/plugins/extensions/swiper.css')) }}">
@endsection

@section('content')

  <!-- pagination swiper start -->
  <section id="component-swiper-pagination">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Pagination</h4>
      </div>
      <div class="card-content">
        <div class="card-body">
          <div class="swiper-paginations swiper-container">
            <div class="swiper-wrapper">
              <div class="swiper-slide"> <img class="img-fluid" src="{{ asset('images/banner/banner-12.jpg') }}"
                                              alt="banner">
              </div>
              <div class="swiper-slide"> <img class="img-fluid" src="{{ asset('images/banner/banner-9.jpg') }}"
                                              alt="banner">
              </div>
              <div class="swiper-slide"> <img class="img-fluid" src="{{ asset('images/banner/banner-8.jpg') }}"
                                              alt="banner">
              </div>
              <div class="swiper-slide"> <img class="img-fluid" src="{{ asset('images/banner/banner-7.jpg') }}"
                                              alt="banner">
              </div>
              <div class="swiper-slide"> <img class="img-fluid" src="{{ asset('images/banner/banner-20.jpg') }}"
                                              alt="banner">
              </div>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- pagination swiper ends -->



@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/extensions/swiper.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/extensions/swiper.js')) }}"></script>
@endsection
