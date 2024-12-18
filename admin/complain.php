<?php
include 'config.php';

$status_message = ""; // To hold the modal content
$update_message = ""; // To hold the status update message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_status'])) {
        // Handle status update
        $complaint_id = $_POST['complaint_id'];
        $status = $_POST['status'];
        $resolved_at = ($status == 'resolved') ? date('Y-m-d H:i:s') : NULL;

        $sql = "UPDATE complaints SET status = '$status', resolved_at = '$resolved_at' WHERE complaint_id = '$complaint_id'";
        if ($conn->query($sql) === TRUE) {
            $update_message = "Complaint status has been updated successfully.";
        } else {
            $update_message = "Error: " . $conn->error;
        }
    }

    if (isset($_POST['reject_complaint'])) {
        // Handle complaint rejection
        $complaint_id = $_POST['complaint_id'];
        $status = 'rejected';  // Set the status to rejected
        $resolved_at = NULL;

        $sql = "UPDATE complaints SET status = '$status', resolved_at = '$resolved_at' WHERE complaint_id = '$complaint_id'";
        if ($conn->query($sql) === TRUE) {
            $update_message = "Complaint has been rejected.";
        } else {
            $update_message = "Error: " . $conn->error;
        }
    }
}

// Fetch all complaints for the admin view
$sql = "SELECT * FROM complaints ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check if query was successful
if ($result === FALSE) {
    die("Error: Could not execute query. " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Complaint Management</title>
    <link rel="stylesheet" href="adminCSS/admin_complain.css">
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>

    <div class="sidebar">
        <h3>Admin Guidelines</h3>
        <ul>
            <li>Review all complaints carefully.</li>
            <li>Update the status to "resolved" or "pending" as appropriate.</li>
            <li>Ensure to update the resolution date when resolving complaints.</li>
            <li>Click "Update Status" to save changes.</li>
        </ul>
    </div>

    <div class="container">
        <a href="admin_page.php" class="back-dashboard">← Back to Dashboard</a>

        <h2>Complaints List</h2>

        <?php if (!empty($update_message)) { ?>
        <div class="modal-message"><?php echo $update_message; ?></div>
        <?php } ?>

        <table>
            <tr>
                <th>Complaint ID</th>
                <th>Username</th>
                <th>Complaint Text</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['complaint_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['complaint_text']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <button onclick="openModal(<?php echo $row['complaint_id']; ?>, '<?php echo $row['status']; ?>')">Update Status</button>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="complaint_id" value="<?php echo $row['complaint_id']; ?>">
                        <button type="submit" name="reject_complaint">Reject</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>

        <div id="updateModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h3>Update Complaint Status</h3>
                <form method="post" action="">
                    <input type="hidden" id="complaint_id" name="complaint_id">
                    <div class="update-form">
                        <label for="status">Complaint Status:</label>
                        <select id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                        </select>

                        <button type="submit" name="update_status">Update Status</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        // Show modal for updating status
        function openModal(complaintId, currentStatus) {
            document.getElementById('complaint_id').value = complaintId;
            document.getElementById('status').value = currentStatus;
            document.getElementById('updateModal').style.display = 'block';
        }

        // Close modal
        function closeModal() {
            document.getElementById('updateModal').style.display = 'none';
        }
    </script>
</body>
</html>
