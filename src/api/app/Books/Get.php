<?php

namespace App\Books;

use App\Core\Database;
use App\Core\Response;
use App\Core\Template;

class Get
{
    public function list()
    {
        // Query books
        $books = Database::table('books')->get();

        $htmlOutput = Template::render('components/book_list.twig', ['books' => $books]);

        return Response::fromClientAccept([
            'json' => $books,
            'html' => $htmlOutput
        ])->send();
    }

    public function get($id)
    {
        // Query single book
        $book = Database::table('books')->where('id', $id)->first();

        if (!$book) {
            http_response_code(404);
            return Response::fromClientAccept([
                'json' => ['error' => 'Book not found'],
                'html' => '<p>Book not found</p>'
            ])->send();
        }

        $htmlOutput = Template::render('components/book_detail.twig', ['book' => $book]);

        return Response::fromClientAccept([
            'json' => $book,
            'html' => $htmlOutput
        ])->send();
    }
}
