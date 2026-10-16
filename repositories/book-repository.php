<?php
require_once __DIR__ . '/../config/database.php';

function getBooks()
{
  global $pdo;

  $query = "select b.id, b.title, b.stock, c.name as category from books b
            left join categories c on b.category_id = c.id
           ";
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $books = $stmt->fetchAll();
  $query = "select ba.book_id, a.name from book_author ba
            left join authors a on ba.author_id = a.id
          ";

  $stmt = $pdo->prepare($query);
  $stmt->execute();
  $bookAuthors = $stmt->fetchAll();

  $bookByAuthors = [];

  foreach ($bookAuthors as $bookAuthor) {
    $bookByAuthors[$bookAuthor['book_id']][] = $bookAuthor['name'];
  }

  foreach ($books as $index => $book) {
    $books[$index]['authors'] = $bookByAuthors[$book['id']] ?? [];
  }

  return $books;
}


function getBook($id)
{
  global $pdo;

  $query = "select id, title, isbn, year, stock, category_id, description from books where id = :id";
  $stmt = $pdo->prepare($query);
  $stmt->execute(['id' => $id]);
  $book = $stmt->fetch();

  if (!$book) {
    return false;
  }

  $query = "select a.id, a.name from book_author ba
    left join authors a on ba.author_id = a.id
    where ba.book_id = :book_id";
  $stmt = $pdo->prepare($query);
  $stmt->execute(['book_id' => $book['id']]);
  $book['authors'] = $stmt->fetchAll();

  $authorIds = $stmt->fetchAll();

  $book['authors_ids'] = array_column($authorIds, 'id');


  return $book;
}
