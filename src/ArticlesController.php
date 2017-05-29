<?php

	namespace Egorryaroslavl\Articles;

	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use Illuminate\Validation\Rule;
	use Egorryaroslavl\Articles\Models\ArticleModel;

	class ArticlesController extends Controller
	{


		function messages()
		{
			$strLimit = config( 'admin.settings.text_limit.text_short_description.', 300 );
			return [
				'name.required'         => 'Поле "Имя" обязятельно для заполнения!',
				'alias.required'        => 'Поле "Алиас" обязятельно для заполнения!',
				'name.unique'           => 'Значение поля "Имя" не является уникальным!',
				'alias.unique'          => 'Значение поля "Алиас" не является уникальным!',
				'description.required'  => 'Поле "Текст" обязятельно для заполнения!',
				'short_description.max' => 'Поле "Краткий текст" не должно быть более ' . $strLimit . ' символов!',

			];

		}

		public function index()
		{

			$data        = ArticleModel::orderBy( 'pos','DESC' )->paginate( config('admin.articles.paginate') );
			$data->table = 'articles';
			$breadcrumbs = '<div class="row wrapper border-bottom white-bg page-heading"><div class="col-lg-12"><h2>Статьи</h2><ol class="breadcrumb"><li><a href="/admin">Главная</a></li><li class="active"><a href="/admin/articles">Статьи</a></li></ol></div></div>';


			return view( 'articles::index',
				[
					'data'        => $data,
					'breadcrumbs' => $breadcrumbs
				] );


		}


		public function create()
		{
			$data        = new ArticleModel();
			$data->act   = 'articles-store';
			$data->table = 'articles';

			$breadcrumbs = '<div class="row wrapper border-bottom white-bg page-heading"><div class="col-lg-12"><h2>Статьи</h2><ol class="breadcrumb"><li><a href="/admin">Главная</a></li><li class="active"><a href="/admin/articles">Статьи</a></li><li><strong>Создание новой статьи</strong></li></ol></div></div>';

			return view( 'articles::form', [ 'data' => $data, 'breadcrumbs' => $breadcrumbs ] );


		}

		public function store( Request $request )
		{

			$v = \Validator::make( $request->all(), [
				'name' => 'required|unique:articles|max:255',


			], $this->messages() );


			if( $v->fails() ){
				return redirect( 'admin/articles/create' )
					->withErrors( $v )
					->withInput();
			}

			$input            = $request->all();
			$input            = array_except( $input, '_token' );
			$input['short_description']   = str_limit( trim( $input['short_description'] ), $strLimit, '...' );
			$id               = ArticleModel::create( $input );


			\Session::flash( 'message', 'Запись добавлена!' );

			if( isset( $request->submit_button_stay ) ){
				return redirect()->back();
			}
			return redirect( '/admin/articles' );


		}

		public function edit( $id )
		{


			$articleModel = ArticleModel::class;
			$data         = $articleModel::where( 'id', $id )->first();

			$data->table = 'articles';
			$data->act   = 'articles-update';

			if( $data->related === null ){
				$data->related = [];
			}


			/* все статьи за исключением текущей */
			$articles = $articleModel::where( 'id', '!=', $id )
				->whereNotIn( 'id', $data->related )
				->get();

			/* статьи с отобранными id */
			$reletedArticles = $articleModel::whereIn( 'id', $data->related )->get();


			$breadcrumbs = '<div class="row wrapper border-bottom white-bg page-heading"><div class="col-lg-12"><h2>Статьи</h2><ol 
class="breadcrumb"><li><a href="/admin">Главная</a></li><li 
class="active"><a href="/admin/articles">Статьи</a></li><li>Редактирование <strong>[
 <a href="/articles/' . $data->alias . '" style="color:blue" title="Смотреть на пользовательской части">' . $data->name . ' <img src="/_admin/img/extlink.png" alt="" 
 style="margin:0"></a> ]</strong></li></ol></div></div>';

			return view( 'articles::form', [
				'data'        => $data,
				'breadcrumbs' => $breadcrumbs,
				'first'       => $articles,
				'second'      => $reletedArticles
			] );
		}


		public function update( Request $request )
		{

			// dd($request->all());

			$strLimit = config( 'admin.settings.text_limit.short_description' );
			$direct   = isset( $request->submit_button_stay ) ? 'stay' : 'back';

			$v = \Validator::make( $request->all(), [
				'name' => [
					'required',
					Rule::unique( 'articles' )->ignore( $request->id ),
					'max:255'
				],

				'alias'       => [
					'required',
					Rule::unique( 'articles' )->ignore( $request->id ),
					'max:255'
				],
				'description' => 'required',
				/*'short_description' => 'max:' . $strLimit,*/


			], $this->messages() );


			if( $v->fails() ){
				return redirect( 'admin/articles/' . $request->id . '/edit' )
					->withErrors( $v )
					->withInput();
			}

			$article                      = ArticleModel::find( $request->id );
			$article->name                = $request->name;
			$article->alias               = $request->alias;
			$article->description         = $request->description;
			$article->short_description   = str_limit( trim( $request->short_description ), $strLimit, '...' );
			$article->public              = isset( $request->public ) ? $request->public : 0;
			$article->anons               = isset( $request->anons ) ? $request->anons : 0;
			$article->hit                 = isset( $request->hit ) ? $request->hit : 0;
			$article->related             = isset( $request->related ) ? $request->related : [];
			$article->metatag_title       = $request->metatag_title;
			$article->metatag_description = $request->metatag_description;
			$article->metatag_keywords    = $request->metatag_keywords;
			$article->save();

			\Session::flash( 'message', 'Запись обновлена!' );


			if( $direct == 'back' ){
				return redirect( url( '/admin/articles' ) );
			}

			if( $direct == 'stay' ){
				return redirect()->back();
			}
		}


		public function destroy( $id )
		{

			$article = ArticleModel::find( $id );
			$article->delete();
			return redirect()->back();

		}

		public function translite( Request $request )
		{

			$dictionary = array(
				"А" => "a",
				"Б" => "b",
				"В" => "v",
				"Г" => "g",
				"Д" => "d",
				"Е" => "e",
				"Ж" => "zh",
				"З" => "z",
				"И" => "i",
				"Й" => "y",
				"К" => "K",
				"Л" => "l",
				"М" => "m",
				"Н" => "n",
				"О" => "o",
				"П" => "p",
				"Р" => "r",
				"С" => "s",
				"Т" => "t",
				"У" => "u",
				"Ф" => "f",
				"Х" => "h",
				"Ц" => "ts",
				"Ч" => "ch",
				"Ш" => "sh",
				"Щ" => "sch",
				"Ъ" => "",
				"Ы" => "yi",
				"Ь" => "",
				"Э" => "e",
				"Ю" => "yu",
				"Я" => "ya",
				"а" => "a",
				"б" => "b",
				"в" => "v",
				"г" => "g",
				"д" => "d",
				"е" => "e",
				"ж" => "zh",
				"з" => "z",
				"и" => "i",
				"й" => "y",
				"к" => "k",
				"л" => "l",
				"м" => "m",
				"н" => "n",
				"о" => "o",
				"п" => "p",
				"р" => "r",
				"с" => "s",
				"т" => "t",
				"у" => "u",
				"ф" => "f",
				"х" => "h",
				"ц" => "ts",
				"ч" => "ch",
				"ш" => "sh",
				"щ" => "sch",
				"ъ" => "y",
				"ы" => "y",
				"ь" => "",
				"э" => "e",
				"ю" => "yu",
				"я" => "ya",
				"-" => "_",
				" " => "_",
				"," => "_",
				"." => "_",
				"?" => "",
				"!" => "",
				"«" => "",
				"»" => "",
				":" => "",
				'ё' => "e",
				'Ё' => "e",
				"*" => "",
				"(" => "",
				")" => "",
				"[" => "",
				"]" => "",
				"<" => "",
				">" => ""
			);
			$string     = preg_replace( '/[^\w\s]/u', ' ', $request->alias_source );
			$string     = mb_strtolower( strtr( strip_tags( trim( $string ) ), $dictionary ) );
			$alias      = preg_replace( '/[_]+/', '_', $string );
			return json_encode( [ 'alias' => $alias ] );
		}


		public static function changestatus( Request $request )
		{
			$sql = "
			UPDATE `" . $request->table . "` 
			SET `" . $request->field . "` = NOT `" . $request->field . "` WHERE id =" . $request->id;

			$res = \DB::update( $sql );

			if( $res > 0 ){
				$current = $request->value > 0 ? '0' : '1';
				echo json_encode( [ 'error' => 'ok', 'message' => $current ] );
			} else{
				echo json_encode( [ 'error' => 'error', 'message' => '' ] );
			}

		}

		public function reorder( Request $request )
		{


			if( isset( $request->sort_data ) ){

				$id        = array();
				$table     = $request->table;
				$sort_data = $request->sort_data;

				parse_str( $sort_data );

				$count = count( $id );
				for( $i = 0; $i < $count; $i++ ){

					\DB::update( 'UPDATE `' . $table . '` SET `pos`=' . $i . ' WHERE `id`=? ', [ $id[ $i ] ] );

				}

			}
		}


	}