document.addEventListener('DOMContentLoaded', function() {
  fetch('php/courses.php')
    .then(response => response.text())
    .then(html => {
      document.getElementById('courses-list').innerHTML = html;
    });
}); 