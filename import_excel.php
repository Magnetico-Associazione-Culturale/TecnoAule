<?php
require_once 'config/init.php';

if (!isset($_SESSION['admin_id'])) {
    die('Accesso negato');
}

$db = new Database();
$pdo = $db->connect();

echo "<h2>Importazione dati Excel</h2>";

try {
    // Prima rimuovo tutti i membri esistenti (preservando l'admin che è in admin_users, non in members)
    echo "<p>Rimozione dati tessere esistenti...</p>";
    $stmt = $pdo->prepare("DELETE FROM members");
    $stmt->execute();
    echo "<p style='color: green;'>Tessere esistenti rimosse con successo.</p>";
    
    // Ora posso mostrare il form per caricare il file Excel
    echo "<form method='post' enctype='multipart/form-data' style='margin: 20px 0;'>";
    echo "<div>";
    echo "<label>Carica il file Excel (.xlsx):</label><br>";
    echo "<input type='file' name='excel_file' accept='.xlsx,.xls' required style='margin: 10px 0;'><br>";
    echo "<button type='submit' name='import' style='background: #ff6600; color: white; padding: 10px 20px; border: none; border-radius: 5px;'>Importa Dati</button>";
    echo "</div>";
    echo "</form>";
    
    // Se è stato caricato un file
    if (isset($_POST['import']) && isset($_FILES['excel_file'])) {
        
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $uploadFile = $uploadDir . basename($_FILES['excel_file']['name']);
        
        if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $uploadFile)) {
            echo "<p style='color: green;'>File caricato con successo: " . htmlspecialchars($_FILES['excel_file']['name']) . "</p>";
            
            // Qui dovresti includere una libreria per leggere Excel come PhpSpreadsheet
            echo "<p style='color: orange;'>Per completare l'importazione, devi fornire i dati dal file Excel in formato CSV o elenco.</p>";
            echo "<p>Una volta analizzato il file, posso inserire i dati nel database.</p>";
            
            echo "<h3>Formato richiesto per ogni tessera:</h3>";
            echo "<ul>";
            echo "<li>Numero tessera</li>";
            echo "<li>Nome completo</li>";
            echo "<li>Email</li>";
            echo "<li>Telefono</li>";
            echo "<li>Status socio (0=normale, 1=associato)</li>";
            echo "</ul>";
            
        } else {
            echo "<p style='color: red;'>Errore nel caricamento del file.</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Errore: " . $e->getMessage() . "</p>";
}

echo "<br><a href='admin.php' style='color: #ff6600;'>&larr; Torna al pannello amministratore</a>";
?>