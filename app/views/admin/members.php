<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-danger fw-bold">Member Management</h2>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg"></i> Add Member
        </button>
    </div>

    <input type="text" class="form-control mb-3" placeholder="Search by name, email...">

    <div class="card shadow-sm">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['members'] as $m): ?>
                <tr>
                    <td><?= $m['full_name'] ?></td>
                    <td><?= $m['email'] ?></td>
                    <td><?= $m['username'] ?></td>
                    <td>
                        <span class="badge <?= $m['status']=='active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                            <?= ucfirst($m['status']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $m['user_id'] ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <a href="/admin/member/toggle/<?= $m['user_id'] ?>/<?= $m['status'] ?>" 
                           class="btn btn-sm <?= $m['status']=='active' ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                            <i class="bi <?= $m['status']=='active' ? 'bi-lock' : 'bi-unlock' ?>"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="/admin/member/add" method="POST" class="modal-content">
      <div class="modal-header"><h5>Add New Member</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <label>Full Name</label><input type="text" name="fullname" class="form-control mb-2" required>
        <label>Email</label><input type="email" name="email" class="form-control mb-2" required>
        <label>Username</label><input type="text" name="username" class="form-control mb-2" required>
        <label>Password</label><input type="password" name="password" class="form-control mb-2" required>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Member</button></div>
    </form>
  </div>
</div>