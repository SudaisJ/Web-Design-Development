<?php
require_once 'includes/config.php';
requireLogin();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $shop_number = $_POST['shop_number'];
        $monthly_rent = $_POST['monthly_rent'];
        $join_date = $_POST['join_date'];

        $stmt = $pdo->prepare("INSERT INTO tenants (name, phone, shop_number, monthly_rent, join_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $shop_number, $monthly_rent, $join_date]);
        
        header("Location: tenants.php?success=1");
        exit;
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['tenant_id'];
        $stmt = $pdo->prepare("DELETE FROM tenants WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: tenants.php?deleted=1");
        exit;
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = $_POST['tenant_id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $shop_number = $_POST['shop_number'];
        $monthly_rent = $_POST['monthly_rent'];
        $join_date = $_POST['join_date'];

        $stmt = $pdo->prepare("UPDATE tenants SET name=?, phone=?, shop_number=?, monthly_rent=?, join_date=? WHERE id=?");
        $stmt->execute([$name, $phone, $shop_number, $monthly_rent, $join_date, $id]);
        
        header("Location: tenants.php?updated=1");
        exit;
    }
}

$tenants = $pdo->query("SELECT * FROM tenants ORDER BY created_at DESC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-users me-2 text-primary"></i>Manage Tenants</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTenantModal">
        <i class="fa-solid fa-plus me-1"></i> Add New Tenant
    </button>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success bg-success text-white border-0">Tenant added successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success bg-success text-white border-0">Tenant updated successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-info bg-info text-white border-0">Tenant removed successfully.</div>
<?php endif; ?>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover text-white align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Shop No.</th>
                    <th>Monthly Rent</th>
                    <th>Join Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tenants as $t): ?>
                <tr>
                    <td>#<?= $t['id'] ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($t['name']) ?></td>
                    <td><?= htmlspecialchars($t['phone']) ?></td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($t['shop_number']) ?></span></td>
                    <td class="text-info fw-bold">$<?= number_format($t['monthly_rent'], 2) ?></td>
                    <td><?= htmlspecialchars($t['join_date']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editTenantModal<?= $t['id'] ?>"><i class="fa-solid fa-edit"></i></button>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this tenant and all related records?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="tenant_id" value="<?= $t['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>

                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($tenants) === 0): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No tenants found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Tenant Modals (Placed outside stacking contexts) -->
<?php foreach ($tenants as $t): ?>
<div class="modal fade" id="editTenantModal<?= $t['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content glass-card border-secondary text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Edit Tenant</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="">
          <div class="modal-body text-start">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="tenant_id" value="<?= $t['id'] ?>">
              <div class="mb-3">
                  <label class="form-label text-muted">Full Name</label>
                  <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($t['name']) ?>" required>
              </div>
              <div class="mb-3">
                  <label class="form-label text-muted">Phone Number</label>
                  <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($t['phone']) ?>" required>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label text-muted">Shop Number</label>
                      <input type="text" name="shop_number" class="form-control" value="<?= htmlspecialchars($t['shop_number']) ?>" required>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label text-muted">Monthly Rent ($)</label>
                      <input type="number" step="0.01" name="monthly_rent" class="form-control" value="<?= htmlspecialchars($t['monthly_rent']) ?>" required>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label text-muted">Join Date</label>
                  <input type="date" name="join_date" class="form-control" value="<?= htmlspecialchars($t['join_date']) ?>" required>
              </div>
          </div>
          <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-info text-white">Update Tenant</button>
          </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- Add Tenant Modal -->
<div class="modal fade" id="addTenantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content glass-card border-secondary text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Add New Tenant</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="">
          <div class="modal-body">
              <input type="hidden" name="action" value="add">
              <div class="mb-3">
                  <label class="form-label text-muted">Full Name</label>
                  <input type="text" name="name" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label class="form-label text-muted">Phone Number</label>
                  <input type="text" name="phone" class="form-control" required>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label text-muted">Shop Number</label>
                      <input type="text" name="shop_number" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label text-muted">Monthly Rent ($)</label>
                      <input type="number" step="0.01" name="monthly_rent" class="form-control" required>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label text-muted">Join Date</label>
                  <input type="date" name="join_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
              </div>
          </div>
          <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Tenant</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
