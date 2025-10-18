document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.style.animation = 'bounce 0.3s ease-in-out';
            setTimeout(() => {
                btn.style.animation = '';
                alert('Login berhasil!');
            }, 300);
        });