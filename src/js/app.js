import '../scss/app.scss';
import '../css/navbar-modern.css';

document.addEventListener('DOMContentLoaded', (e) => {
    const dropdown = document.querySelector('.dropdown-menu');
    dropdown.style.margin = 0;


    let items = document.querySelectorAll('.nav-link')
    items.forEach(item => {
        if (item.href == location.href) {
            item.classList.add('active')
            if (item.classList.contains('dropdown-item')) {
                item.parentElement.parentElement.previousElementSibling.classList.add('active')
            }
        }
    });


})



document.onreadystatechange = () => {
    switch (document.readyState) {
        case "loading":

            break;
        case "interactive":
            document.getElementById('bar') ? document.getElementById('bar').style.width = '35%' : null;
            break;

        case "complete":
            document.getElementById('bar') ? document.getElementById('bar').style.width = '100%' : null;
            setTimeout(() => {
                document.getElementById('bar') ? document.getElementById('bar').parentElement.style.display = 'none' : null
            }, 1000);
            break;
    }
}
// Animación de la barra de progreso
document.addEventListener('DOMContentLoaded', function () {
    const bar = document.getElementById('bar');
    if (bar) {
        let progress = 0;
        setInterval(() => {
            progress = (progress + 1) % 101;
            bar.style.width = progress + '%';
        }, 50);
    }
});