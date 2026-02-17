<?php

use App\Livewire\Auth\ForgotPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoryPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrderPage;
use App\Livewire\OrderDetailsPage;
use App\Livewire\ProductDetailsPage;
use App\Livewire\ProductPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;




Route::get('/', HomePage::class)->name('home');
Route::get('/categories', CategoryPage::class)->name('categories');
Route::get('/products', ProductPage::class)->name('products');
Route::get('/products-details', ProductDetailsPage::class)->name('products-details');
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/checkout', CheckoutPage::class)->name('checkout');
Route::get('/my-order', MyOrderPage::class)->name('my-order');
Route::get('/my-order/details', OrderDetailsPage::class)->name('order-details');

Route::get('/cancel', CancelPage::class)->name('cancel');
Route::get('/success', SuccessPage::class)->name('success');

Route::get('/login', LoginPage::class)->name('login');
Route::get('/register', RegisterPage::class)->name('register');
Route::get('/forgot', ForgotPage::class)->name('forgot');
Route::get('/reset', ResetPage::class)->name('reset');
