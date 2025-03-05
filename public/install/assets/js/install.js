document.addEventListener('DOMContentLoaded', function() {
    // تحديث اتجاه الصفحة عند تغيير اللغة
    const langButtons = document.querySelectorAll('.lang-btn');
    langButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const lang = this.getAttribute('href').split('lang=')[1];
            document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
            document.body.className = `lang-${lang}`;
        });
    });
}); 