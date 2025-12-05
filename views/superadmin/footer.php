<footer class="bg-dark text-white text-center py-4"
    style="
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 999;
    ">
    
    <style>
        footer {
            font-family: 'Poppins', sans-serif;
        }

        footer .container {
            max-width: 800px;
        }

        footer p {
            margin-bottom: 1rem;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }

        footer a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        footer a:hover {
            color: #0d6efd;
            transform: scale(1.1);
        }

        footer [data-lucide] {
            width: 24px;
            height: 24px;
            stroke-width: 1.8;
        }

        footer .social-icons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
        }
    </style>

    <div class="container">
        <p>&copy; 2025 PBL IF1A-8. All rights reserved.</p>

        <div class="social-icons">
            <a href="https://www.facebook.com/" title="Facebook"><i data-lucide="facebook"></i></a>
            <a href="https://www.instagram.com/" title="Instagram"><i data-lucide="instagram"></i></a>
            <a href="https://x.com/" title="twitter"><i data-lucide="twitter"></i></a>
            <a href="https://www.youtube.com/" title="YouTube"><i data-lucide="youtube"></i></a>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</footer>
