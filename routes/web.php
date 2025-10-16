<?php

use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\PosController;
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

Route::post("/pos/setSession", [PosController::class, "setSession"])->name("setSession");
Route::get("/pos/deleteSession/{id}", [PosController::class, "deleteSessionProduct"])->name("deleteSession");
Route::get("/pos/riwayat", [PosController::class, "riwayat"])->name("pos.riwayat");
Route::post("/pos/updateDebt", [PosController::class, "updateDebt"]);
Route::post("/pos/updateDiscount", [PosController::class, "updateDiscount"]);
Route::post("/pos/setSaleTotalDisc", [PosController::class, "setSaleTotalDisc"]);
Route::post("/pos/return", [PosController::class, "returnProduct"])->name("pos.return");

Route::get("/report/sales/{date}", [ReportController::class, "salesReport"])->name("report.sales");
Route::get('/report/purchases/{date}', [ReportController::class, "purchasesReport"])->name("report.purchases");

Route::post("/discounts/updateRule", [\App\Http\Controllers\DiscountRuleController::class, "updateRule"]);

Route::post("/sales/setSession", [\App\Http\Controllers\SaleController::class, "setSession"]);
Route::post("/sales/updateQty", [\App\Http\Controllers\SaleController::class, "updateQuantity"]);
Route::post("/sales/changeProduct", [\App\Http\Controllers\SaleController::class, "changeProduct"]);
Route::post("/sales/updateDiscount", [\App\Http\Controllers\SaleController::class, "updateDiscount"]);

Route::resource("purchase", \App\Http\Controllers\PurchaseController::class);
Route::post("/purchases/setSession", [\App\Http\Controllers\PurchaseController::class, "setSession"]);
Route::post("/purchases/updateQty", [\App\Http\Controllers\PurchaseController::class, "updateQuantity"]);
Route::post("/purchases/changeProduct", [\App\Http\Controllers\PurchaseController::class, "changeProduct"]);
Route::post("/purchases/updatePrice", [\App\Http\Controllers\PurchaseController::class, "updatePrice"]);

Route::resource("/", FeatureController::class);
Route::resource("settings", ConfigurationsController::class);
Route::resource("pos", PosController::class);
Route::resource("inventory", \App\Http\Controllers\InventoryController::class);
Route::resource("sales", \App\Http\Controllers\SaleController::class);

Route::resource("customer", \App\Http\Controllers\CustomerController::class);
Route::resource("supplier", \App\Http\Controllers\SupplierController::class);
Route::resource("debt", \App\Http\Controllers\DebtController::class);
Route::resource("category", \App\Http\Controllers\CategoryController::class);
Route::resource("discounts", \App\Http\Controllers\DiscountRuleController::class);