/**
 * SIPKO — Sistem Informasi Operasional Satpol PP Kab. Ketapang
 * main.js — Shared JavaScript Utilities
 * v2.0 — Dark/Light Mode + Mobile Responsive Support
 */

(function () {
  "use strict";

  /* ================================================================
     DARK / LIGHT MODE
  ================================================================ */
  const THEME_KEY = "sipko-theme";

  function applyTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem(THEME_KEY, theme);
    // Update toggle icon
    const btn = document.getElementById("darkModeToggle");
    if (btn) {
      btn.innerHTML = theme === "dark"
        ? '<i class="bi bi-sun-fill"></i>'
        : '<i class="bi bi-moon-fill"></i>';
      btn.title = theme === "dark" ? "Mode Terang" : "Mode Gelap";
    }
  }

  function initTheme() {
    const saved = localStorage.getItem(THEME_KEY);
    const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    applyTheme(saved || (prefersDark ? "dark" : "light"));
  }

  window.toggleTheme = function () {
    const current = document.documentElement.getAttribute("data-theme") || "light";
    applyTheme(current === "dark" ? "light" : "dark");
  };

  /* ================================================================
     SIDEBAR TOGGLE (Desktop & Mobile)
  ================================================================ */
  const isMobile = () => window.innerWidth <= 1199;

  function initSidebar() {
    const toggleBtn = document.getElementById("toggleSidebarBtn");
    const overlay   = document.getElementById("sidebarOverlay");
    const sidebar   = document.getElementById("sidebar");

    function openMobileSidebar() {
      document.body.classList.add("toggle-sidebar");
      if (overlay) overlay.classList.add("open");
    }

    function closeMobileSidebar() {
      document.body.classList.remove("toggle-sidebar");
      if (overlay) overlay.classList.remove("open");
    }

    function toggleDesktopSidebar() {
      document.body.classList.toggle("toggle-sidebar");
    }

    if (toggleBtn) {
      toggleBtn.addEventListener("click", () => {
        if (isMobile()) {
          document.body.classList.contains("toggle-sidebar")
            ? closeMobileSidebar()
            : openMobileSidebar();
        } else {
          toggleDesktopSidebar();
        }
      });
    }

    // Close sidebar when overlay is clicked on mobile
    if (overlay) {
      overlay.addEventListener("click", closeMobileSidebar);
    }

    // Close mobile sidebar when a nav link is clicked
    if (sidebar) {
      sidebar.querySelectorAll(".nav-link:not([data-bs-toggle='collapse']), .nav-content a").forEach((link) => {
        link.addEventListener("click", () => {
          if (isMobile()) closeMobileSidebar();
        });
      });
    }

    // On resize, reset sidebar state
    window.addEventListener("resize", () => {
      if (!isMobile()) {
        if (overlay) overlay.classList.remove("open");
      }
    });
  }

  /* ================================================================
     BACK TO TOP
  ================================================================ */
  const btt = document.querySelector(".back-to-top");
  if (btt) {
    window.addEventListener("scroll", () =>
      btt.classList.toggle("active", window.scrollY > 100)
    );
  }

  /* ================================================================
     SPA — PAGE SECTION NAVIGATION
  ================================================================ */
  window.showSection = function (id) {
    document.querySelectorAll(".page-section").forEach((p) =>
      p.classList.remove("active")
    );
    const target = document.getElementById("page-" + id);
    if (target) {
      target.classList.add("active");
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
    // Update breadcrumb title
    const titleEl = document.getElementById("pageTitle");
    if (titleEl && window.PAGE_TITLES && window.PAGE_TITLES[id]) {
      titleEl.textContent = window.PAGE_TITLES[id];
    }
    // Update mobile bottom nav active state
    updateMobileBottomNav(id);
    // Lazy chart rendering
    if (typeof window.renderChartFor === "function") {
      window.renderChartFor(id);
    }
  };

  /* ================================================================
     MOBILE BOTTOM NAV ACTIVE STATE
  ================================================================ */
  function updateMobileBottomNav(sectionId) {
    document.querySelectorAll(".mobile-bottom-nav a").forEach((a) => {
      a.classList.remove("active");
      if (a.dataset.section === sectionId) a.classList.add("active");
    });
  }

  /* ================================================================
     ACTIVE NAV LINK
  ================================================================ */
  window.setActive = function (el) {
    if (el.classList.contains("nav-link")) {
      document.querySelectorAll(".sidebar-nav .nav-link").forEach((l) => {
        l.classList.remove("active");
        l.classList.add("collapsed");
      });
      el.classList.add("active");
      el.classList.remove("collapsed");
    }
    if (el.tagName === "A" && el.closest(".nav-content")) {
      document.querySelectorAll(".nav-content a").forEach((a) =>
        a.classList.remove("active")
      );
      el.classList.add("active");
    }
  };

  /* ================================================================
     MODAL
  ================================================================ */
  window.openModal = function (id) {
    const el = document.getElementById(id);
    if (el) {
      el.classList.add("open");
      document.body.style.overflow = "hidden"; // prevent background scroll
    }
  };
  window.closeModal = function (id) {
    const el = document.getElementById(id);
    if (el) {
      el.classList.remove("open");
      document.body.style.overflow = "";
    }
  };

  document.querySelectorAll(".sipko-modal-overlay").forEach((overlay) => {
    overlay.addEventListener("click", (e) => {
      if (e.target === overlay) {
        overlay.classList.remove("open");
        document.body.style.overflow = "";
      }
    });
  });

  // Close modal with Escape key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      document.querySelectorAll(".sipko-modal-overlay.open").forEach((m) => {
        m.classList.remove("open");
        document.body.style.overflow = "";
      });
    }
  });

  /* ================================================================
     TOAST
  ================================================================ */
  window.showToast = function (msg, type = "info") {
    const icons  = {
      success: "bi-check-circle-fill",
      error:   "bi-x-circle-fill",
      info:    "bi-info-circle-fill"
    };
    const colors = {
      success: "#198754",
      error:   "#dc2626",
      info:    "#4154f1"
    };
    const container = document.getElementById("toastContainer");
    if (!container) return;
    const toast = document.createElement("div");
    toast.className = `sipko-toast ${type}`;
    toast.innerHTML = `
      <i class="bi ${icons[type]}" style="color:${colors[type]};font-size:18px;flex-shrink:0"></i>
      <span style="flex:1">${msg}</span>
      <i class="bi bi-x" style="cursor:pointer;color:#aaa;flex-shrink:0" onclick="this.closest('.sipko-toast').remove()"></i>
    `;
    container.appendChild(toast);
    setTimeout(() => {
      toast.style.cssText += "opacity:0;transform:translateX(120%);transition:all .3s";
      setTimeout(() => toast.remove(), 300);
    }, 3500);
  };

  /* ================================================================
     SWIPE TO OPEN/CLOSE SIDEBAR (Mobile touch)
  ================================================================ */
  let touchStartX = 0;
  let touchEndX   = 0;
  const SWIPE_THRESHOLD = 60;

  document.addEventListener("touchstart", (e) => {
    touchStartX = e.changedTouches[0].screenX;
  }, { passive: true });

  document.addEventListener("touchend", (e) => {
    touchEndX = e.changedTouches[0].screenX;
    if (!isMobile()) return;
    const diff = touchEndX - touchStartX;
    const overlay = document.getElementById("sidebarOverlay");
    // Swipe right from left edge to open
    if (touchStartX < 30 && diff > SWIPE_THRESHOLD) {
      document.body.classList.add("toggle-sidebar");
      if (overlay) overlay.classList.add("open");
    }
    // Swipe left to close
    if (diff < -SWIPE_THRESHOLD && document.body.classList.contains("toggle-sidebar")) {
      document.body.classList.remove("toggle-sidebar");
      if (overlay) overlay.classList.remove("open");
    }
  }, { passive: true });

  /* ================================================================
     INIT
  ================================================================ */
  document.addEventListener("DOMContentLoaded", () => {
    initTheme();
    initSidebar();
    if (typeof window.initCharts === "function") window.initCharts();
  });

})();
