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
                    $row['text'],
                    PriorityLevel::from($row['priority_level']),
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
                    $row['text'],
                    PriorityLevel::from($row['priority_level']),
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

    public function create(Article $article): void {
        try {
            $query = $this->database->prepare(
                "INSERT INTO articles (title, description, summary, category, link, text, priority_level, status, author, date) 
                VALUES (:title, :description, :summary, :category, :link, :text, :priority_level, :status, :author, :date)"
            );
            $query->execute([
                'title' => $article->get_title(),
                'description' => $article->get_description(),
                'summary' => $article->get_summary(),
                'category' => $article->get_category()->value,
                'link' => $article->get_link(),
                'text' => $article->get_text(),
                'priority_level' => $article->get_priority_level()->value,
                'author' => $article->get_author(),
                'date' => $article->get_date()->format('Y-m-d H:i:s'),
                'status' => $article->get_status()->value
            ]);
        } catch (Exception $e) {
            throw new RepositoryErrorException("Error creating article: " . $e->getMessage());
        }
    }

    public function update(Article $article): void {
        try {
            $query = $this->database->prepare(
                "UPDATE articles
                SET title = :title, description = :description, summary = :summary, category = :category, link = :link, text = :text, priority_level = :priority_level, author = :author, date = :date, status = :status
                WHERE id = :id");
            $query->execute([
                'id' => $article->get_id(),
                'title' => $article->get_title(),
                'description' => $article->get_description(),
                'summary' => $article->get_summary(),
                'category' => $article->get_category()->value,
                'link' => $article->get_link(),
                'text' => $article->get_text(),
                'priority_level' => $article->get_priority_level()->value,
                'status' => $article->get_status()->value,
                'author' => $article->get_author(),
                'date' => $article->get_date()->format('Y-m-d H:i:s')
            ]);
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