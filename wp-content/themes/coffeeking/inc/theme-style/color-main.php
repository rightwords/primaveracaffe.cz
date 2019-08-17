<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

// Color
$theme_style .= "
a,
a.black:hover,
a.black:focus,
.woocommerce ul.products li.product .woocommerce-loop-category__title:hover,
.woocommerce ul.products li.product .woocommerce-loop-product__title:hover,
.woocommerce ul.products li.product h3:hover,
.products-sc article:hover .header,
.color-main,
header.page-header,
div.top-bar.container .cart:hover,div.top-bar.container .cart:focus,
.top-search a:focus,
.top-search a:hover,
.comments-area .comment-info .comment-author,
.comments-area .comment-reply-link:hover,
.comments-area .comment-reply-link:before,
.heading.spanned h4,
.heading.color-main .header,
.heading.subcolor-main .subheader,
.multi-doc .block-right .descr,
.tariff-item .header,
#block-footer h4,
#block-footer .social-icons-list a:hover,
#block-footer .address li span,
#block-footer .address li a:hover,
#block-footer .widget_nav_menu ul li.active a,
#block-footer .widget_nav_menu ul li a:before,
footer,
footer a:hover,
.widget_calendar caption,
body.body-black-dark .blog article .description .header,
.blog-sc article .blog-info .cat,
.blog-post .tags-short,
.blog-post .cats-short,
.events-list .date .date-day,
.gallery-page .descr .fa,
.woocommerce #payment #place_order.btn-second:hover,
.woocommerce-page #payment #place_order.btn-second:hover,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button.btn-second:hover,
.woocommerce div.product form.cart .button.btn-second:hover,
.woocommerce #respond input#submit.btn-second:hover,
.woocommerce a.button.btn-second:hover,
.woocommerce button.button.btn-second:hover,
.woocommerce input.button.btn-second:hover,
.button.btn-second:hover,input[type=\"submit\"].btn-second:hover,
.wpcf7-submit.btn-second:hover,
.btn.btn-second:hover,
.woocommerce-product-search input[type=\"submit\"].btn-second:hover,
.wp-searchform input[type=\"submit\"].btn-second:hover,form.post-password-form input[type=\"submit\"].btn-second:hover,form.search-form input[type=\"submit\"].btn-second:hover,form.wpcf7-form input[type=\"submit\"].btn-second:hover,form.form input[type=\"submit\"].btn-second:hover,form.comment-form input[type=\"submit\"].btn-second:hover,form input[type=\"submit\"].btn-second:hover,
.woocommerce #payment #place_order.btn-default-bordered,
.woocommerce-page #payment #place_order.btn-default-bordered,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button.btn-default-bordered,
.woocommerce div.product form.cart .button.btn-default-bordered,
.woocommerce #respond input#submit.btn-default-bordered,
.woocommerce a.button.btn-default-bordered,
.woocommerce button.button.btn-default-bordered,
.woocommerce input.button.btn-default-bordered,
.button.btn-default-bordered,input[type=\"submit\"].btn-default-bordered,
.wpcf7-submit.btn-default-bordered,
.btn.btn-default-bordered,
.woocommerce-product-search input[type=\"submit\"].btn-default-bordered,
.wp-searchform input[type=\"submit\"].btn-default-bordered,form.post-password-form input[type=\"submit\"].btn-default-bordered,form.search-form input[type=\"submit\"].btn-default-bordered,form.wpcf7-form input[type=\"submit\"].btn-default-bordered,form.form input[type=\"submit\"].btn-default-bordered,form.comment-form input[type=\"submit\"].btn-default-bordered,form input[type=\"submit\"].btn-default-bordered,
.alert.alert-warning .fa,
.alert.alert-warning .header,
.block-descr h4,
.wpb-js-composer .vc_tta-panel .vc_tta-icon,
.block-icon.layout-cols6 li h5,
.block-icon.icon-h-right span,
.block-icon.layout-inline.i-transparent a,
.block-icon.layout-inline.i-transparent span,
.tags a,
.tags a:hover,
.team-item h4,
.products-sc article .price.color-main,
.services-sc .arrow-left,
.services-sc .arrow-right,
.zs-enabled .zs-arrows .arrow-right:hover,
.zs-enabled .zs-arrows .arrow-left:hover,
.zs-enabled .zs-arrows .arrow-right:hover:before,
.zs-enabled .zs-arrows .arrow-left:hover:before,
.zs-enabled .zs-arrows .arrow-right:hover:after,
.zs-enabled .zs-arrows .arrow-left:hover:after,
.woocommerce .product_meta > span span,
.woocommerce div.product .woocommerce-product-rating,
.woocommerce .star-rating span,
.woocommerce-MyAccount-navigation ul li:before,
.woocommerce-MyAccount-navigation ul li a:hover,
.woocommerce-message::before,
nav.navbar #navbar ul.navbar-nav ul.children li.current_page_item > a,  nav.navbar #navbar ul.navbar-nav ul.sub-menu li.current_page_item > a,
nav.navbar #navbar ul.navbar-nav .current_page_parent > a,  nav.navbar #navbar ul.navbar-nav .current_page_item > a,
nav.navbar #navbar ul.navbar-nav > li.hasSub:hover > a,
nav.navbar #navbar ul.navbar-nav a:hover,
nav.navbar #navbar ul.navbar-nav ul,
nav.navbar #navbar ul.navbar-nav > li.page_item_has_children:hover > ul, 
ul.navbar-nav > li.current-menu-ancestor > a,
nav.navbar #navbar ul.navbar-nav > li.current-menu-item > a,
nav.navbar #navbar ul.navbar-nav > li.current-menu-parent > a,
nav.navbar #navbar ul.navbar-nav > li.current_page_parent > a,
nav.navbar #navbar ul.navbar-nav > li.current_page_item > a,
nav.navbar.navbar-transparent .nav-right .cart:hover,
.navbar.navbar-transparent .top-search a:hover,
nav.navbar .nav-right .cart:hover,
nav.navbar .nav-right .cart:focus,
.social-icons-list li span.fa,
.woocommerce.widget_shopping_cart .quantity .amount,
.woocommerce .widget_shopping_cart .quantity .amount,
.woocommerce div.product p.price,
.woocommerce div.product span.price,
.woocommerce ul.products li.product .price,
.woocommerce table.shop_table .woocommerce-cart-form__cart-item .product-subtotal,
.woocommerce table.shop_table .woocommerce-cart-form__cart-item .product-price,
.widget-area aside ul li:before,
.widget-area aside ul li a:hover
{ color: {$css['main_color']};  }


@media (min-width: 1199px) {

	nav.navbar #navbar ul.navbar-nav > li.current-menu-ancestor > a, 
	nav.navbar #navbar ul.navbar-nav > li.current-menu-item > a, 
	nav.navbar #navbar ul.navbar-nav > li.current-menu-parent > a, 
	nav.navbar #navbar ul.navbar-nav > li.current_page_parent > a, 
	nav.navbar #navbar ul.navbar-nav > li.current_page_item > a,
	nav.navbar #navbar ul.navbar-nav a:hover,
	nav.navbar #navbar ul.navbar-nav > li.page_item_has_children > a:hover:after,
	nav.navbar #navbar ul.navbar-nav > li.menu-item-has-children > a:hover:after,
	nav.navbar #navbar ul.navbar-nav > li.hasSub > a:hover:after,
	nav.navbar #navbar ul.navbar-nav ul.children li.current-menu-item > a,
	nav.navbar #navbar ul.navbar-nav ul.sub-menu:not(.mega-menu-row) li.current-menu-item > a,
	nav.navbar #navbar ul.navbar-nav ul.children li.current-menu-parent > a,
	nav.navbar #navbar ul.navbar-nav ul.sub-menu:not(.mega-menu-row) li.current-menu-parent > a,
	nav.navbar #navbar ul.navbar-nav ul.children li.current_page_parent > a,
	nav.navbar #navbar ul.navbar-nav ul.sub-menu:not(.mega-menu-row) li.current_page_parent > a,
	nav.navbar #navbar ul.navbar-nav ul.children li.current_page_item > a,
	nav.navbar #navbar ul.navbar-nav ul.sub-menu:not(.mega-menu-row) li.current_page_item > a
	{ color: {$css['main_color']};  }
}

@media (min-width: 991px) {

	nav.navbar.navbar-transparent #navbar ul.navbar-nav > li > a:hover,
	nav.navbar.navbar-transparent #navbar ul.navbar-nav > li.page_item_has_children > a:hover:after,
	nav.navbar.navbar-transparent #navbar ul.navbar-nav > li.menu-item-has-children > a:hover:after,
	nav.navbar.navbar-transparent #navbar ul.navbar-nav > li.hasSub > a:hover:after
	{ color: {$css['main_color']};  }
}


#block-footer .widget_nav_menu ul li.current_page_parent a,
#block-footer .widget_nav_menu ul li.current_page_item a,
#block-footer .widget_nav_menu ul li.current_menu_item a,
#block-footer a:hover:not(.btn):not(.fa),
.woocommerce ul.products li.product a:hover,
.blog a.header:hover,
.blog article .description .header:hover,
.vc_tta-accordion h4 a,
#block-footer .social-icons-list .fa,
.vc_message_box.vc_color-info,
.alert.vc_color-info,
.vc_message_box.vc_color-info .fa,
.alert.vc_color-info .fa
{ color: {$css['main_color']} !important; }
";



// Background
$theme_style .= "
.woocommerce div.product .woocommerce-tabs ul.tabs li,
.widget_calendar #today::before,
.paceloader-dots .dot::before,
.testimonials-list .arrow-left, .testimonials-list .arrow-right,
nav.navbar #navbar ul.navbar-nav ul.children > li:hover > a,
nav.navbar #navbar ul.navbar-nav ul.sub-menu > li:hover > a,
.swiper-pagination .swiper-pagination-bullet-active:after,
.zs-enabled .zs-arrows .arrow-right:hover:before,
.zs-enabled .zs-arrows .arrow-left:hover:before,
.zs-enabled .zs-arrows .arrow-right:hover:after,
.zs-enabled .zs-arrows .arrow-left:hover:after,
.header-rounded > *,
.comment-text table thead th,
.text-page table thead th,
footer .go-top,
.woocommerce #payment #place_order,
.woocommerce-page #payment #place_order,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
.woocommerce div.product form.cart .button,
.woocommerce #respond input#submit,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
.button,input[type=\"submit\"],
.wpcf7-submit,
.btn,
.woocommerce-product-search input[type=\"submit\"],
.wp-searchform input[type=\"submit\"],
form.post-password-form input[type=\"submit\"],
form.search-form input[type=\"submit\"],
form.wpcf7-form input[type=\"submit\"],
form.form input[type=\"submit\"],
form.comment-form input[type=\"submit\"],
form input[type=\"submit\"],
.woocommerce #payment #place_order.btn-main-filled,
.woocommerce-page #payment #place_order.btn-main-filled,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button.btn-main-filled,
.woocommerce div.product form.cart .button.btn-main-filled,
.woocommerce #respond input#submit.btn-main-filled,
.woocommerce a.button.btn-main-filled,
.woocommerce button.button.btn-main-filled,
.woocommerce input.button.btn-main-filled,
.button.btn-main-filled,input[type=\"submit\"].btn-main-filled,
.wpcf7-submit.btn-main-filled,
.btn.btn-main-filled,
.woocommerce-product-search input[type=\"submit\"].btn-main-filled,
.wp-searchform input[type=\"submit\"].btn-main-filled,
form.post-password-form input[type=\"submit\"].btn-main-filled,
form.search-form input[type=\"submit\"].btn-main-filled,
form.wpcf7-form input[type=\"submit\"].btn-main-filled,
form.form input[type=\"submit\"].btn-main-filled,
form.comment-form input[type=\"submit\"].btn-main-filled,
form input[type=\"submit\"].btn-main-filled,
.woocommerce #payment #place_order.btn-gray-filled:hover,
.woocommerce-page #payment #place_order.btn-gray-filled:hover,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button.btn-gray-filled:hover,
.woocommerce div.product form.cart .button.btn-gray-filled:hover,
.woocommerce #respond input#submit.btn-gray-filled:hover,
.woocommerce a.button.btn-gray-filled:hover,
.woocommerce button.button.btn-gray-filled:hover,
.woocommerce input.button.btn-gray-filled:hover,
.button.btn-gray-filled:hover,input[type=\"submit\"].btn-gray-filled:hover,
.wpcf7-submit.btn-gray-filled:hover,
.btn.btn-gray-filled:hover,
.woocommerce-product-search input[type=\"submit\"].btn-gray-filled:hover,
.wp-searchform input[type=\"submit\"].btn-gray-filled:hover,
form.post-password-form input[type=\"submit\"].btn-gray-filled:hover,
form.search-form input[type=\"submit\"].btn-gray-filled:hover,
form.wpcf7-form input[type=\"submit\"].btn-gray-filled:hover,
form.form input[type=\"submit\"].btn-gray-filled:hover,
form.comment-form input[type=\"submit\"].btn-gray-filled:hover,
form input[type=\"submit\"].btn-gray-filled:hover,
.woocommerce #payment #place_order.color-hover-main:hover,
.woocommerce-page #payment #place_order.color-hover-main:hover,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button.color-hover-main:hover,
.woocommerce div.product form.cart .button.color-hover-main:hover,
.woocommerce #respond input#submit.color-hover-main:hover,
.woocommerce a.button.color-hover-main:hover,
.woocommerce button.button.color-hover-main:hover,
.woocommerce input.button.color-hover-main:hover,
.button.color-hover-main:hover,input[type=\"submit\"].color-hover-main:hover,
.wpcf7-submit.color-hover-main:hover,
.btn.color-hover-main:hover,
.woocommerce-product-search input[type=\"submit\"].color-hover-main:hover,
.wp-searchform input[type=\"submit\"].color-hover-main:hover,
form.post-password-form input[type=\"submit\"].color-hover-main:hover,
form.search-form input[type=\"submit\"].color-hover-main:hover,
form.wpcf7-form input[type=\"submit\"].color-hover-main:hover,
form.form input[type=\"submit\"].color-hover-main:hover,
form.comment-form input[type=\"submit\"].color-hover-main:hover,
form input[type=\"submit\"].color-hover-main:hover,
.swiper-pagination .swiper-pagination-bullet-active,
.alert.alert-important,
.social-icons-list.icon-style-round span.fa,
.block-icon.icon-ht-left a,
.block-icon.icon-ht-right a,
.block-icon.icon-ht-left span,
.block-icon.icon-ht-right span,
.block-icon.icon-top a,
.block-icon.icon-top span,
.block-icon li .bg-main,
.zs-enabled .zs-slideshow .zs-bullets .zs-bullet,
.menu-sc .header,
.menu-sc .price,
.bg-color-theme_color.vc_row-fluid,
.bg-color-theme_color.vc_section,
.bg-color-theme_color.vc_column_container .vc_column-inner,
.progressBar .bar div,
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle
{ background-color: {$css['main_color']}; }

.bg-color-black .btn-white-filled:hover,
.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a,
.woocommerce .swiper-container .arrow-left:hover,
.woocommerce .swiper-container .arrow-right:hover,
.btn.btn-active,
.vc_progress_bar .vc_single_bar .vc_bar,
.social-big li a:hover,
.vc_message_box.vc_color-warning,
alert.vc_color-warning
{ background-color: {$css['main_color']} !important; }

@media (max-width: 1199px) {
	nav.navbar #navbar
	{ background-color: {$css['main_color']}; }
}
";

// Border-color
$theme_style .= "
.vc_tta-tabs.vc_tta-style-flat .vc_tta-tabs-list .vc_active a span,
.countUp-item,
.tabs-cats li span.cat-active,
.swiper-pagination .swiper-pagination-bullet-active::after,
.sticky,
.footer-widget-area .null-instagram-feed .instagram-pics a img:hover,
.woocommerce #payment #place_order.btn-default-bordered,
.woocommerce-page #payment #place_order.btn-default-bordered,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button.btn-default-bordered,
.woocommerce div.product form.cart .button.btn-default-bordered,
.woocommerce #respond input#submit.btn-default-bordered,
.woocommerce a.button.btn-default-bordered,
.woocommerce button.button.btn-default-bordered,
.woocommerce input.button.btn-default-bordered,
.button.btn-default-bordered,
.nput[type=\"submit\"].btn-default-bordered,
.wpcf7-submit.btn-default-bordered,
.btn.btn-default-bordered,
.woocommerce-product-search input[type=\"submit\"].btn-default-bordered,
.wp-searchform input[type=\"submit\"].btn-default-bordered,
form.post-password-form input[type=\"submit\"].btn-default-bordered,
form.search-form input[type=\"submit\"].btn-default-bordered,
form.wpcf7-form input[type=\"submit\"].btn-default-bordered,
form.form input[type=\"submit\"].btn-default-bordered,
form.comment-form input[type=\"submit\"].btn-default-bordered,
form input[type=\"submit\"].btn-default-bordered,
.tags a,
.tags a:hover
.tariff-item.vip,
.testimonials-list .inner
{ border-color: {$css['main_color']}; }
";



