<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<style>
    #ques {
        min-height: 433px;
     }
</style>
<title>ADK92 - Threads</title>
</head>

<body>

    <?php include 'partials/dbconnect.php'; ?>
    <?php include 'partials/header.php'; ?>
    

    <!-- menggabungkan semua kolom kategori dan menyesuaikan id dengan thread forum-->

    <?php 
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` WHERE category_id=$id";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $catname = $row['category_name'];
        $catdesc = $row['category_description'];
    }
    ?>

    <?php
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    if($method == 'POST'){
        $th_title = $_POST['title'];
        $th_desc = $_POST['desc'];

        $th_title = str_replace("<", "&lt;", "$th_title");
        $th_title = str_replace(">", "&gt;", "$th_title");

        $th_desc = str_replace("<", "&lt;", "$th_desc");
        $th_desc = str_replace(">", "&gt;", "$th_desc");

        $sno = $_POST['sno'];
        $sql = "INSERT INTO `threads`(`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ('$th_title', '$th_desc', '$id', $sno, current_timestamp())";
        $result = mysqli_query($conn, $sql);
        $showAlert = true;
        if($showAlert){
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Selamat!</strong> Pertanyaan anda sudah dimuat, silakan menunggu seseorang untuk membalas pertanyaan anda.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>';
        }
    }
    ?>


     
    <div class="container my-4">
            <div class="jumbotron">
            <h1 class="display-4">Selamat Datang di Forum <?php echo $catname;?></h1>
            <p class="lead"><?php echo $catdesc;?></p>
            <hr class="my-4">
            <p>Forum ini bertujuan untuk sharing informasi sekaligus tempat diskusi. Peraturan Forum : Tidak melakukan perbuatan haram dan merusak.</p>
            <a class="btn btn-primary btn-lg" href="#" role="button">Learn More</a>
         </div>
    </div>

    <?php

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo '<div class="container">
                    <h1 class="py-2">Tulis Topikmu</h1>
                    <form action="' . $_SERVER['REQUEST_URI'] .'" method="post">
                        <div class="form-group">
                            <label for="title">Judul Topik</label>
                            <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
                            <small id="emailHelp" class="form-text text-muted">Isi judul ini sesuai dengan apa yang ingin kamu bahas.</small>
                        </div>
                        <input type="hidden" name="sno" value="'. $_SESSION["sno"] .'">
                        <div class="form-group">
                            <label for="desc">Isi Teks</label>
                            <textarea class="form-control" id="desc" name="desc" cols="60" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
             </div>';
    }
    else {
        echo '<div class="container">
            <h1 class="py-2">Tulis Topikmu</h1>
            <p class="lead">Info : Kamu belum masuk menjadi pengguna di forum ini. Silakan masuk ke forum ini jika ingin memulai topik.</p>
        </div>';
    }

    ?>

    <div class="container" id="ques">
        <h1 class="py-2">Daftar Topik</h1>
        <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `threads` WHERE thread_cat_id=$id";
        $result = mysqli_query($conn, $sql);
        $noResult = true;
        while($row = mysqli_fetch_assoc($result)){
            $noResult = false;
            $id = $row['thread_id'];
            $title = $row['thread_title'];
            $desc = $row['thread_desc'];
            $thread_time = $row['timestamp'];
            $thread_user_id = $row['thread_user_id'];
            $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);

        echo'<div class="media my-3">
                <img src="img/user-1.png" width="64px;" class="mr-3" alt="...">
                <div class="media-body">' . '<h5 class="mt-0"><a href="thread.php?threadid='. $id .'" class="text-dark">'. $title .'</a></h5>
                '. $desc .' </div>' . '<div class="font-weight-bold">Ditulis oleh :'. $row2['user_email'] .' at '. $thread_time .' </div>' . '</div>';
        }

        if($noResult){
            echo '<div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h1 class="display-4">Tidak ada pertanyaan yang tersedia</h1>
                        <p class="lead">Jadilah orang pertama yang bertanya atau berdiskusi.</p>
                    </div>
                    </div>';
        }
        ?>

    </div>

    <?php include 'partials/footer.php'; ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
</body>
</html>