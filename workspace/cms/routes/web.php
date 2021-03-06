<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
* ポストとゲットを受け取る時に必要（２行目） */
use App\Book;
use Illuminate\Http\Request; 

/**
* 本のダッシュボード表示 */
Route::get('/', function () {
     $books = Book::orderBy('created_at', 'asc')->get();
    return view('books', [
        'books' => $books
    ]);
});

/**
* 新「本」を追加　ポストで受け取りリクエストで受け取る */
Route::post('/books', function (Request $request) {
   //バリデーション
    $validator = Validator::make($request->all(), [
            'item_name' => 'required|max:255',
     ]);
     
    //バリデーション:エラー
    if ($validator->fails()) {
            return redirect('/')->withInput()->withErrors($validator);
    } 
    // Eloquent モデル
    $books = Book::find($request->id);
    $books->item_name = $request->item_name;
    $books->item_number = '1';
    $books->item_amount = '1000';
    $books->published = '2017-03-07 00:00:00';
    $books->save();   //「/」ルートにリダイレクト 
    return redirect('/');
});

/**
* 本を削除 　bookって名前で受け取る*/
Route::post('/book/delete/{book}', function (Book $book) {
    $book->delete();
    return redirect('/');
});

//更新処理
Route::post('/books/update', function(Request $request){
 //バリデーション
 $validator = Validator::make($request->all(),[
     'id'=>'required',
     'item_name'=>'required|min:3|max:255',
      'item_number'=>'required|min:1|max:3',
       'item_amount'=>'required|max:6',
       'pub|ished'=>'required',
     ]);
//バリデーションエラー
if($validator->fails()){
    return redirect('/')
    ->withInput()
    ->withErrors($validator);
}

//データ更新
$books = Book::find($request->id);
$books->item_name = $request->item_name;
$books->item_number = $request->item_number;
$books->item_amount = $request->item_amount;
$books->published = $request->published;
$books->save();
return redirect('/');
     
});






?>