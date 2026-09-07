<?php
/**
 * Contact Page
 * Note: This file is loaded directly by index.php which handles the layout
 */
?>

<section class="section">
    <div class="container">
        <h1 class="reveal">Contact Us</h1>
        
        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 3rem;">
            <div class="reveal">
                <div class="card">
                    <h3 class="card-title">Get in Touch</h3>
                    <form class="card-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea class="form-textarea" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
            
            <div class="reveal reveal-delay-1">
                <div class="card">
                    <h3 class="card-title">Contact Information</h3>
                    <div class="card-body">
                        <p><strong>Email:</strong> info@apnamens.com</p>
                        <p><strong>Phone:</strong> +91 9876543210</p>
                        <p><strong>Address:</strong> 123 Fashion Street, Mumbai, India</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

