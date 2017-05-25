<?php


	namespace Egorryaroslavl\Articles\Models;

	use Illuminate\Database\Eloquent\Model;


	class ArticleModel extends Model
	{
		protected $table = 'articles';

		protected $fillable = [
			'name',
			'alias',
			'description',
			'short_description',
			'related',
			'icon',
			'pos',
			'public',
			'anons',
			'hit',
			'metatag_title',
			'metatag_description',
			'metatag_keywords' ];

		protected $casts = [
			'public'  => 'boolean',
			'anons'   => 'boolean',
			'hit'     => 'boolean',
			'related' => 'array',
		];

	}