<?php

namespace App\Models;

enum Role: string {
    case Admin = 'admin';
    case Student = 'student';
    case Viewer = 'viewer';
}
