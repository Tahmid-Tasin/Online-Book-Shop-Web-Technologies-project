<?php
declare(strict_types=1);

class Book extends Model
{
    public function categories(): array
    {
        return $this->db->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    }

    public function latest(): array
    {
        return $this->db->query('SELECT b.*, c.name AS category_name FROM books b LEFT JOIN categories c ON c.id = b.category_id ORDER BY b.created_at DESC LIMIT 8')->fetchAll();
    }

    public function byCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare('SELECT b.*, c.name AS category_name FROM books b LEFT JOIN categories c ON c.id = b.category_id WHERE b.category_id = ? ORDER BY b.title');
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT b.*, c.name AS category_name FROM books b LEFT JOIN categories c ON c.id = b.category_id WHERE b.id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function all(): array
    {
        return $this->db->query('SELECT b.*, c.name AS category_name FROM books b LEFT JOIN categories c ON c.id = b.category_id ORDER BY b.created_at DESC')->fetchAll();
    }

    public function search(string $q, string $filter): array
    {
        $q = '%' . $q . '%';
        $column = match ($filter) {
            'author' => 'b.author',
            'genre' => 'c.name',
            default => 'b.title',
        };

        $stmt = $this->db->prepare("SELECT b.id, b.title, b.author, b.description, b.price, b.stock, c.name AS category_name FROM books b LEFT JOIN categories c ON c.id = b.category_id WHERE {$column} LIKE ? ORDER BY b.title LIMIT 20");
        $stmt->execute([$q]);
        return $stmt->fetchAll();
    }

    public function save(array $data, ?int $id = null): bool
    {
        if ($id) {
            $stmt = $this->db->prepare('UPDATE books SET title = ?, author = ?, description = ?, price = ?, category_id = ?, image_path = ?, stock = ? WHERE id = ?');
            return $stmt->execute([$data['title'], $data['author'], $data['description'], $data['price'], $data['category_id'], $data['image_path'], $data['stock'], $id]);
        }

        $stmt = $this->db->prepare('INSERT INTO books (title, author, description, price, category_id, image_path, stock) VALUES (?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$data['title'], $data['author'], $data['description'], $data['price'], $data['category_id'], $data['image_path'], $data['stock']]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM books WHERE id = ? AND id NOT IN (SELECT book_id FROM order_items)');
        return $stmt->execute([$id]);
    }
}