<?php

namespace App\Models; 

enum Category : string {
    case Sport = 'sport';
    case Fashion = 'fashion';
    case SocialActivity = 'social_activity';
    case Charity = 'charity';
    case Challenges = 'challenges';
    case StyleRecommendations = 'style_recommendations';
    case Events = 'events';
    case Other = 'other';
}
