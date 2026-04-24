<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TechUrbis | Cidade Inteligente</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS Extra -->
    <link rel="stylesheet" href="styles/style.css">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SEO -->
    <meta name="description" content="TechUrbis - soluções inteligentes para cidades modernas e sustentáveis.">
    <meta name="author" content="TechUrbis">

    <link rel="icon" type="image/png" href="Img/logo.png">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .icon-box {
            font-size: 3rem;
            color: #0d6efd;
        }
        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top navbar-transparent">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="Img/logo.png" alt="Logo TechUrbis" width="40" class="me-2">
            TechUrbis
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Upgrades</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contacts</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="login.html">
                        <img src="Img/IconUser.png" alt="Login"
                            width="28" height="28">
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- CAROUSEL -->
<section class="p-0 m-0"><div id="cityCarousel" class="carousel slide shadow overflow-hidden" data-bs-ride="carousel" data-bs-interval="3000">

    <!-- Indicadores -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#cityCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#cityCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#cityCarousel" data-bs-slide-to="2"></button>
    </div>

        <!-- Imagens -->
    <div class="carousel-item active">
        <img src="Img/Img1.jpeg" class="d-block w-100" alt="Cidade inteligente moderna">
    </div>

    <div class="carousel-item">
        <img src="Img/Img2.jpeg" class="d-block w-100" alt="Iluminação pública inteligente">
    </div>

    <div class="carousel-item">
        <img src="Img/Img3.jpeg" class="d-block w-100" alt="Estacionamento inteligente">
    </div>

    <div class="carousel-caption custom-caption text-center">

        <div class="hero-content">
            <h1 class="fw-bold display-1">Smart City</h1>
            <a href="#features" class="btn btn-primary btn-lg mt-3">
                Learn more
            </a>
        </div>
    

        <!-- Controlos -->
        <button class="carousel-control-prev" type="button" data-bs-target="#cityCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#cityCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>
</section>

<!-- SOBRE -->
<section id="about" class="bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center">About</h2>
        <p class="text-center mb-5">
            TechUrbis develops technological solutions that make cities smarter,
            more efficient and sustainable.
        </p>

       <h3 class="text-center mb-4">Our Team</h3>

<div class="row g-4 justify-content-center">
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <img src="img/joao.png" class="img-fluid rounded-circle mb-3" style="width: 120px;" alt="João Nicolau">
                <h6 class="card-title">João Nicolau</h6>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <img src="img/rodrigo.png" class="img-fluid rounded-circle mb-3" style="width: 120px;" alt="Rodrigo Gomes">
                <h6 class="card-title">Rodrigo Gomes</h6>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <img src="img/esther.png" class="img-fluid rounded-circle mb-3" style="width: 120px;" alt="Esther Pereira">
                <h6 class="card-title">Esther Pereira</h6>
            </div>
        </div>
    </div>

</div>

<!-- MELHORAMENTOS -->
<section id="features" class="py-5">
    <div class="container">
        <h2 class="section-title text-center">What we will improve in this city</h2>

        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">🗑️ Smart Waste Bins</h5>
                        <p class="card-text">
                            We are creating smart waste bins with integrated sensors that allow monitoring the fill level.
                            When the bin is full, an automatic notification is sent to the waste management system,
                            enabling faster and more efficient collection. This solution reduces unnecessary collections, prevents overflow
                            of waste bins, keeps public spaces cleaner and improves overall waste management.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">💡 Smart Streetlights</h5>
                        <p class="card-text">
                            We are developing smart streetlights, equipped with motion detection sensors.
                            These sensors identify the presence of people, vehicles or other objects and automatically turn on the lighting
                            to ensure greater safety and visibility. When no movement is detected, the light intensity decreases or
                            turns off, allowing energy savings and more efficient electricity usage.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">🅿️ Smart Parking Lots</h5>
                        <p class="card-text">
                            We are developing smart parking lots that use advanced sensors to monitor each parking space.
                            These sensors detect whether a space is occupied or available and send this information in real-time to a central system.
                            This allows users to find parking more quickly, reduces the time spent searching for a spot, and contributes to better traffic and parking management.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTEÚDO -->
<main class="container my-5">

    <!-- LINGUAGENS -->
    <section class="mb-5">
        <h2 class="text-center mb-4">💻 Programming Languages</h2>
        <div class="row g-4">
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-code icon-box"></i><h5 class="mt-3">C</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-code icon-box"></i><h5 class="mt-3">C++</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-filetype-html icon-box"></i><h5 class="mt-3">HTML</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-filetype-css icon-box"></i><h5 class="mt-3">CSS</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-filetype-js icon-box"></i><h5 class="mt-3">JavaScript</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-filetype-java icon-box"></i><h5 class="mt-3">Java</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-filetype-sql icon-box"></i><h5 class="mt-3">SQL</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-filetype-php icon-box"></i><h5 class="mt-3">PHP</h5></div></div>
        </div>
    </section>

    <!-- SOFTWARES -->
    <section>
        <h2 class="text-center mb-4">🛠️ Softwares & Tools</h2>
        <div class="row g-4">
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-git icon-box"></i><h5 class="mt-3">Git</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-github icon-box"></i><h5 class="mt-3">GitHub</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-window icon-box"></i><h5 class="mt-3">VS Code</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-database icon-box"></i><h5 class="mt-3">MySQL</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-database icon-box"></i><h5 class="mt-3">Laragon</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-badge-3d icon-box"></i><h5 class="mt-3">Blender</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-android icon-box"></i><h5 class="mt-3">Android Studio</h5></div></div>
            <div class="col-md-4 col-lg-3"><div class="card text-center p-4 shadow-sm"><i class="bi bi-infinity icon-box"></i><h5 class="mt-3">Arduino IDE</h5></div></div>
        </div>
    </section>
</main>



<!-- CONTACTOS -->
<section id="contact" class="py-5">
    <div class="container">
        <h2 class="section-title text-center">Contact Us</h2>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <select class="form-select" required>
                            <option selected disabled>Choose a subject</option>
                            <option>Smart Containers</option>
                            <option>Smart Lighting</option>
                            <option>Smart Parking</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4" required></textarea>
                    </div>

                    <button class="btn btn-primary w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-light text-center py-3">
    <p class="mb-1">&copy; 2025 TechUrbis</p>
    <small>Smart and Sustainable City</small>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="scripts/scripts.js"></script>

</body>
</html>
