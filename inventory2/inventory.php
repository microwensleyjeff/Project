<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "inventory_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch unit list
$units = [];
$result = $conn->query("SELECT id, name FROM units");
while ($row = $result->fetch_assoc()) {
    $units[] = $row;
}

// Fetch brands per unit
$unitBrands = [];
foreach ($units as $unit) {
    $unitId = $unit['id'];
    $unitName = $unit['name'];

    $stmt = $conn->prepare("SELECT id, name FROM brands WHERE unit_id = ?");
    $stmt->bind_param("i", $unitId);
    $stmt->execute();
    $result = $stmt->get_result();

    $brands = [];
    while ($brand = $result->fetch_assoc()) {
        $brands[] = [
            'id' => $brand['id'],
            'name' => $brand['name']
        ];
    }

    $unitBrands[] = [
        'unit_name' => $unitName,
        'brands' => $brands
    ];
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 40px;
        }

        h2, h3, h4 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"],
        select {
            padding: 8px;
            width: 200px;
            margin-right: 10px;
        }

        input[type="submit"] {
            padding: 8px 12px;
            background-color: #28a745;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }

        ul {
            list-style: none;
            padding-left: 20px;
        }

        li {
            background: #fff;
            margin-bottom: 8px;
            padding: 8px 12px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .delete-btn {
            background: #dc3545;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
        }

        .logout {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .logout:hover {
            text-decoration: underline;
        }

        .request-text {
            flex-grow: 1;
            margin-right: 10px;
        }

        .formaddunit {
            border-width: 2px;
            border-top-style: solid;
            border-left-style: none;
            border-right-style: none;
            border-bottom-style: solid;
            padding: 20px;
        }
    </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

<!-- Add Unit Form -->
<form action="add_unit.php" method="post" class="formaddunit">
    <label>Add Unit:
        <input type="text" name="unit_name" required>
    </label>
    <input type="submit" value="Add Unit">
</form>

<!-- Available Units List -->
<h3>Available Units:</h3>
<ul>
    <?php foreach ($units as $unit): ?>
        <li>
            <?= htmlspecialchars($unit['name']) ?>
            <form class="formavailableunits" method="post" action="delete_unit.php" style="display:inline;" onsubmit="return confirmDelete('<?= htmlspecialchars($unit['name']) ?>')">
                <input type="hidden" name="unit_name" value="<?= htmlspecialchars($unit['name']) ?>">
                <button type="submit" class="delete-btn" title="Remove">×</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<!-- Add Brand to Unit -->
<form action="add_brand.php" method="post">
    <label>Select Unit:
        <select name="unit_id" required>
            <option value="" disabled selected>Select a Unit</option>
            <?php foreach ($units as $unit): ?>
                <option value="<?= $unit['id'] ?>"><?= htmlspecialchars($unit['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Add Brand:
        <input type="text" name="brand_name" required>
    </label>
    <input type="submit" value="Add Brand">
</form>

<!-- Brands List per Unit -->
<h3>Unit Brands:</h3>
<?php foreach ($unitBrands as $entry): ?>
    <h4><?= htmlspecialchars($entry['unit_name']) ?>:</h4>
    <ul>
        <?php if (empty($entry['brands'])): ?>
            <li><em>No brands available.</em></li>
        <?php else: ?>
            <?php foreach ($entry['brands'] as $brand): ?>
                <li>
                    <?= htmlspecialchars($brand['name']) ?>
                    <form method="post" action="delete_brand.php" style="display:inline;" onsubmit="return confirmDeleteBrand('<?= htmlspecialchars($brand['name']) ?>')">
                        <input type="hidden" name="brand_id" value="<?= $brand['id'] ?>">
                        <button type="submit" class="delete-btn" title="Remove Brand">×</button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
<?php endforeach; ?>

<!-- Pending Requests -->
<h3>User Submitted Requests:</h3>
<ul>
<?php
$conn = new mysqli("localhost", "root", "", "inventory_system");

$pendingQuery = "
    SELECT r.id, u.username, un.name AS unit_name, b.name AS brand_name, r.quantity, r.created_at
    FROM requests r
    JOIN users u ON r.user_id = u.id
    JOIN units un ON r.unit_id = un.id
    JOIN brands b ON r.brand_id = b.id
    WHERE r.status = 'pending'
    ORDER BY r.created_at DESC
";
$pendingResult = $conn->query($pendingQuery);

if ($pendingResult->num_rows === 0) {
    echo "<li><em>No pending requests.</em></li>";
} else {
    while ($row = $pendingResult->fetch_assoc()) {
        echo "<li><span class='request-text'><strong>{$row['username']}</strong> requested <strong>{$row['quantity']}</strong> of <em>{$row['unit_name']}</em> ({$row['brand_name']}) on {$row['created_at']}</span>
            <form method='post' action='mark_done.php' style='display:inline;'>
                <input type='hidden' name='request_id' value='{$row['id']}'>
                <button type='submit' title='Mark as done'>✅</button>
            </form>
        </li>";
    }
}
?>
</ul>

<!-- Done Requests -->
<h3>Done Requests:</h3>
<ul>
<?php
$doneQuery = "
    SELECT r.id, u.username, un.name AS unit_name, b.name AS brand_name, r.quantity, r.done_at
    FROM requests r
    JOIN users u ON r.user_id = u.id
    JOIN units un ON r.unit_id = un.id
    JOIN brands b ON r.brand_id = b.id
    WHERE r.status = 'done'
    ORDER BY r.done_at DESC
";
$doneResult = $conn->query($doneQuery);

if ($doneResult->num_rows === 0) {
    echo "<li><em>No done requests yet.</em></li>";
} else {
    while ($row = $doneResult->fetch_assoc()) {
        echo "<li>
                <div class='request-text'>
                    <strong>{$row['username']}</strong> requested 
                    <strong>{$row['quantity']}</strong> of 
                    <em>{$row['unit_name']}</em> ({$row['brand_name']}) 
                    <br><small>Marked done on: {$row['done_at']}</small>
                </div>
                <form method='post' action='delete_done_request.php' style='display:inline;'>
                    <input type='hidden' name='request_id' value='{$row['id']}'>
                    <button type='submit' class='delete-btn' title='Delete Done Request'>×</button>
                </form>
              </li>";
    }
}
?>
</ul>

<a href="login.php" class="logout">Logout</a>

<script>
function confirmDelete(unit) {
    return confirm(`Are you sure you want to delete unit: "${unit}"?`);
}
</script>

</body>
</html>
