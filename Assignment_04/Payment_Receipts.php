<?php
require_once 'includes/config.php';
requireLogin();

if (!isset($_GET['id'])) {
    die("Invalid receipt ID.");
}

$stmt = $pdo->prepare("SELECT p.*, t.name, t.phone, t.shop_number FROM payments p JOIN tenants t ON p.tenant_id = t.id WHERE p.id = ?");
$stmt->execute([$_GET['id']]);
$payment = $stmt->fetch();

if (!$payment) {
    die("Receipt not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - <?= htmlspecialchars($payment['receipt_number']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; color: #0f172a; padding: 40px; }
        .receipt-card { background: #fff; max-width: 600px; margin: auto; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px; text-align: center; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt-card { box-shadow: none; max-width: 100%; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-card">
        <div class="header">
            <h2>Rental Shop Manager</h2>
            <p class="mb-0 text-muted">Payment Receipt</p>
        </div>
        <div class="row mb-4">
            <div class="col-sm-6">
                <h6 class="mb-3">To:</h6>
                <div><strong><?= htmlspecialchars($payment['name']) ?></strong></div>
                <div>Shop Number: <?= htmlspecialchars($payment['shop_number']) ?></div>
                <div>Phone: <?= htmlspecialchars($payment['phone']) ?></div>
            </div>
            <div class="col-sm-6 text-sm-end">
                <h6 class="mb-3">Details:</h6>
                <div><strong>Receipt No:</strong> <?= htmlspecialchars($payment['receipt_number']) ?></div>
                <div><strong>Date:</strong> <?= htmlspecialchars($payment['payment_date']) ?></div>
            </div>
        </div>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Description</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Rent Payment</td>
                    <td class="text-end">$<?= number_format($payment['amount_paid'], 2) ?></td>
                </tr>
                <tr>
                    <td class="text-end fw-bold">Total Paid</td>
                    <td class="text-end fw-bold text-success">$<?= number_format($payment['amount_paid'], 2) ?></td>
                </tr>
            </tbody>
        </table>
        <div class="text-center mt-5">
            <p class="text-muted">Thank you for your payment!</p>
            <button onclick="window.print()" class="btn btn-primary no-print mt-3">Print Receipt</button>
            <a href="payments.php" class="btn btn-secondary no-print mt-3 ms-2">Back to Payments</a>
        </div>
    </div>
</body>
</html>
