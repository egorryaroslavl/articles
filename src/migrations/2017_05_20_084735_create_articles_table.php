<?php

	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateArticlesTable extends Migration
	{

		public function up()
		{
			Schema::create( 'articles', function ( Blueprint $table ){
				$table->increments( 'id' );
				$table->string( 'name' );
				$table->string( 'alias' );
				$table->text( 'description' )->nullable();
				$table->string( 'short_description' )->nullable();
				$table->text( 'related' )->nullable();
				$table->string( 'icon' )->nullable();
				$table->text( 'images' )->nullable();
				$table->boolean( 'public' )->default( 1 );
				$table->boolean( 'anons' )->default( 0 );
				$table->boolean( 'hit' )->default( 0 );
				$table->integer( 'pos' )->default( 0 );
				$table->string( 'metatag_title' )->nullable();
				$table->string( 'metatag_description' )->nullable();
				$table->string( 'metatag_keywords' )->nullable();
				$table->timestamps();
			} );
		}


		public function down()
		{
			//
		}
	}
