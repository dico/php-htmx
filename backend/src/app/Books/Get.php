<?php

namespace App\Books;

use App\Core\Response;
use App\Core\Template;

class Get
{
    private $books = [
        ['id' => 1, 'title' => 'Book One', 'author' => 'Author One'],
        ['id' => 2, 'title' => 'Book Two', 'author' => 'Author Two'],
        ['id' => 3, 'title' => 'Book Three', 'author' => 'Author Three'],
    ];


	/**
	 * Get a list of books.
	 *
	 * This method returns a list of books available in the library.
	 *
	 * @title Book List
	 * @description Retrieves an array of book objects in JSON or HTML format.
	 * @author Your Name
	 *
	 * @OA\Get(
	 *     path="/books",
	 *     summary="Get a list of books",
	 *     tags={"Books"},
	 *     @OA\Response(
	 *         response=200,
	 *         description="List of books in JSON or HTML format",
	 *         content={
	 *             @OA\MediaType(
	 *                 mediaType="application/json",
	 *                 @OA\Schema(
	 *                     type="array",
	 *                     @OA\Items(
	 *                         type="object",
	 *                         @OA\Property(property="id", type="integer"),
	 *                         @OA\Property(property="title", type="string"),
	 *                         @OA\Property(property="author", type="string")
	 *                     )
	 *                 )
	 *             ),
	 *             @OA\MediaType(
	 *                 mediaType="text/html",
	 *                 @OA\Schema(
	 *                     type="string",
	 *                     example="<ul><li>Book One by Author One</li><li>Book Two by Author Two</li></ul>"
	 *                 )
	 *             )
	 *         }
	 *     )
	 * )
	 */

    public function list()
    {
        $htmlOutput = Template::render('components/book_list.twig', ['books' => $this->books]);

        return Response::fromClientAccept([
            'json' => $this->books,
            'html' => $htmlOutput
        ])->send();
    }


    public function get($id)
    {
        $book = array_filter($this->books, fn($b) => $b['id'] == $id);
        if (empty($book)) {
            http_response_code(404);
            return Response::fromClientAccept([
                'json' => ['error' => 'Book not found'],
                'html' => '<p>Book not found</p>'
            ])->send();
        }
        $book = array_values($book)[0];
        $htmlOutput = Template::render('components/book_detail.twig', ['book' => $book]);

        return Response::fromClientAccept([
            'json' => $book,
            'html' => $htmlOutput
        ])->send();
    }
}
