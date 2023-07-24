<?php
require_once('connection.php');

if(!empty($_GET['id'])){
    $sql = "SELECT * FROM students WHERE id=".$_GET['id'];
    $ssr = mysqli_query($conn,$sql);
    $data = mysqli_fetch_assoc($ssr);
    $image = $data['image'];
    if(!empty($image)){
        unlink('uploads/'.$image);
    }

    $delete = "DELETE FROM students WHERE id =".$_GET['id'];
    $res = mysqli_query($conn,$delete);

    if($res){
        $_SESSION['success'] = "Data was deleted";
        header('Location:index.php');
        exit();
    }
    else{
        $_SESSION['success'] = "Data not deleted";
        header('Location:index.php');
        exit();
    }
}
else{
    $_SESSION['error'] = "Invalid ID";
    header('Location:index.php');
    exit();
}
?>