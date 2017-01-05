<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'HomeController@index')->name('index');
Route::match(['get', 'post'], '/list-nois', 'HomeController@listNodesOfInterest')->name('list-nois');
Route::post('/submit-extraction', 'HomeController@submitExtractionJob')->name('submit-extraction');
Route::get('/contacts', function () {
    return view('contacts');
});
Route::get('/references', function () {
    return view('references');
});
Route::get('/extraction/{jobKey}', 'ExtractionResultController@viewExtractionResult')->name('extraction-results');
Route::post('/extraction/{jobKey}/structures', 'ExtractionResultController@listStructuresData')
    ->name('extraction-structures');
Route::get('/extraction/{jobKey}/enrich/{id}', 'ExtractionResultController@runEnrichment')
    ->name('extraction-enrich');
Route::get('/extraction/{extractionJobKey}/enrichment/{enrichmentJobKey}',
    'EnrichmentResultController@viewEnrichmentResult')->name('extraction-enrichment');