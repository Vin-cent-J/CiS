<?php

use Illuminate\Support\Facades\Route;

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

Route::get("/sales/detail/{detail}", function(){
  return view("sales.detail");
})->name("salesdetail");

Route::get("/purchase", function(){
  return view("purchase.app");
})->name("purchase");

Route::get("/inventory", function(){
  return view("inventory.app");
})->name("inventory");

Route::get("/report", function(){
  return view("report.app");
})->name("report");

Route::get("/settings", function(){
  return view("setting");
})->name("setting");

