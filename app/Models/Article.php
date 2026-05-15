<?php

namespace App\Models;

use DateTime;

class Article {
    private ?int $id;
    private ?string $title;
    private ?string $description;
    private ?string $summary;
    private ?Category $category;
    private ?string $link;
    private ?string $text;
    private ?PriorityLevel $priority_level;
    private ?string $author;
    private ?DateTime $date;
    private ?Status $status;
    

    public function __construct(?int $id, ?string $title, ?string $description, ?string $summary, ?Category $category, ?string $link, ?string $text, ?PriorityLevel $priority_level, ?string $author, ?DateTime $date, ?Status $status) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->summary = $summary; 
        $this->category = $category;
        $this->link = $link;
        $this->text = $text;
        $this->priority_level = $priority_level;
        $this->author = $author;
        $this->date = $date;
        $this->status = $status;
    }

    public function get_id(): ?int {
        return $this->id;
    }

    public function get_title(): ?string {
        return $this->title;
    
    }

    public function get_description(): ?string {
        return $this->description;
    }

    public function get_summary(): ?string {
        return $this->summary;
    }

    public function get_category(): ?Category {
        return $this->category;
    }

    public function get_link(): ?string {
        return $this->link;
    }

    public function get_text(): ?string {
        return $this->text;
    }

    public function get_priority_level(): ?PriorityLevel {
        return $this->priority_level;
    }   

    public function get_author(): ?string {
        return $this->author;
    }

    public function get_date(): ?DateTime {
        return $this->date;
    }   

    public function get_status(): ?Status {
        return $this->status;
    }   

}
