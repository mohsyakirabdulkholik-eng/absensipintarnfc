window.addEventListener("scroll", function () {
  const navbar = document.querySelector(".landing-navbar");

  if (window.scrollY > 40) {
    navbar.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
  }
});

const counters = document.querySelectorAll(".counter");

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      const counter = entry.target;

      const target = +counter.dataset.target;

      let current = 0;

      const increment = target / 120;

      const update = () => {
        current += increment;

        if (current < target) {
          counter.innerText = Math.ceil(current).toLocaleString("id-ID");

          requestAnimationFrame(update);
        } else {
          counter.innerText = target.toLocaleString("id-ID");
        }
      };

      update();

      observer.unobserve(counter);
    }
  });
});

counters.forEach((counter) => observer.observe(counter));
