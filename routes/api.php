<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('org/login', 'OrgController@login');
    Route::post('org/signup', 'OrgController@signup');
    Route::get('org/signup/activate/{token}', 'OrgController@Activatesignup');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('org/logout', 'OrgController@logout');
        Route::get('org/user', 'OrgController@user');
        
        
        Route::get('org/checkrole', [
           'uses'=> "OrgController@checkrole",
            'as' => "org/checkrole",
            'middleware' => "roles",
            'roles'=> ['organization']
        ]);
        
         Route::get('org/usermailcheck/{email}', [
           'uses'=> "OrgController@usermailcheck",
            'as' => "org/usermailcheck",
            'middleware' => "roles",
            'roles'=> ['organization']
        ]);
        
        
    });
});
