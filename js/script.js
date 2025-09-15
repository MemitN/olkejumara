document.addEventListener("DOMContentLoaded", () => {
  // Initialize all interactive features
  initPackageFilters()
  initGalleryFilters()
  initCounterAnimation()
  initScrollAnimations()
  initDropdownHovers()
  initParallaxEffects()
  initTypewriterEffect()
  initSmoothScrolling()
  initFormEnhancements()
  initImageLazyLoading()
  initHeroSlider()
  initLiveChatWidget() // New feature
  initWeatherWidget() // New feature
  initBookingProgress() // New feature

  // Add page transition effect
  document.body.classList.add("page-transition")

  // Initialize floating elements animation
  initFloatingElements()
})

function initHeroSlider() {
  const slides = document.querySelectorAll(".hero-slide")
  const indicators = document.querySelectorAll(".indicator")
  let currentSlide = 0
  let slideInterval
  let isTransitioning = false

  function startSlideshow() {
    slideInterval = setInterval(() => {
      if (!isTransitioning) {
        nextSlide()
      }
    }, 3000) // Reduced from 5000ms to 3000ms for more dynamic feel
  }

  function stopSlideshow() {
    clearInterval(slideInterval)
  }

  function showSlide(index) {
    if (isTransitioning) return
    isTransitioning = true

    // Remove active class from all slides and indicators
    slides.forEach((slide) => slide.classList.remove("active"))
    indicators.forEach((indicator) => indicator.classList.remove("active"))

    // Add active class to current slide and indicator with enhanced animation
    slides[index].classList.add("active")
    if (indicators[index]) {
      indicators[index].classList.add("active")
    }

    currentSlide = index

    setTimeout(() => {
      isTransitioning = false
    }, 500) // Match CSS transition duration
  }

  function nextSlide() {
    const next = (currentSlide + 1) % slides.length
    showSlide(next)
  }

  function prevSlide() {
    const prev = (currentSlide - 1 + slides.length) % slides.length
    showSlide(prev)
  }

  if (slides.length > 1) {
    startSlideshow()

    // Pause on hover with smooth restart
    const heroSection = document.querySelector(".hero-section")
    if (heroSection) {
      heroSection.addEventListener("mouseenter", stopSlideshow)
      heroSection.addEventListener("mouseleave", () => {
        // Restart after a brief delay when mouse leaves
        setTimeout(startSlideshow, 1000)
      })
    }

    let touchStartX = 0
    let touchEndX = 0

    heroSection?.addEventListener("touchstart", (e) => {
      touchStartX = e.changedTouches[0].screenX
    })

    heroSection?.addEventListener("touchend", (e) => {
      touchEndX = e.changedTouches[0].screenX
      handleSwipe()
    })

    function handleSwipe() {
      const swipeThreshold = 50
      const diff = touchStartX - touchEndX

      if (Math.abs(diff) > swipeThreshold) {
        stopSlideshow()
        if (diff > 0) {
          nextSlide() // Swipe left - next slide
        } else {
          prevSlide() // Swipe right - previous slide
        }
        setTimeout(startSlideshow, 2000)
      }
    }
  }

  // Make navigation functions globally available with enhanced functionality
  window.changeSlide = (direction) => {
    if (isTransitioning) return
    stopSlideshow()
    if (direction === 1) {
      nextSlide()
    } else {
      prevSlide()
    }
    setTimeout(startSlideshow, 2000) // Reduced from 3000ms
  }

  window.currentSlide = (index) => {
    if (isTransitioning) return
    stopSlideshow()
    showSlide(index - 1) // Convert to 0-based index
    setTimeout(startSlideshow, 2000) // Reduced from 3000ms
  }

  document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft") {
      window.changeSlide(-1)
    } else if (e.key === "ArrowRight") {
      window.changeSlide(1)
    }
  })
}

function initPackageFilters() {
  const filterButtons = document.querySelectorAll(".package-tabs .nav-link")
  const packageItems = document.querySelectorAll(".package-item")

  filterButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const filter = this.getAttribute("data-filter")

      // Update active button with enhanced animation
      filterButtons.forEach((btn) => btn.classList.remove("active"))
      this.classList.add("active")

      // Enhanced filter animation with better timing
      packageItems.forEach((item, index) => {
        const category = item.getAttribute("data-category")
        if (filter === "all" || category === filter) {
          setTimeout(() => {
            item.style.display = "block"
            item.style.animation = "fadeInUp 0.5s ease-out forwards"
            item.style.opacity = "1"
            item.style.transform = "translateY(0)"
          }, index * 80) // Reduced delay for faster animation
        } else {
          item.style.animation = "fadeOut 0.2s ease-out forwards"
          item.style.transform = "translateY(-20px)"
          setTimeout(() => {
            item.style.display = "none"
          }, 200)
        }
      })
    })
  })
}

function initGalleryFilters() {
  const filterButtons = document.querySelectorAll(".gallery-tabs .nav-link")
  const galleryItems = document.querySelectorAll(".gallery-item")

  filterButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const filter = this.getAttribute("data-filter")

      filterButtons.forEach((btn) => btn.classList.remove("active"))
      this.classList.add("active")

      galleryItems.forEach((item, index) => {
        const category = item.getAttribute("data-category")
        if (filter === "all" || category === filter) {
          setTimeout(() => {
            item.style.display = "block"
            item.style.animation = "zoomIn 0.4s ease-out forwards"
            item.style.transform = "scale(1)"
          }, index * 60) // Faster stagger
        } else {
          item.style.animation = "zoomOut 0.2s ease-out forwards"
          item.style.transform = "scale(0.8)"
          setTimeout(() => {
            item.style.display = "none"
          }, 200)
        }
      })
    })
  })
}

function initCounterAnimation() {
  const counters = document.querySelectorAll(".counter")
  const observerOptions = {
    threshold: 0.5, // Reduced threshold for earlier trigger
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const counter = entry.target
        const target = Number.parseInt(counter.getAttribute("data-target"))
        animateCounter(counter, target)
        observer.unobserve(counter)
      }
    })
  }, observerOptions)

  counters.forEach((counter) => observer.observe(counter))
}

function animateCounter(element, target) {
  let current = 0
  const increment = target / 80 // Smoother animation with more steps
  const timer = setInterval(() => {
    current += increment
    if (current >= target) {
      element.textContent = target.toLocaleString() // Add number formatting
      clearInterval(timer)
    } else {
      element.textContent = Math.floor(current).toLocaleString()
    }
  }, 25) // Faster update rate
}

function initScrollAnimations() {
  const animateElements = document.querySelectorAll(
    ".feature-card, .package-card, .gallery-item, .testimonial-card, .contact-item, .wildlife-card",
  )

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const animationOrder = entry.target.style.getPropertyValue("--animation-order") || 0
          setTimeout(() => {
            entry.target.classList.add("scroll-animate", "animate")
            entry.target.style.transform = "translateY(0)"
            entry.target.style.opacity = "1"
          }, animationOrder * 100)
          observer.unobserve(entry.target) // Stop observing once animated
        }
      })
    },
    {
      threshold: 0.1,
      rootMargin: "0px 0px -50px 0px", // Trigger animation earlier
    },
  )

  animateElements.forEach((el) => {
    el.classList.add("scroll-animate")
    el.style.opacity = "0"
    el.style.transform = "translateY(30px)"
    el.style.transition = "all 0.6s ease-out"
    observer.observe(el)
  })
}

function initDropdownHovers() {
  const dropdowns = document.querySelectorAll(".dropdown")

  dropdowns.forEach((dropdown) => {
    const menu = dropdown.querySelector(".dropdown-menu")
    let timeout

    dropdown.addEventListener("mouseenter", () => {
      clearTimeout(timeout)
      menu.style.display = "block"
      setTimeout(() => menu.classList.add("show"), 10)
    })

    dropdown.addEventListener("mouseleave", () => {
      timeout = setTimeout(() => {
        menu.classList.remove("show")
        setTimeout(() => (menu.style.display = "none"), 200)
      }, 150)
    })
  })
}

function initParallaxEffects() {
  const heroSection = document.querySelector(".hero-section")
  if (heroSection && window.innerWidth > 768) {
    // Only on desktop
    let ticking = false

    function updateParallax() {
      const scrolled = window.pageYOffset
      const rate = scrolled * -0.3 // Reduced intensity for smoother effect
      heroSection.style.transform = `translateY(${rate}px)`
      ticking = false
    }

    window.addEventListener("scroll", () => {
      if (!ticking) {
        requestAnimationFrame(updateParallax)
        ticking = true
      }
    })
  }
}

function initTypewriterEffect() {
  const typewriterElements = document.querySelectorAll(".typewriter")

  typewriterElements.forEach((element) => {
    const text = element.textContent
    element.textContent = ""
    element.style.borderRight = "2px solid #ffd700"

    let i = 0
    const timer = setInterval(() => {
      element.textContent += text.charAt(i)
      i++
      if (i > text.length) {
        clearInterval(timer)
        setTimeout(() => {
          element.style.borderRight = "none"
        }, 1000)
      }
    }, 100)
  })
}

function initSmoothScrolling() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))
      if (target) {
        const offsetTop = target.offsetTop - 80 // Account for fixed navbar
        window.scrollTo({
          top: offsetTop,
          behavior: "smooth",
        })
      }
    })
  })
}

function initFormEnhancements() {
  const forms = document.querySelectorAll("form")

  forms.forEach((form) => {
    const inputs = form.querySelectorAll("input, select, textarea")

    inputs.forEach((input) => {
      // Add floating label effect
      input.addEventListener("focus", () => {
        input.parentElement.classList.add("focused")
      })

      input.addEventListener("blur", () => {
        if (!input.value) {
          input.parentElement.classList.remove("focused")
        }
      })

      // Real-time validation
      input.addEventListener("input", () => {
        validateField(input)
      })
    })
  })

  // Newsletter form enhancement
  const newsletterForm = document.querySelector("form")
  if (newsletterForm) {
    newsletterForm.addEventListener("submit", function (e) {
      e.preventDefault()
      const email = this.querySelector('input[type="email"]').value

      if (!isValidEmail(email)) {
        showNotification("Please enter a valid email address", "error")
        return
      }

      const button = this.querySelector("button")
      const originalText = button.textContent

      button.innerHTML = '<span class="spinner"></span> Subscribing...'
      button.disabled = true

      setTimeout(() => {
        button.textContent = "Subscribed!"
        button.classList.remove("btn-dark")
        button.classList.add("btn-success")
        showNotification("Successfully subscribed to our newsletter!", "success")

        setTimeout(() => {
          button.textContent = originalText
          button.classList.remove("btn-success")
          button.classList.add("btn-dark")
          button.disabled = false
          this.reset()
        }, 2000)
      }, 1500)
    })
  }
}

function validateField(field) {
  const value = field.value.trim()
  const fieldType = field.type
  let isValid = true

  switch (fieldType) {
    case "email":
      isValid = isValidEmail(value)
      break
    case "tel":
      isValid = value.length >= 10
      break
    default:
      isValid = value.length > 0
  }

  if (isValid) {
    field.classList.remove("is-invalid")
    field.classList.add("is-valid")
  } else {
    field.classList.remove("is-valid")
    field.classList.add("is-invalid")
  }
}

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

function showNotification(message, type = "info") {
  const notification = document.createElement("div")
  notification.className = `notification notification-${type}`
  notification.innerHTML = `
    <div class="notification-content">
      <i class="fas fa-${type === "success" ? "check-circle" : type === "error" ? "exclamation-circle" : "info-circle"}"></i>
      <span>${message}</span>
      <button class="notification-close">&times;</button>
    </div>
  `

  document.body.appendChild(notification)

  setTimeout(() => {
    notification.classList.add("show")
  }, 100)

  const closeBtn = notification.querySelector(".notification-close")
  closeBtn.addEventListener("click", () => {
    notification.classList.remove("show")
    setTimeout(() => {
      document.body.removeChild(notification)
    }, 300)
  })

  setTimeout(() => {
    if (document.body.contains(notification)) {
      notification.classList.remove("show")
      setTimeout(() => {
        document.body.removeChild(notification)
      }, 300)
    }
  }, 5000)
}

function initImageLazyLoading() {
  const images = document.querySelectorAll("img[data-src]")

  const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target
        img.src = img.dataset.src
        img.classList.remove("lazy")
        imageObserver.unobserve(img)
      }
    })
  })

  images.forEach((img) => {
    img.classList.add("lazy")
    imageObserver.observe(img)
  })
}

function initFloatingElements() {
  const floatingElements = document.querySelectorAll(".floating-animal")

  floatingElements.forEach((element, index) => {
    const randomDelay = Math.random() * 3
    const randomDuration = 3 + Math.random() * 3 // Faster animations
    const randomX = (Math.random() - 0.5) * 100
    const randomY = (Math.random() - 0.5) * 100

    element.style.animationDelay = `${randomDelay}s`
    element.style.animationDuration = `${randomDuration}s`
    element.style.setProperty("--random-x", `${randomX}px`)
    element.style.setProperty("--random-y", `${randomY}px`)
  })
}

function bookWhatsApp() {
  const message =
    "Hello! I'm interested in booking a safari with Olkeju Mara Tours. Could you please provide more information about your packages and availability?"
  const phoneNumber = "254713525190" // Updated phone number
  const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`

  // Add click animation
  const whatsappBtn = document.querySelector(".whatsapp-float")
  if (whatsappBtn) {
    whatsappBtn.style.transform = "scale(0.9)"
    setTimeout(() => {
      whatsappBtn.style.transform = "scale(1)"
    }, 150)
  }

  window.open(url, "_blank")
}

function bookPackage(packageName, price) {
  const message = `Hello! I'm interested in booking the "${packageName}" package (${price} per person). Could you please provide more details about:

• Package inclusions and itinerary
• Available dates
• Group size and requirements
• Payment options
• Pickup arrangements

Looking forward to hearing from you!`

  const phoneNumber = "254713525190" // Updated phone number
  const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`
  window.open(url, "_blank")
}

function initLiveChatWidget() {
  const chatWidget = document.createElement("div")
  chatWidget.className = "live-chat-widget"
  chatWidget.innerHTML = '<i class="fas fa-comments me-2"></i>Chat with us!'
  chatWidget.onclick = bookWhatsApp
  document.body.appendChild(chatWidget)
}

function initWeatherWidget() {
  const weatherWidget = document.createElement("div")
  weatherWidget.className = "weather-widget"
  weatherWidget.innerHTML = `
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <small class="text-muted">Maasai Mara Weather</small>
        <div class="fw-bold">25°C <i class="fas fa-sun text-warning"></i></div>
      </div>
      <small class="text-muted">Perfect for Safari!</small>
    </div>
  `

  const heroSection = document.querySelector(".hero-section")
  if (heroSection) {
    heroSection.appendChild(weatherWidget)
  }
}

function initBookingProgress() {
  const bookingForms = document.querySelectorAll('form[data-booking="true"]')

  bookingForms.forEach((form) => {
    const progressHTML = `
      <div class="booking-progress mb-4">
        <div class="progress-step active">
          <div class="step-circle">1</div>
          <div class="step-label">Select Package</div>
        </div>
        <div class="progress-step">
          <div class="step-circle">2</div>
          <div class="step-label">Choose Date</div>
        </div>
        <div class="progress-step">
          <div class="step-circle">3</div>
          <div class="step-label">Guest Details</div>
        </div>
        <div class="progress-step">
          <div class="step-circle">4</div>
          <div class="step-label">Confirmation</div>
        </div>
      </div>
    `
    form.insertAdjacentHTML("afterbegin", progressHTML)
  })
}

const lightboxStyles = `
  .lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
    opacity: 1;
    transition: opacity 0.2s ease;
  }
  
  .lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .lightbox img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    transition: opacity 0.15s ease;
  }
  
  .lightbox-close, .lightbox-prev, .lightbox-next {
    position: absolute;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    padding: 10px;
    border-radius: 50%;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
  }
  
  .lightbox-close {
    top: -60px;
    right: 0;
    width: 50px;
    height: 50px;
  }
  
  .lightbox-prev, .lightbox-next {
    top: 50%;
    transform: translateY(-50%);
    width: 60px;
    height: 60px;
    font-size: 2.5rem;
  }
  
  .lightbox-prev {
    left: -80px;
  }
  
  .lightbox-next {
    right: -80px;
  }
  
  .lightbox-counter {
    position: absolute;
    bottom: -50px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    background: rgba(0,0,0,0.7);
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 1rem;
  }
  
  .lightbox-close:hover, .lightbox-prev:hover, .lightbox-next:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
  }
  
  .lightbox-prev:hover {
    transform: translateY(-50%) scale(1.1);
  }
  
  .lightbox-next:hover {
    transform: translateY(-50%) scale(1.1);
  }
  
  .notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    padding: 1rem 1.5rem;
    z-index: 10000;
    transform: translateX(400px);
    transition: all 0.3s ease;
    max-width: 400px;
  }
  
  .notification.show {
    transform: translateX(0);
  }
  
  .notification-content {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .notification-success {
    border-left: 5px solid #28a745;
  }
  
  .notification-error {
    border-left: 5px solid #dc3545;
  }
  
  .notification-info {
    border-left: 5px solid #17a2b8;
  }
  
  .notification-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
    margin-left: auto;
  }
  
  .lazy {
    opacity: 0;
    transition: opacity 0.3s;
  }
  
  .lazy.loaded {
    opacity: 1;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  @keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-20px); }
  }
  
  @keyframes fadeInUp {
    from { 
      opacity: 0; 
      transform: translateY(30px); 
    }
    to { 
      opacity: 1; 
      transform: translateY(0); 
    }
  }
  
  @keyframes zoomIn {
    from { 
      opacity: 0; 
      transform: scale(0.8); 
    }
    to { 
      opacity: 1; 
      transform: scale(1); 
    }
  }
  
  @keyframes zoomOut {
    from { 
      opacity: 1; 
      transform: scale(1); 
    }
    to { 
      opacity: 0; 
      transform: scale(0.8); 
    }
  }
  
  @media (max-width: 768px) {
    .lightbox-prev {
      left: -60px;
    }
    
    .lightbox-next {
      right: -60px;
    }
    
    .lightbox-close {
      top: -50px;
    }
    
    .notification {
      right: 10px;
      left: 10px;
      max-width: none;
    }
  }
`

const styleSheet = document.createElement("style")
styleSheet.textContent = lightboxStyles
document.head.appendChild(styleSheet)

document.querySelectorAll(".gallery-item").forEach((item, index) => {
  item.addEventListener("click", function () {
    const img = this.querySelector("img")
    const allImages = document.querySelectorAll(".gallery-item img")

    const lightbox = document.createElement("div")
    lightbox.className = "lightbox"
    lightbox.innerHTML = `
      <div class="lightbox-content">
        <img src="${img.src}" alt="${img.alt}">
        <button class="lightbox-close">&times;</button>
        <button class="lightbox-prev">&#8249;</button>
        <button class="lightbox-next">&#8250;</button>
        <div class="lightbox-counter">${index + 1} / ${allImages.length}</div>
      </div>
    `

    document.body.appendChild(lightbox)
    document.body.style.overflow = "hidden"

    let currentIndex = index

    // Navigation functions
    const showImage = (newIndex) => {
      const lightboxImg = lightbox.querySelector("img")
      const counter = lightbox.querySelector(".lightbox-counter")

      // Add fade transition
      lightboxImg.style.opacity = "0"
      setTimeout(() => {
        lightboxImg.src = allImages[newIndex].src
        lightboxImg.alt = allImages[newIndex].alt
        counter.textContent = `${newIndex + 1} / ${allImages.length}`
        lightboxImg.style.opacity = "1"
      }, 150)

      currentIndex = newIndex
    }

    // Event listeners
    lightbox.querySelector(".lightbox-prev").addEventListener("click", () => {
      const newIndex = currentIndex > 0 ? currentIndex - 1 : allImages.length - 1
      showImage(newIndex)
    })

    lightbox.querySelector(".lightbox-next").addEventListener("click", () => {
      const newIndex = currentIndex < allImages.length - 1 ? currentIndex + 1 : 0
      showImage(newIndex)
    })

    lightbox.addEventListener("click", (e) => {
      if (e.target === lightbox || e.target.classList.contains("lightbox-close")) {
        lightbox.style.opacity = "0"
        setTimeout(() => {
          document.body.removeChild(lightbox)
          document.body.style.overflow = "auto"
        }, 200)
      }
    })

    // Keyboard navigation
    document.addEventListener("keydown", function handleKeydown(e) {
      if (e.key === "Escape") {
        lightbox.style.opacity = "0"
        setTimeout(() => {
          document.body.removeChild(lightbox)
          document.body.style.overflow = "auto"
        }, 200)
        document.removeEventListener("keydown", handleKeydown)
      } else if (e.key === "ArrowLeft") {
        lightbox.querySelector(".lightbox-prev").click()
      } else if (e.key === "ArrowRight") {
        lightbox.querySelector(".lightbox-next").click()
      }
    })
  })
})
