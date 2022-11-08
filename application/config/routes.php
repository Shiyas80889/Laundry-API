<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['api/login']     = 'api/Login/login';
$route['api/validateOtp']     = 'api/Login/validateOtp';
$route['api/signup']     = 'api/Login/signup';
$route['api/listShop']     = 'api/Login/listShop';
$route['api/listService']     = 'api/Login/listService';
$route['api/createShopItems']     = 'api/Login/createShopItems';
$route['api/listShopServices']     = 'api/Login/listShopServices';
$route['api/createPincodeDetails']     = 'api/Login/createPincodeDetails';
$route['api/listPincodeDetails']     = 'api/Login/listPincodeDetails';
$route['api/listPriceList']     = 'api/Login/listPriceList';
$route['api/createOrder']     = 'api/Login/createOrder';
$route['api/listOrder']     = 'api/Login/listOrder';
$route['api/listUserDetails']     = 'api/Login/listUserDetails';
$route['api/payment']     = 'api/Login/payment';
$route['api/listPayment']     = 'api/Login/listPayment';
$route['api/getjobcount']     = 'api/Login/getjobcount';
$route['api/listJobItems']     = 'api/Login/listJobItems';
$route['api/listJobDetails']     = 'api/Login/listJobDetails';
$route['api/test']     = 'api/Login/test';
$route['api/createGalleryFolder']     = 'api/Login/createGalleryFolder';
$route['api/listGalleryFolder']     = 'api/Login/listGalleryFolder';
$route['api/uploadImage']     = 'api/Login/uploadImage';
$route['api/listGalleryDetails']     = 'api/Login/listGalleryDetails';
$route['api/createAddress']     = 'api/Login/createAddress';
$route['api/listAddresses']     = 'api/Login/listAddresses';
$route['api/listAddressesTag']     = 'api/Login/listAddressesTag';
$route['api/loginVendor']     = 'api/Login/loginVendor';
$route['api/listOrderStatus']     = 'api/Login/listOrderStatus';
$route['api/listOrderStatusDetails']     = 'api/Login/listOrderStatusDetails';
$route['api/listOrderStatusDetails1']     = 'api/Login/listOrderStatusDetails1';
$route['api/listOrderStatusDetails2']     = 'api/Login/listOrderStatusDetails2';
$route['api/listProducts']     = 'api/Login/listProducts';
$route['api/createOrderDetails']     = 'api/Login/createOrderDetails';
$route['api/listOrderDetails']     = 'api/Login/listOrderDetails'; 
$route['api/completeOrder']     = 'api/Login/completeOrder';