<?php
// welcome.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'db_config.php';

// Get total registered guests
$total_guests = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM guests");
if ($result && $row = $result->fetch_assoc()) {
    $total_guests = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Guest Registration</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <!-- DataTables CSS (Bootstrap 4 style for AdminLTE) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="dashboard.php" class="brand-link">
            <span class="brand-text font-weight-light">GuestReg Admin</span>
        </a>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="guests.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Guests</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="export_excel.php" class="nav-link">
                            <i class="nav-icon fas fa-file-excel"></i>
                            <p>Export Excel</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="export_pdf.php" class="nav-link">
                            <i class="nav-icon fas fa-file-pdf"></i>
                            <p>Export PDF</p>
                        </a>
                    </li>
                    <!-- Add more sidebar links as needed -->
                </ul>
            </nav>
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper pt-3">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Dashboard Cards Row (Statistics and Export) -->
                <div class="row mb-4">
                    <div class="col-12 col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="card-title">Guest Statistics</h3>
                                <p class="card-text">Total Registered Guests: <strong><?= $total_guests ?></strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">Export Data</h3>
                            </div>
                            <div class="card-body">
                                <p>Download the guest list in your preferred format. The export will include all registered guests based on the current search criteria (if any).</p>
                                <a href="export_excel.php?search=" class="btn btn-success mr-2"><i class="fas fa-file-excel"></i> Export to Excel (CSV)</a>
                                <a href="export_pdf.php?search=" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Export to PDF</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controls Section -->
               

                <!-- Guest Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Registered Guests</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="guestTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Agency/Org.</th>
                                        <th>Designation</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Gender</th>
                                        <th>Signature</th>
                                        <th>Registered At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$query = $conn->query("SELECT * FROM guests ORDER BY registered_at DESC");
while ($row = $query->fetch_assoc()):
?>
    <tr>
        <td></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['agency_org']) ?></td>
        <td><?= htmlspecialchars($row['designation']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['mobile']) ?></td>
        <td><?= htmlspecialchars($row['gender']) ?></td>
        <td>
            <?php
$signature_file = $row['signature_data'];
if (!empty($signature_file) && file_exists(__DIR__ . '/signature/' . $signature_file)) {
    // Show the image from the signature folder
    echo '<img src="signature/' . htmlspecialchars($signature_file) . '" alt="Signature" style="max-width:120px;max-height:60px;"/>';
} else {
    echo '<span class="text-muted">No signature</span>';
}
?>
        </td>
        <td><?= htmlspecialchars($row['registered_at']) ?></td>
        <td>
            <!-- Example action buttons -->
            <a href="edit_guest.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
            <a href="delete_guest.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this guest?')">Delete</a>
        </td>
    </tr>
<?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer text-center">
        <strong>&copy; <?= date('Y') ?> GuestReg Admin.</strong>
        All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- AdminLTE & DataTables JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#guestTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0 // first column
                }
            ],
            "order": [[8, 'asc']] // order by Name or your preferred column
        }).on('order.dt search.dt', function () {
            let table = $('#guestTable').DataTable();
            table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });
</script>
</body>
</html>
