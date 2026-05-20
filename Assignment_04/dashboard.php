<?php
require_once 'includes/config.php';
requireLogin();

// Fetch summary data
$totalTenants = $pdo->query("SELECT COUNT(*) FROM tenants")->fetchColumn();
$totalBilledThisMonth = $pdo->query("SELECT SUM(total_amount) FROM bills WHERE bill_month = '" . date('Y-m') . "'")->fetchColumn() ?: 0;
$totalCollectedThisMonth = $pdo->query("SELECT SUM(amount_paid) FROM payments WHERE DATE_FORMAT(payment_date, '%Y-%m') = '" . date('Y-m') . "'")->fetchColumn() ?: 0;
$totalOutstanding = ($pdo->query("SELECT SUM(total_amount) FROM bills")->fetchColumn() ?: 0) - ($pdo->query("SELECT SUM(amount_paid) FROM payments")->fetchColumn() ?: 0);

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-gauge me-2 text-primary"></i>Dashboard</h2>
    <div>
        <span class="text-muted">Welcome back, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></span>
    </div>
</div>

<div class="dashboard-stats">
    <div class="glass-card stat-card border-primary">
        <div>
            <p class="text-muted mb-1">Total Tenants</p>
            <h3 class="mb-0 fw-bold"><?= $totalTenants ?></h3>
        </div>
        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
    </div>
    <div class="glass-card stat-card border-info">
        <div>
            <p class="text-muted mb-1">Billed This Month</p>
            <h3 class="mb-0 fw-bold">$<?= number_format($totalBilledThisMonth, 2) ?></h3>
        </div>
        <div class="stat-icon text-info"><i class="fa-solid fa-file-invoice-dollar"></i></div>
    </div>
    <div class="glass-card stat-card border-success">
        <div>
            <p class="text-muted mb-1">Collected This Month</p>
            <h3 class="mb-0 fw-bold">$<?= number_format($totalCollectedThisMonth, 2) ?></h3>
        </div>
        <div class="stat-icon text-success"><i class="fa-solid fa-hand-holding-dollar"></i></div>
    </div>
    <div class="glass-card stat-card border-warning">
        <div>
            <p class="text-muted mb-1">Total Outstanding</p>
            <h3 class="mb-0 fw-bold text-warning">$<?= number_format(max(0, $totalOutstanding), 2) ?></h3>
        </div>
        <div class="stat-icon text-warning"><i class="fa-solid fa-triangle-exclamation"></i></div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="glass-card p-4 h-100">
            <h5 class="mb-4">Quick Actions</h5>
            <div class="d-grid gap-3">
                <a href="tenants.php" class="btn btn-outline-primary text-start"><i class="fa-solid fa-user-plus me-2"></i> Manage Tenants</a>
                <a href="bills.php" class="btn btn-outline-info text-start"><i class="fa-solid fa-file-invoice me-2"></i> Generate Monthly Bills</a>
                <a href="payments.php" class="btn btn-outline-success text-start"><i class="fa-solid fa-money-bill-wave me-2"></i> Record Rent Payment</a>
                <a href="reports.php" class="btn btn-outline-secondary text-start"><i class="fa-solid fa-chart-line me-2"></i> View Reports</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="glass-card p-4 h-100">
            <h5 class="mb-4">Recent Payments</h5>
            <div class="table-responsive">
                <table class="table table-hover table-borderless text-white">
                    <thead>
                        <tr>
                            <th>Tenant</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recentPayments = $pdo->query("SELECT p.amount_paid, p.payment_date, t.name FROM payments p JOIN tenants t ON p.tenant_id = t.id ORDER BY p.created_at DESC LIMIT 5")->fetchAll();
                        if (count($recentPayments) > 0) {
                            foreach ($recentPayments as $rp) {
                                echo "<tr>
                                        <td>".htmlspecialchars($rp['name'])."</td>
                                        <td class='text-success'>+$".number_format($rp['amount_paid'], 2)."</td>
                                        <td class='text-muted'>".htmlspecialchars($rp['payment_date'])."</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center text-muted'>No recent payments.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
