<?php include 'includes/header.php'; ?>

  <main>
  <!-- HERO -->
  <section class="hero">
    <div class="container hero-container">
      <h1>Reuniting What’s Lost, Finding What’s Yours.</h1>
      <p>Connect with your lost treasures or report finds to help others in your community.</p>

      <div class="hero-buttons">
      </div>
    </div>
  </section>

  <!-- RECENTLY LOST ITEMS -->
  <section class="items-section">
    <div class="container">
      <h2>
        Recently Lost Items
        <a href="lost_list.php" class="view-all">View All Lost</a>
      </h2>
      <div class="items-grid">
        <!-- repeat this card for each item -->
        <article class="item-card">
          <div class="item-image">
            <span class="badge badge-lost">Lost</span>
            <img src="views/img/black leather wallet.png" alt="Lost Leather Wallet" />
          </div>
          <div class="item-content">
            <h3>Lost Leather Wallet</h3>
            <p>Black leather wallet containing ID, credit cards, and some cash. Lost near Central Park.</p>
            <div class="item-meta">
              <span class="category">Personal Belongings</span>
              <span class="time">2 hours ago</span>
            </div>
            <a href="#" class="btn btn-outline">View Details</a>
          </div>
        </article>
        <!-- …more cards… -->
      </div>
    </div>
  </section>

  <!-- RECENTLY FOUND ITEMS -->
  <section class="items-section">
    <div class="container">
      <h2>
        Recently Found Items
        <a href="found_list.php" class="view-all">View All Found</a>
      </h2>
      <div class="items-grid">
        <!-- same structure, but use badge-found -->
        <article class="item-card">
          <div class="item-image">
            <span class="badge badge-found">Found</span>
            <img src="views/img/silver apple watch.png" alt="Found Smartwatch" />
          </div>
          <div class="item-content">
            <h3>Found Smartwatch</h3>
            <p>Silver Apple Watch found near the university sports field. In good condition.</p>
            <div class="item-meta">
              <span class="category">Electronics</span>
              <span class="time">1 hour ago</span>
            </div>
            <a href="#" class="btn btn-outline">View Details</a>
          </div>
        </article>
        <!-- …more cards… -->
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="how-it-works">
    <div class="container">
      <h2>How It Works</h2>
      <div class="features">
        <div class="feature-card">
          <div class="icon report-icon"></div>
          <h3>Report Your Item</h3>
          <p>Easily submit details about your lost or found item through our simple, guided forms. Add photos for better recognition.</p>
        </div>
        <div class="feature-card">
          <div class="icon browse-icon"></div>
          <h3>Browse & Connect</h3>
          <p>Explore a wide database of reported items. Use filters to quickly find matches and connect with item owners.</p>
        </div>
        <div class="feature-card">
          <div class="icon reclaim-icon"></div>
          <h3>Reclaim Your Property</h3>
          <p>Once a match is found, safely arrange a meeting to verify and retrieve your item. We facilitate the secure handover.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- SUCCESS STORIES -->
  <section class="testimonials">
    <div class="container">
      <h2>Success Stories</h2>
      <div class="testimonials-grid">
        <div class="testimonial">
          <p>“I lost my engagement ring at the beach, and thanks to Lost & Found Hub, it was returned to me within 24 hours! I’m incredibly grateful for this service.”</p>
          <div class="user">
            <img src="views/img/sarah.png" alt="Sarah M." />
            <div>
              <span class="name">Sarah M.</span>
              <span class="role">Found Engagement Ring</span>
            </div>
          </div>
        </div>
        <!-- …more testimonials… -->
      </div>
    </div>
  </section>

  <!-- PARTNERS -->
  <section class="partners">
    <div class="container">
      <h2>Our Community Partners</h2>
      <div class="partners-logos">
        <img src="views/img/partner1.png" alt="Partner 1" />
        <img src="views/img/partner2.png" alt="Partner 2" />
        <img src="views/img/partner3.png" alt="Partner 3" />
        <!-- …etc… -->
      </div>
    </div>
  </section>
</main>

<!-- LOGIN MODAL -->
<div id="loginModal" class="modal">
  <div class="modal-backdrop"></div>
  <div class="modal-card login-card">
    <button class="modal-close" id="closeLogin">&times;</button>
    <a href="#" class="logo login-logo">
      <img src="views/img/logo.png" alt="Lost & Found Hub Logo" />
    </a>
    <h2>Login to Lost &amp; Found Hub</h2>
    <p class="subheading">Welcome back! Please enter your credentials to access your account.</p>
    <a href="#" class="btn-social btn-social--google">
      <img src="views/img/google icon.png" alt="" class="social-icon" />
      Continue with Google
    </a>
    <a href="#" class="btn-social btn-social--apple">
      <img src="views/img/apple icon.png" alt="" class="social-icon" />
      Continue with Apple
    </a>
    <div class="separator">OR</div>
    <form action="login.php" method="POST" class="login-form">
  <div class="form-group">
    <label for="loginEmail">Email</label>
    <input id="loginEmail" name="email" type="email" placeholder="name@example.com" required />
  </div>
  <div class="form-group">
    <label for="loginPassword">Password</label>
    <a href="#" class="forgot-link">Forgot your password?</a>
    <input id="loginPassword" name="password" type="password" placeholder="••••••••" required />
  </div>
  <button type="submit" class="btn btn-primary btn-login">Login</button>
</form>
    <p class="signup-prompt">
      Don’t have an account? <a href="#" id="openRegister">Register</a>
    </p>
  </div>
</div>

<!-- REGISTER MODAL -->
<div id="registerModal" class="modal">
  <div class="modal-backdrop"></div>
  <div class="modal-card login-card">
    <button class="modal-close" id="closeRegister">&times;</button>
    <a href="#" class="logo login-logo">
      <img src="views/img/logo.png" alt="Lost & Found Hub Logo" />
    </a>
    <h2>Create Your Account</h2>
    <p class="subheading">Enter your details to get started on your journey to recover or report items.</p>
    <form action="register.php" method="POST" class="login-form">
  <div class="form-group">
    <label for="fullName">Full Name</label>
    <input id="fullName" name="name" type="text" placeholder="John Doe" required />
  </div>
  <div class="form-group">
    <label for="registerEmail">Email</label>
    <input id="registerEmail" name="email" type="email" placeholder="john.doe@example.com" required />
  </div>
  <div class="form-group">
    <label for="registerPassword">Password</label>
    <input id="registerPassword" name="password" type="password" placeholder="••••••••" required />
  </div>
  <div class="form-group">
    <label for="confirmPassword">Confirm Password</label>
    <input id="confirmPassword" name="confirm_password" type="password" placeholder="••••••••" required />
  </div>
  <button type="submit" class="btn btn-primary btn-login">Register</button>
</form>
    <p class="terms-text">
      By registering, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
    </p>
    <p class="signup-prompt">
      Already have an account? <a href="#" id="backToLogin">Login</a>
    </p>
  </div>
</div>

<!-- REPORT LOST MODAL -->
<div id="reportLostModal" class="modal">
  <div class="modal-backdrop"></div>
  <div class="modal-card report-card">
    <button class="modal-close" id="closeReportLost">&times;</button>
    <h2>Report a Lost Item</h2>
    <p class="subheading">Provide details about your lost item to help us find it.</p>
    <form action="#" class="report-form">
      <div class="form-group">
        <label for="modalItemTitle">Item Title</label>
        <input id="modalItemTitle" type="text" placeholder="e.g., Black Leather Wallet" required />
      </div>
      <div class="form-group">
        <label for="modalDescription">Description</label>
        <textarea id="modalDescription" rows="4" placeholder="e.g., Contains ID, bank cards, and a family photo. Scratches on the back." required></textarea>
      </div>
      <div class="form-group">
        <label for="modalCategory">Category</label>
        <select id="modalCategory" required>
          <option value="">Select a category</option>
          <option>Electronics</option>
          <option>Personal Belongings</option>
          <option>Keys</option>
          <option>Bags</option>
          <option>Pets</option>
        </select>
      </div>
      <div class="form-group">
        <label for="modalLocationLost">Location Lost</label>
        <input id="modalLocationLost" type="text" placeholder="e.g., University Library, 3rd Floor" required />
      </div>
      <div class="form-group">
        <label for="modalDateLost">Date Lost</label>
        <input id="modalDateLost" type="date" required />
      </div>
      <hr />
      <h3>Contact Information</h3>
      <div class="form-group">
        <label for="modalContactInfo">Preferred Contact (Email or Phone)</label>
        <input id="modalContactInfo" type="text" placeholder="e.g., your@email.com or +1 (555) 123-4567" required />
      </div>
      <hr />
      <h3>Additional Details</h3>
      <div class="form-group">
        <label for="modalItemImage">Item Image (Optional)</label>
        <input id="modalItemImage" type="file" accept="image/*" />
      </div>
      <div class="form-group">
        <label for="modalProof">Proof of Ownership (Optional)</label>
        <textarea id="modalProof" rows="3" placeholder="e.g., Serial number, unique markings, purchase details, or specific content."></textarea>
      </div>
      <div class="form-actions">
        <button type="reset" class="btn btn-secondary">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit Report</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="js/main.js"></script>
</body>
</html>
