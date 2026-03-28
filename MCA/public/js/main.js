// MCA - Movilización Cristiana
// Main JavaScript File

document.addEventListener('DOMContentLoaded', function() {
    // Navbar scroll effect
    const navbar = document.getElementById('mainNav');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });

    // Attendance toggle buttons
    const attendanceToggles = document.querySelectorAll('.attendance-toggle .btn');
    attendanceToggles.forEach(btn => {
        btn.addEventListener('click', function() {
            const parent = this.parentElement;
            parent.querySelectorAll('.btn').forEach(b => b.classList.remove('active', 'btn-primary', 'btn-success', 'btn-danger', 'btn-warning'));
            this.classList.add('active');
            
            const value = this.dataset.value;
            if (value === 'si') {
                this.classList.add('btn-success');
            } else if (value === 'no') {
                this.classList.add('btn-danger');
            } else {
                this.classList.add('btn-warning');
            }
            
            // Update hidden input
            const input = parent.querySelector('input[type="hidden"]');
            if (input) {
                input.value = value;
            }

            // Calculate and update percentage if in report form
            updateAttendancePercentage(this.closest('tr'));
        });
    });

    // Function to calculate attendance percentage for a row
    function updateAttendancePercentage(row) {
        if (!row) return;
        
        const cells = row.querySelectorAll('.attendance-toggle');
        let total = 0;
        let attended = 0;

        cells.forEach(cell => {
            const activeBtn = cell.querySelector('.btn.active');
            if (activeBtn) {
                const value = activeBtn.dataset.value;
                if (value !== 'no_hubo') {
                    total++;
                    if (value === 'si') {
                        attended++;
                    }
                }
            }
        });

        const percentageCell = row.querySelector('.percentage');
        if (percentageCell && total > 0) {
            const percentage = Math.round((attended / total) * 100);
            percentageCell.textContent = percentage + '%';
            
            // Color coding
            percentageCell.classList.remove('text-success', 'text-warning', 'text-danger');
            if (percentage >= 80) {
                percentageCell.classList.add('text-success');
            } else if (percentage >= 50) {
                percentageCell.classList.add('text-warning');
            } else {
                percentageCell.classList.add('text-danger');
            }
        }
    }

    // Form validation with Bootstrap
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Image preview for file uploads
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const preview = document.getElementById(this.dataset.preview);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const message = this.dataset.confirm || '¿Estás seguro de que deseas eliminar este elemento?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Admin sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.querySelector('.admin-sidebar');
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('show');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
            if (!adminSidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                adminSidebar.classList.remove('show');
            }
        });
    }

    // Dynamic form fields (for formularios editables)
    const addFieldBtn = document.getElementById('addFormField');
    const fieldsContainer = document.getElementById('formFields');
    if (addFieldBtn && fieldsContainer) {
        let fieldCount = fieldsContainer.children.length;

        addFieldBtn.addEventListener('click', function() {
            fieldCount++;
            const fieldHtml = `
                <div class="field-item border rounded p-3 mb-3" data-field="${fieldCount}">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary">Campo ${fieldCount}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-field">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre del Campo</label>
                            <input type="text" name="fields[${fieldCount}][name]" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo</label>
                            <select name="fields[${fieldCount}][type]" class="form-select" required>
                                <option value="text">Texto</option>
                                <option value="textarea">Área de Texto</option>
                                <option value="number">Número</option>
                                <option value="email">Email</option>
                                <option value="date">Fecha</option>
                                <option value="select">Selección</option>
                                <option value="checkbox">Casilla</option>
                                <option value="radio">Opción Múltiple</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="fields[${fieldCount}][required]" class="form-check-input" id="required${fieldCount}">
                                <label class="form-check-label" for="required${fieldCount}">Campo Obligatorio</label>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            fieldsContainer.insertAdjacentHTML('beforeend', fieldHtml);
        });

        // Remove field
        fieldsContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-field')) {
                e.target.closest('.field-item').remove();
            }
        });
    }

    // Statistics charts (if Chart.js is loaded)
    if (typeof Chart !== 'undefined') {
        const statsChart = document.getElementById('statsChart');
        if (statsChart) {
            const ctx = statsChart.getContext('2d');
            const labels = JSON.parse(statsChart.dataset.labels || '[]');
            const data = JSON.parse(statsChart.dataset.values || '[]');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Asistencia %',
                        data: data,
                        backgroundColor: [
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }
    }

    // Counter animation for stats
    const counters = document.querySelectorAll('.stat-number[data-count]');
    if (counters.length > 0) {
        const animateCounter = (el) => {
            const target = parseInt(el.dataset.count);
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    el.textContent = target;
                    clearInterval(timer);
                } else {
                    el.textContent = Math.floor(current);
                }
            }, 16);
        };

        // Use Intersection Observer for animation trigger
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    }

    // Tooltip initialization
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

    // Copy to clipboard
    const copyButtons = document.querySelectorAll('[data-copy]');
    copyButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const text = this.dataset.copy;
            navigator.clipboard.writeText(text).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check"></i> Copiado!';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            });
        });
    });

    // Print functionality
    const printButtons = document.querySelectorAll('[data-print]');
    printButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            window.print();
        });
    });
});

// Helper function to format dates
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('es-ES', options);
}

// Helper to escape HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
