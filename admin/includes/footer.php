</main>
    </div>

    <!-- TOAST CONTAINER -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="js/main.js"></script>
    <script>
        // Toast notification function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="toast-icon ${icons[type]}"></i>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideInRight 0.4s ease reverse';
                setTimeout(() => toast.remove(), 400);
            }, 5000);
        }

        // Show success/error messages from PHP
        <?php if(isset($_SESSION['toast'])): ?>
            showToast('<?php echo addslashes($_SESSION['toast']['message']); ?>', '<?php echo $_SESSION['toast']['type']; ?>');
            <?php unset($_SESSION['toast']); ?>
        <?php endif; ?>

        // Loading animation
        function showLoading() {
            const overlay = document.createElement('div');
            overlay.className = 'loading-overlay';
            overlay.innerHTML = '<div class="loading-spinner"></div>';
            document.body.appendChild(overlay);
            return overlay;
        }

        function hideLoading(overlay) {
            if(overlay) overlay.remove();
        }

        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            
            // Add fade-in animation
            document.querySelectorAll('.card, .stat-card').forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.5s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 100 + (index * 100));
            });
        });
    </script>
</body>
</html>