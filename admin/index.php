<?php
declare(strict_types=1);

require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/_menu_images.php';
require_once __DIR__ . '/../includes/database.php';

requireAdmin();

$pdo = null;
$errors = [];
$successMessage = '';
$formData = [
    'id' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'is_active' => '1',
    'sort_order' => '0',
    'image_path' => '',
];

try {
    $pdo = getDatabaseConnection();
} catch (RuntimeException $exception) {
    $errors[] = $exception->getMessage();
}

if (isset($_GET['saved']) && $_GET['saved'] === '1') {
    $successMessage = 'Menu item saved.';
} elseif (isset($_GET['deleted']) && $_GET['deleted'] === '1') {
    $successMessage = 'Menu item deleted.';
}

if ($pdo instanceof PDO && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hasValidCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your form session expired. Reload the page and try again.';
    } else {
        $action = (string) ($_POST['action'] ?? '');
        $productId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($action === 'delete' && $productId !== false) {
            try {
                $pdo->beginTransaction();
                $imageStatement = $pdo->prepare('SELECT image_path FROM products WHERE id = :id');
                $imageStatement->execute(['id' => $productId]);
                $imagePath = $imageStatement->fetchColumn();

                if ($imagePath === false) {
                    $pdo->rollBack();
                    $errors[] = 'That menu item no longer exists.';
                } else {
                    $deleteStatement = $pdo->prepare('DELETE FROM products WHERE id = :id');
                    $deleteStatement->execute(['id' => $productId]);
                    $pdo->commit();
                    removeUploadedMenuImage((string) $imagePath);
                    header('Location: index.php?deleted=1');
                    exit;
                }
            } catch (PDOException $exception) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log('Unable to delete menu item: ' . $exception->getMessage());
                $errors[] = 'The menu item could not be deleted. Please try again.';
            }
        } elseif ($action === 'save') {
            $formData = [
                'id' => $productId === false ? '' : (string) $productId,
                'name' => trim((string) ($_POST['name'] ?? '')),
                'description' => trim((string) ($_POST['description'] ?? '')),
                'price' => trim((string) ($_POST['price'] ?? '')),
                'is_active' => (string) ($_POST['is_active'] ?? '1'),
                'sort_order' => trim((string) ($_POST['sort_order'] ?? '0')),
                'image_path' => '',
            ];

            if ($productId === false && !empty($_POST['id'])) {
                $errors[] = 'The menu item ID is invalid.';
            }
            if ($formData['name'] === '' || strlen($formData['name']) > 160) {
                $errors[] = 'Enter a name that is no longer than 160 characters.';
            }
            if (strlen($formData['description']) > 2000) {
                $errors[] = 'The description must be no longer than 2,000 characters.';
            }
            if (!is_numeric($formData['price']) || (float) $formData['price'] < 0 || (float) $formData['price'] > 999999.99) {
                $errors[] = 'Enter a price between 0 and 999,999.99.';
            }
            if (!in_array($formData['is_active'], ['0', '1'], true)) {
                $errors[] = 'Choose whether this menu item is visible.';
            }
            if (!ctype_digit($formData['sort_order']) || (int) $formData['sort_order'] > 1000000) {
                $errors[] = 'Display order must be a whole number between 0 and 1,000,000.';
            }

            $existingItem = null;
            if ($productId !== false) {
                try {
                    $existingStatement = $pdo->prepare('SELECT id, image_path FROM products WHERE id = :id');
                    $existingStatement->execute(['id' => $productId]);
                    $existingItem = $existingStatement->fetch();
                    if (!$existingItem) {
                        $errors[] = 'That menu item no longer exists.';
                    } else {
                        $formData['image_path'] = $existingItem['image_path'];
                    }
                } catch (PDOException $exception) {
                    error_log('Unable to load menu item before saving: ' . $exception->getMessage());
                    $errors[] = 'The menu item could not be checked before saving.';
                }
            } elseif (
                !isset($_FILES['image'])
                || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE
            ) {
                $errors[] = 'Choose a picture for the new menu item.';
            }

            $newImagePath = null;
            if (!$errors && isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                try {
                    $newImagePath = storeMenuImage($_FILES['image']);
                    $formData['image_path'] = $newImagePath;
                } catch (InvalidArgumentException $exception) {
                    $errors[] = $exception->getMessage();
                } catch (RuntimeException $exception) {
                    error_log('Unable to save menu image: ' . $exception->getMessage());
                    $errors[] = $exception->getMessage();
                }
            }

            if (!$errors) {
                try {
                    if ($productId === false) {
                        $statement = $pdo->prepare(
                            'INSERT INTO products (name, description, price, image_path, is_active, sort_order)
                             VALUES (:name, :description, :price, :image_path, :is_active, :sort_order)'
                        );
                    } else {
                        $statement = $pdo->prepare(
                            'UPDATE products
                             SET name = :name, description = :description, price = :price,
                                 image_path = :image_path, is_active = :is_active, sort_order = :sort_order
                             WHERE id = :id'
                        );
                    }

                    $parameters = [
                        'name' => $formData['name'],
                        'description' => $formData['description'],
                        'price' => number_format((float) $formData['price'], 2, '.', ''),
                        'image_path' => $formData['image_path'],
                        'is_active' => (int) $formData['is_active'],
                        'sort_order' => (int) $formData['sort_order'],
                    ];
                    if ($productId !== false) {
                        $parameters['id'] = $productId;
                    }
                    $statement->execute($parameters);

                    if ($newImagePath !== null && $existingItem) {
                        removeUploadedMenuImage((string) $existingItem['image_path']);
                    }
                    header('Location: index.php?saved=1');
                    exit;
                } catch (PDOException $exception) {
                    if ($newImagePath !== null) {
                        removeUploadedMenuImage($newImagePath);
                    }
                    error_log('Unable to save menu item: ' . $exception->getMessage());
                    $errors[] = 'The menu item could not be saved. Please try again.';
                }
            } elseif ($newImagePath !== null) {
                removeUploadedMenuImage($newImagePath);
            }
        } else {
            $errors[] = 'Choose a valid dashboard action.';
        }
    }
}

$items = [];
if ($pdo instanceof PDO) {
    try {
        $items = $pdo->query(
            'SELECT id, name, description, price, image_path, is_active, sort_order
             FROM products
             ORDER BY sort_order ASC, name ASC'
        )->fetchAll();
    } catch (PDOException $exception) {
        error_log('Unable to list menu items: ' . $exception->getMessage());
        $errors[] = 'The menu list could not be loaded.';
    }
}

if (isset($_GET['edit']) && !ctype_digit((string) $_GET['edit'])) {
    $errors[] = 'The selected menu item ID is invalid.';
} elseif ($pdo instanceof PDO && isset($_GET['edit'])) {
    try {
        $editStatement = $pdo->prepare(
            'SELECT id, name, description, price, image_path, is_active, sort_order
             FROM products WHERE id = :id'
        );
        $editStatement->execute(['id' => (int) $_GET['edit']]);
        $editItem = $editStatement->fetch();
        if ($editItem) {
            $formData = $editItem;
        } else {
            $errors[] = 'The selected menu item could not be found.';
        }
    } catch (PDOException $exception) {
        error_log('Unable to load menu item for editing: ' . $exception->getMessage());
        $errors[] = 'The selected menu item could not be loaded.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Dashboard - Pet-astic Petshop</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-page">
    <header class="admin-header">
        <div>
            <p class="eyebrow">Pet-astic PetShop</p>
            <h1>Menu dashboard</h1>
        </div>
        <div class="admin-header-actions">
            <a class="button button-light" href="../index.php">View website</a>
            <form method="post" action="logout.php">
                <input type="hidden" name="csrf_token" value="<?= h(adminCsrfToken()) ?>">
                <button class="button button-light" type="submit">Sign out</button>
            </form>
        </div>
    </header>

    <main class="admin-layout">
        <section class="admin-card">
            <p class="eyebrow"><?= $formData['id'] !== '' ? 'Update a product' : 'Add a product' ?></p>
            <h2><?= $formData['id'] !== '' ? 'Edit menu item' : 'New menu item' ?></h2>
            <?php if ($successMessage !== ''): ?>
                <p class="notice"><?= h($successMessage) ?></p>
            <?php endif; ?>
            <?php foreach ($errors as $error): ?>
                <p class="notice notice-error"><?= h($error) ?></p>
            <?php endforeach; ?>

            <form method="post" enctype="multipart/form-data" class="product-form">
                <input type="hidden" name="csrf_token" value="<?= h(adminCsrfToken()) ?>">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" value="<?= h($formData['id']) ?>">
                <label for="name">Product name</label>
                <input id="name" name="name" type="text" maxlength="160" value="<?= h($formData['name']) ?>" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" maxlength="2000"><?= h($formData['description']) ?></textarea>

                <div class="form-row">
                    <div>
                        <label for="price">Price ($)</label>
                        <input id="price" name="price" type="number" min="0" max="999999.99" step="0.01" value="<?= h($formData['price']) ?>" required>
                    </div>
                    <div>
                        <label for="sort_order">Display order</label>
                        <input id="sort_order" name="sort_order" type="number" min="0" max="1000000" step="1" value="<?= h($formData['sort_order']) ?>" required>
                    </div>
                </div>

                <label for="is_active">Visibility</label>
                <select id="is_active" name="is_active">
                    <option value="1" <?= (string) $formData['is_active'] === '1' ? 'selected' : '' ?>>Visible on the website</option>
                    <option value="0" <?= (string) $formData['is_active'] === '0' ? 'selected' : '' ?>>Hidden from the website</option>
                </select>

                <label for="image">Product picture <?= $formData['id'] !== '' ? '(optional; leave empty to keep current picture)' : '' ?></label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" <?= $formData['id'] === '' ? 'required' : '' ?>>
                <small>JPEG, PNG, or WebP; maximum file size is 5 MB.</small>
                <?php if ($formData['image_path'] !== ''): ?>
                    <img class="form-image-preview" src="../<?= h($formData['image_path']) ?>" alt="Current picture for <?= h($formData['name']) ?>">
                <?php endif; ?>

                <button class="button" type="submit"><?= $formData['id'] !== '' ? 'Save changes' : 'Add menu item' ?></button>
                <?php if ($formData['id'] !== ''): ?>
                    <a class="text-link" href="index.php">Cancel editing</a>
                <?php endif; ?>
            </form>
        </section>

        <section class="admin-card product-list-card">
            <p class="eyebrow">Your shop offerings</p>
            <h2>Product items (<?= count($items) ?>)</h2>
            <?php if (empty($items)): ?>
                <p>No product items yet. Use the form to add your product.</p>
            <?php else: ?>
                <div class="product-list">
                    <?php foreach ($items as $item): ?>
                        <article class="product-row">
                            <img src="../<?= h($item['image_path']) ?>" alt="">
                            <div class="product-row-details">
                                <h3><?= h($item['name']) ?></h3>
                                <p>$<?= number_format((float) $item['price'], 2) ?> · Order <?= (int) $item['sort_order'] ?></p>
                                <span class="status <?= (int) $item['is_active'] === 1 ? 'status-visible' : 'status-hidden' ?>">
                                    <?= (int) $item['is_active'] === 1 ? 'Visible' : 'Hidden' ?>
                                </span>
                            </div>
                            <div class="product-row-actions">
                                <a class="button button-light" href="index.php?edit=<?= (int) $item['id'] ?>">Edit</a>
                                <form method="post" onsubmit="return confirm('Delete this menu item? This cannot be undone.');">
                                    <input type="hidden" name="csrf_token" value="<?= h(adminCsrfToken()) ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                    <button class="button button-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
