<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Giới thiệu về cụm rạp HCTV Cinema
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Tiện ích Online tại HCTV
     */
    public function onlineServices()
    {
        return view('pages.online-services');
    }

    /**
     * Thẻ quà tặng E-Gift Card HCTV
     */
    public function giftCards()
    {
        return view('pages.gift-cards');
    }

    /**
     * Cơ hội nghề nghiệp & Tuyển dụng
     */
    public function careers()
    {
        return view('pages.careers');
    }

    /**
     * Liên hệ quảng cáo tại HCTV Cinema
     */
    public function advertising()
    {
        return view('pages.advertising');
    }

    /**
     * Dành cho đối tác doanh nghiệp & phát hành
     */
    public function partners()
    {
        return view('pages.partners');
    }

    /**
     * Điều khoản chung
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Điều khoản giao dịch
     */
    public function termsTransaction()
    {
        return view('pages.terms-transaction');
    }

    /**
     * Chính sách thanh toán & bảo mật giao dịch
     */
    public function paymentPolicy()
    {
        return view('pages.payment-policy');
    }

    /**
     * Chính sách bảo mật thông tin
     */
    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    /**
     * Những quy định tại rạp chiếu phim HCTV
     */
    public function cinemaRules()
    {
        return view('pages.cinema-rules');
    }

    /**
     * Câu hỏi thường gặp (FAQ)
     */
    public function faq()
    {
        return view('pages.faq');
    }
}
