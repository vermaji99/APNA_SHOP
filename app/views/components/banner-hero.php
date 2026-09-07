<?php
/**
 * Hero Banner Slideshow Component
 * @var array $banners
 */
?>

<div class="hero-wrapper">
    <?php if (!empty($banners) && count($banners) > 0): ?>
        <div class="hero-slider" id="heroSlider">
            <?php foreach ($banners as $i => $banner): ?>
                <div class="hero-slide <?php echo $i === 0 ? 'active' : ''; ?>"
                     style="background-image: linear-gradient(90deg, rgba(10, 10, 10, 0.75) 0%, rgba(10, 10, 10, 0.35) 45%, rgba(10, 10, 10, 0.05) 100%), url('/uploads/banners/<?php echo htmlspecialchars($banner['image'] ?: 'default.jpg'); ?>');">
                    <div class="container">
                        <div class="hero-content">
                            <span class="hero-eyebrow">NEW SEASON / <?php echo date('Y'); ?> COLLECTION</span>
                            <h1 class="hero-title"><?php echo htmlspecialchars($banner['title']); ?></h1>
                            <?php if (!empty($banner['subtitle'])): ?>
                                <p class="hero-subtitle"><?php echo htmlspecialchars($banner['subtitle']); ?></p>
                            <?php else: ?>
                                <p class="hero-subtitle">Premium menswear designed for confidence, comfort and modern style.</p>
                            <?php endif; ?>
                            <div class="hero-actions">
                                <a href="<?php echo htmlspecialchars($banner['link'] ?: '/pages/shop.php'); ?>" class="btn btn-primary btn-lg">
                                    <?php echo htmlspecialchars($banner['link_text'] ?? 'SHOP NOW'); ?>
                                </a>
                                <a href="/pages/shop.php?sale=1" class="btn btn-outline btn-lg">EXPLORE SALE</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (count($banners) > 1): ?>
                <!-- Slider Controls -->
                <button class="hero-ctrl hero-prev" id="heroPrev" aria-label="Previous Slide">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="hero-ctrl hero-next" id="heroNext" aria-label="Next Slide">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
                <!-- Slider Indicators -->
                <div class="hero-dots" id="heroDots">
                    <?php foreach ($banners as $i => $banner): ?>
                        <span class="hero-dot <?php echo $i === 0 ? 'active' : ''; ?>" data-slide="<?php echo $i; ?>"></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Default Hero (Fallback - Bright & Vivid) -->
        <div class="hero-slide active" style="background-image: linear-gradient(90deg, rgba(10, 10, 10, 0.75) 0%, rgba(10, 10, 10, 0.3) 50%, rgba(10, 10, 10, 0.05) 100%), url('https://images.unsplash.com/photo-1490578474895-699bc4e2cf59?auto=format&fit=crop&w=1920&q=80');">
            <div class="container">
                <div class="hero-content">
                    <span class="hero-eyebrow">NEW SEASON / <?php echo date('Y'); ?> COLLECTION</span>
                    <h1 class="hero-title">ELEVATE YOUR EVERYDAY</h1>
                    <p class="hero-subtitle">Premium menswear designed for confidence, comfort and modern style.</p>
                    <div class="hero-actions">
                        <a href="/pages/shop.php" class="btn btn-primary btn-lg">SHOP NOW</a>
                        <a href="/pages/shop.php?sale=1" class="btn btn-outline btn-lg">EXPLORE SALE</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('heroSlider');
    if (!slider) return;
    const slides = slider.querySelectorAll('.hero-slide');
    const dots = slider.querySelectorAll('.hero-dot');
    const prevBtn = document.getElementById('heroPrev');
    const nextBtn = document.getElementById('heroNext');
    if (slides.length <= 1) return;

    let currentIndex = 0;
    let autoSlideInterval;

    function goToSlide(index) {
        slides[currentIndex].classList.remove('active');
        if (dots[currentIndex]) dots[currentIndex].classList.remove('active');
        currentIndex = (index + slides.length) % slides.length;
        slides[currentIndex].classList.add('active');
        if (dots[currentIndex]) dots[currentIndex].classList.add('active');
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(() => goToSlide(currentIndex + 1), 5000);
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            goToSlide(currentIndex - 1);
            resetAutoSlide();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            goToSlide(currentIndex + 1);
            resetAutoSlide();
        });
    }

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => {
            goToSlide(idx);
            resetAutoSlide();
        });
    });

    startAutoSlide();
});
</script>
