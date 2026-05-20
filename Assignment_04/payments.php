<?php
require_once 'includes/config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    $tenant_id = $_POST['tenant_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_date = $_POST['payment_date'];
    $receipt_number = 'REC-' . strtoupper(uniqid());

    $stmt = $pdo->prepare("INSERT INTO payments (tenant_id, amount_paid, payment_date, receipt_number) VALUES (?, ?, ?, ?)");
    $stmt->execute([$tenant_id, $amount_paid, $payment_date, $receipt_number]);
    
    $payment_id = $pdo->lastInsertId();
    header("Location: Payment_Receipts.php?id=" . $payment_id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        $id = $_POST['payment_id'];
        $stmt = $pdo->prepare("DELETE FROM payments WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: payments.php?deleted=1");
        exit;
    }
    
    if ($_POST['action'] === 'update') {
        $id = $_POST['payment_id'];
        $amount_paid = $_POST['amount_paid'];
        $payment_date = $_POST['payment_date'];

        $stmt = $pdo->prepare("UPDATE payments SET amount_paid = ?, payment_date = ? WHERE id = ?");
        $stmt->execute([$amount_paid, $payment_date, $id]);
        header("Location: payments.php?updated=1");
        exit;
    }
}

$payments = $pdo->query("SELECT p.*, t.name, t.shop_number FROM payments p JOIN tenants t ON p.tenant_id = t.id ORDER BY p.created_at DESC")->fetchAll();
$tenants = $pdo->query("SELECT id, name, shop_number FROM tenants ORDER BY name ASC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-money-bill-wave me-2 text-success"></i>Payments</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
        <i class="fa-solid fa-cash-register me-1"></i> Record Payment
    </button>
</div>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success bg-success text-white border-0">Payment updated successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-info bg-info text-white border-0">Payment deleted successfully.</div>
<?php endif; ?>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover text-white align-middle">
            <thead>
                <tr>
                    <th>Receipt No.</th>
                    <th>Tenant</th>
                    <th>Date</th>
                    <th>Amount Paid</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $p): ?>
                <tr>
                    <td><span class="badge bg-primary"><?= htmlspecialchars($p['receipt_number']) ?></span></td>
                    <td class="fw-bold"><?= htmlspecialchars($p['name']) ?> <span class="badge bg-secondary ms-1"><?= htmlspecialchars($p['shop_number']) ?></span></td>
                    <td><?= htmlspecialchars($p['payment_date']) ?></td>
                    <td class="text-success fw-bold">+$<?= number_format($p['amount_paid'], 2) ?></td>
                    <td>
                        <a href="Payment_Receipts.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-success" target="_blank"><i class="fa-solid fa-print"></i> Receipt</a>
                        <button class="btn btn-sm btn-outline-info ms-1" data-bs-toggle="modal" data-bs-target="#editPaymentModal<?= $p['id'] ?>"><i class="fa-solid fa-edit"></i></button>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="payment_id" value="<?= $p['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>

                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($payments) === 0): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">No payments recorded.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Payment Modals (Placed outside stacking contexts) -->
<?php foreach ($payments as $p): ?>
<div class="modal fade" id="editPaymentModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content glass-card border-secondary text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Edit Payment Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="">
          <div class="modal-body text-start">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="payment_id" value="<?= $p['id'] ?>">
              <div class="mb-3">
                  <label class="form-label text-muted">Amount Paid ($)</label>
                  <input type="number" step="0.01" name="amount_paid" class="form-control" value="<?= htmlspecialchars($p['amount_paid']) ?>" required>
              </div>
              <div class="mb-3">
                  <label class="form-label text-muted">Payment Date</label>
                  <input type="date" name="payment_date" class="form-control" value="<?= htmlspecialchars($p['payment_date']) ?>" required>
              </div>
          </div>
          <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-info text-white">Update Payment</button>
          </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content glass-card border-secondary text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Record Rent Payment</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="">
          <div class="modal-body">
              <input type="hidden" name="pay" value="1">
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
                  <label class="form-label text-muted">Amount Paid ($)</label>
                  <input type="number" step="0.01" name="amount_paid" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label class="form-label text-muted">Payment Date</label>
                  <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
              </div>
          </div>
          <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Save Payment</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
