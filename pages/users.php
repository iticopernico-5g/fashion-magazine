<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Components\UserGroup;
use App\Layouts\MainLayout;
use App\Models\Category;
use App\Models\Status;
use App\Services\ArticleService;
use App\Services\UserService;
use Camezilla\Pages\Page;

// Solo admin e student possono accedere
if (!is_user_authenticated()) {
    redirect_to_login_page();
}

$role = get_session_item('authentication.role');
if (!in_array($role, ['admin', 'student'], true)) {
    header("Location: " . page('index.php'));
    exit();
}

$isAdmin = $role === 'admin';

$articleService = new ArticleService();
$articles = $articleService->get_all();

$users = [];
if ($isAdmin) {
    $userService = new UserService();
    $users = $userService->get_all();
}

$successMsg = get_action_success(true);
$errorMsg   = get_action_error(true);

$page = new class($isAdmin, $users, $articles, $successMsg, $errorMsg) extends Page {

    public function __construct(
        private bool    $isAdmin,
        private array   $users,
        private array   $articles,
        private ?string $successMsg,
        private ?string $errorMsg
    ) {
        parent::__construct(new MainLayout("Pannello di Gestione"), function () {
            $this->renderContent();
        });
    }

    private function renderContent(): void { ?>
        <div class="pannello">

            <?php if ($this->successMsg): ?>
                <div class="alert alert-success"><?= htmlspecialchars($this->successMsg) ?></div>
            <?php endif; ?>
            <?php if ($this->errorMsg): ?>
                <div class="alert alert-error"><?= htmlspecialchars($this->errorMsg) ?></div>
            <?php endif; ?>

            <!-- TAB BUTTONS -->
            <div class="pannello-tabs">
                <?php if ($this->isAdmin): ?>
                    <button class="pannello-tab-btn active" data-tab="utenti">Utenti</button>
                <?php endif; ?>
                <button class="pannello-tab-btn <?= $this->isAdmin ? '' : 'active' ?>" data-tab="articoli">Articoli</button>
            </div>

            <!-- TAB UTENTI (solo admin) -->
            <?php if ($this->isAdmin): ?>
            <div class="pannello-tab-content active" id="tab-utenti">
                <div class="tab-header">
                    <h2>Gestione Utenti</h2>
                </div>
                <?= new UserGroup($this->users) ?>
            </div>
            <?php endif; ?>

            <!-- TAB ARTICOLI -->
            <div class="pannello-tab-content <?= $this->isAdmin ? '' : 'active' ?>" id="tab-articoli">
                <div class="tab-header">
                    <h2>Gestione Articoli</h2>
                    <button class="btn-primary" onclick="document.getElementById('modal-nuovo').style.display='flex'">
                        + Nuovo Articolo
                    </button>
                </div>

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
                                        <button class="btn-edit" onclick="openEditModal(
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
                                        )">Modifica</button>
                                        <?php if ($this->isAdmin): ?>
                                            <form method="POST" action="<?= action('article.php', 'delete', 'users.php') ?>"
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

        </div>

        <!-- Modal: Nuovo Articolo -->
        <div id="modal-nuovo" class="art-modal-overlay" style="display:none">
            <div class="modal-box">
                <h2>Nuovo Articolo</h2>
                <form method="POST" action="<?= action('article.php', 'create', 'users.php') ?>">
                    <?= $this->articleFields() ?>
                    <div class="modal-actions">
                        <button type="submit" class="btn-primary">Salva</button>
                        <button type="button" class="btn-secondary"
                            onclick="document.getElementById('modal-nuovo').style.display='none'">Annulla</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Modifica Ruolo Utente -->
        <div id="modal-utente" class="art-modal-overlay" style="display:none">
            <div class="modal-box">
                <h2>Modifica Ruolo Utente</h2>
                <form method="POST" action="<?= action('account.php', 'update', 'users.php') ?>">
                    <input type="hidden" name="id" id="user-edit-id">
                    <input type="hidden" name="first_name" id="user-edit-first-name">
                    <input type="hidden" name="last_name" id="user-edit-last-name">
                    <input type="hidden" name="email" id="user-edit-email">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" id="user-edit-name-display" disabled style="background:#f5f5f5;color:#666">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" id="user-edit-email-display" disabled style="background:#f5f5f5;color:#666">
                    </div>
                    <div class="form-group">
                        <label>Ruolo *</label>
                        <select name="role" id="user-edit-role" required>
                            <option value="admin">Admin</option>
                            <option value="student">Student</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="submit" class="btn-primary">Salva</button>
                        <button type="button" class="btn-secondary"
                            onclick="document.getElementById('modal-utente').style.display='none'">Annulla</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Modifica Articolo -->
        <div id="modal-modifica" class="art-modal-overlay" style="display:none">
            <div class="modal-box">
                <h2>Modifica Articolo</h2>
                <form method="POST" action="<?= action('article.php', 'update', 'users.php') ?>">
                    <input type="hidden" name="id" id="edit-id">
                    <?= $this->articleFields('edit-') ?>
                    <div class="modal-actions">
                        <button type="submit" class="btn-primary">Aggiorna</button>
                        <button type="button" class="btn-secondary"
                            onclick="document.getElementById('modal-modifica').style.display='none'">Annulla</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        // Tab switcher
        document.querySelectorAll('.pannello-tab-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.pannello-tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.pannello-tab-content').forEach(c => c.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
            });
        });

        // Apri modal modifica utente
        function openUserModal(id, firstName, lastName, email, role) {
            document.getElementById('user-edit-id').value = id;
            document.getElementById('user-edit-first-name').value = firstName ?? '';
            document.getElementById('user-edit-last-name').value = lastName ?? '';
            document.getElementById('user-edit-email').value = email ?? '';
            document.getElementById('user-edit-name-display').value = (firstName ?? '') + ' ' + (lastName ?? '');
            document.getElementById('user-edit-email-display').value = email ?? '';
            document.getElementById('user-edit-role').value = role ?? 'viewer';
            document.getElementById('modal-utente').style.display = 'flex';
        }

        // Apri modal modifica articolo
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
        document.querySelectorAll('.art-modal-overlay').forEach(function(overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) overlay.style.display = 'none';
            });
        });
        </script>

        <style>
        .pannello { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }

        .pannello-tabs { display: flex; gap: 0; border-bottom: 2px solid #e0e0e0; margin-bottom: 1.5rem; }
        .pannello-tab-btn { padding: 12px 28px; background: none; border: none; border-bottom: 3px solid transparent; margin-bottom: -2px; color: #999; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .pannello-tab-btn:hover { color: #333; }
        .pannello-tab-btn.active { color: #111; border-bottom-color: #111; }

        .pannello-tab-content { display: none; }
        .pannello-tab-content.active { display: block; }

        .tab-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; }
        .tab-header h2 { font-size: 1.4rem; margin: 0; }

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

        .alert { padding: .9rem 1.2rem; border-radius: 6px; margin-bottom: 1rem; font-size: .9rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error   { background: #fee2e2; color: #991b1b; }

        .btn-primary  { background: #111; color: #fff; border: none; padding: .5rem 1.2rem; border-radius: 6px; cursor: pointer; font-size: .9rem; }
        .btn-primary:hover  { background: #333; }
        .btn-secondary { background: #e5e7eb; color: #111; border: none; padding: .5rem 1.2rem; border-radius: 6px; cursor: pointer; font-size: .9rem; }
        .btn-secondary:hover { background: #d1d5db; }
        .btn-edit   { background: #2563eb; color: #fff; border: none; padding: .35rem .9rem; border-radius: 6px; cursor: pointer; font-size: .85rem; }
        .btn-edit:hover   { background: #1d4ed8; }
        .btn-delete { background: #dc2626; color: #fff; border: none; padding: .35rem .9rem; border-radius: 6px; cursor: pointer; font-size: .85rem; }
        .btn-delete:hover { background: #b91c1c; }

        .art-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); display: flex; align-items: center; justify-content: center; z-index: 1000; }
        .modal-box { background: #fff; border-radius: 10px; padding: 2rem; width: 100%; max-width: 560px; max-height: 90vh; overflow-y: auto; box-shadow: 0 8px 32px rgba(0,0,0,.2); }
        .modal-box h2 { margin: 0 0 1.5rem; font-size: 1.3rem; }
        .modal-actions { display: flex; gap: .75rem; justify-content: flex-end; margin-top: 1.5rem; }

        .form-group { display: flex; flex-direction: column; gap: .3rem; margin-bottom: 1rem; }
        .form-group label { font-size: .85rem; font-weight: 600; color: #374151; }
        .form-group input, .form-group select, .form-group textarea { border: 1px solid #d1d5db; border-radius: 6px; padding: .5rem .75rem; font-size: .9rem; width: 100%; box-sizing: border-box; }
        .form-group textarea { resize: vertical; min-height: 80px; }
        </style>
    <?php }

    private function articleFields(string $prefix = ''): string {
        $categories = Category::cases();
        $statuses   = Status::cases();
        ob_start(); ?>
        <div class="form-group">
            <label>Titolo *</label>
            <input type="text" id="<?= $prefix ?>title" name="title" required maxlength="255">
        </div>
        <div class="form-group">
            <label>Autore *</label>
            <input type="text" id="<?= $prefix ?>author" name="author" required maxlength="255">
        </div>
        <div class="form-group">
            <label>Data *</label>
            <input type="date" id="<?= $prefix ?>date" name="date" required>
        </div>
        <div class="form-group">
            <label>Categoria *</label>
            <select id="<?= $prefix ?>category" name="category" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->value ?>"><?= ucfirst(str_replace('_', ' ', $cat->value)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Stato *</label>
            <select id="<?= $prefix ?>status" name="status" required>
                <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s->value ?>"><?= ucfirst($s->value) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Descrizione</label>
            <textarea id="<?= $prefix ?>description" name="description" maxlength="500"></textarea>
        </div>
        <div class="form-group">
            <label>Sommario</label>
            <textarea id="<?= $prefix ?>summary" name="summary"></textarea>
        </div>
        <div class="form-group">
            <label>Testo</label>
            <textarea id="<?= $prefix ?>text" name="text" style="min-height:120px"></textarea>
        </div>
        <div class="form-group">
            <label>Link</label>
            <input type="url" id="<?= $prefix ?>link" name="link" maxlength="2048">
        </div>
        <?php return ob_get_clean();
    }
};

echo $page->render();
