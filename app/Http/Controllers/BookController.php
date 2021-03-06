<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //'$books'にすべてのBook(本の情報)を格納し、json形式でviewに送る
        $books = Book::all();
        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //添付する画像ファイルのヴァリデーション
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,png,jpg,bmb,mp4,qt,x-ms-wmv,mpeg,x-msvideo'
        ], [
            'file.required' => 'ファイルが選択されていません',
            'file.mimes' => '画像もしくは動画ファイルではありません',
        ]);

        if (request()->file) {
            $file_name = time() . '.' . request()->file->getClientOriginalName();
            request()->file->storeAs('public', $file_name);

        //viewで入力した'$request'のタイトル、著者、コメントを'$book'に入れて保存
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->comment = $request->comment;
        $book->path = 'storage/' . $file_name;
        $book->save();

        return ['success' => '登録しました!'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Search for Book title.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $books
     * @return \Illuminate\Http\Response
     */
    public function search(Book $books, $title)
    {
        //タイトルに含まれる文言をもとにBookの'title'を検索
        $books = Book::where('title', 'like', '%' .$title. '%')->get();
        return response()->json($books);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book, $id)
    {
        //viewで選択した'$book'を'id'で探し出し'$book'に情報を再入力して更新する
        $book = Book::find($id);
        $book->title = $request->title;
        $book->author = $request->author;
        $book->comment = $request->comment;
        $book->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //viewで選択した'$book'をidで指定して削除する
        $book = Book::find($id);
        $book->delete();
    }
}
