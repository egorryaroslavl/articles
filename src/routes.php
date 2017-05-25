<?php


	/*=============  ARTICLES  ==============*/

	Route::group( [ 'middleware' => 'web' ], function (){

		Route::get( '/admin/articles', 'egorryaroslavl\articles\ArticlesController@index' );
		Route::get( '/admin/articles/create', 'egorryaroslavl\articles\ArticlesController@create' )->middleware( 'web' );
		Route::get( '/admin/articles/{id}/edit', 'egorryaroslavl\articles\ArticlesController@edit' )->middleware( 'web' );
		Route::get( '/admin/articles/{id}/delete', 'egorryaroslavl\articles\ArticlesController@destroy' )->middleware( 'web' );
		Route::post( '/admin/articles/store', 'egorryaroslavl\articles\ArticlesController@store' )->middleware( 'web' )->name('articles-store');
		Route::post( '/admin/articles/update', 'egorryaroslavl\articles\ArticlesController@update' )->middleware( 'web' )->name('articles-update');

		Route::post( '/translite', 'egorryaroslavl\articles\ArticlesController@translite' )->middleware( 'web' )->name('translite');


		Route::post( '/changestatus', 'egorryaroslavl\articles\ArticlesController@changestatus' )->middleware( 'web' )->name('changestatus');



		Route::post( '/reorder', 'egorryaroslavl\articles\ArticlesController@reorder' )->middleware( 'web' )->name('reorder');


	} );




	/*=============  /ARTICLES  ==============*/

