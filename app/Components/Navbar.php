<?php

namespace App\Components;

use Camezilla\Components\Component;

class Navbar extends Component
{

    protected function build(): void 
    { ?>
        <nav class="filter-menu">
            <div class="header-container menu-wrapper">
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <ul id="nav-links">
                    <li><a href="index.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="categories.php"><i class="fas fa-th-list"></i> Categories</a></li>
                    <li><a href="articles.php"><i class="fas fa-book-open"></i> All Articles</a></li>
                    <li><a href="events.php"><i class="fas fa-calendar-alt"></i> Events</a></li>
                </ul>
            </div>
        </nav>
<?php }
}
