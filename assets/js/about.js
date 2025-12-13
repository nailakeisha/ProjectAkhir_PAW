// About Page JavaScript
class AboutPage {
    constructor() {
        this.init();
    }

    init() {
        this.setupMobileMenu();
        this.setupScrollEffects();
        this.loadTeamData();
        this.loadStatsData();
        this.setupTimelineAnimation();
    }

    // Mobile Menu Toggle
    setupMobileMenu() {
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navLinks = document.querySelector('.nav-links');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenuBtn.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        // Close mobile menu when clicking on links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuBtn.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    }

    // Scroll Effects
    setupScrollEffects() {
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
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
    }

    // Load Team Data from Backend
    async loadTeamData() {
        try {
            const response = await fetch('../backend/controllers/AboutController.php?action=getTeam');
            const data = await response.json();

            if (data.success) {
                this.renderTeam(data.team);
            } else {
                this.renderTeam(this.getDefaultTeamData());
            }
        } catch (error) {
            console.error('Error loading team data:', error);
            this.renderTeam(this.getDefaultTeamData());
        }
    }

    // Render Team Members
    renderTeam(teamData) {
        const teamContainer = document.getElementById('teamContainer');
        
        teamContainer.innerHTML = teamData.map(member => `
            <div class="team-card">
                <div class="team-image" style="background-image: url('../${member.image}')"></div>
                <div class="team-social">
                    <a href="${member.linkedin}" class="social-link-team" target="_blank">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="${member.twitter}" class="social-link-team" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="mailto:${member.email}" class="social-link-team">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
                <div class="team-info">
                    <h3 class="team-name">${member.name}</h3>
                    <p class="team-role">${member.role}</p>
                    <p class="team-bio">${member.bio}</p>
                </div>
            </div>
        `).join('');
    }

    // Default Team Data (fallback)
    getDefaultTeamData() {
        return [
            {
                name: "Ahmad Wijaya",
                role: "CEO & Founder",
                bio: "Ahli pertanian dengan 15 tahun pengalaman dalam transformasi digital sektor agrikultur.",
                image: "folderimage/team1.jpg",
                linkedin: "#",
                twitter: "#",
                email: "ahmad@nisevaagro.com"
            },
            {
                name: "Sari Dewi",
                role: "CTO",
                bio: "Spesialis IoT dan AI dengan passion dalam mengembangkan solusi teknologi untuk pertanian.",
                image: "folderimage/team2.jpg",
                linkedin: "#",
                twitter: "#",
                email: "sari@nisevaagro.com"
            },
            {
                name: "Budi Santoso",
                role: "Head of Agriculture",
                bio: "Pakar agronomi dengan fokus pada implementasi praktik pertanian berkelanjutan.",
                image: "folderimage/team3.jpg",
                linkedin: "#",
                twitter: "#",
                email: "budi@nisevaagro.com"
            }
        ];
    }

    // Load Stats Data from Backend
    async loadStatsData() {
        try {
            const response = await fetch('../backend/controllers/AboutController.php?action=getStats');
            const data = await response.json();

            if (data.success) {
                this.renderStats(data.stats);
            } else {
                this.renderStats(this.getDefaultStatsData());
            }
        } catch (error) {
            console.error('Error loading stats data:', error);
            this.renderStats(this.getDefaultStatsData());
        }
    }

    // Render Statistics
    renderStats(statsData) {
        const statsContainer = document.getElementById('statsContainer');
        
        statsContainer.innerHTML = statsData.map(stat => `
            <div class="stat-item-white">
                <span class="stat-number-white">${stat.number}</span>
                <span class="stat-label-white">${stat.label}</span>
            </div>
        `).join('');
    }

    // Default Stats Data (fallback)
    getDefaultStatsData() {
        return [
            { number: "10,000+", label: "Petani Terdaftar" },
            { number: "50+", label: "Kota Terjangkau" },
            { number: "15", label: "Provinsi" },
            { number: "98%", label: "Kepuasan Petani" }
        ];
    }

    // Timeline Animation
    setupTimelineAnimation() {
        const timelineItems = document.querySelectorAll('.timeline-item');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.3,
            rootMargin: '0px 0px -50px 0px'
        });

        timelineItems.forEach(item => {
            observer.observe(item);
        });
    }
}

// Backend Configuration
class AboutAPI {
    static async getTeamData() {
        try {
            const response = await fetch('../backend/controllers/AboutController.php?action=getTeam', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            return { success: false, error: 'Network error' };
        }
    }

    static async getStatsData() {
        try {
            const response = await fetch('../backend/controllers/AboutController.php?action=getStats', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            return { success: false, error: 'Network error' };
        }
    }

    static async submitContact(formData) {
        try {
            const response = await fetch('../backend/controllers/AboutController.php?action=contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            return { success: false, error: 'Network error' };
        }
    }
}

// Utility Functions
const AboutUtils = {
    // Format number with commas
    formatNumber: (num) => {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },

    // Animate counting numbers
    animateCount: (element, target, duration = 2000) => {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = AboutUtils.formatNumber(target);
                clearInterval(timer);
            } else {
                element.textContent = AboutUtils.formatNumber(Math.floor(current));
            }
        }, 16);
    },

    // Debounce function for performance
    debounce: (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AboutPage();
});

// Export for global access if needed
window.AboutPage = AboutPage;
window.AboutAPI = AboutAPI;
window.AboutUtils = AboutUtils;