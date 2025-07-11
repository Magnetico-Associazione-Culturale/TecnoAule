<?php
require_once 'config/init.php';

if (isset($_SESSION['user_id'])) {
    header('Location: /dashboard.php');
    exit;
}

$page_title = 'Registrati - TecnoAule';
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="form-section">
                <div class="text-center mb-4">
                    <h2>Crea il tuo account</h2>
                    <p class="text-muted">Unisciti alla comunità TecnoAule</p>
                </div>

                <div id="registerAlert" class="alert" style="display: none;"></div>

                <form id="registerForm">
                    <div class="mb-3">
                        <label for="user_type" class="form-label">Tipo di utente</label>
                        <select class="form-control" id="user_type" required>
                            <option value="">Seleziona tipo di utente</option>
                            <option value="individual">Privato</option>
                            <option value="company">Azienda</option>
                            <option value="freelancer">Libero professionista</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome completo</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>

                    <!-- Company/Business Fields -->
                    <div id="businessFields" style="display: none;">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nome azienda/attività</label>
                            <input type="text" class="form-control" id="company_name">
                        </div>
                        
                        <div class="mb-3">
                            <label for="vat_number" class="form-label">Partita IVA</label>
                            <input type="text" class="form-control" id="vat_number" placeholder="es. 12345678901">
                        </div>
                        
                        <div class="mb-3">
                            <label for="fiscal_code" class="form-label">Codice fiscale</label>
                            <input type="text" class="form-control" id="fiscal_code" placeholder="es. RSSMRA80A01H501U">
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Indirizzo</label>
                            <input type="text" class="form-control" id="address">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="city" class="form-label">Città</label>
                                <input type="text" class="form-control" id="city">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">CAP</label>
                                <input type="text" class="form-control" id="postal_code" placeholder="es. 00100">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefono</label>
                            <input type="tel" class="form-control" id="phone" placeholder="es. +39 123 456 7890">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tessera_number" class="form-label">Numero tessera (opzionale)</label>
                        <input type="text" class="form-control" id="tessera_number" placeholder="es. 0001" maxlength="10">
                        <small class="form-text text-muted">Se possiedi una tessera Magnetico, inserisci il numero per accedere agli sconti</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required minlength="6">
                        <small class="form-text text-muted">Minimo 6 caratteri</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Conferma password</label>
                        <input type="password" class="form-control" id="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-orange w-100 mb-3">
                        <span class="spinner" id="registerSpinner" style="display: none;"></span>
                        Registrati
                    </button>
                </form>

                <div class="text-center">
                    <p class="text-muted">Hai già un account? 
                        <a href="/login.php" class="text-orange text-decoration-none">Accedi qui</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const spinner = document.getElementById('registerSpinner');
    const alert = document.getElementById('registerAlert');
    
    // Validate passwords match
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        alert.className = 'alert alert-danger';
        alert.textContent = 'Le password non corrispondono.';
        alert.style.display = 'block';
        return;
    }
    
    spinner.style.display = 'inline-block';
    alert.style.display = 'none';
    
    try {
        const formData = new FormData();
        formData.append('action', 'register');
        formData.append('name', document.getElementById('name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('tessera_number', document.getElementById('tessera_number').value);
        formData.append('password', password);
        formData.append('user_type', document.getElementById('user_type').value);
        
        // Add business fields if applicable
        const userType = document.getElementById('user_type').value;
        if (userType === 'company' || userType === 'freelancer') {
            formData.append('company_name', document.getElementById('company_name').value);
            formData.append('vat_number', document.getElementById('vat_number').value);
            formData.append('fiscal_code', document.getElementById('fiscal_code').value);
            formData.append('address', document.getElementById('address').value);
            formData.append('city', document.getElementById('city').value);
            formData.append('postal_code', document.getElementById('postal_code').value);
            formData.append('phone', document.getElementById('phone').value);
        }
        
        const response = await fetch('/api/auth.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert.className = 'alert alert-success';
            alert.textContent = 'Registrazione completata! Accedi con le tue credenziali.';
            alert.style.display = 'block';
            
            // Clear form
            document.getElementById('registerForm').reset();
            
            // Redirect to login after 2 seconds
            setTimeout(() => {
                window.location.href = '/login.php';
            }, 2000);
        } else {
            alert.className = 'alert alert-danger';
            alert.textContent = result.message;
            alert.style.display = 'block';
        }
    } catch (error) {
        alert.className = 'alert alert-danger';
        alert.textContent = 'Errore durante la registrazione. Riprova.';
        alert.style.display = 'block';
    }
    
    spinner.style.display = 'none';
});

// Handle user type selection
document.getElementById('user_type').addEventListener('change', function() {
    const userType = this.value;
    const businessFields = document.getElementById('businessFields');
    
    if (userType === 'company' || userType === 'freelancer') {
        businessFields.style.display = 'block';
        
        // Make business fields required
        document.getElementById('company_name').required = true;
        document.getElementById('vat_number').required = true;
        document.getElementById('fiscal_code').required = true;
        document.getElementById('address').required = true;
        document.getElementById('city').required = true;
        document.getElementById('postal_code').required = true;
        document.getElementById('phone').required = true;
        
        // Update labels based on type
        const companyLabel = document.querySelector('label[for="company_name"]');
        if (userType === 'company') {
            companyLabel.textContent = 'Nome azienda';
        } else {
            companyLabel.textContent = 'Nome attività professionale';
        }
    } else {
        businessFields.style.display = 'none';
        
        // Remove required attribute from business fields
        document.getElementById('company_name').required = false;
        document.getElementById('vat_number').required = false;
        document.getElementById('fiscal_code').required = false;
        document.getElementById('address').required = false;
        document.getElementById('city').required = false;
        document.getElementById('postal_code').required = false;
        document.getElementById('phone').required = false;
    }
});

// VAT number validation
document.getElementById('vat_number').addEventListener('input', function() {
    const vatNumber = this.value.replace(/\D/g, ''); // Remove non-digits
    if (vatNumber.length > 11) {
        this.value = vatNumber.slice(0, 11);
    } else {
        this.value = vatNumber;
    }
});

// Fiscal code validation
document.getElementById('fiscal_code').addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    if (this.value.length > 16) {
        this.value = this.value.slice(0, 16);
    }
});

// Postal code validation
document.getElementById('postal_code').addEventListener('input', function() {
    const postalCode = this.value.replace(/\D/g, '');
    if (postalCode.length > 5) {
        this.value = postalCode.slice(0, 5);
    } else {
        this.value = postalCode;
    }
});
</script>

<?php include 'includes/footer.php'; ?>
