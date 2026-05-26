<!-- Favicons -->
<link href="{{ $setting ? $setting->favicon : asset('admin/img/favicon.png') }}" rel="icon">
<link href="{{ $setting ? $setting->favicon : asset('admin/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

<!-- Meta tag seo -->
<meta name="keywords" content="{{ $setting ? $setting->meta_keyword : '' }}">
<meta name="tag" content="{{ $setting ? $setting->meta_tag : '' }}">
<meta name="description" content="{{ $setting ? $setting->og_des : '' }}">

<!-- Meta Robots -->
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">
<meta name="bingbot" content="index, follow">
<meta name="yandexbot" content="index, follow">

<meta name="google-site-verification"
      content="{{ $setting ? $setting->google_site_verification : '' }}"/>

<!-- Open Graph (Facebook, LinkedIn) -->
<meta property="og:title" content="{{ $setting ? $setting->og_title : '' }}">
<meta property="og:description" content="{{ $setting ? $setting->og_des : '' }}">
<meta property="og:image" content="{{ $setting ? $setting->og_img : '' }}">
<meta property="og:url" content="{{ $setting ? $setting->og_url : '' }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="{{ $setting ? $setting->og_site : '' }}">
<meta name="twitter:title" content="{{ $setting ? $setting->og_title : '' }}">
<meta name="twitter:description" content="{{ $setting ? $setting->og_des : '' }}">
<meta name="twitter:image" content="{{ $setting ? $setting->og_img : '' }}">

<!-- Meta Author -->
<meta name="author" content="{{ $setting ? $setting->author_name : '' }}">

<!-- Meta Owner -->
<meta name="owner" content="{{ $setting ? $setting->owner_name : '' }}">

<!-- Meta Publisher -->
<meta name="publisher" content="{{ $setting ? $setting->author_name : '' }}">
