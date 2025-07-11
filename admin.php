<?php
require_once 'config/init.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: /login.php');
    exit;
}

$page_title = 'Pannello Amministratore - TecnoAule';
include 'includes/header.php';

$db = new Database();
$pdo = $db->connect();

// Get stats
$stmt = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'");
$pendingBookings = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_date >= CURRENT_DATE");
$upcomingBookings = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM rooms");
$totalRooms = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM registered_users");
$totalMembers = $stmt->fetchColumn();

// Get recent bookings with new structure
$stmt = $pdo->query("
    SELECT b.*, r.name as room_name, ru.name as member_name, t.tessera_number, ru.email as member_email, ru.phone as member_phone
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    LEFT JOIN registered_users ru ON b.user_id = ru.id 
    LEFT JOIN tessere t ON ru.tessera_id = t.id 
    ORDER BY b.created_at DESC 
    LIMIT 10
");
$recentBookings = $stmt->fetchAll();

// Get all rooms
$stmt = $pdo->query("SELECT * FROM rooms ORDER BY name");
$rooms = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mb-4">Pannello Amministratore</h1>
            <p class="text-muted mb-5">Gestisci prenotazioni, aule e utenti del sistema TecnoAule.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card text-center">
                <div class="action-icon mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-orange"><?php echo $pendingBookings; ?></h3>
                <p class="text-muted mb-0">Prenotazioni in attesa</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card text-center">
                <div class="action-icon mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="text-orange"><?php echo $upcomingBookings; ?></h3>
                <p class="text-muted mb-0">Prenotazioni future</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card text-center">
                <div class="action-icon mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fas fa-door-open"></i>
                </div>
                <h3 class="text-orange"><?php echo $totalRooms; ?></h3>
                <p class="text-muted mb-0">Aule totali</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card text-center">
                <div class="action-icon mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-orange"><?php echo $totalMembers; ?></h3>
                <p class="text-muted mb-0">Utenti registrati</p>
            </div>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="admin-section">
                <h4 class="mb-3">Azioni rapide</h4>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-orange w-100" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                            <i class="fas fa-plus me-2"></i>Aggiungi aula
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-orange w-100" onclick="showSection('bookings')">
                            <i class="fas fa-list me-2"></i>Gestisci prenotazioni
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-orange w-100" onclick="showSection('rooms')">
                            <i class="fas fa-cog me-2"></i>Gestisci aule
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-secondary w-100" onclick="location.reload()">
                            <i class="fas fa-refresh me-2"></i>Aggiorna
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="row">
        <div class="col-lg-12">
            <div class="admin-section" id="bookingsSection">
                <h4 class="mb-3">Prenotazioni recenti</h4>
                <?php if (empty($recentBookings)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="text-muted">Nessuna prenotazione</h5>
                        <p class="text-muted">Non ci sono prenotazioni da gestire al momento.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utente & Contatti</th>
                                    <th>Aula</th>
                                    <th>Data/Ora</th>
                                    <th>Prezzo</th>
                                    <th>Stato</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentBookings as $booking): ?>
                                    <tr>
                                        <td><?php echo $booking['id']; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($booking['member_name']); ?>
                                            <?php if ($booking['tessera_number']): ?>
                                                <br><small class="text-muted">Tessera: <?php echo $booking['tessera_number']; ?></small>
                                            <?php endif; ?>
                                            <?php if ($booking['member_email'] || $booking['member_phone']): ?>
                                                <br>
                                                <?php if ($booking['member_email']): ?>
                                                    <a href="mailto:<?php echo $booking['member_email']; ?>" class="text-primary" style="font-size: 0.8rem;">
                                                        <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($booking['member_email']); ?>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($booking['member_phone']): ?>
                                                    <br><a href="tel:<?php echo $booking['member_phone']; ?>" class="text-success" style="font-size: 0.8rem;">
                                                        <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($booking['member_phone']); ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($booking['booking_date'])); ?><br>
                                            <small class="text-muted">
                                                <?php echo date('H:i', strtotime($booking['start_time'])); ?> - 
                                                <?php echo date('H:i', strtotime($booking['end_time'])); ?>
                                            </small>
                                        </td>
                                        <td>€<?php echo number_format($booking['total_price'], 2); ?></td>
                                        <td>
                                            <span class="badge status-<?php echo $booking['status']; ?>">
                                                <?php echo ucfirst($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <?php if ($booking['status'] === 'pending'): ?>
                                                    <button class="btn btn-success" onclick="updateBookingStatus(<?php echo $booking['id']; ?>, 'approved')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-danger" onclick="updateBookingStatus(<?php echo $booking['id']; ?>, 'rejected')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-outline-primary" onclick="editBooking(<?php echo htmlspecialchars(json_encode($booking)); ?>)" title="Modifica prenotazione">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteBooking(<?php echo $booking['id']; ?>)" title="Elimina prenotazione">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Rooms Management -->
    <div class="row" id="roomsSection" style="display: none;">
        <div class="col-lg-12">
            <div class="admin-section">
                <h4 class="mb-3">Gestione aule</h4>
                <div class="row">
                    <?php foreach ($rooms as $room): ?>
                        <div class="col-lg-6 mb-4">
                            <div class="room-card">
                                <div class="room-image">
                                    <?php if (!empty($room['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($room['image_url']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" class="room-photo">
                                    <?php else: ?>
                                        <i class="fas fa-door-open"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="room-details">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0"><?php echo htmlspecialchars($room['name']); ?></h5>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="editRoom(<?php echo htmlspecialchars(json_encode($room)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteRoom(<?php echo $room['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-muted text-truncate-2"><?php echo htmlspecialchars($room['description']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-users text-orange me-1"></i><?php echo $room['capacity']; ?> persone</span>
                                        <span class="fw-bold">€<?php echo number_format($room['price_base'], 2); ?>/ora</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aggiungi nuova aula</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addRoomForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_name" class="form-label">Nome aula</label>
                            <input type="text" class="form-control" id="room_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="room_capacity" class="form-label">Capienza</label>
                            <input type="number" class="form-control" id="room_capacity" required min="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="room_description" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="room_description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_price" class="form-label">Prezzo base (€/ora)</label>
                            <input type="number" class="form-control" id="room_price" required min="0" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="room_image" class="form-label">URL Immagine</label>
                            <input type="url" class="form-control" id="room_image" placeholder="/assets/images/rooms/nome-aula.jpg">
                            <small class="form-text text-muted">Inserisci il percorso dell'immagine della stanza</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Attrezzature</label>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Proiettore" id="eq_proiettore">
                                    <label class="form-check-label" for="eq_proiettore">Proiettore</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Lavagna interattiva" id="eq_lavagna">
                                    <label class="form-check-label" for="eq_lavagna">Lavagna interattiva</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Aria condizionata" id="eq_aria">
                                    <label class="form-check-label" for="eq_aria">Aria condizionata</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Sistema audio" id="eq_audio">
                                    <label class="form-check-label" for="eq_audio">Sistema audio</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="custom_equipment" placeholder="Aggiungi attrezzatura personalizzata">
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCustomEquipment('add')">
                                    <i class="fas fa-plus me-1"></i>Aggiungi
                                </button>
                            </div>
                        </div>
                        <div id="equipment_list" class="mt-2"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-orange" onclick="saveRoom()">Salva aula</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifica aula</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editRoomForm">
                    <input type="hidden" id="edit_room_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_room_name" class="form-label">Nome aula</label>
                            <input type="text" class="form-control" id="edit_room_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_room_capacity" class="form-label">Capienza</label>
                            <input type="number" class="form-control" id="edit_room_capacity" required min="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_room_description" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="edit_room_description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_room_price" class="form-label">Prezzo base (€/ora)</label>
                            <input type="number" class="form-control" id="edit_room_price" required min="0" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_room_image" class="form-label">URL Immagine</label>
                            <input type="url" class="form-control" id="edit_room_image" placeholder="/assets/images/rooms/nome-aula.jpg">
                            <small class="form-text text-muted">Inserisci il percorso dell'immagine della stanza</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Attrezzature</label>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Proiettore" id="edit_eq_proiettore">
                                    <label class="form-check-label" for="edit_eq_proiettore">Proiettore</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Lavagna interattiva" id="edit_eq_lavagna">
                                    <label class="form-check-label" for="edit_eq_lavagna">Lavagna interattiva</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Aria condizionata" id="edit_eq_aria">
                                    <label class="form-check-label" for="edit_eq_aria">Aria condizionata</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Sistema audio" id="edit_eq_audio">
                                    <label class="form-check-label" for="edit_eq_audio">Sistema audio</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="edit_custom_equipment" placeholder="Aggiungi attrezzatura personalizzata">
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCustomEquipment('edit')">
                                    <i class="fas fa-plus me-1"></i>Aggiungi
                                </button>
                            </div>
                        </div>
                        <div id="edit_equipment_list" class="mt-2"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-orange" onclick="updateRoom()">Aggiorna aula</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Booking Modal -->
<div class="modal fade" id="editBookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifica prenotazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editBookingForm">
                    <input type="hidden" id="edit_booking_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_booking_date" class="form-label">Data</label>
                            <input type="date" class="form-control" id="edit_booking_date" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="edit_start_time" class="form-label">Ora inizio</label>
                            <input type="time" class="form-control" id="edit_start_time" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="edit_end_time" class="form-label">Ora fine</label>
                            <input type="time" class="form-control" id="edit_end_time" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_booking_room" class="form-label">Aula</label>
                            <select class="form-control" id="edit_booking_room" required>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?php echo $room['id']; ?>"><?php echo htmlspecialchars($room['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_booking_status" class="form-label">Stato</label>
                            <select class="form-control" id="edit_booking_status" required>
                                <option value="pending">In attesa</option>
                                <option value="approved">Approvata</option>
                                <option value="rejected">Rifiutata</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_total_price" class="form-label">Prezzo totale (€)</label>
                        <input type="number" class="form-control" id="edit_total_price" step="0.01" min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-orange" onclick="updateBooking()">Aggiorna prenotazione</button>
            </div>
        </div>
    </div>
</div>

<script>
function showSection(section) {
    document.getElementById('bookingsSection').style.display = section === 'bookings' ? 'block' : 'none';
    document.getElementById('roomsSection').style.display = section === 'rooms' ? 'block' : 'none';
}

async function updateBookingStatus(bookingId, status) {
    try {
        const formData = new FormData();
        formData.append('action', 'update_status');
        formData.append('booking_id', bookingId);
        formData.append('status', status);
        
        const response = await fetch('/api/bookings.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Errore durante l\'aggiornamento: ' + result.message);
        }
    } catch (error) {
        alert('Errore durante l\'aggiornamento. Riprova.');
    }
}

async function saveRoom() {
    try {
        const equipment = [];
        document.querySelectorAll('#addRoomForm input[type="checkbox"]:checked').forEach(cb => {
            equipment.push(cb.value);
        });
        
        // Add custom equipment to the list
        const customEquipmentList = document.getElementById('equipment_list');
        if (customEquipmentList) {
            customEquipmentList.querySelectorAll('.custom-equipment-item').forEach(item => {
                equipment.push(item.textContent.replace('×', '').trim());
            });
        }

        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('name', document.getElementById('room_name').value);
        formData.append('description', document.getElementById('room_description').value);
        formData.append('capacity', document.getElementById('room_capacity').value);
        formData.append('price_base', document.getElementById('room_price').value);
        formData.append('image_url', document.getElementById('room_image').value);
        formData.append('equipment', JSON.stringify(equipment));
        
        const response = await fetch('/api/rooms.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Errore durante il salvataggio: ' + result.message);
        }
    } catch (error) {
        alert('Errore durante il salvataggio. Riprova.');
    }
}

function editRoom(room) {
    // Parse equipment string to array
    let equipment = [];
    if (room.equipment) {
        // Handle both PostgreSQL array format {item1,item2} and SQLite format item1,item2
        let cleaned = room.equipment.replace(/[{}]/g, '');
        if (cleaned.trim()) {
            equipment = cleaned.split(',').map(item => item.trim()).filter(item => item);
        }
    }
    
    // Fill form with room data
    document.getElementById('edit_room_id').value = room.id;
    document.getElementById('edit_room_name').value = room.name;
    document.getElementById('edit_room_description').value = room.description || '';
    document.getElementById('edit_room_capacity').value = room.capacity;
    document.getElementById('edit_room_price').value = room.price_base;
    document.getElementById('edit_room_image').value = room.image_url || '';
    
    // Clear all checkboxes first
    document.querySelectorAll('#editRoomForm input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
    });
    
    // Check appropriate equipment checkboxes and show custom equipment
    const customEquipmentContainer = document.getElementById('edit_equipment_list');
    if (customEquipmentContainer) {
        customEquipmentContainer.innerHTML = '';
    }
    
    equipment.forEach(eq => {
        const cleanEq = eq.trim();
        let checkboxId = '';
        
        // Check if it's a standard equipment
        if (cleanEq === 'Proiettore') {
            checkboxId = 'edit_eq_proiettore';
        } else if (cleanEq === 'Lavagna interattiva') {
            checkboxId = 'edit_eq_lavagna';
        } else if (cleanEq === 'Aria condizionata') {
            checkboxId = 'edit_eq_aria';
        } else if (cleanEq === 'Sistema audio') {
            checkboxId = 'edit_eq_audio';
        } else {
            // Custom equipment - add to custom list
            if (customEquipmentContainer && cleanEq) {
                const badge = document.createElement('span');
                badge.className = 'badge bg-secondary me-2 mb-2 custom-equipment-item';
                badge.innerHTML = cleanEq + ' <span style="cursor: pointer;" onclick="this.parentElement.remove()">×</span>';
                customEquipmentContainer.appendChild(badge);
            }
        }
        
        if (checkboxId) {
            const checkbox = document.getElementById(checkboxId);
            if (checkbox) {
                checkbox.checked = true;
            }
        }
    });
    
    // Show modal
    new bootstrap.Modal(document.getElementById('editRoomModal')).show();
}

async function updateRoom() {
    try {
        const equipment = [];
        document.querySelectorAll('#editRoomForm input[type="checkbox"]:checked').forEach(cb => {
            equipment.push(cb.value);
        });
        
        // Add custom equipment to the list
        const customEquipmentList = document.getElementById('edit_equipment_list');
        if (customEquipmentList) {
            customEquipmentList.querySelectorAll('.custom-equipment-item').forEach(item => {
                equipment.push(item.textContent.replace('×', '').trim());
            });
        }

        const formData = new FormData();
        formData.append('action', 'update');
        formData.append('room_id', document.getElementById('edit_room_id').value);
        formData.append('name', document.getElementById('edit_room_name').value);
        formData.append('description', document.getElementById('edit_room_description').value);
        formData.append('capacity', document.getElementById('edit_room_capacity').value);
        formData.append('price_base', document.getElementById('edit_room_price').value);
        formData.append('image_url', document.getElementById('edit_room_image').value);
        formData.append('equipment', JSON.stringify(equipment));
        
        const response = await fetch('/api/rooms.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Errore durante l\'aggiornamento: ' + result.message);
        }
    } catch (error) {
        alert('Errore durante l\'aggiornamento. Riprova.');
    }
}

async function deleteRoom(roomId) {
    if (!confirm('Sei sicuro di voler eliminare questa aula? Questa azione non può essere annullata.')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('room_id', roomId);
        
        const response = await fetch('/api/rooms.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Errore durante l\'eliminazione: ' + result.message);
        }
    } catch (error) {
        alert('Errore durante l\'eliminazione. Riprova.');
    }
}

function editBooking(booking) {
    // Fill form with booking data
    document.getElementById('edit_booking_id').value = booking.id;
    document.getElementById('edit_booking_date').value = booking.booking_date;
    document.getElementById('edit_start_time').value = booking.start_time;
    document.getElementById('edit_end_time').value = booking.end_time;
    document.getElementById('edit_booking_room').value = booking.room_id;
    document.getElementById('edit_booking_status').value = booking.status;
    document.getElementById('edit_total_price').value = booking.total_price;
    
    // Show modal
    new bootstrap.Modal(document.getElementById('editBookingModal')).show();
}

async function updateBooking() {
    try {
        const formData = new FormData();
        formData.append('action', 'update');
        formData.append('booking_id', document.getElementById('edit_booking_id').value);
        formData.append('booking_date', document.getElementById('edit_booking_date').value);
        formData.append('start_time', document.getElementById('edit_start_time').value);
        formData.append('end_time', document.getElementById('edit_end_time').value);
        formData.append('room_id', document.getElementById('edit_booking_room').value);
        formData.append('status', document.getElementById('edit_booking_status').value);
        formData.append('total_price', document.getElementById('edit_total_price').value);
        
        const response = await fetch('/api/bookings.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Errore durante l\'aggiornamento: ' + result.message);
        }
    } catch (error) {
        alert('Errore durante l\'aggiornamento. Riprova.');
    }
}

async function deleteBooking(bookingId) {
    if (!confirm('Sei sicuro di voler eliminare questa prenotazione? Questa azione non può essere annullata.')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('booking_id', bookingId);
        
        const response = await fetch('/api/bookings.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Errore durante l\'eliminazione: ' + result.message);
        }
    } catch (error) {
        alert('Errore durante l\'eliminazione. Riprova.');
    }
}

// Function to add custom equipment
function addCustomEquipment(context) {
    const inputId = context === 'edit' ? 'edit_custom_equipment' : 'custom_equipment';
    const containerId = context === 'edit' ? 'edit_equipment_list' : 'equipment_list';
    
    const input = document.getElementById(inputId);
    const container = document.getElementById(containerId);
    
    if (input && container && input.value.trim()) {
        const badge = document.createElement('span');
        badge.className = 'badge bg-secondary me-2 mb-2 custom-equipment-item';
        badge.innerHTML = input.value.trim() + ' <span style="cursor: pointer;" onclick="this.parentElement.remove()">×</span>';
        container.appendChild(badge);
        input.value = '';
    }
}

// Add enter key listener for custom equipment inputs
document.addEventListener('DOMContentLoaded', function() {
    const customInput = document.getElementById('custom_equipment');
    const editCustomInput = document.getElementById('edit_custom_equipment');
    
    if (customInput) {
        customInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addCustomEquipment('add');
            }
        });
    }
    
    if (editCustomInput) {
        editCustomInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addCustomEquipment('edit');
            }
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
