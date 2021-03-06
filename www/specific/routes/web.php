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
Route::get('/history', 'HomeController@history')->name('history');
Route::post('/history', 'HomeController@submitHistory')->name('submit-history');
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
Route::get('/extraction/{jobKey}/download', 'ExtractionResultController@download')->name('download-structures');
Route::get('/extraction/{jobKey}/enrich/{id}', 'ExtractionResultController@runEnrichment')
     ->name('extraction-enrich');
Route::get('/extraction/{extractionJobKey}/enrichment/{enrichmentJobKey}',
    'EnrichmentResultController@viewEnrichmentResult')->name('extraction-enrichment');
Route::post('/enrichment/{jobKey}/terms', 'EnrichmentResultController@listTerms')->name('enrichment-terms');
Route::post('/enrichment/{jobKey}/view', 'EnrichmentResultController@viewSubStructure')
     ->name('enrichment-view');
Route::get('/enrichment/{jobKey}/heatmap', 'EnrichmentResultController@heatmap')->name('enrichment-heatmap');
Route::get('/enrichment/{jobKey}/download', 'EnrichmentResultController@download')->name('enrichment-download');