<?php
require_once "connection.php";

$selectSql = "SELECT * FROM students ORDER BY id DESC";
$sRespnse = mysqli_query($conn, $selectSql);


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

        $image = $imageName;
    }

    $sql = "INSERT INTO students(name,email,gender,language,country,image) VALUES('$name','$email','$gender','$language','$country','$image')";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        $_SESSION['success'] = "Student added successfully";
        header("Location:index.php");
        exit();

    } else {
        $_SESSION['error'] = "Student added failed";
        header("Location:index.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD site</title>
    <link rel="stylesheet" href="./bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-3 mt-3">
                <h1>Add Students</h1>
            </div>
            <div class="col-md-12">
                <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                </div>
                <?php endif; ?>



                <?php if (isset($_SESSION['error'])): ?>
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
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter your Name"
                            required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email"
                            required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="gender">Gender</label>
                        <label><input type="radio" name="gender" value="male">Male</label>
                        <label><input type="radio" name="gender" value="Female">Female</label>
                    </div>

                    <div class="form-group mb-2">
                        <label for="language">Language</label>
                        <label><input type="checkbox" name="language[]" value="nepali">Nepali</label>
                        <label><input type="checkbox" name="language[]" value="english">English</label>
                        <label><input type="checkbox" name="language[]" value="chiniese">Chinese</label>
                    </div>

                    <div class="form-group mb-2">
                        <label for="country">Country</label>
                        <select name="country" id="country" class="form-control">
                            <option value="">----Select Country----</option>
                            <option value="nepal">Nepal</option>
                            <option value="us">USA</option>
                            <option value="china">China</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" name="image">
                    </div>

                    <div class="form-group mb-3">
                        <button class="btn btn-success">Add Record</button>
                    </div>
                </form>
            </div>

            <div class="col-md-8">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Language</th>
                            <th>Country</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($sRespnse as $key => $student): ?>
                        <tr>
                            <td>
                                <?php echo ++$key?>
                            </td>
                            <td>
                                <?=$student['name']?>
                            </td>
                            <td>
                                <?=$student['email']?>
                            </td>
                            <td>
                                <?=$student['gender']?>
                            </td>
                            <td>
                                <?=$student['language']?>
                            </td>
                            <td>
                                <?=$student['country']?>
                            </td>
                            <td>
                                <img src="uploads/<?= $student['image']; ?>" width="80" alt="">
                            </td>
                            <td>
                            <a href="edit.php?id=<?= $student['id']; ?>" class="btn btn-success"><i class="bi bi-pencil-square"></i></a>
                                <a href="delete.php?id=<?=$student['id'];?>" class="btn btn-danger"><i class="bi bi-trash3"></i></a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>

</html>