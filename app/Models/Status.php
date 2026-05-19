<?php

namespace App\Models;

enum Status: string {
    case Draft = 'draft';
    case Pending = 'pending';
    case Approved = 'approved';
}