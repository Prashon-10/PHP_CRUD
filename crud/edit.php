<?php
require_once "connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $selectSql = "SELECT * FROM students WHERE id = $id";
    $result = mysqli_query($conn, $selectSql);
    $student = mysqli_fetch_assoc($result);
} else {
    $_SESSION['error'] = "Invalid ID";
    header('Location: index.php');
    exit();
}

if (!empty($_POST)) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $language = implode(",", $_POST['language']);
    $country = $_POST['country'];

    if (!empty($_FILES['image']['name'])) {
        $tmpName = $_FILES['image']['tmp_name'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = md5(microtime()) . "." . $ext;
        $image = "";
        $upload = 'uploads/';

        if (!move_uploaded_file($tmpName, $upload . $imageName)) {
            $_SESSION['error'] = "Image upload failed";
        }

        // Remove the old image if a new one is uploaded
        if (!empty($student['image'])) {
            unlink('uploads/' . $student['image']);
        }

        $image = $imageName;
    } else {
        $image = $student['image']; // Use the existing image if no new image is uploaded
    }

    $updateSql = "UPDATE students SET name = '$name', email = '$email', gender = '$gender', language = '$language', country = '$country', image = '$image' WHERE id = $id";

    $res = mysqli_query($conn, $updateSql);

    if ($res) {
        $_SESSION['success'] = "Student updated successfully";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Student update failed";
        header("Location: edit.php?id=$id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="./bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-3 mt-3">
                <h1>Edit Student</h1>
            </div>
            <div class="col-md-12">
                <?php if (isset($_SESSION['error'])) : ?>
                    <div class="alert alert-danger">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter your Name" value="<?= $student['name'] ?>" required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email" value="<?= $student['email'] ?>" required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="gender">Gender</label>
                        <label><input type="radio" name="gender" value="male" <?= ($student['gender'] === 'male') ? 'checked' : '' ?>>Male</label>
                        <label><input type="radio" name="gender" value="female" <?= ($student['gender'] === 'female') ? 'checked' : '' ?>>Female</label>
                    </div>

                    <div class="form-group mb-2">
                        <label for="language">Language</label>
                        <?php
                        $languages = explode(",", $student['language']);
                        ?>
                        <label><input type="checkbox" name="language[]" value="nepali" <?= (in_array('nepali', $languages)) ? 'checked' : '' ?>>Nepali</label>
                        <label><input type="checkbox" name="language[]" value="english" <?= (in_array('english', $languages)) ? 'checked' : '' ?>>English</label>
                        <label><input type="checkbox" name="language[]" value="chinese" <?= (in_array('chinese', $languages)) ? 'checked' : '' ?>>Chinese</label>
                    </div>

                    <div class="form-group mb-2">
                        <label for="country">Country</label>
                        <select name="country" id="country" class="form-control">
                            <option value="">----Select Country----</option>
                            <option value="nepal" <?= ($student['country'] === 'nepal') ? 'selected' : '' ?>>Nepal</option>
                            <option value="us" <?= ($student['country'] === 'us') ? 'selected' : '' ?>>USA</option>
                            <option value="china" <?= ($student['country'] === 'china') ? 'selected' : '' ?>>China</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" name="image">
                    </div>

                    <div class="form-group mb-3">
                        <button class="btn btn-success">Update Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
