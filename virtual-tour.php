<?php
require_once 'config/init.php';
$page_title = 'Virtual Tour del Tecnopolo - TecnoAule';
include 'includes/header.php';
?>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-12 text-center mb-4">
            <h1 class="mb-3">Virtual Tour del Tecnopolo</h1>
            <p class="lead text-muted">Esplora virtualmente i nostri spazi di 1500 mq</p>
        </div>
    </div>

    <!-- Virtual Tour Interface -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="virtual-tour-container">
                <div class="tour-viewer bg-light rounded">
                    <div class="tour-placeholder d-flex align-items-center justify-content-center">
                        <div class="text-center p-5">
                            <i class="fas fa-vr-cardboard text-orange mb-3" style="font-size: 4rem;"></i>
                            <h3 class="mb-3">Virtual Tour Interattivo</h3>
                            <p class="text-muted mb-4">Esplora ogni angolo del Tecnopolo con il nostro tour virtuale immersivo</p>
                            <div class="tour-controls">
                                <button class="btn btn-orange btn-lg me-3" onclick="startTour()">
                                    <i class="fas fa-play me-2"></i>Inizia il Tour
                                </button>
                                <button class="btn btn-outline-orange btn-lg" onclick="showRoomsList()">
                                    <i class="fas fa-list me-2"></i>Lista Aule
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tour Navigation -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="tour-navigation">
                <h4 class="mb-3">Naviga per Aree</h4>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="tour-area-card" onclick="goToArea('ingresso')">
                            <div class="area-icon">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <h6>Ingresso</h6>
                            <p class="text-muted">Area di accoglienza</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="tour-area-card" onclick="goToArea('aula-innovation')">
                            <div class="area-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h6>Aula Innovation</h6>
                            <p class="text-muted">Spazio creativo</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="tour-area-card" onclick="goToArea('sala-meeting')">
                            <div class="area-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h6>Sala Meeting</h6>
                            <p class="text-muted">Riunioni professionali</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="tour-area-card" onclick="goToArea('coworking')">
                            <div class="area-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <h6>Spazio Coworking</h6>
                            <p class="text-muted">Lavoro collaborativo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Highlights -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <h4 class="mb-4">Caratteristiche del Tecnopolo</h4>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <h6>Connessione Ultra-Veloce</h6>
                        <p class="text-muted">WiFi 6 e connessione fibra ottica per massime prestazioni</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h6>Tecnologie Avanzate</h6>
                        <p class="text-muted">Lavagne interattive, proiettori 4K e sistemi audio professionali</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h6>Ambiente Sostenibile</h6>
                        <p class="text-muted">Climatizzazione intelligente e illuminazione LED eco-friendly</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="cta-section bg-light rounded p-5">
                <h4 class="mb-3">Pronto a prenotare?</h4>
                <p class="text-muted mb-4">Scegli l'aula perfetta per le tue esigenze</p>
                <div class="cta-buttons">
                    <a href="/booking.php" class="btn btn-orange btn-lg me-3">
                        <i class="fas fa-calendar-plus me-2"></i>Prenota Ora
                    </a>
                    <a href="/map.php" class="btn btn-outline-orange btn-lg">
                        <i class="fas fa-map me-2"></i>Vedi Mappa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function startTour() {
    // Placeholder for tour functionality
    alert('Il tour virtuale sarà disponibile a breve. Per ora puoi esplorare la mappa interattiva!');
    window.location.href = '/map.php';
}

function showRoomsList() {
    window.location.href = '/map.php';
}

function goToArea(area) {
    // Placeholder for area navigation
    alert(`Navigando verso: ${area}. Funzionalità in via di sviluppo.`);
}

// Add some interactive elements
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to tour area cards
    const areaCards = document.querySelectorAll('.tour-area-card');
    areaCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>