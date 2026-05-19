<?php

namespace App\Models;

enum PriorityLevel: string {
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
}
