<?php

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection
include '../includes/db.php';

// Handle delete request
if (isset($_POST['delete_feedback'])) {
    $feedback_id = (int) $_POST['feedback_id']; // Explicitly cast to integer for safety

    // Prepare and execute the delete query
    $deleteQuery = $conn->prepare("DELETE FROM feedback WHERE id = ?");
    $deleteQuery->bind_param("i", $feedback_id);

    if ($deleteQuery->execute()) {
        $success = "Feedback deleted successfully!";
    } else {
        $error = "Error deleting feedback: " . $conn->error;
    }

    $deleteQuery->close();
}

// Fetch feedback and user details (name from users table)
$feedbackQuery = "SELECT f.id, u.full_name AS customer_name, u.email, f.message 
                  FROM feedback f
                  JOIN users u ON f.user_id = u.id
                  ORDER BY f.id DESC";
$feedbackResult = $conn->query($feedbackQuery);

// Initialize the $feedback variable
if ($feedbackResult) {
    $feedback = $feedbackResult->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error fetching feedback: " . $conn->error;
    $feedback = []; // Set to an empty array to avoid further errors
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
            max-width: 1200px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 36px;
            color: #333;
        }
        .table {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .table td {
            font-size: 14px;
            color: #555;
        }
        .alert {
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .action-btn {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 4px;
        }
        .action-btn:hover {
            opacity: 0.8;
        }
        .message {
            max-width: 300px;
            word-wrap: break-word;
            font-size: 13px;
        }
        /* Added hover effect for the table rows */
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>View Feedback</h2>

    <!-- Display success or error messages -->
    <?php if (isset($success)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedback as $fb): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fb['id']); ?></td>
                        <td><?php echo htmlspecialchars($fb['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($fb['email']); ?></td>
                        <td class="message" title="<?php echo htmlspecialchars($fb['message']); ?>">
                            <?php echo htmlspecialchars(strlen($fb['message']) > 50 ? substr($fb['message'], 0, 50) . '...' : $fb['message']); ?>
                        </td>
                        <td>
                            <!-- Delete button with a form to submit the delete request -->
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                                <input type="hidden" name="feedback_id" value="<?php echo $fb['id']; ?>">
                                <button type="submit" name="delete_feedback" class="btn btn-danger action-btn">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
