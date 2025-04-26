<?php
session_start();

include 'includes/navbar.php';
include 'config/db_connect.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .carousel-item img {
            height: 500px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center"></h2>
        
        <div id="carouselExampleIndicators" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/images/kidszone1.jpg" class="d-block w-100" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/kidszone2.jpg" class="d-block w-100" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/kidszone3.jpg" class="d-block w-100" alt="Slide 3">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/kidszone4.jpg" class="d-block w-100" alt="Slide 4">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        
        <div class="text-center mb-4">
            <a href="#" class="btn btn-success">COME JOIN US FOR FUN</a>
        </div>


	<div class="container mt-5">


	   <div class="row g-4">
        <!--This code is for Thumbnail 1 -->
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <img src="assets/images/tile1.jpg" class="card-img-top img-fluid" alt="Thumbnail 1">
                <div class="card-body">
                    <i class="bi bi-camera fs-1 text-primary mb-2"></i>
                    <h5 class="card-title">Education</h5>
                    <p class="card-text">"Learning is not a product of schooling, but the lifelong process of discovering." - Albert Einstein</p>
                    <a href="#" class="btn btn-primary">View More</a>
                </div>
            </div>
        </div>
        
        <!--This code is for Thumbnail 2 -->
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <img src="assets/images/tile2.jpg" class="card-img-top img-fluid" alt="Thumbnail 2">
                <div class="card-body">
                    <i class="bi bi-music-note-beamed fs-1 text-success mb-2"></i>
                    <h5 class="card-title">Music</h5>
                    <p class="card-text">Discover the rhythm of life with our music courses. Learn Guitar - Piano - Drum ....</p>
                    <a href="#" class="btn btn-success">View More</a>
                </div>
            </div>
	</div>

	      
        <!--This code is for Thumbnail 3 -->
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <img src="assets/images/tile3.jpg" class="card-img-top img-fluid" alt="Thumbnail 3">
                <div class="card-body">
                    <i class="bi bi-palette fs-1 text-danger mb-2"></i>
                    <h5 class="card-title">Art</h5>
                    <p class="card-text">Express your creativity with our professional art workshops. Unlock the potential in your kids.</p>
                    <a href="#" class="btn btn-danger">View More</a>
                </div>
            </div>
        </div>
        
        <!--This code is for Thumbnail 4 -->
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <img src="assets/images/tile4.jpg" class="card-img-top img-fluid" alt="">
                <div class="card-body">
                    <i class="bi bi-laptop fs-1 text-warning mb-2"></i>
                    <h5 class="card-title">Technology</h5>
                    <p class="card-text">Stay ahead with our cutting-edge technology courses and training. Build the imaginable.</p>
                    <a href="#" class="btn btn-warning text-white">View More</a>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>

<?php include 'includes/footer.php'; ?>


</html>
