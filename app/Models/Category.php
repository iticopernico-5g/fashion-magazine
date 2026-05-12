<?php

namespace App\Models; 

enum Category : string {
    case Food = 'food';
    case Electronics = 'electronics';
    case Clothing = 'clothing';
    case Books = 'books';
    case Home = 'home';
}
