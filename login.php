<?php
require_once 'config/init.php';

if (isset($_SESSION['user_id'])) {
    header('Location: /dashboard.php');
    exit;
}

$page_title = 'Accedi - TecnoAule';
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="form-section">
                <div class="text-center mb-4">
                    <h2>Accedi al tuo account</h2>
                    <p class="text-muted">Benvenuto in TecnoAule</p>
                </div>

                <div id="loginAlert" class="alert" style="display: none;"></div>

                <form id="loginForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>

                    <button type="submit" class="btn btn-orange w-100 mb-3">
                        <span class="spinner" id="loginSpinner" style="display: none;"></span>
                        Accedi
                    </button>
                </form>

                <div class="text-center">
                    <p class="text-muted">Non hai un account? 
                        <a href="/register.php" class="text-orange text-decoration-none">Registrati qui</a>
                    </p>
                </div>

                <!-- Admin Login -->
                <hr class="my-4">
                <div class="text-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#adminLoginModal">
                        Accesso amministratore
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Login Modal -->
<div class="modal fade" id="adminLoginModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Accesso Amministratore</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="adminLoginAlert" class="alert" style="display: none;"></div>
                <form id="adminLoginForm">
                    <div class="mb-3">
                        <label for="admin_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="admin_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="admin_password" required>
                    </div>
                    <button type="submit" class="btn btn-orange w-100">
                        <span class="spinner" id="adminLoginSpinner" style="display: none;"></span>
                        Accedi come Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// User Login
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const spinner = document.getElementById('loginSpinner');
    const alert = document.getElementById('loginAlert');
    
    spinner.style.display = 'inline-block';
    alert.style.display = 'none';
    
    try {
        const formData = new FormData();
        formData.append('action', 'login');
        formData.append('email', document.getElementById('email').value);
        formData.append('password', document.getElementById('password').value);
        
        const response = await fetch('/api/auth.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            const redirect = new URLSearchParams(window.location.search).get('redirect') || '/dashboard.php';
            window.location.href = redirect;
        } else {
            alert.className = 'alert alert-danger';
            alert.textContent = result.message;
            alert.style.display = 'block';
        }
    } catch (error) {
        alert.className = 'alert alert-danger';
        alert.textContent = 'Errore durante il login. Riprova.';
        alert.style.display = 'block';
    }
    
    spinner.style.display = 'none';
});

// Admin Login
document.getElementById('adminLoginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const spinner = document.getElementById('adminLoginSpinner');
    const alert = document.getElementById('adminLoginAlert');
    
    spinner.style.display = 'inline-block';
    alert.style.display = 'none';
    
    try {
        const formData = new FormData();
        formData.append('action', 'admin_login');
        formData.append('username', document.getElementById('admin_username').value);
        formData.append('password', document.getElementById('admin_password').value);
        
        const response = await fetch('/api/auth.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.href = '/admin.php';
        } else {
            alert.className = 'alert alert-danger';
            alert.textContent = result.message;
            alert.style.display = 'block';
        }
    } catch (error) {
        alert.className = 'alert alert-danger';
        alert.textContent = 'Errore durante il login admin. Riprova.';
        alert.style.display = 'block';
    }
    
    spinner.style.display = 'none';
});
</script>

<?php include 'includes/footer.php'; ?>
