document.addEventListener('DOMContentLoaded', function() {
  fetch('php/mycourses.php')
    .then(response => response.text())
    .then(html => {
      document.getElementById('mycourses-list').innerHTML = html;
    });
}); 