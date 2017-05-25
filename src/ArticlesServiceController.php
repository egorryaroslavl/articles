<?php

	namespace Egorryaroslavl\Articles;

	use Illuminate\Support\ServiceProvider;

	class ArticlesServiceProvider extends ServiceProvider
	{

		public function boot()
		{
			$this->loadViewsFrom( __DIR__ . '/views', 'articles' );
			$this->loadRoutesFrom( __DIR__ . '/routes.php' );
			$this->publishes( [ __DIR__ . '/views' => resource_path( 'views/admin/articles' ) ], 'articles' );
			$this->publishes( [ __DIR__ . '/config/articles.php' => config_path( '/admin/articles.php' ) ], 'config' );
			$this->publishes( [
				__DIR__ . '/migrations/2017_05_20_084735_create_articles_table.php' => base_path( 'database/migrations/2017_05_20_084735_create_articles_table.php' )
			], '' );


		}

		public function register()
		{

			$this->app->make( 'Egorryaroslavl\Articles\ArticlesController' );
			$this->mergeConfigFrom( __DIR__ . '/config/articles.php', 'articles' );
		}

	}