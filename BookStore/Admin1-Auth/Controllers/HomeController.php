<?php
declare(strict_types=1);

class HomeController extends Controller
{
    private Book $books;

    public function __construct()
    {
        $this->books = new Book();
    }

    public function index(): void
    {
        $this->view('home', [
            'title' => 'Online Book Store',
            'categories' => $this->books->categories(),
            'books' => $this->books->latest(),
            'heading' => 'Featured Books',
        ]);
    }

    public function category(int $id): void
    {
        $this->view('home', [
            'title' => 'Books by Category',
            'categories' => $this->books->categories(),
            'books' => $this->books->byCategory($id),
            'heading' => 'Category Books',
        ]);
    }

    public function book(int $id): void
    {
        $book = $this->books->find($id);
        if (!$book) {
            throw new RuntimeException('Book not found.');
        }
        $this->view('books/show', ['title' => $book['title'], 'book' => $book]);
    }
}