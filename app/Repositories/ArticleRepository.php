<?php

namespace App\Repositories;

use Camezilla\Repositories\Repository;
use App\Models\Article;
use App\Models\Category;
use App\Models\PriorityLevel;
use App\Models\Status;
use Camezilla\Exceptions\RepositoryErrorException;
use Exception;
use DateTime;

class ArticleRepository extends Repository{

    public function __construct() {
        parent::__construct();
    }

    public function get_all(): array {
        try {
            $query = $this->database->query(
                "SELECT * FROM articles"
            );

            $articles = [];
            while (($row = $query->fetch()) !== false) {
                $articles[] = new Article(
                    $row['id'],
                    $row['title'],
                    $row['description'],
                    $row['summary'],    
                    Category::from($row['category']),
                    $row['link'],
                    $row['image'] ?? null,
                    $row['text'],
                    $row['author'],
                    new DateTime($row['date']),
                    Status::from($row['status'])
                );
            }

            return $articles;
        } catch (Exception $e) {
            throw new RepositoryErrorException("Error fetching articles: " . $e->getMessage());
        }
    }
    
    public function get_by_id(int $id): ?Article {
        try {
            $query = $this->database->prepare(
                "SELECT * FROM articles WHERE id = :id"
            );
            $query->execute([
                'id' => $id
            ]);
            $row = $query->fetch();

            if ($row !== false) {
                return new Article(
                    $row['id'],
                    $row['title'],
                    $row['description'],
                    $row['summary'],
                    Category::from($row['category']),
                    $row['link'],
                    $row['image'] ?? null,
                    $row['text'],
                    $row['author'],
                    new DateTime($row['date']),
                    Status::from($row['status'])
                );
            } 
    
            return null;    
        } catch (Exception $e) {
            throw new RepositoryErrorException("Error fetching article by ID: " . $e->getMessage());
        }
    }

    /**
     * Ritorna SOLO articoli approvati, ordinati per data desc.
     */
    public function get_recent(int $limit = 5): array {
        try {
            $query = $this->database->prepare(
                "SELECT * FROM articles 
                 WHERE status = :status
                 ORDER BY date DESC 
                 LIMIT :limit"
            );

            // Nota: LIMIT con parametro può richiedere bindValue come INT (dipende dal driver PDO)
            $query->bindValue(':status', Status::Approved->value);
            $query->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
            $query->execute();

            $articles = [];
            while (($row = $query->fetch()) !== false) {
                $articles[] = new Article(
                    $row['id'],
                    $row['title'],
                    $row['description'],
                    $row['summary'],
                    Category::from($row['category']),
                    $row['link'],
                    $row['image'] ?? null,
                    $row['text'],
                    $row['author'],
                    new DateTime($row['date']),
                    Status::from($row['status'])
                );
            }

            return $articles;
        } catch (Exception $e) {
            throw new RepositoryErrorException("Error fetching recent articles: " . $e->getMessage());
        }
    }

    /**
     * Ritorna SOLO articoli approvati di una categoria, ordinati per data desc.
     */
    public function get_by_category(Category $category): array {
        try {
            $query = $this->database->prepare(
                "SELECT * FROM articles 
                 WHERE category = :category
                   AND status = :status
                 ORDER BY date DESC"
            );
            $query->execute([
                'category' => $category->value,
                'status' => Status::Approved->value
            ]);

            $articles = [];
            while (($row = $query->fetch()) !== false) {
                $articles[] = new Article(
                    $row['id'],
                    $row['title'],
                    $row['description'],
                    $row['summary'],
                    Category::from($row['category']),
                    $row['link'],
                    $row['image'] ?? null,
                    $row['text'],
                    $row['author'],
                    new DateTime($row['date']),
                    Status::from($row['status'])
                );
            }

            return $articles;
        } catch (Exception $e) {
            throw new RepositoryErrorException("Error fetching articles by category: " . $e->getMessage());
        }
    }

    public function get_by_status(Status $status): array {
        try {
            $query = $this->database->prepare(
                "SELECT * FROM articles WHERE status = :status"
            );
            $query->execute([
                'status' => $status->value
            ]);

            $articles = [];
            while (($row = $query->fetch()) !== false) {
                $articles[] = new Article(
                    $row['id'],
                    $row['title'],
                    $row['description'],
                    $row['summary'],
                    Category::from($row['category']),
                    $row['link'],
                    $row['image'] ?? null,
                    $row['text'],
                    $row['author'],
                    new DateTime($row['date']),
                    Status::from($row['status'])
                );
            }

            return $articles;
        } catch (Exception $e) {
            throw new RepositoryErrorException("Error fetching articles by status: " . $e->getMessage());
        }
    }

    public function create(Article $article): void {
        try {
            $query = $this->database->prepare(
                "INSERT INTO articles (title, description, summary, category, link, image, text, status, author, date)
                VALUES (:title, :description, :summary, :category, :link, :image, :text, :status, :author, :date)"
            );
            $query->bindValue(':title',       $article->get_title());
            $query->bindValue(':description', $article->get_description());
            $query->bindValue(':summary',     $article->get_summary());
            $query->bindValue(':category',    $article->get_category()->value);
            $query->bindValue(':link',        $article->get_link());
            $query->bindValue(':image',       $article->get_image(), $article->get_image() !== null ? \PDO::PARAM_LOB : \PDO::PARAM_NULL);
            $query->bindValue(':text',        $article->get_text());
            $query->bindValue(':status',      $article->get_status()->value);
            $query->bindValue(':author',      $article->get_author());
            $query->bindValue(':date',        $article->get_date()->format('Y-m-d'));
            $query->execute();
        } catch (Exception $e) {
            throw new RepositoryErrorException("Error creating article: " . $e->getMessage());
        }
    }

    public function update(Article $article): void {
        try {
            $hasImage = $article->get_image() !== null;
            $sql = $hasImage
                ? "UPDATE articles SET title=:title, description=:description, summary=:summary, category=:category, link=:link, image=:image, text=:text, status=:status, author=:author, date=:date WHERE id=:id"
                : "UPDATE articles SET title=:title, description=:description, summary=:summary, category=:category, link=:link, text=:text, status=:status, author=:author, date=:date WHERE id=:id";
            $query = $this->database->prepare($sql);
            $query->bindValue(':id',          $article->get_id(),                  \PDO::PARAM_INT);
            $query->bindValue(':title',       $article->get_title());
            $query->bindValue(':description', $article->get_description());
            $query->bindValue(':summary',     $article->get_summary());
            $query->bindValue(':category',    $article->get_category()->value);
            $query->bindValue(':link',        $article->get_link());
            $query->bindValue(':text',        $article->get_text());
            $query->bindValue(':status',      $article->get_status()->value);
            $query->bindValue(':author',      $article->get_author());
            $query->bindValue(':date',        $article->get_date()->format('Y-m-d'));
            if ($hasImage) {
                $query->bindValue(':image', $article->get_image(), \PDO::PARAM_LOB);
            }
            $query->execute();
        } catch (Exception $e) {
            throw new RepositoryErrorException("Error updating article: " . $e->getMessage());
        }
    }

    public function delete_by_id(int $id): void {
        try {
            $query = $this->database->prepare(
                "DELETE FROM articles 
                WHERE id = :id"
            );
            $query->execute([
                'id' => $id
            ]);

            log_debug($id);
        } catch (Exception $e) {
            throw new RepositoryErrorException("Error deleting article: " . $e->getMessage());
        }
    }
}