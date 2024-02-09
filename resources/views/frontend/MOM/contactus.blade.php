@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

@extends('frontend.'.$active_theme -> theme_abrv.'.layouts.app')
@section('title','Contact Us')
@section('content')
<div id="main-wrapper">

  @include('frontend.'.$active_theme -> theme_abrv.'.components.header')

  <div class="site-wrapper-reveal">
    <div class="contact-us-page-warpper mt-30 section-space--mb_60" id="contact-us">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="section-title text-center mb-20">
              <h2 class="section-title--one section-title--center">Contact Us</h2>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="row">
              <div class="col-lg-7">
                <div class="contact-form-wrap  section-space--mt_60">
                  <h5 class="mb-10">Get in touch</h5>
                  <form action="{{route('form.submission', ['contact_us'])}}" method="post">
                    @if (Session::has('message') && isset(Session::get('message')['referrer']) && Session::get('message')['referrer'] == 'contact_us')
                    <div class="alert alert-{{Session::get('message')['type']}}">
                      {{Session::get('message')['body']}}
                    </div>
                    @endif
                    @csrf
                    <div>
                      <div class="contact-input">
                        <div class="contact-inner">
                          <input name="name" type="text" placeholder="Name *" required>
                        </div>
                        <div class="contact-inner">
                          <input name="subject" type="text" placeholder="Subject *" required>
                        </div>
                      </div>

                      <div class="contact-input">
                        <div class="contact-inner">
                          <input name="email" type="email" placeholder="Email *" required>
                        </div>
                        <div class="contact-inner">
                          <input name="phone" type="number" placeholder="Phone *" required>
                        </div>
                      </div>

                      <div class="contact-input">
                        <div class="contact-inner">
                          <input name="company" type="text" placeholder="Company *" required>
                        </div>
                        <div class="contact-inner">
                          <input name="city" type="text" placeholder="City *" required>
                        </div>
                      </div>

                      <div class="contact-inner">
                        <input name="state" type="text" placeholder="State *" required>
                      </div>

                      <div class="contact-inner contact-message">
                        <textarea name="message" placeholder="Please type a message here" required></textarea>
                      </div>
                      <div class="submit-btn mt-20">
                        <input type="submit" class="btn btn--black btn--md">
                        <!--<button class="btn btn--black btn--md" type="submit">Submit</button>-->
                        <!--<p class="form-messege"></p>-->
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-lg-4 ml-auto">
                <div class="conatact-info-text section-space--mt_60">
                  <div class="logo text-md-left"> <a href="/"><img src="/MOM/images/momeni-logoo.png" alt=""></a> </div>
                  <br>
                  <h5 class="mb-10">Our address</h5>
                  <p>60 Broad Street ,<br />
                  Carlstadt, NJ 07072</p>
                  <p class="mt-30"><strong>Call Us</strong> <br>
                  (201)-549-7220 <br>
                  </p>
                  <div class="product_socials mt-30"> <span class="label">FOLLOW US:</span>
                    <ul class="helendo-social-share socials-inline">
                      <li> <a class="share-google-plus helendo-google-plus" href="{{$pages -> all_pages -> sections -> footer_social_media -> insta_url}}" target="_blank"><i class="social_instagram"></i></a> </li>
                      <li> <a class="share-facebook helendo-facebook" href="{{$pages -> all_pages -> sections -> footer_social_media -> facebook_url}}" target="_blank"><i class="social_facebook"></i></a> </li>
                      <li> <a class="share-pinterest helendo-pinterest" href="{{$pages -> all_pages -> sections -> footer_social_media -> pinterest_url}}" target="_blank"><i class="social_pinterest"></i></a> </li>
                      <li> <a class="share-twitter helendo-twitter" href="{{$pages -> all_pages -> sections -> footer_social_media -> twitter_url}}" target="_blank"><i class="social_twitter"></i></a> </li>
                      <li> <a class="share-linkedin helendo-linkedin" href="{{$pages -> all_pages -> sections -> footer_social_media -> linkedin_url}}" target="_blank"><i class="social_linkedin"></i></a> </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
</div>
@endsection
@section('scripts')
<style>
  .grid-item {
    width: 25%;
  }

  .grid-item--width2 {
    width: 50%;
  }
</style>
@endsection
