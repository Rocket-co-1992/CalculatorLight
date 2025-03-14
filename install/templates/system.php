<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Instalação - Configuração do Sistema</title>
    <link href="/css/install.css" rel="stylesheet">
</head>
<body>
    <div class="install-container">
        <h1>Configuração do Sistema</h1>
        
        <form method="POST" class="config-form">
            <div class="form-group">
                <label>Nome da Aplicação:</label>
                <input type="text" name="app_name" value="Web-to-Print" required>
            </div>

            <div class="form-group">
                <label>URL do Site:</label>
                <input type="url" name="app_url" required 
                       placeholder="https://seudominio.com">
            </div>

            <div class="form-group">
                <label>Email do Administrador:</label>
                <input type="email" name="admin_email" required>
            </div>

            <div class="form-group">
                <label>Senha do Administrador:</label>
                <input type="password" name="admin_password" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Finalizar Instalação</button>
            </div>
        </form>
    </div>
</body>
</html>
