<?php
// models/productManager.php
require_once __DIR__ . '/product.php';

class ProductManager {
    public function __construct(private PDO $db) {}

    public function getAllProducts(string $search = '', string $category = ''): array {
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (name LIKE :search OR `desc` LIKE :search2)";
            $params[':search']  = "%$search%";
            $params[':search2'] = "%$search%";
        }
        if ($category) {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }

        $sql .= " ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $products = [];
        while ($row = $stmt->fetch()) {
            $p = new Product(
                $row['name'], $row['desc'], (float)$row['price'],
                $row['category'], $row['image_url'], (int)$row['quantity'],
                (float)($row['promo_price'] ?? 0.0), $row['promo_label'] ?? ''
            );
            $p->id = (int)$row['id'];
            $products[] = $p;
        }
        return $products;
    }

    public function getById(int $id): ?Product {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        $p = new Product(
            $row['name'], $row['desc'], (float)$row['price'],
            $row['category'], $row['image_url'], (int)$row['quantity'],
            (float)($row['promo_price'] ?? 0.0), $row['promo_label'] ?? ''
        );
        $p->id = (int)$row['id'];
        return $p;
    }

    public function insert(Product $p): bool {
        $sql = "INSERT INTO products (name, `desc`, price, category, image_url, quantity, promo_price, promo_label)
                VALUES (:name, :desc, :price, :category, :image_url, :quantity, :promo_price, :promo_label)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'       => $p->name,
            ':desc'       => $p->desc,
            ':price'      => $p->price,
            ':category'   => $p->category,
            ':image_url'  => $p->image_url,
            ':quantity'   => $p->quantity,
            ':promo_price'=> $p->promo_price,
            ':promo_label'=> $p->promo_label,
        ]);
    }

    public function update(Product $p): bool {
        $sql = "UPDATE products SET name=:name, `desc`=:desc, price=:price,
                category=:category, image_url=:image_url, quantity=:quantity,
                promo_price=:promo_price, promo_label=:promo_label
                WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'       => $p->name,
            ':desc'       => $p->desc,
            ':price'      => $p->price,
            ':category'   => $p->category,
            ':image_url'  => $p->image_url,
            ':quantity'   => $p->quantity,
            ':promo_price'=> $p->promo_price,
            ':promo_label'=> $p->promo_label,
            ':id'         => $p->id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getCategories(): array {
        $stmt = $this->db->query("SELECT DISTINCT category FROM products ORDER BY category");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getTotalCount(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    }

    public function decrementStock(int $id, int $qty): bool {
        $stmt = $this->db->prepare(
            "UPDATE products SET quantity = quantity - :qty WHERE id = :id AND quantity >= :qty"
        );
        return $stmt->execute([':qty' => $qty, ':id' => $id]);
    }
}