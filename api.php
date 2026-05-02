<?php
// api.php — JSON REST API for the Flipkart clone
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$action = $_GET['action'] ?? '';

try {
    $db = getDB();

    // ── GET /api.php?action=products ─────────────────────────────────────────
    if ($action === 'products' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $rows = $db->query(
            "SELECT * FROM products WHERE active = 1 ORDER BY sort_order ASC, created_at ASC"
        )->fetchAll();
        echo json_encode(['ok' => true, 'products' => $rows]);
        exit;
    }

    // ── GET /api.php?action=settings ─────────────────────────────────────────
    if ($action === 'settings' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $rows = $db->query("SELECT key, value FROM settings")->fetchAll();
        $out = [];
        foreach ($rows as $r) {
            $out[$r['key']] = $r['value'];
        }
        unset($out['admin_password']); // never expose password
        echo json_encode(['ok' => true, 'settings' => $out]);
        exit;
    }

    // All write actions need admin auth via POST body
    $body = [];
    if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
        $raw = file_get_contents('php://input');
        $body = json_decode($raw, true) ?? [];
        // fall back to $_POST for form submissions
        if (empty($body))
            $body = $_POST;
    }

    // Helper: verify admin password
    $adminPwd = $db->query("SELECT value FROM settings WHERE key='admin_password'")->fetchColumn();
    $authOk = isset($body['password']) && $body['password'] === $adminPwd;

    // ── POST /api.php?action=add_product ─────────────────────────────────────
    if ($action === 'add_product' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$authOk) {
            jsonError('Unauthorized', 401);
        }
        $required = ['name', 'selling_price', 'mrp', 'img1'];
        foreach ($required as $f) {
            if (empty($body[$f]))
                jsonError("Field '$f' is required");
        }
        $id = uniqid('prod_', true);
        $maxOrder = (int)$db->query("SELECT COALESCE(MAX(sort_order),0) FROM products")->fetchColumn();
        $stmt = $db->prepare("INSERT INTO products
            (id, name, color, storage, selling_price, mrp, img1, img2, img3, img4, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $id,
            trim($body['name']),
            trim($body['color'] ?? 'N/A'),
            trim($body['storage'] ?? 'NA'),
            trim($body['selling_price']),
            trim($body['mrp']),
            trim($body['img1']),
            trim($body['img2'] ?? ''),
            trim($body['img3'] ?? ''),
            trim($body['img4'] ?? ''),
            $maxOrder + 1,
        ]);
        echo json_encode(['ok' => true, 'id' => $id, 'message' => 'Product added']);
        exit;
    }

    // ── POST /api.php?action=update_product ──────────────────────────────────
    if ($action === 'update_product' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$authOk) {
            jsonError('Unauthorized', 401);
        }
        if (empty($body['id']))
            jsonError("Field 'id' is required");
        $stmt = $db->prepare("UPDATE products SET
            name          = COALESCE(?, name),
            color         = COALESCE(?, color),
            storage       = COALESCE(?, storage),
            selling_price = COALESCE(?, selling_price),
            mrp           = COALESCE(?, mrp),
            img1          = COALESCE(?, img1),
            img2          = COALESCE(?, img2),
            img3          = COALESCE(?, img3),
            img4          = COALESCE(?, img4)
            WHERE id = ?");
        $stmt->execute([
            isset($body['name']) ? trim($body['name']) : null,
            isset($body['color']) ? trim($body['color']) : null,
            isset($body['storage']) ? trim($body['storage']) : null,
            isset($body['selling_price']) ? trim($body['selling_price']) : null,
            isset($body['mrp']) ? trim($body['mrp']) : null,
            isset($body['img1']) ? trim($body['img1']) : null,
            isset($body['img2']) ? trim($body['img2']) : null,
            isset($body['img3']) ? trim($body['img3']) : null,
            isset($body['img4']) ? trim($body['img4']) : null,
            $body['id'],
        ]);
        echo json_encode(['ok' => true, 'message' => 'Product updated']);
        exit;
    }

    // ── POST /api.php?action=delete_product ──────────────────────────────────
    if ($action === 'delete_product' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$authOk) {
            jsonError('Unauthorized', 401);
        }
        if (empty($body['id']))
            jsonError("Field 'id' is required");
        // Soft delete
        $stmt = $db->prepare("UPDATE products SET active = 0 WHERE id = ?");
        $stmt->execute([$body['id']]);
        echo json_encode(['ok' => true, 'message' => 'Product deleted']);
        exit;
    }

    // ── POST /api.php?action=update_upi ──────────────────────────────────────
    if ($action === 'update_upi' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$authOk) {
            jsonError('Unauthorized', 401);
        }
        $stmt = $db->prepare("INSERT OR REPLACE INTO settings (key, value) VALUES (?, ?)");
        if (!empty($body['upi_id'])) {
            $stmt->execute(['upi_id', trim($body['upi_id'])]);
        }
        if (!empty($body['merchant_name'])) {
            $stmt->execute(['merchant_name', trim($body['merchant_name'])]);
        }
        echo json_encode(['ok' => true, 'message' => 'UPI settings updated']);
        exit;
    }

    // ── POST /api.php?action=update_payment_settings ─────────────────────────
    if ($action === 'update_payment_settings' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$authOk) {
            jsonError('Unauthorized', 401);
        }
        $stmt = $db->prepare("INSERT OR REPLACE INTO settings (key, value) VALUES (?, ?)");
        if (isset($body['razorpay_key_id']))
            $stmt->execute(['razorpay_key_id', trim($body['razorpay_key_id'])]);
        if (isset($body['razorpay_key_secret']))
            $stmt->execute(['razorpay_key_secret', trim($body['razorpay_key_secret'])]);
        if (isset($body['cod_advance_type']))
            $stmt->execute(['cod_advance_type', trim($body['cod_advance_type'])]);
        if (isset($body['cod_advance_value']))
            $stmt->execute(['cod_advance_value', trim($body['cod_advance_value'])]);
        echo json_encode(['ok' => true, 'message' => 'Payment settings updated']);
        exit;
    }

    // ── POST /api.php?action=razorpay_create_order ───────────────────────────
    if ($action === 'razorpay_create_order' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $keyId = $db->query("SELECT value FROM settings WHERE key='razorpay_key_id'")->fetchColumn();
        $keySecret = $db->query("SELECT value FROM settings WHERE key='razorpay_key_secret'")->fetchColumn();
        if (empty($keyId) || empty($keySecret)) {
            jsonError('Razorpay not configured. Please set keys in admin panel.');
        }
        $amount = intval($body['amount'] ?? 0); // amount in paise
        $currency = $body['currency'] ?? 'INR';
        if ($amount <= 0)
            jsonError('Invalid amount');

        $receipt = 'order_' . uniqid();
        $payload = json_encode(['amount' => $amount, 'currency' => $currency, 'receipt' => $receipt]);

        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_USERPWD => "$keyId:$keySecret",
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);
        if ($httpCode !== 200 || empty($result['id'])) {
            jsonError('Razorpay order creation failed: ' . ($result['error']['description'] ?? 'Unknown error'));
        }
        echo json_encode(['ok' => true, 'order_id' => $result['id'], 'amount' => $result['amount'], 'currency' => $result['currency'], 'key_id' => $keyId]);
        exit;
    }

    // ── POST /api.php?action=razorpay_verify ─────────────────────────────────
    if ($action === 'razorpay_verify' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $keySecret = $db->query("SELECT value FROM settings WHERE key='razorpay_key_secret'")->fetchColumn();
        $orderId = $body['razorpay_order_id'] ?? '';
        $paymentId = $body['razorpay_payment_id'] ?? '';
        $signature = $body['razorpay_signature'] ?? '';
        $generated = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);
        if (hash_equals($generated, $signature)) {
            echo json_encode(['ok' => true, 'message' => 'Payment verified']);
        }
        else {
            jsonError('Payment verification failed', 400);
        }
        exit;
    }

    // ── GET /api.php?action=payment_config ───────────────────────────────────
    if ($action === 'payment_config' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $keyId = $db->query("SELECT value FROM settings WHERE key='razorpay_key_id'")->fetchColumn();
        $codAdvanceType = $db->query("SELECT value FROM settings WHERE key='cod_advance_type'")->fetchColumn();
        $codAdvanceValue = $db->query("SELECT value FROM settings WHERE key='cod_advance_value'")->fetchColumn();
        echo json_encode([
            'ok' => true,
            'razorpay_enabled' => !empty($keyId),
            'razorpay_key_id' => $keyId ?: '',
            'cod_advance_type' => $codAdvanceType ?: 'percent',
            'cod_advance_value' => $codAdvanceValue ?: '20',
        ]);
        exit;
    }

    // ── POST /api.php?action=change_password ─────────────────────────────────
    if ($action === 'change_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$authOk) {
            jsonError('Unauthorized', 401);
        }
        if (empty($body['new_password']) || strlen($body['new_password']) < 4) {
            jsonError('New password must be at least 4 characters');
        }
        $stmt = $db->prepare("UPDATE settings SET value = ? WHERE key = 'admin_password'");
        $stmt->execute([trim($body['new_password'])]);
        echo json_encode(['ok' => true, 'message' => 'Password changed']);
        exit;
    }

    jsonError('Unknown action: ' . htmlspecialchars($action), 404);

}
catch (Throwable $e) {
    jsonError('Server error: ' . $e->getMessage(), 500);
}

function jsonError(string $msg, int $code = 400): never
{
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $msg]);
    exit;
}
