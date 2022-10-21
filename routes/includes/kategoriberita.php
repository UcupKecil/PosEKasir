<?php

use Illuminate\Support\Facades\Route;

Route::get('/kategoriberita', 'KategoriBeritaController@index');
Route::get('/kategoriberita/{id}', 'KategoriBeritaController@show');
Route::post('/kategoriberita', 'KategoriBeritaController@store');
Route::post('/kategoriberita/{id}', 'KategoriBeritaController@edit');
//Route::post('/kategoriberita/{id}', 'KategoriBeritaController@update');
Route::delete('/kategoriberita/{id}', 'KategoriBeritaController@destroy');
