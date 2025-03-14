<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Instalação - Verificação de Requisitos</title>
    <link href="/css/install.css" rel="stylesheet">
</head>
<body>
    <div class="install-container">
        <h1>Verificação de Requisitos do Sistema</h1>
        
        <div class="requirements-list">
            <?php foreach ($requirements as $name => $check): ?>
                <div class="requirement-item <?= $check ? 'passed' : 'failed' ?>">
                    <span class="status-icon"><?= $check ? '✅' : '❌' ?></span>
                    <span class="requirement-name"><?= htmlspecialchars($name) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($installer->getErrors())): ?>
            <a href="?step=2" class="btn btn-primary">Continuar</a>
        <?php else: ?>
            <div class="alert alert-danger">
                <strong>Corrija os requisitos acima antes de continuar.</strong>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
