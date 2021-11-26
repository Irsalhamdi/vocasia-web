<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');

$routes->group('admin', ['namespace' => 'App\Controllers\Backend', 'filter' => 'auth:admin'], function ($routes) {
    $routes->get('courses', 'Courses::index');
    $routes->get('course/(:num)', 'Courses::show_detail/$1');
    $routes->post('course', 'Courses::create');
    $routes->post('course-thumbnail/(:num)', 'Courses::thumbnail/$1');
    $routes->put('course/(:num)', 'Courses::update/$1');
    $routes->delete('course/(:num)', 'Courses::delete/$1');
    $routes->get('categories', 'Category::index');
    $routes->get('category/(:num)', 'Category::show/$1');
    $routes->post('category', 'Category::create');
    $routes->post('category-thumbnail/(:num)', 'Category::thumbnail/$1');
    $routes->put('category/(:num)', 'Category::update/$1');
    $routes->delete('category/(:num)', 'Category::delete/$1');
    $routes->get('coupons', 'Coupon::index');
    $routes->get('coupon/(:num)', 'Coupon::show_detail/$1');
    $routes->post('coupon', 'Coupon::create');
    $routes->put('coupon/(:num)', 'Coupon::update/$1');
    $routes->delete('coupon/(:num)', 'Coupon::delete/$1');
    $routes->get('users-mitra', 'UsersMitra::index');
    $routes->get('users-mitra/(:num)', 'UsersMitra::show_detail/$1');
    $routes->post('users-mitra', 'UsersMitra::create');
    $routes->put('users-mitra/(:num)', 'UsersMitra::update/$1');
    $routes->delete('users-mitra/(:num)', 'UsersMitra::delete/$1');
    $routes->get('affiliates', 'Affiliate::index');
    $routes->get('affiliate/(:num)', 'Affiliate::show_detail/$1');
    $routes->post('affiliate', 'Affiliate::create');
    $routes->put('affiliate/(:num)', 'Affiliate::update/$1');
    $routes->delete('affiliate/(:num)', 'Affiliate::delete/$1');
    $routes->get('enrols', 'Enrol::index');
    $routes->get('enrol/(:num)', 'Enrol::show_detail/$1');
    $routes->post('enrol', 'Enrol::create');
    $routes->put('enrol/(:num)', 'Enrol::update/$1');
    $routes->delete('enrol/(:num)', 'Enrol::delete/$1');
    $routes->get('revenue', 'Revenue::admin_revenue');
    $routes->get('revenue-instructor/(:num)', 'Revenue::instructor_revenue/$1');
    $routes->patch('delete/revenue-admin', 'Revenue::update_admin_revenue');
    $routes->post('community', 'Community::create');
    $routes->get('communities', 'Community::index');
    $routes->get('community/(:num)', 'Community::show/$1');
    $routes->put('community/(:num)', 'Community::update/$1');
    $routes->delete('community/(:num)', 'Community::delete/$1');
    $routes->get('lessons-admin', 'LessonAdmin::index');
    $routes->get('lesson-admin/(:num)', 'LessonAdmin::show_detail/$1');
    $routes->post('lesson-admin', 'LessonAdmin::create');
    $routes->put('lesson-admin/(:num)', 'LessonAdmin::update/$1');
    $routes->delete('lesson-admin/(:num)', 'LessonAdmin::delete/$1');
    $routes->get('dashboard-admin', 'Dashboard::index');
    $routes->get('course/info', 'Courses::info_courses');
});
$routes->group('auth', ['namespace' => 'App\Controllers', 'filter' => 'cors'], function ($routes) {
    $routes->post('register', 'Auth::register');
    $routes->post('web/login', 'Auth::login');
    $routes->get('web/logout', 'Auth::logout');
    $routes->post('mobile/login', 'Auth::login_mobile');
    $routes->get('refreshtoken', 'Auth::get_refresh_token');
});
$routes->group('instructor', ['namespace' => 'App\Controllers\Frontend', 'filter' => 'auth:user'], function ($routes) {
    $routes->get('lessons', 'Lesson::index');
    $routes->get('lesson/(:num)', 'Lesson::show_detail/$1');
    $routes->post('lesson', 'Lesson::create');
    $routes->put('lesson/(:num)', 'Lesson::update/$1');
    $routes->delete('lesson/(:num)', 'Lesson::delete/$1');
    $routes->get('dashboard-instructors', 'DashboardInstructor::index');
    $routes->get('dashboard-instructor/(:num)', 'DashboardInstructor::show_detail/$1');
    $routes->post('dashboard-instructor', 'DashboardInstructor::create');
    $routes->put('dashboard-instructor/(:num)', 'DashboardInstructor::update/$1');
    $routes->delete('dashboard-instructor/(:num)', 'DashboardInstructor::delete/$1');
    $routes->get('question/(:num)', 'Question::index/$1');
    $routes->get('paymentbalance/(:num)', 'PaymentBalance::index/$1');
    $routes->post('paymentbalance', 'PaymentBalance::create');
    $routes->get('guide-users', 'GuideUserInstructor::index');
    $routes->get('guide-user/(:num)', 'GuideUserInstructor::show/$1');
    $routes->post('guide-user', 'GuideUserInstructor::create');
    $routes->put('guide-user/(:num)', 'GuideUserInstructor::update/$1');
    $routes->delete('guide-user/(:num)', 'GuideUserInstructor::delete/$1');
});
$routes->group('homepage', ['namespace' => 'App\Controllers\Frontend'], function ($routes) {
    $routes->get('courses', 'Home::get_all_courses');
    $routes->get('categories', 'Home::get_all_category');
    $routes->post('wishlist', 'Home::add_to_wishlist');
    $routes->delete('wishlist/delete/(:num)', 'Home::delete_wishlist/$1');
    $routes->get('filter', 'Home::filter');
    $routes->get('course/(:num)', 'Home::detail_courses/$1');
    $routes->get('course/detail/instructor/(:num)', 'Home::get_instructor_student/$1');
    $routes->get('section/(:num)', 'Home::get_sections/$1');
    $routes->get('course/rating/(:num)', 'Home::get_rating/$1');
    $routes->get('banners', 'Banner::index');
    $routes->get('banner/(:num)', 'Banner::show/$1');
    $routes->post('banner', 'Banner::create');
    $routes->post('banner-img/(:num)', 'Banner::img/$1');
    $routes->put('banner/(:num)', 'Banner::update/$1');
    $routes->delete('banner/(:num)', 'Banner::delete/$1');
});
$routes->group('mobile', ['namespace' => 'App\Controllers\Frontend'], function ($routes) {
    $routes->get('courses', 'Home::get_all_courses');
    $routes->get('categories', 'Home::get_all_category');
    $routes->get('carts/(:num)', 'Home::cart_list/$1');
    $routes->delete('wishlist/delete/(:num)', 'Home::delete_wishlist/$1');
    $routes->get('filter', 'Home::filter');
    $routes->get('course/(:num)', 'Home::detail_courses/$1');
    $routes->get('course/detail/instructor/(:num)', 'Home::get_instructor_student/$1');
    $routes->get('section/(:num)', 'Home::get_sections/$1');
    $routes->get('course/rating/(:num)', 'Home::get_rating/$1');
    $routes->get('banners', 'Banner::index');
    $routes->get('banner/(:num)', 'Banner::show/$1');
    $routes->post('banner', 'Banner::create');
    $routes->post('banner-img/(:num)', 'Banner::img/$1');
    $routes->put('banner/(:num)', 'Banner::update/$1');
    $routes->delete('banner/(:num)', 'Banner::delete/$1');
});
$routes->group('users', ['namespace' => 'App\Controllers\Frontend', 'filter' => 'auth:user'], function ($routes) {
    $routes->get('me', 'Home::detail_users_login');
    $routes->get('wishlist', 'Home::wishlist');
    $routes->post('wishlist', 'Home::add_to_wishlist');
    $routes->delete('wishlist/delete/(:num)', 'Home::delete_wishlist/$1');
    $routes->get('carts/(:num)', 'Home::cart_list/$1');
    $routes->post('add-to-cart', 'Home::add_to_cart');
    $routes->delete('cart/delete/(:num)', 'Home::delete_cart/$1');
    $routes->put('profile/user-profile/(:num)', 'Home::user_profile/$1');
    $routes->put('profile/user-credentials/(:num)', 'Home::user_credentials/$1');
    $routes->post('profile/user-photo/(:num)', 'Home::user_photo/$1');
    $routes->get('users-info/(:num)', 'Home::users_detail/$1');
    $routes->post('voucher', 'Home::redeem_voucher');
    $routes->post('payment/course', 'Home::payment');
    $routes->get('course/my/(:num)', 'Home::my_course/$1');
    $routes->get('course/my/lesson/(:num)', 'Home::my_lesson/$1');
    $routes->post('lesson/update/progress', 'Home::watch_history');
    $routes->get('lesson/progress/(:num)', 'Home::get_watch_history/$1');
    $routes->get('comments', 'Comment::index');
    $routes->get('comment/(:num)', 'Comment::show/$1');
    $routes->post('comment', 'Comment::create');
    $routes->put('comment/(:num)', 'Comment::update/$1');
    $routes->delete('comment/(:num)', 'Comment::delete/$1');
});

$routes->group('affiliate', ['namespace' => 'App\Controllers\Frontend', 'filter' => 'auth:user'], function ($routes) {
    $routes->get('access/(:num)', 'Affiliate::access/$1');
    $routes->get('saldo/(:num)', 'Affiliate::saldo/$1');
    $routes->post('saldo/(:num)', 'Affiliate::pull/$1');
    $routes->get('subscription/(:num)', 'Affiliate::subscription/$1');
    $routes->get('komisi-affiliate/(:num)', 'Affiliate::commitions/$1');
    $routes->get('dashboard-affiliate/(:num)', 'Affiliate::dashboard_affiliate/$1');
    $routes->get('courses', 'Affiliate::courses');
    $routes->get('courses/(:num)', 'Affiliate::share_link/$1');
    $routes->post('add-affiliator', 'Affiliate::add_affiliator');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
