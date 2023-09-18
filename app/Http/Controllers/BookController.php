<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Catalog;
use App\Models\Publisher;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publishers = Publisher::latest()->get();
        $authors = Author::latest()->get();
        $catalogs = Catalog::latest()->get();
        $tes = date("Y-m-d");
       
        return view(
            'pages.book',
            [
                'publishers' => $publishers,
                'authors' => $authors,
                'catalogs' => $catalogs,
              
            ]
        );
    }

    public function api()
    {
        $books = Book::where('qty','>','0')->latest()->get();
        return json_encode($books);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "isbn" => "required|string",
            "title" => "required|email",
            "year" => "required|numeric",
            "publisher_id" => "required|numeric",
            "author_id" => "required|numeric",
            "catalog_id" => "required|numeric",
            "qty" => "required|numeric",
            "price" => "required|numeric",
        ]);

        Book::create($data);
        return redirect()->route('books.index');
    }

    public function apiStore()
    {
        $editStatus = request('editStatus');

        if ($editStatus != "null") {
            $book = Book::find($editStatus);
            $book->isbn = request('isbn');
            $book->title = request('title');
            $book->year = request('year');
            $book->publisher_id = request('publisher_id');
            $book->author_id = request('author_id');
            $book->catalog_id = request('catalog_id');
            $book->qty = request('qty');
            $book->price = request('price');
            $book->save();
        } else {
            $book = new Book;
            $book->isbn = request('isbn');
            $book->title = request('title');
            $book->year = request('year');
            $book->publisher_id = request('publisher_id');
            $book->author_id = request('author_id');
            $book->catalog_id = request('catalog_id');
            $book->qty = request('qty');
            $book->price = request('price');
            $book->save();
        }

        return response()->json('success', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    public function apiGetBook($id)
    {
        $book = Book::find($id);

        return response()->json($book, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            "isbn" => "required|string",
            "title" => "required|email",
            "year" => "required|numeric",
            "publisher_id" => "required|numeric",
            "author_id" => "required|numeric",
            "catalog_id" => "required|numeric",
            "qty" => "required|numeric",
            "price" => "required|numeric",
        ]);

        $validateData = $request->validate($data);
        $author = Book::findOrFail($id);

        $author->update($validateData);

        return redirect()->route('authors.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function apiDelete($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json('success', 200);
    }
}
