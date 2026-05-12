<?php

namespace App\Models;

enum Status: string {
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case APPROVED = 'approved';
}