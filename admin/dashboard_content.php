
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            margin-left: -140px; 
            width: 100%; 
            max-width: 350px; 
        }
        .h1 {
            text-align: center;
        }
    </style>
</head>
<body>
<h1 >Dashboard</h1>
<div class="content">
    
    <div class="row justify-content-center"> 
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Total Orders</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_orders; ?></h5>
                    <p class="card-text">Total number of orders placed.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Feedback</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $feedbackResult->num_rows; ?></h5>
                    <p class="card-text">Total feedback messages received.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Categories</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo isset($total_categories) ? $total_categories : 0; ?></h5>
                    <p class="card-text">Total number of categories available.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Total Products</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo isset($total_products) ? $total_products : 0; ?></h5>
                    <p class="card-text">Total number of products listed.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">Total Users</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo isset($total_users) ? $total_users : 0; ?></h5>
                    <p class="card-text">Total number of registered users.</p>
                </div>
            </div>
        </div>
    </div>

    
</div>
</body>
</html>
