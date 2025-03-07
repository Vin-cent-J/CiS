<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeatureController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name("home");

Route::get("/pos", function(){
  return view("pos.app");
})->name("pos");

Route::get("/sales", function(){
  return view("sales.app");
})->name("sales");

Route::get("/sales/new", function(){
  return view("sales.new");
})->name("newsales");

Route::get("/sales/detail/{id}", function(){
  return view("sales.detail");
})->name("salesdetail");

Route::get("/purchases", function(){
  return view("purchase.app");
})->name("purchase");

Route::get("/purchases/new", function(){
  return view("purchase.new");
})->name("newpurchase");

Route::get("/purchases/detail/{id}", function(){
  return view("purchase.detail");
})->name("purchasedetail");

Route::get("/inventory", function(){
  return view("inventory.app");
})->name("inventory");

Route::get("/inventory/new", function(){
  return view("inventory.new");
})->name("newinventory");

Route::get("/report", function(){
  return view("report.app");
})->name("report");

Route::get("/settings/{tab?}", function($tab = 1){
  return view("setting", ["tab"=>$tab]);
})->name("setting");

Route::get("/debts", function(){
  return view("debt.tracker");
})->name("debt");

Route::get("/customer", function(){
  return view("customer.app");
})->name("customer");

Route::get("/customer/new", function(){
  return view("customer.new");
})->name("newcustomer");

Route::get("/customer/detail/{id}", function(){
  return view("customer.detail");
})->name("customerdetail");

Route::get("/supplier", function(){
  return view("supplier.app");
})->name("supplier");

Route::get("/supplier/new", function(){
  return view("supplier.new");
})->name("newsupplier");


Route::resource("test", FeatureController::class);