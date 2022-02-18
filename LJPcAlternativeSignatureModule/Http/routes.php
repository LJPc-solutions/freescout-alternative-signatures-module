<?php

Route::group(['middleware' => 'web', 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\LJPcAlternativeSignaturesModule\Http\Controllers'], function()
{
	Route::get( '/mailbox/{id}/signatures/{signatureId}', [ 'uses' => 'MailboxCustomSignaturesController@get', 'laroute' => true ] )->name( 'mailbox.custom_signatures' );
});
