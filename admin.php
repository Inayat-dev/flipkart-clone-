<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Flipkart Admin</title>
    <!-- Bootstrap 5 + Icons + Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- Custom overrides for pro look -->
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        body {
            background: #f4f6f9;
            overflow-x: hidden;
        }
        /* Sidebar modern glass style */
        .admin-sidebar {
            background: linear-gradient(180deg, #0f212e 0%, #0a1820 100%);
            min-height: 100vh;
            box-shadow: 2px 0 12px rgba(0,0,0,0.05);
            transition: all 0.25s ease;
        }
        .sidebar-brand {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #FFB300, #FF6F00);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: none;
        }
        .brand-icon {
            background: #FF6F00;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: white;
            font-size: 1.2rem;
        }
        .nav-link-custom {
            color: #b0c4de !important;
            padding: 12px 20px !important;
            margin: 4px 8px;
            border-radius: 14px;
            font-weight: 500;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        .nav-link-custom i {
            width: 24px;
            font-size: 1.2rem;
            margin-right: 10px;
        }
        .nav-link-custom:hover {
            background: rgba(255,255,255,0.08);
            color: white !important;
            transform: translateX(4px);
        }
        .nav-link-custom.active {
            background: #1e88e5;
            color: white !important;
            box-shadow: 0 6px 12px rgba(30,136,229,0.2);
        }
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.08);
            margin-top: auto;
            padding: 20px 16px;
        }
        /* Main content */
        .main-content {
            background: #f4f6f9;
            min-height: 100vh;
        }
        .top-navbar {
            background: white;
            backdrop-filter: blur(0px);
            border-bottom: 1px solid #e9ecef;
            padding: 0.8rem 1.8rem;
        }
        .stat-card-modern {
            background: white;
            border-radius: 28px;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.03);
        }
        .stat-card-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 30px -12px rgba(0,0,0,0.1);
        }
        .card-pro {
            border: none;
            border-radius: 24px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }
        .card-header-clean {
            background: transparent;
            border-bottom: 1px solid #eff2f6;
            padding: 1.2rem 1.5rem;
            font-weight: 700;
            font-size: 1rem;
        }
        .table-custom th {
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5e6f8d;
            background: #fafcff;
            border-top: none;
        }
        .prod-thumb {
            width: 52px;
            height: 52px;
            object-fit: contain;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #edf2f7;
            padding: 4px;
        }
        .badge-discount {
            background: #eef2ff;
            color: #1e40af;
            font-weight: 600;
            border-radius: 40px;
            padding: 4px 12px;
            font-size: 0.7rem;
        }
        .btn-outline-modern {
            border-radius: 40px;
            padding: 6px 14px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        .btn-icon-sm {
            border-radius: 40px;
            padding: 6px 14px;
        }
        .search-wrapper {
            position: relative;
        }
        .search-wrapper i {
            position: absolute;
            left: 16px;
            top: 12px;
            color: #94a3b8;
        }
        .search-wrapper input {
            padding-left: 42px;
            border-radius: 48px;
            border: 1px solid #e2e8f0;
            background: white;
            font-size: 0.85rem;
        }
        .flash-message {
            border-radius: 60px;
            font-weight: 500;
            backdrop-filter: blur(4px);
        }
        .login-card-modern {
            background: rgba(255,255,255,0.96);
            backdrop-filter: blur(8px);
            border-radius: 40px;
            box-shadow: 0 25px 45px -12px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.5);
        }
        .btn-flipkart {
            background: #fb641b;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            padding: 10px 8px;
            transition: all 0.2s;
        }
        .btn-flipkart:hover {
            background: #f25a10;
            transform: scale(0.98);
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                position: fixed;
                z-index: 1050;
                width: 260px;
                transform: translateX(-100%);
                transition: transform 0.2s ease;
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .top-navbar {
                padding: 0.6rem 1rem;
            }
            .stat-card-modern .value {
                font-size: 1.6rem;
            }
            .table-custom td, .table-custom th {
                font-size: 0.75rem;
                padding: 10px 8px;
            }
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top:0;left:0;right:0;bottom:0;
            background: rgba(0,0,0,0.4);
            z-index: 1040;
        }
        .sidebar-overlay.show {
            display: block;
        }
        .img-preview {
            width: 64px;
            height: 64px;
            object-fit: contain;
            border-radius: 12px;
            border: 1px solid #dee2e6;
            background: #fefefe;
            margin-top: 8px;
        }
        .code-block {
            background: #f1f5f9;
            border-radius: 16px;
            padding: 12px;
            font-size: 0.7rem;
            font-family: monospace;
            word-break: break-all;
        }
        footer {
            font-size: 0.7rem;
        }
        .btn-sm-custom {
            padding: 5px 12px;
            font-size: 0.7rem;
            border-radius: 40px;
        }
    </style>
</head>
<body>

<?php
// --- DYNAMIC PHP LOGIC (SAME AS ORIGINAL BUT ENHANCED) ---
require_once __DIR__ . '/db.php';

$db = getDB();
$adminPwd = $db->query("SELECT value FROM settings WHERE key='admin_password'")->fetchColumn();
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_password'])) {
    if ($_POST['login_password'] === $adminPwd) {
        $_SESSION['admin_auth'] = true;
    } else {
        $loginError = 'Incorrect password.';
    }
}
if (isset($_POST['logout'])) {
    session_destroy();
    echo "<script>window.location='admin.php';</script>";
}
$authed = !empty($_SESSION['admin_auth']);

$flash = '';
if ($authed && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['_action'] ?? '';
    if ($act === 'add_product') {
        if (empty($_POST['name']) || empty($_POST['selling_price']) || empty($_POST['mrp']) || empty($_POST['img1'])) {
            $flash = 'error:Name, Selling Price, MRP, and Image 1 are required.';
        } else {
            $id = uniqid('prod_', true);
            $maxOrder = (int)$db->query("SELECT COALESCE(MAX(sort_order),0) FROM products")->fetchColumn();
            $stmt = $db->prepare("INSERT INTO products (id, name, color, storage, selling_price, mrp, img1, img2, img3, img4, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id, trim($_POST['name']), trim($_POST['color'] ?: 'N/A'), trim($_POST['storage'] ?: 'NA'), trim($_POST['selling_price']), trim($_POST['mrp']), trim($_POST['img1']), trim($_POST['img2'] ?? ''), trim($_POST['img3'] ?? ''), trim($_POST['img4'] ?? ''), $maxOrder + 1]);
            $flash = 'success:Product added successfully!';
        }
    } elseif ($act === 'update_product') {
        $id = $_POST['id'] ?? '';
        if ($id) {
            $stmt = $db->prepare("UPDATE products SET name=?, color=?, storage=?, selling_price=?, mrp=?, img1=?, img2=?, img3=?, img4=? WHERE id=?");
            $stmt->execute([trim($_POST['name']), trim($_POST['color'] ?: 'N/A'), trim($_POST['storage'] ?: 'NA'), trim($_POST['selling_price']), trim($_POST['mrp']), trim($_POST['img1']), trim($_POST['img2'] ?? ''), trim($_POST['img3'] ?? ''), trim($_POST['img4'] ?? ''), $id]);
            $flash = 'success:Product updated successfully!';
        }
    } elseif ($act === 'delete_product') {
        $id = $_POST['id'] ?? '';
        if ($id) { $db->prepare("UPDATE products SET active = 0 WHERE id = ?")->execute([$id]); $flash = 'success:Product deleted.'; }
    } elseif ($act === 'restore_product') {
        $id = $_POST['id'] ?? '';
        if ($id) { $db->prepare("UPDATE products SET active = 1 WHERE id = ?")->execute([$id]); $flash = 'success:Product restored.'; }
    } elseif ($act === 'update_upi') {
        $stmt = $db->prepare("INSERT OR REPLACE INTO settings (key, value) VALUES (?, ?)");
        if (!empty($_POST['upi_id'])) $stmt->execute(['upi_id', trim($_POST['upi_id'])]);
        if (!empty($_POST['merchant_name'])) $stmt->execute(['merchant_name', trim($_POST['merchant_name'])]);
        $flash = 'success:UPI settings updated!';
    } elseif ($act === 'update_payment_settings') {
        $stmt = $db->prepare("INSERT OR REPLACE INTO settings (key, value) VALUES (?, ?)");
        $stmt->execute(['razorpay_key_id',     trim($_POST['razorpay_key_id']     ?? '')]);
        $stmt->execute(['razorpay_key_secret', trim($_POST['razorpay_key_secret'] ?? '')]);
        $stmt->execute(['cod_advance_type',    trim($_POST['cod_advance_type']    ?? 'percent')]);
        $stmt->execute(['cod_advance_value',   trim($_POST['cod_advance_value']   ?? '0')]);
        $flash = 'success:Payment settings saved!';
    } elseif ($act === 'change_password') {
        $cur = trim($_POST['current_password'] ?? '');
        $new = trim($_POST['new_password'] ?? '');
        $con = trim($_POST['confirm_password'] ?? '');
        if ($cur !== $adminPwd) { $flash = 'error:Current password is wrong.'; }
        elseif (strlen($new) < 4) { $flash = 'error:New password must be at least 4 characters.'; }
        elseif ($new !== $con) { $flash = 'error:New passwords do not match.'; }
        else { $db->prepare("UPDATE settings SET value = ? WHERE key = 'admin_password'")->execute([$new]); $adminPwd = $new; $flash = 'success:Password changed!'; }
    }
}

$activeProducts = $authed ? $db->query("SELECT * FROM products WHERE active=1 ORDER BY sort_order")->fetchAll() : [];
$deletedProducts = $authed ? $db->query("SELECT * FROM products WHERE active=0 ORDER BY created_at DESC")->fetchAll() : [];
$settings = [];
if ($authed) { foreach ($db->query("SELECT key, value FROM settings")->fetchAll() as $r) { $settings[$r['key']] = $r['value']; } }

$editProduct = null;
if ($authed && isset($_GET['edit'])) {
    $s = $db->prepare("SELECT * FROM products WHERE id = ?");
    $s->execute([$_GET['edit']]);
    $editProduct = $s->fetch();
}

$tab = $_GET['tab'] ?? 'dashboard';
function esc(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function flashParts(string $f): array { return explode(':', $f, 2); }
?>

<?php if (!$authed): ?>
<!-- LOGIN SECTION (Bootstrap Modern) -->
<div class="container d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="login-card-modern p-4 p-md-5 shadow">
                <div class="text-center mb-4">
                    <span class="brand-icon d-inline-flex mx-auto mb-3"><i class="bi bi-bag-check fs-3"></i></span>
                    <h3 class="fw-bold" style="color: #0f212e;">Flipkart<span class="text-warning"> Admin</span></h3>
                    <p class="text-muted small">Sign in to manage your store</p>
                </div>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Admin Password</label>
                        <input type="password" name="login_password" class="form-control rounded-pill p-2 px-3" placeholder="Enter password" autofocus required>
                    </div>
                    <button type="submit" class="btn btn-flipkart w-100 text-white py-2 rounded-pill fw-semibold">Sign In <i class="bi bi-arrow-right-short"></i></button>
                    <?php if (!empty($loginError)): ?>
                        <div class="alert alert-danger mt-3 py-2 small rounded-pill text-center"><?= esc($loginError) ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<!-- ADMIN PANEL BOOTSTRAP ADVANCED -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
<div class="d-flex flex-column flex-lg-row">
    <!-- SIDEBAR (responsive) -->
    <aside class="admin-sidebar" id="adminSidebar" style="width: 280px; flex-shrink: 0;">
        <div class="d-flex align-items-center gap-2 px-3 py-4 border-bottom border-white-10">
            <div class="brand-icon bg-warning text-dark"><i class="bi bi-cart3"></i></div>
            <div><span class="sidebar-brand fw-bold fs-4">Flipkart</span><span class="text-white-50 ms-1 small">Admin</span></div>
            <button class="btn btn-sm d-lg-none text-white ms-auto" onclick="toggleSidebar()"><i class="bi bi-x-lg"></i></button>
        </div>
        <nav class="flex-column mt-3 px-2">
            <a href="?tab=dashboard" class="nav-link-custom d-flex align-items-center <?= $tab==='dashboard'?'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="?tab=products" class="nav-link-custom d-flex align-items-center <?= $tab==='products'?'active':'' ?>"><i class="bi bi-grid-3x3-gap-fill"></i> Products</a>
            <a href="?tab=add" class="nav-link-custom d-flex align-items-center <?= $tab==='add'?'active':'' ?>"><i class="bi bi-plus-circle"></i> Add Product</a>
            <a href="?tab=upi" class="nav-link-custom d-flex align-items-center <?= $tab==='upi'?'active':'' ?>"><i class="bi bi-credit-card"></i> UPI Settings</a>
            <a href="?tab=payment" class="nav-link-custom d-flex align-items-center <?= $tab==='payment'?'active':'' ?>"><i class="bi bi-lightning-charge"></i> Payment Settings</a>
            <a href="?tab=password" class="nav-link-custom d-flex align-items-center <?= $tab==='password'?'active':'' ?>"><i class="bi bi-lock"></i> Change Password</a>
            <a href="?tab=deleted" class="nav-link-custom d-flex align-items-center <?= $tab==='deleted'?'active':'' ?>"><i class="bi bi-trash3"></i> Deleted Products</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST">
                <input type="hidden" name="logout" value="1">
                <button type="submit" class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content flex-grow-1">
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn d-lg-none p-0 border-0 fs-4" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
                <h5 class="mb-0 fw-semibold text-dark">
                    <?php $tabNames = ['dashboard'=>'Dashboard','products'=>'Product Management','add'=>'Create Product','upi'=>'UPI Configuration','payment'=>'Payment Settings','password'=>'Security','deleted'=>'Archived Items']; echo esc($tabNames[$tab] ?? 'Admin'); ?>
                </h5>
            </div>
            <div>
                <a href="index.php" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-eye me-1"></i> View Store</a>
            </div>
        </div>

        <div class="container-fluid px-3 px-md-4 py-4">
            <?php if ($flash): list($ftype, $fmsg) = flashParts($flash); ?>
                <div class="alert alert-<?= $ftype === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show rounded-pill shadow-sm mb-4" role="alert">
                    <i class="bi bi-<?= $ftype === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?> me-2"></i> <?= esc($fmsg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- DASHBOARD SECTION -->
            <?php if ($tab === 'dashboard'): ?>
            <div class="row g-4 mb-5">
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card-modern p-3 p-xl-4"><div class="d-flex justify-content-between"><span class="text-secondary small fw-semibold">ACTIVE PRODUCTS</span><i class="bi bi-bag-check fs-3 text-primary opacity-50"></i></div><div class="value fs-1 fw-bold mt-2"><?= count($activeProducts) ?></div></div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card-modern p-3 p-xl-4"><div class="d-flex justify-content-between"><span class="text-secondary small fw-semibold">DELETED ITEMS</span><i class="bi bi-archive fs-3 text-danger opacity-50"></i></div><div class="value fs-1 fw-bold mt-2"><?= count($deletedProducts) ?></div></div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card-modern p-3 p-xl-4"><div class="d-flex justify-content-between"><span class="text-secondary small fw-semibold">UPI ID</span><i class="bi bi-upc-scan fs-3 text-success opacity-50"></i></div><div class="fw-semibold mt-2 text-truncate"><?= esc($settings['upi_id'] ?? 'Not set') ?></div></div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card-modern p-3 p-xl-4"><div class="d-flex justify-content-between"><span class="text-secondary small fw-semibold">MERCHANT</span><i class="bi bi-shop fs-3 text-warning opacity-50"></i></div><div class="fw-semibold mt-2"><?= esc($settings['merchant_name'] ?? '—') ?></div></div>
                </div>
            </div>
            <div class="card-pro card">
                <div class="card-header-clean d-flex justify-content-between flex-wrap"><span><i class="bi bi-lightning-charge-fill me-2 text-primary"></i> Quick Actions</span></div>
                <div class="card-body pt-0 pb-4"><div class="d-flex flex-wrap gap-3"><a href="?tab=add" class="btn btn-dark rounded-pill px-4"><i class="bi bi-plus-lg me-1"></i>Add Product</a><a href="?tab=upi" class="btn btn-outline-warning rounded-pill px-4"><i class="bi bi-credit-card"></i> Update UPI</a><a href="?tab=products" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-grid"></i> Manage Catalog</a></div></div>
            </div>
            <?php endif; ?>

            <!-- PRODUCTS LIST (RESPONSIVE TABLE) -->
            <?php if ($tab === 'products'): ?>
            <div class="card-pro card">
                <div class="card-header-clean d-flex justify-content-between align-items-center flex-wrap"><span><i class="bi bi-box-seam me-2"></i>All Products · <?= count($activeProducts) ?> items</span><a href="?tab=add" class="btn btn-sm btn-flipkart text-white rounded-pill"><i class="bi bi-plus"></i> New Product</a></div>
                <div class="card-body">
                    <div class="search-wrapper mb-3"><i class="bi bi-search"></i><input type="text" id="productSearch" class="form-control" placeholder="Search by name, color, ID..."></div>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle" id="productTable">
                            <thead><tr><th>Image</th><th>Product details</th><th>Color</th><th>Selling</th><th>MRP</th><th>Discount</th><th>Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($activeProducts as $p): $disc = $p['mrp']>0 ? round(($p['mrp']-$p['selling_price'])/$p['mrp']*100) : 0; ?>
                                <tr><td><img class="prod-thumb" src="<?= esc($p['img1']) ?>" onerror="this.src='https://placehold.co/52x52?text=img'"></td>
                                    <td><div class="fw-semibold text-dark"><?= esc(mb_substr($p['name'],0,55)) ?></div><small class="text-muted">ID: <?= esc($p['id']) ?></small></td>
                                    <td><?= esc($p['color']) ?></td>
                                    <td class="fw-bold text-success">₹<?= number_format($p['selling_price']) ?></td>
                                    <td class="text-decoration-line-through text-secondary">₹<?= number_format($p['mrp']) ?></td>
                                    <td><span class="badge-discount"><?= $disc ?>% off</span></td>
                                    <td><div class="d-flex gap-2"><a href="?tab=edit&id=<?= esc($p['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill"><i class="bi bi-pencil"></i></a><form method="POST" onsubmit="return confirm('Delete product?')"><input type="hidden" name="_action" value="delete_product"><input type="hidden" name="id" value="<?= esc($p['id']) ?>"><button class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-trash"></i></button></form></div></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- EDIT PRODUCT -->
            <?php if ($tab === 'edit' && $editProduct): ?>
            <div class="card-pro card"><div class="card-header-clean"><i class="bi bi-pencil-square me-2"></i>Edit Product · <?= esc($editProduct['name']) ?></div>
            <div class="card-body"><form method="POST"><input type="hidden" name="_action" value="update_product"><input type="hidden" name="id" value="<?= esc($editProduct['id']) ?>"><div class="row g-3"><div class="col-12"><label class="form-label fw-semibold">Product Name *</label><input class="form-control" name="name" value="<?= esc($editProduct['name']) ?>" required></div><div class="col-md-6"><label>Color</label><input class="form-control" name="color" value="<?= esc($editProduct['color']) ?>"></div><div class="col-md-6"><label>Storage</label><input class="form-control" name="storage" value="<?= esc($editProduct['storage']) ?>"></div><div class="col-md-6"><label>Selling Price *</label><input type="number" class="form-control" name="selling_price" value="<?= esc($editProduct['selling_price']) ?>" required></div><div class="col-md-6"><label>MRP *</label><input type="number" class="form-control" name="mrp" value="<?= esc($editProduct['mrp']) ?>" required></div><?php for($i=1;$i<=4;$i++): $key="img$i"; ?><div class="col-md-6"><label>Image <?= $i ?> URL <?= $i===1?'*':'' ?></label><input type="url" class="form-control" name="<?= $key ?>" value="<?= esc($editProduct[$key]) ?>" <?= $i===1?'required':'' ?> oninput="document.getElementById('prev_edit<?=$i?>').src=this.value; document.getElementById('prev_edit<?=$i?>').style.display='block'"><img id="prev_edit<?=$i?>" class="img-preview mt-2" src="<?= esc($editProduct[$key]) ?>" style="max-width:80px"></div><?php endfor; ?></div><div class="mt-4"><button type="submit" class="btn btn-flipkart text-white rounded-pill px-4"><i class="bi bi-save"></i> Save Changes</button><a href="?tab=products" class="btn btn-link ms-2">Cancel</a></div></form></div></div>
            <?php elseif ($tab === 'edit' && !$editProduct): ?><div class="alert alert-warning">Product not found.</div><?php endif; ?>

            <!-- ADD PRODUCT FORM -->
            <?php if ($tab === 'add'): ?>
            <div class="card-pro card"><div class="card-header-clean"><i class="bi bi-plus-circle me-2"></i>Create New Product</div><div class="card-body"><form method="POST"><input type="hidden" name="_action" value="add_product"><div class="row g-3"><div class="col-12"><label>Product Name *</label><input class="form-control" name="name" required></div><div class="col-md-6"><label>Color</label><input class="form-control" name="color"></div><div class="col-md-6"><label>Storage</label><input class="form-control" name="storage"></div><div class="col-md-6"><label>Selling Price *</label><input type="number" class="form-control" name="selling_price" required></div><div class="col-md-6"><label>MRP *</label><input type="number" class="form-control" name="mrp" required></div><?php for($i=1;$i<=4;$i++): ?><div class="col-md-6"><label>Image <?= $i ?> URL <?= $i===1?'*':'' ?></label><input type="url" class="form-control" name="img<?=$i?>" <?=$i===1?'required':''?> oninput="previewAdd<?=$i?>(this)"><img id="prevAdd<?=$i?>" class="img-preview mt-2" style="display:none"></div><?php endfor; ?></div><div class="mt-4"><button type="submit" class="btn btn-flipkart text-white rounded-pill px-5"><i class="bi bi-cloud-upload"></i> Publish Product</button></div></form></div></div>
            <script> <?php for($i=1;$i<=4;$i++): ?> function previewAdd<?=$i?>(input){ let img=document.getElementById('prevAdd<?=$i?>'); if(input.value){ img.src=input.value; img.style.display='block'; }else{ img.style.display='none'; } } <?php endfor; ?> </script>
            <?php endif; ?>

            <!-- UPI SETTINGS + PREVIEW -->
            <?php if ($tab === 'upi'): ?>
            <div class="row g-4"><div class="col-md-6"><div class="card-pro card"><div class="card-header-clean">Current UPI Info</div><div class="card-body"><i class="bi bi-phone"></i> <strong>UPI ID:</strong> <?= esc($settings['upi_id'] ?? '—') ?><br><i class="bi bi-person-badge"></i> <strong>Merchant:</strong> <?= esc($settings['merchant_name'] ?? '—') ?></div></div></div><div class="col-md-6"><div class="card-pro card"><div class="card-header-clean">Update UPI Settings</div><div class="card-body"><form method="POST"><input type="hidden" name="_action" value="update_upi"><div class="mb-3"><label>UPI ID</label><input class="form-control" name="upi_id" value="<?= esc($settings['upi_id'] ?? '') ?>" placeholder="example@okhdfcbank"></div><div class="mb-3"><label>Merchant Name</label><input class="form-control" name="merchant_name" value="<?= esc($settings['merchant_name'] ?? '') ?>" placeholder="Store Name"></div><button class="btn btn-flipkart text-white rounded-pill"><i class="bi bi-save"></i> Save UPI Config</button></form></div></div></div></div>
            <div class="card-pro card mt-4"><div class="card-header-clean">Payment Deep Link Preview</div><div class="card-body"><?php $uid=$settings['upi_id']??'your@upi'; $mname=urlencode($settings['merchant_name']??'Store'); $links=['GPay'=>"tez://upi/pay?pa=$uid&pn=$mname&tn=Order&am={amount}&cu=INR",'PhonePe'=>"phonepe://pay?pa=$uid&pn=$mname&tn=Order&am={amount}&cu=INR",'Paytm'=>"paytmmp://pay?pa=$uid&pn=$mname&tn=Order&am={amount}&cu=INR",'UPI'=>"upi://pay?pa=$uid&pn=$mname&tn=Order&am={amount}&cu=INR"]; foreach($links as $app=>$url){ echo "<div class='mb-3'><span class='badge bg-dark me-2'>$app</span><code class='code-block d-block'>".esc($url)."</code></div>"; } ?></div></div>
            <?php endif; ?>


            <!-- PAYMENT SETTINGS -->
            <?php if ($tab === 'payment'): ?>
            <div class="row g-4">
                <!-- COD Advance Settings -->
                <div class="col-12">
                    <div class="card-pro card">
                        <div class="card-header-clean"><i class="bi bi-truck me-2 text-warning"></i>Cash on Delivery — Advance Payment Settings</div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">When a customer selects COD, a popup asks them to pay an advance via UPI. Configure the advance amount below.</p>
                            <form method="POST">
                                <input type="hidden" name="_action" value="update_payment_settings">
                                <!-- Pass empty razorpay fields so handler doesn't break -->
                                <input type="hidden" name="razorpay_key_id" value="<?= esc($settings['razorpay_key_id'] ?? '') ?>">
                                <input type="hidden" name="razorpay_key_secret" value="<?= esc($settings['razorpay_key_secret'] ?? '') ?>">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Advance Type</label>
                                        <select class="form-select" name="cod_advance_type">
                                            <option value="percent" <?= ($settings['cod_advance_type'] ?? 'percent') === 'percent' ? 'selected' : '' ?>>Percentage (%) of order</option>
                                            <option value="fixed"   <?= ($settings['cod_advance_type'] ?? '') === 'fixed'   ? 'selected' : '' ?>>Fixed Amount (₹)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Advance Value</label>
                                        <input type="number" min="0" class="form-control" name="cod_advance_value"
                                               value="<?= esc($settings['cod_advance_value'] ?? '20') ?>"
                                               placeholder="e.g. 20 for 20% or ₹200">
                                        <div class="form-text">Enter 20 for 20% or enter ₹ amount if fixed.</div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="alert alert-success w-100 py-2 small mb-0">
                                            <strong>Current:</strong> <?php
                                            $advType = $settings['cod_advance_type'] ?? 'percent';
                                            $advVal  = $settings['cod_advance_value'] ?? '20';
                                            if ($advType === 'percent') echo "Customer pays {$advVal}% advance via UPI on COD.";
                                            else echo "Customer pays ₹{$advVal} advance via UPI on COD.";
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-flipkart text-white rounded-pill px-4"><i class="bi bi-save me-1"></i>Save COD Settings</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- UPI Deeplink Preview -->
                <div class="col-12">
                    <div class="card-pro card">
                        <div class="card-header-clean"><i class="bi bi-link-45deg me-2 text-primary"></i>UPI Deep Link Preview (sample amount: ₹499)</div>
                        <div class="card-body pb-4">
                            <?php
                            $uid   = $settings['upi_id']       ?? 'your@upi';
                            $mname = urlencode($settings['merchant_name'] ?? 'Store');
                            $tr    = 'TXN' . time();
                            $am    = '499.00';
                            $links = [
                                'GPay'    => "tez://upi/pay?pa={$uid}&pn={$mname}&tn=OrderPayment&tr={$tr}&mc=5945&am={$am}&cu=INR&mode=02",
                                'PhonePe' => "phonepe://pay?pa={$uid}&pn={$mname}&tn=OrderPayment&tr={$tr}&mc=5945&am={$am}&cu=INR&mode=02",
                                'Paytm'   => "paytmmp://pay?pa={$uid}&pn={$mname}&tn=OrderPayment&tr={$tr}&mc=5945&am={$am}&cu=INR&mode=02",
                                'UPI All' => "upi://pay?pa={$uid}&pn={$mname}&tn=OrderPayment&tr={$tr}&mc=5945&am={$am}&cu=INR&mode=19",
                            ];
                            foreach ($links as $app => $url) {
                                echo "<div class='mb-3'><span class='badge bg-dark me-2'>{$app}</span><code class='code-block d-block'>" . esc($url) . "</code></div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- PASSWORD -->
            <?php if ($tab === 'password'): ?>
            <div class="row justify-content-center"><div class="col-lg-5"><div class="card-pro card"><div class="card-header-clean">Change Admin Password</div><div class="card-body"><form method="POST"><input type="hidden" name="_action" value="change_password"><div class="mb-3"><label>Current Password</label><input type="password" class="form-control" name="current_password" required></div><div class="mb-3"><label>New Password (min 4 chars)</label><input type="password" class="form-control" name="new_password" required minlength="4"></div><div class="mb-3"><label>Confirm New Password</label><input type="password" class="form-control" name="confirm_password" required></div><button class="btn btn-flipkart text-white rounded-pill w-100"><i class="bi bi-shield-lock"></i> Update Password</button></form></div></div></div></div>
            <?php endif; ?>

            <!-- DELETED PRODUCTS -->
            <?php if ($tab === 'deleted'): ?>
            <div class="card-pro card"><div class="card-header-clean"><i class="bi bi-trash me-2"></i>Archived Products</div><div class="card-body"><?php if(empty($deletedProducts)): ?><div class="alert alert-info">No deleted products found.</div><?php else: ?><div class="table-responsive"><table class="table table-custom"><thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Action</th></tr></thead><tbody><?php foreach($deletedProducts as $p): ?><tr><td><img class="prod-thumb" src="<?= esc($p['img1']) ?>"></td><td><?= esc(mb_substr($p['name'],0,50)) ?></td><td>₹<?= number_format($p['selling_price']) ?></td><td><form method="POST"><input type="hidden" name="_action" value="restore_product"><input type="hidden" name="id" value="<?= esc($p['id']) ?>"><button class="btn btn-sm btn-success rounded-pill"><i class="bi bi-arrow-repeat"></i> Restore</button></form></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div></div>
            <?php endif; ?>
        </div>
        <footer class="text-center text-muted py-3 small border-top mt-4">Flipkart Admin · Secure Panel v2.0</footer>
    </main>
</div>

<script>
function toggleSidebar() { 
    document.getElementById('adminSidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
function filterTable(q) { 
    let val = q.toLowerCase();
    document.querySelectorAll('#productTable tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
}
document.getElementById('productSearch')?.addEventListener('input', e => filterTable(e.target.value));
<?php if($tab==='products'): ?> window.filterTable = filterTable; <?php endif; ?>
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<?php endif; ?>
</body>
</html>
