<?php

namespace App\Models;

enum Role: string {
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case VIEWER = 'viewer';
}
