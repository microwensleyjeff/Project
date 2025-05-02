<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Connect to DB
$conn = new mysqli("localhost", "root", "", "inventory_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch units
$units = [];
$result = $conn->query("SELECT id, name FROM units");
while ($row = $result->fetch_assoc()) {
    $units[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Panel</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 40px; text-align: center;}
        h2, h3 { color: #333; }
        select, input[type="number"], input[type="submit"] {
            padding: 8px; margin-top: 10px; margin-bottom: 20px; display: block;
            margin-left: 700px;
        }
        input[type="submit"] {
            background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

<form action="submit_request.php" method="post">
    <label for="unit">Select Available Unit:</label>

  
<select name="unit_id" id="unit" required>
    <option value="" disabled selected hidden>-- Select Unit --</option>
    <?php foreach ($units as $unit): ?>
        <option value="<?= $unit['id'] ?>"><?= htmlspecialchars($unit['name']) ?></option>
    <?php endforeach; ?>
</select>
<label for="unit" class="availableunit">Select Available Brand:</label>
<!-- Brand Dropdown -->
<select name="brand_id" id="brand" required>
    <option value="" disabled selected hidden>-- Select Brand --</option>
</select>


    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" min="1" required>

    <input type="submit" value="Submit Request">
</form>

<a href="login.php">Logout</a>

<script>
document.getElementById('unit').addEventListener('change', function () {
    const unitId = this.value;
    const brandSelect = document.getElementById('brand');

    // Clear all options
    brandSelect.innerHTML = '';

    // Add a temporary loading option
    const loadingOption = document.createElement('option');
    loadingOption.textContent = 'Loading...';
    loadingOption.disabled = true;
    loadingOption.selected = true;
    brandSelect.appendChild(loadingOption);

    // Fetch the brands based on selected unit
    fetch(`get_brands.php?unit_id=${unitId}`)
        .then(response => response.json())
        .then(data => {
            brandSelect.innerHTML = ''; // Clear loading

            if (data.length === 0) {
                const noBrandOption = document.createElement('option');
                noBrandOption.textContent = 'No brands available';
                noBrandOption.disabled = true;
                noBrandOption.selected = true;
                brandSelect.appendChild(noBrandOption);
            } else {
                // Add a disabled placeholder first
                const placeholder = document.createElement('option');
                placeholder.textContent = '-- Select Brand --';
                placeholder.disabled = true;
                placeholder.selected = true;
                placeholder.hidden = true;
                brandSelect.appendChild(placeholder);

                // Add real brand options
                data.forEach(brand => {
                    const option = document.createElement('option');
                    option.value = brand.id;
                    option.textContent = brand.name;
                    brandSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading brands:', error);
            brandSelect.innerHTML = '';
            const errorOption = document.createElement('option');
            errorOption.textContent = 'Error loading brands';
            errorOption.disabled = true;
            errorOption.selected = true;
            brandSelect.appendChild(errorOption);
        });
});
</script>


</body>
</html>
