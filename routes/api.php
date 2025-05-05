<?php

use App\Http\Controllers\SellasistController;

Route::get('/orders', [SellasistController::class, 'getOrders']);
