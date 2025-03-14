<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Instalação - Configuração do Banco de Dados</title>
    <link href="/css/install.css" rel="stylesheet">
</head>
<body>
    <div class="install-container">
        <h1>Configuração do Banco de Dados</h1>
        
        <form method="POST" class="config-form">
            <div class="form-group">
                <label>Host do Banco de Dados:</label>
                <input type="text" name="db_host" value="localhost" required>
            </div>

            <div class="form-group">
                <label>Nome do Banco de Dados:</label>
                <input type="text" name="db_name" required>
            </div>

            <div class="form-group">
                <label>Usuário:</label>
                <input type="text" name="db_user" required>
            </div>

            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="db_pass">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Testar e Continuar</button>
            </div>
        </form>
    </div>
</body>
</html>
