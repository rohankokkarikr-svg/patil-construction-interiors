document.addEventListener('DOMContentLoaded', () => {
    const topbar = document.querySelector('.admin-topbar');
    const sidebar = document.querySelector('.admin-sidebar');
    
    if (topbar && sidebar) {
        // Create hamburger button
        const btn = document.createElement('button');
        btn.className = 'admin-menu-toggle';
        btn.innerHTML = '<i class="fas fa-bars"></i>';
        btn.style.cssText = `
            background: transparent;
            border: none;
            color: var(--clr-text);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0.5rem;
            margin-right: 1rem;
            display: none;
        `;
        
        // Insert as first child of topbar
        topbar.insertBefore(btn, topbar.firstChild);
        
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'admin-overlay';
        overlay.style.cssText = `
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 99;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        document.body.appendChild(overlay);
        
        const openSidebar = () => {
            sidebar.classList.add('open');
            overlay.style.display = 'block';
            setTimeout(() => overlay.style.opacity = '1', 10);
        };
        
        const closeSidebar = () => {
            sidebar.classList.remove('open');
            overlay.style.opacity = '0';
            setTimeout(() => overlay.style.display = 'none', 300);
        };
        
        btn.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
        
        overlay.addEventListener('click', closeSidebar);
        
        // Close on escape key
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeSidebar();
        });
    }
});
