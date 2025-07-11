<?php
require_once 'config/init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$page_title = 'Le mie prenotazioni - TecnoAule';
include 'includes/header.php';

$db = new Database();
$pdo = $db->connect();

// Get user info con join per tessera
$stmt = $pdo->prepare("
    SELECT ru.*, t.tessera_number, t.is_associate 
    FROM registered_users ru 
    LEFT JOIN tessere t ON ru.tessera_id = t.id 
    WHERE ru.id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Check if user exists
if (!$user) {
    // User not found, redirect to login
    session_destroy();
    header('Location: /login.php');
    exit;
}

// Get user's bookings
$stmt = $pdo->prepare("
    SELECT b.*, r.name as room_name, r.description as room_description 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    WHERE b.user_id = ? 
    ORDER BY b.booking_date DESC, b.start_time DESC
");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mb-4">Le mie prenotazioni</h1>
            <p class="text-muted mb-4">Benvenuto, <?php echo htmlspecialchars($user['name'] ?? ''); ?>!</p>
        </div>
    </div>

    <!-- User Info -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="dashboard-card">
                <h5 class="mb-3">Informazioni account</h5>
                <div class="mb-2">
                    <strong>Nome:</strong> <?php echo htmlspecialchars($user['name'] ?? ''); ?>
                </div>
                <div class="mb-2">
                    <strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? ''); ?>
                </div>
                <?php if (!empty($user['phone'])): ?>
                <div class="mb-2">
                    <strong>Telefono:</strong> <?php echo htmlspecialchars($user['phone']); ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($user['tessera_number'])): ?>
                    <div class="mb-2">
                        <strong>Tessera:</strong> 
                        <span class="badge <?php echo (!empty($user['is_associate']) && $user['is_associate']) ? 'bg-success' : 'bg-secondary'; ?>">
                            <?php echo htmlspecialchars($user['tessera_number']); ?>
                            <?php if (!empty($user['is_associate']) && $user['is_associate']): ?>
                                <i class="fas fa-star ms-1"></i>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php if (!empty($user['is_associate']) && $user['is_associate']): ?>
                        <div class="alert alert-success py-2 mb-0">
                            <small>
                                <i class="fas fa-percent me-1"></i>
                                Hai diritto allo sconto del 100% come socio!
                            </small>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-info py-2 mb-0">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Nessuna tessera associata
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="dashboard-card">
                <h5 class="mb-3">Azioni rapide</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="/booking.php" class="btn btn-orange w-100">
                            <i class="fas fa-plus me-2"></i>Nuova prenotazione
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/map.php" class="btn btn-outline-orange w-100">
                            <i class="fas fa-map me-2"></i>Mappa aule
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-outline-secondary w-100" onclick="window.location.reload()">
                            <i class="fas fa-refresh me-2"></i>Aggiorna
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Cronologia prenotazioni</h5>
                    <span class="badge bg-light text-dark"><?php echo count($bookings); ?> prenotazioni</span>
                </div>

                <?php if (empty($bookings)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times text-muted mb-3" style="font-size: 3rem;"></i>
                        <h4 class="text-muted">Nessuna prenotazione</h4>
                        <p class="text-muted mb-4">Non hai ancora effettuato nessuna prenotazione.</p>
                        <a href="/booking.php" class="btn btn-orange">
                            <i class="fas fa-plus me-2"></i>Crea la tua prima prenotazione
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Aula</th>
                                    <th>Data</th>
                                    <th>Orario</th>
                                    <th>Prezzo</th>
                                    <th>Stato</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($booking['room_name']); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['room_description']); ?></small>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($booking['booking_date'])); ?>
                                        </td>
                                        <td>
                                            <?php echo date('H:i', strtotime($booking['start_time'])); ?> - 
                                            <?php echo date('H:i', strtotime($booking['end_time'])); ?>
                                        </td>
                                        <td>
                                            <strong>€<?php echo number_format($booking['total_price'], 2); ?></strong>
                                            <?php if ($booking['tessera_used'] && $booking['total_price'] == 0): ?>
                                                <br><small class="text-success">Sconto socio</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = 'status-' . $booking['status'];
                                            $statusText = [
                                                'pending' => 'In attesa',
                                                'approved' => 'Approvata',
                                                'rejected' => 'Rifiutata'
                                            ];
                                            ?>
                                            <span class="badge <?php echo $statusClass; ?>">
                                                <?php echo $statusText[$booking['status']] ?? $booking['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($booking['status'] === 'pending'): ?>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="cancelBooking(<?php echo $booking['id']; ?>)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
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
</div>

<script>
async function cancelBooking(bookingId) {
    if (!confirm('Sei sicuro di voler cancellare questa prenotazione?')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('action', 'cancel');
        formData.append('booking_id', bookingId);
        
        const response = await fetch('/api/bookings.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Errore durante la cancellazione: ' + result.message);
        }
    } catch (error) {
        alert('Errore durante la cancellazione. Riprova.');
    }
}
</script>

<?php include 'includes/footer.php'; ?>
