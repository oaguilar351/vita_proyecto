<?php include 'layouts/session.php'; ?>
<?php include "libs/db.class.php"; ?>
<?php include "libs/db_login.class.php"; ?>
<?php include 'layouts/head-main.php'; ?>
    <head>
        
        <title>Login | VitaInsumos</title>
        <?php include 'layouts/title-meta.php'; ?>

        <?php include 'layouts/head-css.php'; ?>
		</script>
    </head>

    <?php include 'layouts/body.php'; ?>

        <!-- auth-page wrapper -->
        <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
            <div class="bg-overlay"></div>
            <!-- auth-page content -->
            <div class="auth-page-content overflow-hidden pt-lg-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card overflow-hidden">
                                <div class="row g-0">
                                    <div class="col-lg-6" style="border:1px solid #CECECE;">
                                        <div class="p-lg-5 p-4 auth-one-bg h-100">
                                            <!--<div class="bg-overlay"></div>-->
                                            <div class="position-relative h-100 d-flex flex-column">
                                                <div class="mb-4" align="center">
                                                    <!--<a href="index.php" class="d-block">
                                                        <img src="assets/images/logoH.png" alt="" height="160">
                                                    </a>-->
													<!--<span style="color:#FFFFFF; font-size:64px; margin:auto; font-family:Courier New; font-weight:bold;">VitaInsumos</span>-->
                                                </div>
                                                <div class="mt-auto">
                                                    <!--<div class="mb-3">
                                                        <i class="ri-double-quotes-l display-4 text-success"></i>
                                                    </div>-->

                                                    <!--<div id="qoutescarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                                        <div class="carousel-indicators">
                                                            <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="0" class="active"
                                                                aria-current="true" aria-label="Slide 1"></button>
                                                            <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="1"
                                                                aria-label="Slide 2"></button>
                                                            <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="2"
                                                                aria-label="Slide 3"></button>
                                                        </div>
                                                        <div class="carousel-inner text-center text-white pb-5">
                                                            <div class="carousel-item active">
                                                                <p class="fs-15 fst-italic">" Great! Clean code, clean design, easy for customization."</p>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <p class="fs-15 fst-italic">" The theme is really great with an amazing customer support."</p>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <p class="fs-15 fst-italic">" Great! Clean code, clean design, easy for customization."</p>
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                    <!-- end carousel -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->

                                    <div class="col-lg-6" style="border:1px solid #CECECE; background-image: linear-gradient(to bottom right, #689f38, #EEEEEE);">
                                        <div class="p-lg-5 p-4">
                                            <div>
                                                <h5 style="color:#d7dbdd;">Bienvenido</h5>
												<p id="msg_login" name="msg_login" style="color:#d7dbdd;">Favor de inicia sesión para continuar.</p>
                                            </div>
            
                                            <div class="mt-4">
                                               <form action="login.php" method="post" onSubmit="return EW_checkMyForm(this);">
                    
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label" style="color:#d7dbdd;">Usuario</label>
                                                        <input type="text" class="form-control" id="userid" name="userid" placeholder="Ingresar usuario">
                                                    </div>
                            
                                                    <div class="mb-3">
                                                        <!--<div class="float-end">
                                                            <a href="auth-pass-reset-cover.php" class="text-muted">Contraseña</a>
                                                        </div>-->
                                                        <label class="form-label" for="password-input" style="color:#d7dbdd;">Contraseña</label>
                                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                                            <input type="password" class="form-control pe-5" placeholder="Ingresar contraseña" id="passwd" name="passwd">
                                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                        </div>
                                                    </div>
                            
                                                    <!--<div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                                        <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                                    </div>-->
                                                    
                                                    <div class="mt-4" align="right">
														<input class="btn btn-secondary w-25" style="background-color:#5d6d7e; border:#CECECE;" type="button" id="solicitar" name="solicitar" value="Registrar">
														<input class="btn btn-success w-25" style="background-color:#689f38; border:#CECECE;" type="button" id="submit" name="submit" value="Ingresar">
                                                        <!--<button class="btn btn-success w-100" type="submit" name="submit">Ingresar</button>-->
                                                    </div>
                        
                                                    <!--<div class="mt-4 text-center">
                                                        <div class="signin-other-title">
                                                            <h5 class="fs-13 mb-4 title">Sign In with</h5>
                                                        </div>
        
                                                        <div>
                                                            <button type="button" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-facebook-fill fs-16"></i></button>
                                                            <button type="button" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-google-fill fs-16"></i></button>
                                                            <button type="button" class="btn btn-dark btn-icon waves-effect waves-light"><i class="ri-github-fill fs-16"></i></button>
                                                            <button type="button" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-twitter-fill fs-16"></i></button>
                                                        </div>
                                                    </div>-->
                
                                                </form>
                                            </div>
    
                                            <!--<div class="mt-5 text-center">
                                                <p class="mb-0">Don't have an account ? <a href="auth-signup-cover.php" class="fw-semibold text-primary text-decoration-underline"> Signup</a> </p>
                                            </div>-->
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <!-- end row -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
    
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end auth page content -->

            <!-- footer -->
            <footer class="footer start-0">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <p class="mb-0">&copy; <script>document.write(new Date().getFullYear())</script> ViaInsumos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>
        <!-- end auth-page-wrapper -->

        <?php include 'layouts/vendor-scripts.php'; ?>

        <!-- password-addon init -->
        <script src="js/login.js"></script>
    </body>

</html>