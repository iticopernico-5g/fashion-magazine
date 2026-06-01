<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Layouts\MainLayout;
use App\Models\Category;
use App\Models\Status;
use App\Services\ArticleService;
use Camezilla\Pages\Page;

// Solo admin e student possono accedere
$role = get_session_item('authentication.role');
if (!$role || !in_array($role, ['admin', 'student'], true)) {
    header("Location: " . page('index.php'));
    exit();
}

$isAdmin = $role === 'admin';
$articleService = new ArticleService();
$articles = $articleService->get_all();

// Messaggi di feedback dal dispatcher
$successMsg = get_action_success(true);
$errorMsg   = get_action_error(true);

$page = new class($articles, $isAdmin, $successMsg, $errorMsg) extends Page {

    public function __construct(
        private array   $articles,
        private bool    $isAdmin,
        private ?string $successMsg,
        private ?string $errorMsg
    ) {
        parent::__construct(new MainLayout("Gestione Articoli"), function () {
            $this->renderContent();
        });
    }

    private function renderContent(): void { ?>
        <div class="gestione-articoli">

            <div class="gestione-header">
                <h1>Gestione Articoli</h1>
                <button class="btn-primary" onclick="document.getElementById('modal-nuovo').style.display='flex'">
                    + Nuovo Articolo
                </button>
            </div>

            <?php if ($this->successMsg): ?>
                <div class="alert alert-success"><?= htmlspecialchars($this->successMsg) ?></div>
            <?php endif; ?>
            <?php if ($this->errorMsg): ?>
                <div class="alert alert-error"><?= htmlspecialchars($this->errorMsg) ?></div>
            <?php endif; ?>

            <table class="articles-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titolo</th>
                        <th>Autore</th>
                        <th>Categoria</th>
                        <th>Data</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($this->articles)): ?>
                        <tr><td colspan="7" class="empty-row">Nessun articolo trovato.</td></tr>
                    <?php else: ?>
                        <?php foreach ($this->articles as $article): ?>
                            <tr>
                                <td><?= $article->get_id() ?></td>
                                <td><?= htmlspecialchars($article->get_title()) ?></td>
                                <td><?= htmlspecialchars($article->get_author()) ?></td>
                                <td><?= htmlspecialchars($article->get_category()->value) ?></td>
                                <td><?= $article->get_date()->format('d/m/Y') ?></td>
                                <td>
                                    <span class="status-badge status-<?= $article->get_status()->value ?>">
                                        <?= htmlspecialchars($article->get_status()->value) ?>
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <button class="btn-edit"
                                        onclick="openEditModal(
                                            <?= $article->get_id() ?>,
                                            <?= htmlspecialchars(json_encode($article->get_title()), ENT_QUOTES) ?>,
                                            <?= htmlspecialchars(json_encode($article->get_description()), ENT_QUOTES) ?>,
                                            <?= htmlspecialchars(json_encode($article->get_summary()), ENT_QUOTES) ?>,
                                            <?= htmlspecialchars(json_encode($article->get_category()->value), ENT_QUOTES) ?>,
                                            <?= htmlspecialchars(json_encode($article->get_link()), ENT_QUOTES) ?>,
                                            <?= htmlspecialchars(json_encode($article->get_text()), ENT_QUOTES) ?>,
                                            <?= htmlspecialchars(json_encode($article->get_author()), ENT_QUOTES) ?>,
                                            <?= htmlspecialchars(json_encode($article->get_date()->format('Y-m-d')), ENT_QUOTES) ?>,
                                            <?= htmlspecialchars(json_encode($article->get_status()->value), ENT_QUOTES) ?>
                                        )">
                                        Modifica
                                    </button>
                                    <?php if ($this->isAdmin): ?>
                                        <form method="POST" action="<?= action('article.php', 'delete', 'gestione_articoli.php') ?>"
                                              onsubmit="return confirm('Eliminare questo articolo?')">
                                            <input type="hidden" name="id" value="<?= $article->get_id() ?>">
                                            <button type="submit" class="btn-delete">Elimina</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal: Nuovo Articolo -->
        <div id="modal-nuovo" class="modal-overlay" style="display:none">
            <div class="modal-box">
                <h2>Nuovo Articolo</h2>
                <form method="POST" action="<?= action('article.php', 'create', 'gestione_articoli.php') ?>">
                    <?= $this->articleFields() ?>
                    <div class="modal-actions">
                        <button type="submit" class="btn-primary">Salva</button>
                        <button type="button" class="btn-secondary"
                            onclick="document.getElementById('modal-nuovo').style.display='none'">
                            Annulla
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Modifica Articolo -->
        <div id="modal-modifica" class="modal-overlay" style="display:none">
            <div class="modal-box">
                <h2>Modifica Articolo</h2>
                <form method="POST" action="<?= action('article.php', 'update', 'gestione_articoli.php') ?>">
                    <input type="hidden" name="id" id="edit-id">
                    <?= $this->articleFields('edit-') ?>
                    <div class="modal-actions">
                        <button type="submit" class="btn-primary">Aggiorna</button>
                        <button type="button" class="btn-secondary"
                            onclick="document.getElementById('modal-modifica').style.display='none'">
                            Annulla
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function openEditModal(id, title, description, summary, category, link, text, author, date, status) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-title').value = title ?? '';
            document.getElementById('edit-description').value = description ?? '';
            document.getElementById('edit-summary').value = summary ?? '';
            document.getElementById('edit-category').value = category ?? '';
            document.getElementById('edit-link').value = link ?? '';
            document.getElementById('edit-text').value = text ?? '';
            document.getElementById('edit-author').value = author ?? '';
            document.getElementById('edit-date').value = date ?? '';
            document.getElementById('edit-status').value = status ?? '';
            document.getElementById('modal-modifica').style.display = 'flex';
        }

        // Chiudi modal cliccando fuori
        document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) overlay.style.display = 'none';
            });
        });
        </script>

        <style>
        .gestione-articoli { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        .gestione-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .gestione-header h1 { font-size: 1.8rem; }

        .articles-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .articles-table th, .articles-table td { padding: .75rem 1rem; text-align: left; border-bottom: 1px solid #eee; font-size: .9rem; }
        .articles-table th { background: #f5f5f5; font-weight: 600; }
        .articles-table tr:last-child td { border-bottom: none; }
        .empty-row { text-align: center; color: #888; padding: 2rem; }

        .status-badge { display: inline-block; padding: .2rem .6rem; border-radius: 12px; font-size: .75rem; font-weight: 600; text-transform: uppercase; }
        .status-draft    { background: #fef3c7; color: #92400e; }
        .status-pending  { background: #dbeafe; color: #1e40af; }
        .status-approved { background: #d1fae5; color: #065f46; }

        .actions-cell { display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; }
        .actions-cell form { margin: 0; }

        .btn-primary  { background: #111; color: #fff; border: none; padding: .5rem 1.2rem; border-radius: 6px; cursor: pointer; font-size: .9rem; }
        .btn-primary:hover  { background: #333; }
        .btn-secondary { background: #e5e7eb; color: #111; border: none; padding: .5rem 1.2rem; border-radius: 6px; cursor: pointer; font-size: .9rem; }
        .btn-secondary:hover { background: #d1d5db; }
        .btn-edit   { background: #2563eb; color: #fff; border: none; padding: .35rem .9rem; border-radius: 6px; cursor: pointer; font-size: .85rem; }
        .btn-edit:hover   { background: #1d4ed8; }
        .btn-delete { background: #dc2626; color: #fff; border: none; padding: .35rem .9rem; border-radius: 6px; cursor: pointer; font-size: .85rem; }
        .btn-delete:hover { background: #b91c1c; }

        .alert { padding: .9rem 1.2rem; border-radius: 6px; margin-bottom: 1rem; font-size: .9rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error   { background: #fee2e2; color: #991b1b; }

        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); display: flex; align-items: center; justify-content: center; z-index: 1000; }
        .modal-box { background: #fff; border-radius: 10px; padding: 2rem; width: 100%; max-width: 560px; max-height: 90vh; overflow-y: auto; box-shadow: 0 8px 32px rgba(0,0,0,.2); }
        .modal-box h2 { margin: 0 0 1.5rem; font-size: 1.3rem; }
        .modal-actions { display: flex; gap: .75rem; justify-content: flex-end; margin-top: 1.5rem; }

        .form-group { display: flex; flex-direction: column; gap: .3rem; margin-bottom: 1rem; }
        .form-group label { font-size: .85rem; font-weight: 600; color: #374151; }
        .form-group input, .form-group select, .form-group textarea {
            border: 1px solid #d1d5db; border-radius: 6px; padding: .5rem .75rem; font-size: .9rem; width: 100%; box-sizing: border-box;
        }
        .form-group textarea { resize: vertical; min-height: 80px; }
        </style>
    <?php }

    private function articleFields(string $prefix = ''): string {
        $categories = Category::cases();
        $statuses   = Status::cases();

        ob_start(); ?>
        <div class="form-group">
            <label for="<?= $prefix ?>title">Titolo *</label>
            <input type="text" id="<?= $prefix ?>title" name="title" required maxlength="255">
        </div>
        <div class="form-group">
            <label for="<?= $prefix ?>author">Autore *</label>
            <input type="text" id="<?= $prefix ?>author" name="author" required maxlength="255">
        </div>
        <div class="form-group">
            <label for="<?= $prefix ?>date">Data *</label>
            <input type="date" id="<?= $prefix ?>date" name="date" required>
        </div>
        <div class="form-group">
            <label for="<?= $prefix ?>category">Categoria *</label>
            <select id="<?= $prefix ?>category" name="category" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->value ?>"><?= ucfirst(str_replace('_', ' ', $cat->value)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="<?= $prefix ?>status">Stato *</label>
            <select id="<?= $prefix ?>status" name="status" required>
                <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s->value ?>"><?= ucfirst($s->value) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="<?= $prefix ?>description">Descrizione</label>
            <textarea id="<?= $prefix ?>description" name="description" maxlength="500"></textarea>
        </div>
        <div class="form-group">
            <label for="<?= $prefix ?>summary">Sommario</label>
            <textarea id="<?= $prefix ?>summary" name="summary"></textarea>
        </div>
        <div class="form-group">
            <label for="<?= $prefix ?>text">Testo</label>
            <textarea id="<?= $prefix ?>text" name="text" style="min-height:120px"></textarea>
        </div>
        <div class="form-group">
            <label for="<?= $prefix ?>link">Link</label>
            <input type="url" id="<?= $prefix ?>link" name="link" maxlength="2048">
        </div>
        <?php return ob_get_clean();
    }
};

echo $page->render();
