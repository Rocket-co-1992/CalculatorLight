document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('setup-form');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            
            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                if (result.success) {
                    window.location.href = result.next_step;
                } else {
                    showError(result.error);
                }
            } catch (error) {
                showError('Erro ao processar requisição');
            } finally {
                submitButton.disabled = false;
            }
        });
    }
});

function showError(message) {
    const errorContainer = document.getElementById('error-container');
    if (errorContainer) {
        errorContainer.textContent = message;
        errorContainer.style.display = 'block';
    }
}
