<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
}
$showAlert = false;
?>
<!doctype html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- Bootstrap CSS -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0' crossorigin='anonymous'>
    <title>Expense-Edit</title>
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
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
        $id = $_GET['id'];
        include_once 'dbCon.php';
        $con = OpenCon();
        $sql = "SELECT * FROM `expenses` WHERE id='$id'";
        $result = mysqli_query($con, $sql);
        $rowNos = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);
        $amount = $row['amount'];
        $date = $row['date'];
        $category = $row['category'];
        $remark = $row['remark'];
        mysqli_close($con);
    }
    ?>
    <div class="container my-3 text-center">
        <h3>Edit Record Id <?php echo $id ?></h3>
        <form method='POST' action='home.php'>
            <div class='mb-3'>
                <label for='amount' class='form-label float-start'>Amount</label>
                <input value='<?php echo $amount ?>' type=' number' class='form-control' id='edit_amount' name='edit_amount' required>
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
                <script>
                    document.getElementById('category').value = '<?php echo $category ?>';
                </script>
            </div>
            <div class='mb-3'>
                <label for='remark' class='form-label float-start'>Remark</label>
                <input value='<?php echo $remark ?>' type='text' class='form-control' id='remark' name='remark'>
            </div>
            <div class='mb-3'>
                <label for='date' class='form-label float-start'>Date</label>
                <input type='date' value='<?php echo $date ?>' class='form-control' id='date' name='date' required>
            </div>
            <button value='<?php echo $id ?>' name='id' type='submit' class='btn btn-primary'>Submit</button>
        </form>
    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js' integrity='sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8' crossorigin='anonymous'></script>
</body>

</html>