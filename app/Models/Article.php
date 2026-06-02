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
    private ?string $image;
    private ?string $text;
    private ?string $author;
    private ?DateTime $date;
    private ?Status $status;
    

    public function __construct(?int $id, ?string $title, ?string $description, ?string $summary, ?Category $category, ?string $link, ?string $image, ?string $text, ?string $author, ?DateTime $date, ?Status $status) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->summary = $summary;
        $this->category = $category;
        $this->link = $link;
        $this->image = $image;
        $this->text = $text;
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

    public function get_image(): ?string {
        return $this->image;
    }

    public function get_text(): ?string {
        return $this->text;
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
