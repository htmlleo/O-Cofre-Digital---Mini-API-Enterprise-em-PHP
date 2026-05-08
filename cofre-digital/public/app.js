/**
 * Lógica do Frontend - O Cofre Digital
 */

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const secretForm = document.getElementById('secret-form');
    const messageDiv = document.getElementById('message');

    // Alternar entre Login e Registro
    const showRegister = document.getElementById('show-register');
    const showLogin = document.getElementById('show-login');

    if (showRegister) {
        showRegister.onclick = (e) => {
            e.preventDefault();
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            document.getElementById('auth-title').innerText = 'Criar Conta';
        };
    }

    if (showLogin) {
        showLogin.onclick = (e) => {
            e.preventDefault();
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
            document.getElementById('auth-title').innerText = 'O Cofre Digital';
        };
    }

    // Função para exibir mensagens
    const showMsg = (text, type = 'error') => {
        messageDiv.innerText = text;
        messageDiv.className = type === 'error' ? 'msg-error' : 'msg-success';
        messageDiv.classList.remove('hidden');
        setTimeout(() => messageDiv.classList.add('hidden'), 5000);
    };

    // Login
    if (loginForm) {
        loginForm.onsubmit = async (e) => {
            e.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;

            try {
                const res = await fetch('../api/AuthController.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    window.location.href = 'dashboard.html';
                } else {
                    showMsg(data.message);
                }
            } catch (err) {
                showMsg('Erro ao conectar com o servidor.');
            }
        };
    }

    // Registro
    if (registerForm) {
        registerForm.onsubmit = async (e) => {
            e.preventDefault();
            const name = document.getElementById('reg-name').value;
            const email = document.getElementById('reg-email').value;
            const password = document.getElementById('reg-password').value;

            try {
                const res = await fetch('../api/AuthController.php?action=register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, email, password })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    showMsg(data.message, 'success');
                    showLogin.click();
                } else {
                    showMsg(data.message);
                }
            } catch (err) {
                showMsg('Erro ao conectar com o servidor.');
            }
        };
    }

    // Dashboard Logic
    if (window.location.pathname.includes('dashboard.html')) {
        const secretsList = document.getElementById('secrets-list');
        const listView = document.getElementById('list-view');
        const createView = document.getElementById('create-view');
        const detailView = document.getElementById('detail-view');

        const loadSecrets = async () => {
            try {
                const res = await fetch('../api/SecretController.php?action=list');
                const data = await res.json();

                if (data.status === 'success') {
                    if (data.data.length === 0) {
                        secretsList.innerHTML = '<p style="text-align: center; color: var(--text-secondary);">Nenhum segredo guardado ainda.</p>';
                        return;
                    }

                    secretsList.innerHTML = data.data.map(s => `
                        <div class="secret-card">
                            <div class="secret-info">
                                <h3>${s.title}</h3>
                                <span>Criado em: ${new Date(s.created_at).toLocaleDateString('pt-BR')}</span>
                            </div>
                            <button class="btn-view" onclick="viewSecret(${s.id})">Ver</button>
                        </div>
                    `).join('');
                } else if (res.status === 401) {
                    window.location.href = 'index.html';
                }
            } catch (err) {
                showMsg('Erro ao carregar segredos.');
            }
        };

        window.viewSecret = async (id) => {
            try {
                const res = await fetch(`../api/SecretController.php?action=show&id=${id}`);
                const data = await res.json();

                if (data.status === 'success') {
                    document.getElementById('detail-title').innerText = data.data.title;
                    document.getElementById('detail-content').innerText = data.data.content;
                    listView.classList.add('hidden');
                    detailView.classList.remove('hidden');
                } else {
                    showMsg(data.message);
                }
            } catch (err) {
                showMsg('Erro ao carregar detalhes.');
            }
        };

        document.getElementById('btn-new').onclick = () => {
            listView.classList.add('hidden');
            createView.classList.remove('hidden');
        };

        document.getElementById('btn-cancel').onclick = () => {
            createView.classList.add('hidden');
            listView.classList.remove('hidden');
        };

        document.getElementById('btn-back').onclick = () => {
            detailView.classList.add('hidden');
            listView.classList.remove('hidden');
        };

        document.getElementById('btn-logout').onclick = async () => {
            await fetch('../api/AuthController.php?action=logout', { method: 'POST' });
            window.location.href = 'index.html';
        };

        secretForm.onsubmit = async (e) => {
            e.preventDefault();
            const title = document.getElementById('sec-title').value;
            const content = document.getElementById('sec-content').value;

            try {
                const res = await fetch('../api/SecretController.php?action=create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ title, content })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    showMsg(data.message, 'success');
                    secretForm.reset();
                    createView.classList.add('hidden');
                    listView.classList.remove('hidden');
                    loadSecrets();
                } else {
                    showMsg(data.message);
                }
            } catch (err) {
                showMsg('Erro ao salvar segredo.');
            }
        };

        loadSecrets();
    }
});
