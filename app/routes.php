
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'AuthController@showLogin');

Route::get('user/view',                    'UserController@viewUser');

// Confide routes
Route::get( 'user/create',                 'UserController@create');
Route::post('user',                        'UserController@store');
Route::get( 'login',                  'UserController@login');
Route::post('login',                  'UserController@do_login');
Route::get( 'user/confirm/{code}',         'UserController@confirm');
Route::get( 'user/forgot_password',        'UserController@forgot_password');
Route::post('user/forgot_password',        'UserController@do_forgot_password');
Route::get( 'user/reset_password/{token}', 'UserController@reset_password');
Route::post('user/reset_password',         'UserController@do_reset_password');
Route::get( 'logout',                 'UserController@logout');

Route::get('/', function()
{
	return Redirect::to('login');
});

Route::get('/dashboard', 'UserController@dashboard');

Route::get( 'user/edit/{id}', function($id)
{
	return View::make('useredit')->with('id',$id);
});

Route::post('user/edit/{id}',[ 'uses' => 'UserController@edit']);

Route::get('create_roles','UserController@getRole');

Route::get('back', function(){
return View::make('back');

});


Route::post( 'user/delete', function()
{
	$errors="Account Deactivated.";
	$id=Input::get('hide');

	DB::table('users')->where('id', $id)->update(array('confirmed' => 0));
	
	Session::flash('message','Successfully deleted the user.');
	return Redirect::to('user/view');
});

Route::post( 'user/activate', function()
{
	$errors="Account Activated.";
	$id=Input::get('hide');

	DB::table('users')->where('id', $id)->update(array('confirmed' => 1));
	
	//Session::flash('message','Successfully activated the user.');
	return Redirect::to('user/view');
});


//Office routes
Route::resource('offices', 'OfficeController');

Route::get('offices', 'OfficeController@index');
Route::get('offices/delete/{id}',['as' => 'offices.delete', 'uses' => 'OfficeController@deleteOffice']);
Route::post('offices/{id}/edit',['as' => 'offices.update', 'uses' => 'OfficeController@update']);


//For Image Upload Testing


Route::get( 'pr_imageupload', function()
{
	return View::make('pr_imageupload');
});

Route::get('attach/{id}', function($id)
{
Session::put('pr_id', $id);
return Redirect::to('pr_imageupload');

}
	);

Route::get( 'resultstest', function()
{
	return View::make('resultstest');
});


//Purchase Request Routes
//Route::resource('preqList', 'PreqController');
Route::get('purchaseRequest/edit','PurchaseRequestController@edit');
Route::get('purchaseRequest/view','PurchaseRequestController@view');

Route::get('purchaseRequest/vieweach','PurchaseRequestController@vieweach');	//change this for the view of each puchase request

Route::get( 'purchaseRequest/edit/{id}', function($id)
{
	return View::make('pr_edit')->with('id',$id);
});
Route::post('purchaseRequest/edit/{id}',[ 'uses' => 'PurchaseRequestController@edit_submit']);
Route::post( 'purchaseRequest/delete', function()
{
	$errors="Account Deactivated.";
	$id=Input::get('del_pr');
	Purchase::where('id',$id)->delete();
	
	Session::flash('message','Successfully deleted the user.');
	return Redirect::to('purchaseRequest/view');
});

Route::get('/offices', 'OfficeController@index');

//Purchase Request Routes
Route::get('purchaseRequest/create', 'PurchaseRequestController@create');
Route::post('purchaseRequest/create', ['as' => 'purchaseRequest_submit', 'uses' => 'PurchaseRequestController@create_submit']);
Route::get( 'purchaseRequest/vieweach/{id}', 'PurchaseRequestController@vieweach');


Route::get('workflow/below-fifty', function(){
	return View::make('workflows.below_fifty_workflow');
});

// <---- JAN ROUTES
Route::get('workflow/belowFifty', function(){
	return View::make('workflows.below_fifty');
});
Route::get('workflow/aboveFifty', function(){
	return View::make('workflows.above_fifty');
});
Route::get('workflow/aboveFive', function(){
	return View::make('workflows.above_five');
});
Route::get('workflow', function(){
	return View::make('workflows.workflowdash');
});
