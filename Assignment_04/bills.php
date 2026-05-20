<?php
require_once 'includes/config.php';
requireLogin();

$currentMonth = date('Y-m');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    $tenant_id = $_POST['tenant_id'];
    $bill_month = $_POST['bill_month'];
    
    // Check if bill already exists
    $stmt = $pdo->prepare("SELECT id FROM bills WHERE tenant_id = ? AND bill_month = ?");
    $stmt->execute([$tenant_id, $bill_month]);
    if (!$stmt->fetch()) {
        // Calculate arrears: Total Billed - Total Paid
        $billedStmt = $pdo->prepare("SELECT SUM(total_amount) FROM bills WHERE tenant_id = ?");
        $billedStmt->execute([$tenant_id]);
        $totalBilled = $billedStmt->fetchColumn() ?: 0;

        $paidStmt = $pdo->prepare("SELECT SUM(amount_paid) FROM payments WHERE tenant_id = ?");
        $paidStmt->execute([$tenant_id]);
        $totalPaid = $paidStmt->fetchColumn() ?: 0;

        $arrears = max(0, $totalBilled - $totalPaid);
        
        // Get tenant's monthly rent
        $tStmt = $pdo->prepare("SELECT monthly_rent FROM tenants WHERE id = ?");
        $tStmt->execute([$tenant_id]);
        $current_rent = $tStmt->fetchColumn() ?: 0;

        $total_amount = $current_rent + $arrears;

        $insStmt = $pdo->prepare("INSERT INTO bills (tenant_id, bill_month, current_rent, arrears, total_amount) VALUES (?, ?, ?, ?, ?)");
        $insStmt->execute([$tenant_id, $bill_month, $current_rent, $arrears, $total_amount]);
        
        header("Location: bills.php?success=1");
        exit;
    } else {
        header("Location: bills.php?error=exists");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        $id = $_POST['bill_id'];
        $stmt = $pdo->prepare("DELETE FROM bills WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: bills.php?deleted=1");
        exit;
    }
    
    if ($_POST['action'] === 'update') {
        $id = $_POST['bill_id'];
        $current_rent = $_POST['current_rent'];
        $arrears = $_POST['arrears'];
        $total_amount = $current_rent + $arrears;

        $stmt = $pdo->prepare("UPDATE bills SET current_rent = ?, arrears = ?, total_amount = ? WHERE id = ?");
        $stmt->execute([$current_rent, $arrears, $total_amount, $id]);
        header("Location: bills.php?updated=1");
        exit;
    }
}

$bills = $pdo->query("SELECT b.*, t.name, t.shop_number FROM bills b JOIN tenants t ON b.tenant_id = t.id ORDER BY b.created_at DESC")->fetchAll();
$tenants = $pdo->query("SELECT id, name, shop_number FROM tenants ORDER BY name ASC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-file-invoice me-2 text-info"></i>Billing Management</h2>
    <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#generateBillModal">
        <i class="fa-solid fa-plus me-1"></i> Generate Bill
    </button>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success bg-success text-white border-0">Bill generated successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success bg-success text-white border-0">Bill updated successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-info bg-info text-white border-0">Bill deleted successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['error']) && $_GET['error'] == 'exists'): ?>
    <div class="alert alert-warning bg-warning text-dark border-0">A bill for this tenant and month already exists.</div>
<?php endif; ?>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover text-white align-middle">
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Tenant</th>
                    <th>Month</th>
                    <th>Rent</th>
                    <th>Arrears</th>
                    <th>Total Due</th>
                    <th>Generated On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bills as $b): ?>
                <tr>
                    <td>#<?= $b['id'] ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($b['name']) ?> <span class="badge bg-secondary ms-1"><?= htmlspecialchars($b['shop_number']) ?></span></td>
                    <td><span class="badge bg-primary"><?= htmlspecialchars($b['bill_month']) ?></span></td>
                    <td>$<?= number_format($b['current_rent'], 2) ?></td>
                    <td class="text-warning">$<?= number_format($b['arrears'], 2) ?></td>
                    <td class="text-danger fw-bold">$<?= number_format($b['total_amount'], 2) ?></td>
                    <td class="text-muted"><small><?= date('M d, Y', strtotime($b['created_at'])) ?></small></td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editBillModal<?= $b['id'] ?>"><i class="fa-solid fa-edit"></i></button>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this bill?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="bill_id" value="<?= $b['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>

                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($bills) === 0): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No bills generated yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Bill Modals (Placed outside stacking contexts) -->
<?php foreach ($bills as $b): ?>
<div class="modal fade" id="editBillModal<?= $b['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content glass-card border-secondary text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Edit Bill Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="">
          <div class="modal-body text-start">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="bill_id" value="<?= $b['id'] ?>">
              <div class="mb-3">
                  <label class="form-label text-muted">Current Rent ($)</label>
                  <input type="number" step="0.01" name="current_rent" class="form-control" value="<?= htmlspecialchars($b['current_rent']) ?>" required>
              </div>
              <div class="mb-3">
                  <label class="form-label text-muted">Arrears ($)</label>
                  <input type="number" step="0.01" name="arrears" class="form-control" value="<?= htmlspecialchars($b['arrears']) ?>" required>
              </div>
              <p class="text-muted small"><i class="fa-solid fa-info-circle"></i> Total Amount will be recalculated automatically.</p>
          </div>
          <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-info text-white">Update Bill</button>
          </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- Generate Bill Modal -->
<div class="modal fade" id="generateBillModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content glass-card border-secondary text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Generate Monthly Bill</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="">
          <div class="modal-body">
              <input type="hidden" name="generate" value="1">
              <div class="mb-3">
                  <label class="form-label text-muted">Select Tenant</label>
                  <select name="tenant_id" class="form-select" required>
                      <option value="">-- Choose Tenant --</option>
                      <?php foreach ($tenants as $t): ?>
                          <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?> (Shop: <?= htmlspecialchars($t['shop_number']) ?>)</option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="mb-3">
                  <label class="form-label text-muted">Billing Month</label>
                  <input type="month" name="bill_month" class="form-control" value="<?= $currentMonth ?>" required>
              </div>
              <p class="text-muted small mt-3"><i class="fa-solid fa-info-circle"></i> Arrears will be calculated automatically based on previous unpaid amounts.</p>
          </div>
          <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-info text-white">Generate Bill</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
