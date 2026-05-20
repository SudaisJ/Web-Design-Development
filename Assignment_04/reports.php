<?php
require_once 'includes/config.php';
requireLogin();

$tenants = $pdo->query("SELECT * FROM tenants ORDER BY name ASC")->fetchAll();
$selected_tenant_id = $_GET['tenant_id'] ?? null;
$ledger = [];

if ($selected_tenant_id) {
    // Get all bills and payments for the selected tenant to create a ledger
    $bills = $pdo->prepare("SELECT id, bill_month as date_ref, total_amount as debit, 0 as credit, 'Bill Generated' as description, created_at FROM bills WHERE tenant_id = ?");
    $bills->execute([$selected_tenant_id]);
    $billsData = $bills->fetchAll();

    $payments = $pdo->prepare("SELECT id, payment_date as date_ref, 0 as debit, amount_paid as credit, CONCAT('Payment - Receipt: ', receipt_number) as description, created_at FROM payments WHERE tenant_id = ?");
    $payments->execute([$selected_tenant_id]);
    $paymentsData = $payments->fetchAll();

    $ledger = array_merge($billsData, $paymentsData);
    usort($ledger, function($a, $b) {
        return strtotime($a['created_at']) - strtotime($b['created_at']);
    });
}

// Summary Report Data
$monthlySummary = $pdo->query("
    SELECT 
        DATE_FORMAT(payment_date, '%Y-%m') as month, 
        SUM(amount_paid) as total_collected 
    FROM payments 
    GROUP BY month 
    ORDER BY month DESC
")->fetchAll();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-chart-pie me-2 text-warning"></i>Reports & Ledger</h2>
</div>

<ul class="nav nav-pills mb-4" id="reportTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="ledger-tab" data-bs-toggle="pill" data-bs-target="#ledger" type="button" role="tab">Tenant Ledger</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="summary-tab" data-bs-toggle="pill" data-bs-target="#summary" type="button" role="tab">Monthly Collection Summary</button>
  </li>
</ul>

<div class="tab-content glass-card p-4">
    <!-- Tenant Ledger Tab -->
    <div class="tab-pane fade show active" id="ledger" role="tabpanel">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-6">
                <select name="tenant_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select Tenant to View Ledger --</option>
                    <?php foreach ($tenants as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= $selected_tenant_id == $t['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['name']) ?> (Shop: <?= htmlspecialchars($t['shop_number']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if ($selected_tenant_id): ?>
        <div class="table-responsive">
            <table class="table table-hover text-white align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th class="text-danger">Debit (Bill)</th>
                        <th class="text-success">Credit (Payment)</th>
                        <th class="text-info">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $balance = 0;
                    foreach ($ledger as $item):
                        $balance += $item['debit'];
                        $balance -= $item['credit'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['date_ref']) ?></td>
                        <td><?= htmlspecialchars($item['description']) ?></td>
                        <td class="text-danger"><?= $item['debit'] > 0 ? '$'.number_format($item['debit'], 2) : '-' ?></td>
                        <td class="text-success"><?= $item['credit'] > 0 ? '$'.number_format($item['credit'], 2) : '-' ?></td>
                        <td class="text-info fw-bold">$<?= number_format($balance, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($ledger) === 0): ?>
                    <tr><td colspan="5" class="text-center text-muted">No transactions found for this tenant.</td></tr>
                    <?php else: ?>
                    <tr class="table-active">
                        <td colspan="4" class="text-end fw-bold">Total Outstanding Balance:</td>
                        <td class="fw-bold <?= $balance > 0 ? 'text-warning' : 'text-success' ?>">$<?= number_format($balance, 2) ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Monthly Summary Tab -->
    <div class="tab-pane fade" id="summary" role="tabpanel">
        <h5 class="mb-4">Monthly Rent Collection Summary</h5>
        <div class="table-responsive">
            <table class="table table-hover text-white">
                <thead>
                    <tr>
                        <th>Month (YYYY-MM)</th>
                        <th>Total Collected</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthlySummary as $s): ?>
                    <tr>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($s['month']) ?></span></td>
                        <td class="text-success fw-bold">$<?= number_format($s['total_collected'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($monthlySummary) === 0): ?>
                    <tr><td colspan="2" class="text-center text-muted">No collections recorded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
