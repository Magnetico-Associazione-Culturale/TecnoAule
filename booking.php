<?php
require_once 'config/init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$page_title = 'Prenota la tua aula - TecnoAule';
include 'includes/header.php';

$db = new Database();
$pdo = $db->connect();

// Get available rooms
$stmt = $pdo->query("SELECT * FROM rooms ORDER BY name");
$rooms = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mb-4">Prenota la tua aula</h1>
            <p class="text-muted mb-5">Scegli l'aula perfetta per le tue esigenze e prenota facilmente online.</p>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="form-section">
                <h3 class="mb-4">Dettagli prenotazione</h3>
                <form id="bookingForm">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="booking_date" class="form-label">Data</label>
                            <input type="date" class="form-control" id="booking_date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="start_time" class="form-label">Ora inizio</label>
                            <input type="time" class="form-control" id="start_time" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="end_time" class="form-label">Ora fine</label>
                            <input type="time" class="form-control" id="end_time" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tessera_number" class="form-label">Numero tessera (opzionale)</label>
                            <input type="text" class="form-control" id="tessera_number" placeholder="es. 0001" maxlength="10">
                            <small class="form-text text-muted">Inserisci il numero tessera per ottenere lo sconto associato</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-orange" onclick="filterRooms()">
                        <i class="fas fa-search me-2"></i>Cerca aule disponibili
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Available Rooms -->
    <div class="row" id="roomsContainer">
        <?php foreach ($rooms as $room): ?>
            <div class="col-lg-6 room-item" data-room-id="<?php echo $room['id']; ?>">
                <div class="room-card">
                    <div class="room-image">
                        <?php if (!empty($room['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($room['image_url']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" class="room-photo">
                        <?php else: ?>
                            <i class="fas fa-door-open"></i>
                        <?php endif; ?>
                    </div>
                    <div class="room-details">
                        <h4 class="mb-2"><?php echo htmlspecialchars($room['name']); ?></h4>
                        <p class="text-muted text-truncate-2"><?php echo htmlspecialchars($room['description']); ?></p>
                        
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users text-orange me-2"></i>
                            <span>Fino a <?php echo $room['capacity']; ?> persone</span>
                        </div>

                        <?php if ($room['equipment']): ?>
                            <div class="equipment-badges">
                                <?php 
                                // Parse PostgreSQL array format
                                $equipment = str_replace(['{', '}'], '', $room['equipment']);
                                $equipmentList = explode(',', $equipment);
                                foreach ($equipmentList as $item): 
                                    $item = trim($item);
                                    if ($item):
                                        $displayName = str_replace('_', ' ', ucfirst($item));
                                ?>
                                    <span class="equipment-badge"><?php echo htmlspecialchars($displayName); ?></span>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="price-display">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>€<?php echo number_format($room['price_base'], 2); ?></strong>
                                    <small class="opacity-75">/ora</small>
                                </div>
                                <button class="btn btn-light btn-sm" onclick="selectRoom(<?php echo $room['id']; ?>, '<?php echo htmlspecialchars($room['name']); ?>', <?php echo $room['price_base']; ?>)">
                                    Seleziona
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- No rooms message -->
    <div id="noRoomsMessage" class="text-center py-5" style="display: none;">
        <i class="fas fa-search text-muted mb-3" style="font-size: 3rem;"></i>
        <h4 class="text-muted">Nessuna aula disponibile</h4>
        <p class="text-muted">Prova a modificare la data o l'orario della prenotazione.</p>
    </div>
</div>

<!-- Booking Confirmation Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conferma prenotazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="bookingDetails"></div>
                <div class="price-display mt-3">
                    <div class="d-flex justify-content-between">
                        <span>Prezzo totale:</span>
                        <strong id="totalPrice">€0.00</strong>
                    </div>
                    <div id="discountInfo" class="mt-2" style="display: none;">
                        <small class="text-success">
                            <i class="fas fa-percent me-1"></i>
                            Sconto socio applicato (100%)
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-orange" id="confirmBooking">
                    <span class="spinner" id="bookingSpinner" style="display: none;"></span>
                    Conferma prenotazione
                </button>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/booking.js"></script>

<?php include 'includes/footer.php'; ?>
