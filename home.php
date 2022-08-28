<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    $_SESSION['url_req']  = $_SERVER['PHP_SELF'];
    header('Location: index.php');
}
function validateInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$showAlert = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount'])) {
    $amount = validateInput($_POST['amount']);
    $category = validateInput($_POST['category']);
    $remark = validateInput($_POST['remark']);
    $date = validateInput($_POST['date']);
    include_once 'dbCon.php';
    $con = OpenCon();
    $sql = "INSERT INTO `expenses` VALUES (NULL,'$amount','$category','$remark','$date');"; //NULL for auto inrement
    $result = mysqli_query($con, $sql);
    if ($result) {
        $showAlert = true;
        $alertClass = 'alert-success';
        $alertMsg = "Rs $amount added";
    } else {
        $showAlert = true;
        $alertClass = 'alert-danger';
        $alertMsg = "Error, Rs $amount not added";
    }
    mysqli_close($con);
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    include_once 'dbCon.php';
    $con = OpenCon();
    $sql = "DELETE FROM `expenses` WHERE `id`=$delete_id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $showAlert = true;
        $alertClass = 'alert-success';
        $alertMsg = "Record Id $delete_id deleted";
    } else {
        $showAlert = true;
        $alertClass = 'alert-danger';
        $alertMsg = 'Error, Record Id $delete_id not deleted';
    }
    mysqli_close($con);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_amount'])) {
    $amount = $_POST['edit_amount'];
    $category = $_POST['category'];
    $remark = $_POST['remark'];
    $date = $_POST['date'];
    $id = $_POST['id'];
    include_once 'dbCon.php';
    $con = OpenCon();
    $sql = "UPDATE `expenses` SET `amount`='$amount', `category`='$category', `remark`='$remark', `date`='$date'  WHERE `id`='$id'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $showAlert = true;
        $alertClass = 'alert-success';
        $alertMsg = "Record Id $id updated";
    } else {
        $showAlert = true;
        $alertClass = 'alert-danger';
        $alertMsg = "Error, Record Id $id not updated";
    }
    mysqli_close($con);
}
?>
<!doctype html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- Bootstrap CSS -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0' crossorigin='anonymous'>
    <title>Expense-Home</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/expense.png">
</head>

<body>
    <?php
    include 'header.php';
    if ($showAlert) {
        echo "<div class='alert $alertClass alert-dismissible fade show py-2 mb-0' role='alert'>
                <strong >$alertMsg</strong>
                <button type='button' class='btn-close pb-2' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
    }
    ?>
    <div class=" my-3 text-center">
        <!-- Modal Button-->
        <button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add Record
        </button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method='POST' action='home.php'>
                            <div class='mb-3'>
                                <label for='amount' class='form-label float-start'>Amount</label>
                                <input type='number' class='form-control' id='amount' name='amount' required>
                            </div>
                            <div class='mb-3'>
                                <label for='category' class='form-label float-start'>Category</label>
                                <select class="form-select" id="category" name="category" aria-label="Default select example">
                                    <option>Select</option>
                                    <option selected value="Grocery">Grocery</option>
                                    <option value="Travel">Travel</option>
                                    <option value="Shopping">Shopping</option>
                                    <option value="Housing">Housing</option>
                                    <option value="Vehicle">Vehicle</option>
                                    <option value="Phone Bills">Phone Bills</option>
                                    <option value="Health Care">Health Care</option>
                                    <option value="Entertainment">Entertainment</option>
                                    <option value="Gifts">Gifts</option>
                                    <option value="Wellness">Wellness</option>
                                    <option value="Restaurant">Restaurant</option>
                                    <option value="Electronics, Accessories">Electronics, Accessories</option>
                                </select>
                            </div>
                            <div class='mb-3'>
                                <label for='remark' class='form-label float-start'>Remark</label>
                                <input type='text' class='form-control' id='remark' name='remark'>
                            </div>
                            <div class='mb-3'>
                                <label for='date' class='form-label float-start'>Date</label>
                                <?php
                                date_default_timezone_set('Asia/Kolkata');
                                $curr_date = date("Y-m-d");
                                ?>
                                <input type='date' value='<?php echo $curr_date ?>' class='form-control' id='date' name='date' required>
                            </div>
                            <button type='submit' class='btn btn-primary'>Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2 mx-3 mb-0 ">
            <h5>All Expenses Records</h5>
            <div class="mb-2 col-xs-8 col-md-4 ">
                <form method='GET' action='home.php' class="d-flex ">
                    <?php
                    date_default_timezone_set('Asia/Kolkata');
                    $from = date("Y-m-01");
                    $to = date("Y-m-d");
                    ?>
                    <input type='date' value='<?php echo $from ?>' class='form-control ' id='from' name='from' required>
                    <input type='date' value='<?php echo $to ?>' class='form-control mx-1 ' id='to' name='to' required>
                    <button type='submit' class='btn btn-primary '>View</button>
                </form>
            </div>
            <h5 id="total" class="text-danger">Total </h5>
            <div class="mb-3 ">
                <table id="table_id" class="table-light table table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th>Record Id</th>
                            <th>Amount (Rs)</th>
                            <th>Category</th>
                            <th style='min-width:80px'>Date</th>
                            <th style='min-width:200px'>Remark</th>
                            <th style='min-width:110px'>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include_once 'dbCon.php';
                        $con = OpenCon();
                        date_default_timezone_set('Asia/Kolkata');
                        if (isset($_GET['from'])) {
                            $from = $_GET['from'];
                            $to = $_GET['to'];
                        }
                        $sql = "SELECT * FROM `expenses` WHERE date >= '$from' AND date <= '$to'";
                        $result = mysqli_query($con, $sql);
                        $rowNos = mysqli_num_rows($result);
                        $total = 0;
                        for ($i = 0; $i < $rowNos; $i++) {
                            $row = mysqli_fetch_assoc($result);
                            $id = $row['id'];
                            $amount = $row['amount'];
                            $category = $row['category'];
                            $remark = $row['remark'];
                            $date = $row['date'];
                            $total = $total + $amount;
                            echo "<tr>
                                    <td>$id</td>
                                    <td>$amount</td>
                                    <td>$category</td>
                                    <td>$date</td>
                                    <td>$remark</td>
                                    <td>
                                        <a href='expense_edit.php?id=$id' class='btn btn-primary'>Edit</a>
                                        <a href='home.php?delete_id=$id' class='btn btn-danger' onclick=\"return confirm('Sure to delete Id $id?')\">Delete</a>
                                    </td>
                                </tr>";
                        }
                        mysqli_close($con);
                        ?>
                    </tbody>
                </table>
                <script>
                    document.getElementById('total').innerHTML = 'Total = <?php echo number_format($total) ?>';
                    document.getElementById('from').value = '<?php echo $from ?>';
                    document.getElementById('to').value = '<?php echo $to ?>';
                </script>
                <!-- for data table -->
                <script src="https://code.jquery.com/jquery-3.5.1.js"> </script>
                <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"> </script>
                <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"> </script>
                <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css" rel="stylesheet">
                <script>
                    $(document).ready(function() {
                        $('#table_id').DataTable({
                            "scrollX": true,
                            "order": [
                                [0, "desc"]
                            ]
                        });
                    });
                </script>
            </div>
        </div>

    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js' integrity='sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8' crossorigin='anonymous'></script>
</body>

</html>