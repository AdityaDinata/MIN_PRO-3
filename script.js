document.addEventListener('DOMContentLoaded', function() {
    // Statistik Aktivitas Fisik
    var activityCtx = document.getElementById('activityChart').getContext('2d');
    var activityChart = new Chart(activityCtx, {
        type: 'line',
        data: activityData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Statistik Asupan Makanan
    var foodCtx = document.getElementById('foodChart').getContext('2d');
    var foodChart = new Chart(foodCtx, {
        type: 'line',
        data: foodData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Statistik Parameter Kesehatan
    var healthCtx = document.getElementById('healthChart').getContext('2d');
    var healthChart = new Chart(healthCtx, {
        type: 'line',
        data: healthData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});



