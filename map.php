<?php
require_once 'config/init.php';
$page_title = 'Mappa delle aule - TecnoAule';
include 'includes/header.php';
?>

<style>
/* Stili per le stanze SVG */
.room-svg {
    fill: #fef08a; /* Giallo chiaro */
    stroke: #ca8a04; /* Bordo giallo scuro */
    stroke-width: 2;
    cursor: pointer;
    transition: fill 0.3s ease;
}
.room-svg:hover {
    fill: #facc15; /* Giallo più intenso al passaggio del mouse */
}
.room-svg.selected {
    fill: var(--orange-primary); /* Arancione TecnoAule quando selezionato */
    stroke: #b45309;
}
.room-label {
    font-family: 'Inter', Arial, sans-serif;
    font-size: 10px;
    font-weight: 600;
    fill: #422006; /* Colore del testo scuro */
    pointer-events: none; /* Rende il testo non cliccabile */
    text-anchor: middle; /* Centra il testo */
    dominant-baseline: middle;
}

/* Fix map container height */
.map-container {
    min-height: 350px;
    max-height: 400px;
    overflow: hidden;
}

/* Room list improvements */
.room-list-item.active {
    background-color: var(--orange-primary) !important;
    color: white !important;
}

.room-list-item.active .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

.room-list-item.active .badge {
    background-color: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

/* Room details card improvements */
.room-details-card {
    max-height: 550px;
    overflow-y: auto;
}

.room-image-container {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.room-image-container img {
    border: 2px solid var(--orange-primary);
}
</style>

<?php

$db = new Database();
$pdo = $db->connect();

// Get all rooms with their positions
$stmt = $pdo->query("SELECT * FROM rooms ORDER BY name");
$rooms = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mb-4">Mappa del Tecnopolo</h1>
            <p class="text-muted mb-5">Esplora la disposizione delle aule all'interno del nostro complesso di 1500 mq.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="map-container bg-light p-3 rounded border" style="height: fit-content;">
                <svg id="floor-plan" viewBox="0 0 800 300" class="w-100" style="height: 350px; max-height: 400px;" preserveAspectRatio="xMidYMid meet">
                    <!-- Perimetro approssimativo dell'edificio -->
                    <rect x="1" y="1" width="798" height="298" fill="none" stroke="#d1d5db" stroke-width="2" />

                    <!-- Aule cliccabili - Rimosso Lab 1 PERLMAN che non esiste nel database -->
                    
                    <!-- Lab DIJKSTRA -->
                    <rect id="lab2" data-name="Laboratorio Dijkstra" data-room-id="7" class="room-svg" x="10" y="160" width="120" height="120" rx="5" />
                    <text x="70" y="220" class="room-label">Lab<tspan x="70" dy="15">DIJKSTRA</tspan></text>

                    <!-- Aula TURING -->
                    <rect id="aula1" data-name="Aula Alan Turing" data-room-id="5" class="room-svg" x="140" y="160" width="120" height="120" rx="5" />
                     <text x="200" y="220" class="room-label">Aula<tspan x="200" dy="15">TURING</tspan></text>

                    <!-- Aula BERNERS-LEE -->
                    <rect id="aula2" data-name="Aula Berners-Lee" data-room-id="6" class="room-svg" x="270" y="160" width="120" height="120" rx="5" />
                    <text x="330" y="220" class="room-label">Aula<tspan x="330" dy="15">BERNERS-LEE</tspan></text>

                    <!-- COWORKING -->
                    <rect id="coworking" data-name="Spazio Coworking" data-room-id="9" class="room-svg" x="400" y="160" width="210" height="120" rx="5" />
                    <text x="505" y="220" class="room-label">COWORKING</text>

                    <!-- AULA CONFERENZA -->
                    <rect id="conferenza" data-name="Sala Conferenze" data-room-id="8" class="room-svg" x="620" y="160" width="170" height="120" rx="5" />
                    <text x="705" y="220" class="room-label">SALA<tspan x="705" dy="15">CONFERENZE</tspan></text>
                </svg>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="form-section mb-4">
                <h4 class="mb-3">Informazioni aula</h4>
                <div id="roomInfo" class="text-center text-muted">
                    <i class="fas fa-mouse-pointer mb-3" style="font-size: 2rem;"></i>
                    <p>Clicca su un'aula nella mappa per vedere i dettagli</p>
                </div>
            </div>

            <div class="form-section">
                <h4 class="mb-3">Elenco aule</h4>
                <div class="list-group">
                    <?php foreach ($rooms as $room): ?>
                        <div class="list-group-item list-group-item-action room-list-item" 
                             data-room-id="<?php echo $room['id']; ?>"
                             onclick="highlightRoom(<?php echo $room['id']; ?>); showRoomDetails(<?php echo htmlspecialchars(json_encode($room)); ?>)">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($room['name']); ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i><?php echo $room['capacity']; ?> persone
                                    </small>
                                </div>
                                <span class="badge bg-orange">€<?php echo number_format($room['price_base'], 0); ?>/h</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/map.js"></script>

<?php include 'includes/footer.php'; ?>
