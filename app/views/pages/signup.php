<?php
/**
 * Signup Page
 */
?>

<section class="section" style="padding: 4rem 0;">
    <div class="container">
        <div style="display: flex; align-items: center; justify-content: center; min-height: 65vh;">
            <div class="card" style="max-width: 480px; width: 100%; background: #141414; border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 2.5rem 2rem;">
                <div class="card-header" style="text-align: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 2rem;">
                    <span style="font-size: 0.75rem; font-weight: 800; letter-spacing: 0.2em; color: var(--primary); text-transform: uppercase;">JOIN APNA-MENS</span>
                    <h1 style="font-size: 1.875rem; font-weight: 800; color: #ffffff; text-transform: uppercase; margin-top: 0.25rem; margin-bottom: 0;">CREATE ACCOUNT</h1>
                </div>
                
                <form id="signup-form" class="card-body" style="margin-bottom: 0;">
                    <input type="hidden" name="csrf_token" value="<?php echo Session::get('csrf_token'); ?>">
                    
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label" for="name" style="display: block; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 0.5rem;">FULL NAME</label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" required style="width: 100%; background: #1A1A1A; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.875rem 1rem; color: #fff; font-size: 0.875rem;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label" for="email" style="display: block; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 0.5rem;">EMAIL ADDRESS</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="name@example.com" required style="width: 100%; background: #1A1A1A; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.875rem 1rem; color: #fff; font-size: 0.875rem;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label" for="phone" style="display: block; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 0.5rem;">PHONE NUMBER</label>
                        <input type="tel" id="phone" name="phone" class="form-input" placeholder="+91 9876543210" style="width: 100%; background: #1A1A1A; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.875rem 1rem; color: #fff; font-size: 0.875rem;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label" for="password" style="display: block; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 0.5rem;">PASSWORD</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="At least 8 characters" required minlength="8" style="width: 100%; background: #1A1A1A; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.875rem 1rem; color: #fff; font-size: 0.875rem;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 1.75rem;">
                        <label class="form-label" for="password_confirm" style="display: block; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 0.5rem;">CONFIRM PASSWORD</label>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-input" placeholder="Repeat password" required minlength="8" style="width: 100%; background: #1A1A1A; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.875rem 1rem; color: #fff; font-size: 0.875rem;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="width: 100%;">CREATE ACCOUNT</button>
                    </div>
                    
                    <div style="text-align: center;">
                        <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0;">Already have an account? <a href="/pages/login.php" style="color: var(--primary); font-weight: 700;">Log in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
