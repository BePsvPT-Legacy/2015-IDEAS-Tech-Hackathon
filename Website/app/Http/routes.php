<?php

get('/', ['as' => 'index', 'uses' => 'HomeController@index']);
get('pages', ['as' => 'facebook.pages', 'uses' => 'HomeController@pages']);
post('github/pull', ['as' => 'github.pull', 'uses' => 'HomeController@githubPull']);

get('auth/facebook', ['as' => 'facebook.signIn', 'uses' => 'AuthController@facebookSignIn']);
get('auth/facebook/callback', ['as' => 'facebook.signInAuth', 'uses' => 'AuthController@facebookSignInCallback']);
post('auth/facebook/canvas', ['as' => 'facebook.signInWithCanvas', 'uses' => 'AuthController@facebookSignInWithCanvas']);

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function()
{
    Route::group(['prefix' => 'lotteries'], function()
    {
        get('getAll', ['as' => 'api.lotteries.getAll', 'uses' => 'LotteriesController@getAll']);
        get('getSubscribesList', ['as' => 'api.lotteries.getSubscribesList', 'uses' => 'LotteriesController@getSubscribesList']);
        get('getSubscribes', ['as' => 'api.lotteries.getSubscribes', 'uses' => 'LotteriesController@getSubscribes']);
        post('subscribe', ['as' => 'api.lotteries.subscribe', 'uses' => 'LotteriesController@subscribe']);
        get('getSpecific/{id}', ['as' => 'api.lotteries.getSpecific', 'uses' => 'LotteriesController@getSpecific']);
    });
});