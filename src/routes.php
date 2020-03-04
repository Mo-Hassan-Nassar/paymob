<?php

Route::group(['prefix' => 'paymob'], function () {
    Route::get('/pay', 'msh\paymob\PayMobController@pay');
    Route::get('/transactionResponseCallback', 'msh\paymob\PayMobController@transactionResponseCallback');
    Route::get('/transactionResponseCallback', 'msh\paymob\PayMobController@transactionResponseCallback');
});

