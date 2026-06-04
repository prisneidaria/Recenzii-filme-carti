// Dark/Light mode 
function toggleTheme() {
    const body = document.body;
    const currentTheme = body.classList.contains('dark') ? 'dark' : 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    body.classList.remove(currentTheme);
    body.classList.add(newTheme);
    
    // Salveaza preferinta în localStorage
    localStorage.setItem('theme', newTheme);
    
    // Trimite la server prin fetch
    fetch('save_theme.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'theme=' + newTheme
    });
}

// Schimbare limbă 
function changeLanguage(lang) {
    window.location.href = 'set_lang.php?lang=' + lang;
}

// Încarca tema salvata
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    if(savedTheme) {
        document.body.classList.add(savedTheme);
    }
    
    // Meniu responsive pentru mobil
    const menuBtn = document.querySelector('.menu-btn');
    if(menuBtn) {
        menuBtn.addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
        });
    }
});

// Validare formular contact în timp real
function validateContactForm() {
    const email = document.querySelector('input[type="email"]');
    if(email && email.value) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!regex.test(email.value)) {
            showError('Email invalid');
            return false;
        }
    }
    return true;
}