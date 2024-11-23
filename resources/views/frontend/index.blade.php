@extends('frontend.master_dashboard')
@section('main')

@include('frontend.body.home.carousel_slider')
<!--End hero slider-->
@include('frontend.body.home.featured_categories')
<!--End category slider-->
@include('frontend.body.home.banners_section')
<!--End banners-->
@include('frontend.body.home.new_products')
<!--Products Tabs-->
@include('frontend.body.home.featured_products')
<!--End Best Sales-->

<!-- TV Category -->
@include('frontend.body.home.tv_categories')

<!--End TV Category -->

<!-- Tshirt Category -->
@include('frontend.body.home.tshirt_categories')

<!--End Tshirt Category -->

<!-- Computer Category -->

@include('frontend.body.home.computer_categories')

<!--End Computer Category -->

@include('frontend.body.home.hot_deals')
<!--End 4 columns-->

<!--Vendor List -->
@include('frontend.body.home.vendor_list')

<!--End Vendor List -->

@endsection

