<?php
require_once 'config/init.php';
$page_title = 'TecnoAule - Prenota la tua aula nel Tecnopolo';
include 'includes/header.php';
?>

<div class="hero-section">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="display-5 fw-bold mb-3">Benvenuto in TecnoAule</h1>
            <p class="lead mb-4">Prenota la tua aula nel Tecnopolo<br>
            <small class="opacity-75">Un luogo dove le idee prendono forma</small></p>
            <div class="row justify-content-center mb-4">
                <div class="col-md-10">
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <span class="badge bg-white text-dark px-3 py-2">
                            <i class="fas fa-building me-1"></i>1500 mq di spazio
                        </span>
                        <span class="badge bg-white text-dark px-3 py-2">
                            <i class="fas fa-laptop me-1"></i>Tecnologie avanzate
                        </span>
                        <span class="badge bg-white text-dark px-3 py-2">
                            <i class="fas fa-percent me-1"></i>Sconti per associati
                        </span>
                    </div>
                </div>
            </div>
            <div class="hero-cta">
                <a href="/booking.php" class="btn btn-white btn-lg me-3 mb-2">
                    <i class="fas fa-calendar-plus me-2"></i>Prenota Subito
                </a>
                <a href="/virtual-tour.php" class="btn btn-outline-light btn-lg mb-2">
                    <i class="fas fa-vr-cardboard me-2"></i>Virtual Tour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="main-actions">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="mb-3">Prenota un'aula</h3>
                    <p class="text-muted mb-4">Scegli tra le nostre aule moderne e prenota facilmente online. Sconti automatici per i soci con tessera.</p>
                    <a href="/booking.php" class="btn btn-orange btn-lg px-4">
                        Inizia a prenotare <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="mb-3">Mappa interattiva</h3>
                    <p class="text-muted mb-4">Esplora il Tecnopolo e scopri la posizione delle aule all'interno del nostro complesso di 1500 mq.</p>
                    <a href="/map.php" class="btn btn-outline-orange btn-lg px-4">
                        Visualizza mappa <i class="fas fa-map ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto text-center">
            <h2 class="mb-4">Perché scegliere TecnoAule?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="action-icon mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5>Spazi collaborativi</h5>
                        <p class="text-muted">Aule pensate per il lavoro di gruppo e la collaborazione</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="action-icon mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <h5>Tecnologia avanzata</h5>
                        <p class="text-muted">Proiettori, lavagne interattive e connessioni ad alta velocità</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="action-icon mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <i class="fas fa-percent"></i>
                        </div>
                        <h5>Sconti per soci</h5>
                        <p class="text-muted">100% di sconto per i soci Magnetico con tessera valida</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Come raggiungere il Tecnopolo Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5">
                <div class="action-icon mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h2 class="mb-3">Come raggiungere il Tecnopolo</h2>
                <p class="text-muted">Trova facilmente la strada per il nostro complesso di 1500 mq</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="map-container bg-white rounded shadow-sm p-3">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3273.7567!2d14.6256474!3d37.6555295!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x131135004bd582e3%3A0xcdd4146a12d3cf67!2sTecnopolo%20Magnetico!5e0!3m2!1sit!2sit!4v1625745600000!5m2!1sit!2sit" 
                        width="100%" 
                        height="400" 
                        style="border:0; border-radius: 8px;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="text-center mt-4">
                    <a href="https://www.google.it/maps/place/Tecnopolo+Magnetico/@37.6555295,14.6256474,689m/data=!3m2!1e3!4b1!4m6!3m5!1s0x131135004bd582e3:0xcdd4146a12d3cf67!8m2!3d37.6555295!4d14.6282223!16s%2Fg%2F11yf2nsc88?hl=it&entry=ttu&g_ep=EgoyMDI1MDcwOC4wIKXMDSoASAFQAw%3D%3D" 
                       target="_blank" 
                       class="btn btn-orange">
                        <i class="fas fa-external-link-alt me-2"></i>Apri in Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!isset($_SESSION['user_id'])): ?>
<div class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="mb-2">Pronto a prenotare?</h3>
                <p class="text-muted mb-0">Registrati per accedere alle prenotazioni e gestire le tue aule.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="/register.php" class="btn btn-orange me-2">Registrati</a>
                <a href="/login.php" class="btn btn-outline-orange">Accedi</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
