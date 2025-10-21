// admin-custom.js — slideToggle + accordion-style + auto-open when child is .active + robust sidebar height
document.addEventListener('DOMContentLoaded', function () {

  /* ---------- slide helpers ---------- */
  function slideUp(el, duration = 320) {
    el.style.transitionProperty = 'max-height';
    el.style.transitionDuration = duration + 'ms';
    el.style.maxHeight = el.scrollHeight + 'px';
    window.getComputedStyle(el).maxHeight; // force repaint
    el.style.maxHeight = '0';
    el.classList.remove('open');
    setTimeout(function () {
      el.style.removeProperty('max-height');
      el.style.removeProperty('transition-duration');
      el.style.removeProperty('transition-property');
    }, duration + 20);
  }

  function slideDown(el, duration = 320) {
    el.classList.add('open');
    el.style.transitionProperty = 'max-height';
    el.style.transitionDuration = duration + 'ms';
    el.style.maxHeight = '0';
    window.getComputedStyle(el).maxHeight;
    el.style.maxHeight = el.scrollHeight + 'px';
    setTimeout(function () {
      el.style.removeProperty('max-height');
      el.style.removeProperty('transition-duration');
      el.style.removeProperty('transition-property');
    }, duration + 20);
  }

  function slideToggle(el, duration = 320) {
    if (!el) return;
    if (el.classList.contains('open')) {
      slideUp(el, duration);
    } else {
      slideDown(el, duration);
    }
  }

  /* ---------- SimpleBar safe re-calculation (best-effort) ---------- */
  function recalcSimpleBar() {
    try {
      document.querySelectorAll('#sidebar .simplebar').forEach(function (el) {
        var inst = el.SimpleBar || el._simplebar || (el.dataset && el.dataset.simplebarInstance);
        if (inst && typeof inst.recalculate === 'function') {
          inst.recalculate();
        } else if (el._simplebar && typeof el._simplebar.recalculate === 'function') {
          el._simplebar.recalculate();
        } else if (el.SimpleBar && el.SimpleBar.recalculate) {
          el.SimpleBar.recalculate();
        }
      });
    } catch (e) { /* ignore */ }
  }

  /* ---------- compute and set usable max-height for sidebar-content ---------- */
  function setSidebarHeight() {
    var sidebar = document.getElementById('sidebar');
    var content = sidebar && sidebar.querySelector('.sidebar-content');
    if (!sidebar || !content) return;

    var rect = content.getBoundingClientRect();
    var topOffset = Math.max(0, rect.top);
    var newMax = window.innerHeight - topOffset;
    if (newMax < 200) newMax = Math.max(window.innerHeight - 56, 200);

    content.style.maxHeight = newMax + 'px';
    recalcSimpleBar();
  }

  /* ---------- helper: close sibling open menus at same level (accordion) ---------- */
  function closeSiblingMenus(currentMenu, siblingContainer) {
    if (!siblingContainer) return;
    Array.from(siblingContainer.children).forEach(function (childLi) {
      // find immediate submenu (ul.menu.slide_toggle) of this child li
      var childMenu = null;
      Array.from(childLi.children).forEach(function (ch) {
        if (ch.tagName === 'UL' && ch.classList.contains('menu') && ch.classList.contains('slide_toggle')) {
          childMenu = ch;
        }
      });

      if (childMenu && childMenu !== currentMenu && childMenu.classList.contains('open')) {
        slideUp(childMenu, 320);
        // find immediate toggle element (header.show_menu OR .sidebar-link) among children
        var toggleEl = null;
        Array.from(childLi.children).forEach(function (ch) {
          if (ch.nodeType === 1) {
            if (ch.classList && ch.classList.contains('header') && ch.classList.contains('show_menu')) {
              toggleEl = ch;
            } else if (ch.classList && ch.classList.contains('sidebar-link')) {
              toggleEl = ch;
            }
          }
        });
        if (toggleEl) {
          var plus = toggleEl.querySelector('.plus');
          if (plus) plus.classList.remove('plus-change');
          toggleEl.setAttribute('aria-expanded', 'false');
        }
      }
    });
  }

  /* ---------- start up ---------- */
  setSidebarHeight();
  window.addEventListener('resize', setSidebarHeight);
  window.addEventListener('orientationchange', setSidebarHeight);

  /* ---------- header.show_menu listeners (accordion-style) ---------- */
  document.querySelectorAll('.header.show_menu').forEach(function (header) {
    // accessibility
    header.setAttribute('tabindex', '0');
    header.setAttribute('role', 'button');
    header.setAttribute('aria-expanded', 'false');

    header.addEventListener('click', function () {
      var submenu = header.nextElementSibling;
      if (!submenu || !submenu.classList.contains('slide_toggle')) return;

      // accordion: close sibling menus at same level
      var parentUL = header.parentElement && header.parentElement.parentElement ? header.parentElement.parentElement : null;
      closeSiblingMenus(submenu, parentUL);

      // toggle current
      slideToggle(submenu, 320);

      var plus = header.querySelector('.plus');
      if (plus) plus.classList.toggle('plus-change');

      // finalize aria + scroll + height after animation
      setTimeout(function () {
        header.setAttribute('aria-expanded', submenu.classList.contains('open') ? 'true' : 'false');
        setSidebarHeight();
        if (submenu.classList.contains('open') && typeof submenu.scrollIntoView === 'function') {
          submenu.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
      }, 360);
    });

    // keyboard support
    header.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        header.click();
      }
    });
  });

  /* ---------- handle sidebar-links that have nested ULs (make them toggles, accordion-style) ---------- */
  document.querySelectorAll('.sidebar-item > .sidebar-link').forEach(function (link) {
    var next = link.nextElementSibling;
    if (next && next.tagName === 'UL') {
      // ensure submenu has proper classes
      if (!next.classList.contains('menu')) {
        next.classList.add('menu', 'slide_toggle', 'list-unstyled', 'mb-0', 'ms-3');
      }

      // add a plus span if missing
      if (!link.querySelector('.plus')) {
        var plus = document.createElement('span');
        plus.className = 'plus';
        link.appendChild(plus);
      }

      // accessibility
      link.setAttribute('role', 'button');
      link.setAttribute('tabindex', '0');
      link.setAttribute('aria-expanded', 'false');

      link.addEventListener('click', function (e) {
        var href = (link.getAttribute('href') || '').trim();
        if (href === '' || href === '#') e.preventDefault();

        // accordion: close sibling menus at same level
        var parentUL = link.parentElement && link.parentElement.parentElement ? link.parentElement.parentElement : null;
        closeSiblingMenus(next, parentUL);

        // toggle current
        slideToggle(next, 320);

        var plusEl = link.querySelector('.plus');
        if (plusEl) plusEl.classList.toggle('plus-change');

        setTimeout(function () {
          link.setAttribute('aria-expanded', next.classList.contains('open') ? 'true' : 'false');
          setSidebarHeight();
        }, 360);
      });

      // keyboard support
      link.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          link.click();
        }
      });
    }
  });

  /* ---------- auto-open ancestor menus for .sidebar-link.active (and keep accordion behavior) ---------- */
  var activeLink = document.querySelector('.sidebar-link.active');
  if (activeLink) {
    var walker = activeLink.parentElement; // start from li
    while (walker && walker !== document) {
      var possibleMenu = walker.parentElement; // parent of li (could be ul.menu.slide_toggle)
      if (possibleMenu && possibleMenu.tagName === 'UL' && possibleMenu.classList.contains('menu') && possibleMenu.classList.contains('slide_toggle')) {
        // close sibling menus at this level (accordion)
        var container = possibleMenu.parentElement && possibleMenu.parentElement.parentElement ? possibleMenu.parentElement.parentElement : null;
        closeSiblingMenus(possibleMenu, container);

        // open this menu
        if (!possibleMenu.classList.contains('open')) {
          possibleMenu.classList.add('open');
          possibleMenu.style.maxHeight = possibleMenu.scrollHeight + 'px';
          var header = possibleMenu.previousElementSibling;
          if (header) {
            var plus = header.querySelector('.plus');
            if (plus) plus.classList.add('plus-change');
            header.setAttribute('aria-expanded', 'true');
          }
        }
      }
      walker = walker.parentElement;
    }

    // cleanup animation-ready styles and set heights
    setTimeout(function () {
      document.querySelectorAll('.menu.slide_toggle').forEach(function (menu) {
        menu.style.removeProperty('max-height');
      });
      setSidebarHeight();
    }, 350);
  }

  // 🔹 Global Image Preview Function
  function previewImage(input, previewContainerId) {
      const previewContainer = document.getElementById(previewContainerId);
      if (!previewContainer) return;

      previewContainer.innerHTML = ""; // Clear previous previews

      if (input.files) {
          Array.from(input.files).forEach(file => {
              const reader = new FileReader();
              reader.onload = function(e) {
                  const img = document.createElement("img");
                  img.src = e.target.result;
                  img.classList.add("rounded", "shadow-sm", "m-1");
                  img.style.maxWidth = "150px";
                  img.style.maxHeight = "150px";
                  previewContainer.appendChild(img);
              }
              reader.readAsDataURL(file);
          });
      }
  }

  // 🔹 Auto-bind image preview inputs globally
  const primaryInput = document.getElementById("primary_image");
  if (primaryInput) {
      primaryInput.addEventListener("change", function() {
          previewImage(this, "primary_image_preview");
      });
  }

  const imagesInput = document.getElementById("images");
  if (imagesInput) {
      imagesInput.addEventListener("change", function() {
          previewImage(this, "images_preview");
      });
  }

  // 🔹 Subcategory Loader (Global)
  function loadSubcategories(categorySlug, selectedSubcategoryId = null) {
      const subWrapper = document.getElementById("subcategory-wrapper");
      const subSelect = document.getElementById("subcategory_id");

      if (!categorySlug) {
          if (subWrapper) subWrapper.style.display = "none";
          if (subSelect) subSelect.value = "";
          return;
      }

      const url = `/admin/categories/${categorySlug}/children`;

      fetch(url)
          .then(response => response.json())
          .then(data => {
              if (subSelect) {
                  subSelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
                  if (data.length > 0) {
                      data.forEach(child => {
                          const opt = document.createElement("option");
                          opt.value = child.id;
                          opt.textContent = child.name;
                          subSelect.appendChild(opt);
                      });

                      if (selectedSubcategoryId) {
                          subSelect.value = selectedSubcategoryId;
                      }

                      if (subWrapper) subWrapper.style.display = "block";
                  } else {
                      if (subWrapper) subWrapper.style.display = "none";
                  }
              }
          })
          .catch(err => console.error("Subcategory load error:", err));
  }

  // 🔹 Auto-bind for category change
      const categorySelect = document.getElementById("category_id");
      if (categorySelect) {
          categorySelect.addEventListener("change", function () {
              const slug = categorySelect.options[categorySelect.selectedIndex]?.dataset.slug;
              loadSubcategories(slug);
          });

          // Restore old category + subcategory (Blade injects dataset via body attributes)
          const oldCategoryId = document.body.dataset.oldCategoryId;
          const oldSubcategoryId = document.body.dataset.oldSubcategoryId;

          if (oldCategoryId) {
              const oldOption = categorySelect.querySelector(`option[value="${oldCategoryId}"]`);
              if (oldOption) {
                  const oldSlug = oldOption.dataset.slug;
                  loadSubcategories(oldSlug, oldSubcategoryId);
              }
          }
      }

      // 🔹 Global Filters Collapse Toggle
      if (typeof bootstrap !== 'undefined') {
          const header = document.getElementById('filtersHeader');
          const collapseEl = document.getElementById('filtersCollapse');
          if (header && collapseEl) {
              const bsCollapse = new bootstrap.Collapse(collapseEl, { toggle: false });

              function isInteractiveTarget(el) {
                  return !!el.closest('a, button, input, select, textarea, label');
              }

              header.addEventListener('click', function (e) {
                  if (isInteractiveTarget(e.target)) return;
                  bsCollapse.toggle();
              });

              header.addEventListener('keydown', function (e) {
                  if ((e.key === 'Enter' || e.key === ' ') && !isInteractiveTarget(e.target)) {
                      e.preventDefault();
                      bsCollapse.toggle();
                  }
              });

              collapseEl.addEventListener('shown.bs.collapse', function () {
                  header.setAttribute('aria-expanded', 'true');
              });

              collapseEl.addEventListener('hidden.bs.collapse', function () {
                  header.setAttribute('aria-expanded', 'false');
              });
          }
      }

    // 🔹 Disable submit button on all forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerText = 'Saving...';
            }
        });
    });

    const warningMessage = document.body.dataset.warning;

    if (warningMessage) {
        if (Notification.permission === "granted") {
            new Notification("Low Stock Alert", {
                body: warningMessage,
                icon: "{{ asset('images/icons/logo1.png') }}" // ✅ use your existing icon
            });
        } else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(function (permission) {
                if (permission === "granted") {
                    new Notification("Low Stock Alert", {
                        body: warningMessage,
                        icon: "{{ asset('images/icons/logo1.png') }}"
                    });
                }
            });
        }
    }


});
