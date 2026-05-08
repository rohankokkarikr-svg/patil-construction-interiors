<?php
// ============================================================
// CONTACT PAGE — contact.php
// ============================================================
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'Contact Pradeepgouda B Patil | Civil Engineer — Gokak, Karnataka';
$pageDesc  = 'Get in touch with Pradeepgouda B Patil for building projects, interior design consultations, project estimation, or any construction queries.';
$pageName  = 'contact';
include 'includes/header.php';
?>

<div class="bg-grid"></div>

<section class="section" style="padding-top:8rem;" aria-label="Contact">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Let's Talk</span>
      <h1 class="section-title">Get In <span class="accent">Touch</span></h1>
      <div class="section-divider"></div>
      <p class="mt-3" style="max-width:600px;margin:0 auto;">Have a building project, interior design, or estimation need? Let's connect — I typically respond within 24 hours.</p>
    </div>

    <div class="row gy-5">
      <!-- Contact Info -->
      <div class="col-lg-4" data-aos="fade-right">
        <div class="contact-info-card">
          <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <div class="contact-info-title">Email</div>
            <div class="contact-info-value"><a href="mailto:Patilpradeep754@gmail.com">Patilpradeep754@gmail.com</a></div>
          </div>
        </div>
        <div class="contact-info-card">
          <div class="contact-info-icon"><i class="fas fa-phone"></i></div>
          <div>
            <div class="contact-info-title">Phone</div>
            <div class="contact-info-value"><a href="tel:+918747061867">+91 8747061867</a></div>
          </div>
        </div>
        <div class="contact-info-card">
          <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <div class="contact-info-title">Location</div>
            <div class="contact-info-value">Gokak, Karnataka, India</div>
          </div>
        </div>
        <h4 class="mt-4 mb-3" style="font-family:var(--font-heading);font-size:1rem;text-transform:uppercase;letter-spacing:1px;">Connect With Me</h4>
        <div class="d-flex gap-2">
          <a href="https://wa.me/918747061867" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="Chat on WhatsApp" title="Chat on WhatsApp"><i class="fab fa-whatsapp"></i></a>
          <a href="https://www.instagram.com/blacck_heaart?igsh=bnRxc2FsZTZoYXIw" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="col-lg-8" data-aos="fade-left">
        <div class="form-custom">
          <h3 class="mb-4" style="font-family:var(--font-heading);font-size:1.5rem;">Send a <span class="accent">Message</span></h3>
          <div id="formMessage" class="alert-custom" style="display:none;"></div>
          <form id="contactForm" novalidate>
            <div class="row g-3">
              <div class="col-sm-6">
                <label for="cf_name" class="form-label">Full Name *</label>
                <input type="text" id="cf_name" name="name" class="form-control" placeholder="John Doe" required maxlength="150">
              </div>
              <div class="col-sm-6">
                <label for="cf_email" class="form-label">Email Address *</label>
                <input type="email" id="cf_email" name="email" class="form-control" placeholder="john@example.com" required maxlength="200">
              </div>
              <div class="col-sm-6">
                <label for="cf_phone" class="form-label">Phone Number</label>
                <input type="tel" id="cf_phone" name="phone" class="form-control" placeholder="+91 98765 43210" maxlength="20">
              </div>
              <div class="col-sm-6">
                <label for="cf_subject" class="form-label">Subject *</label>
                <input type="text" id="cf_subject" name="subject" class="form-control" placeholder="Project Inquiry" required maxlength="300">
              </div>
              <div class="col-12">
                <label for="cf_message" class="form-label">Message *</label>
                <textarea id="cf_message" name="message" class="form-control" rows="5" placeholder="Tell me about your project or inquiry…" required maxlength="5000"></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-accent" id="contactSubmitBtn">
                  <i class="fas fa-paper-plane me-2"></i>Send Message
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== APPOINTMENT BOOKING ===== -->
<section class="section section-alt" aria-label="Book Appointment">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-title-wrap" data-aos="fade-up">
          <span class="section-label">Schedule</span>
          <h2 class="section-title">Book an <span class="accent">Appointment</span></h2>
          <div class="section-divider"></div>
        </div>
        <div class="form-custom" data-aos="fade-up">
          <div id="apptMessage" class="alert-custom" style="display:none;"></div>
          <form id="appointmentForm" novalidate>
            <div class="row g-3">
              <div class="col-sm-6">
                <label for="ap_name" class="form-label">Full Name *</label>
                <input type="text" id="ap_name" name="name" class="form-control" required placeholder="Your name" maxlength="150">
              </div>
              <div class="col-sm-6">
                <label for="ap_email" class="form-label">Email *</label>
                <input type="email" id="ap_email" name="email" class="form-control" required placeholder="your@email.com" maxlength="200">
              </div>
              <div class="col-sm-6">
                <label for="ap_phone" class="form-label">Phone</label>
                <input type="tel" id="ap_phone" name="phone" class="form-control" placeholder="+91 98765 43210" maxlength="20">
              </div>
              <div class="col-sm-6">
                <label for="ap_type" class="form-label">Project Type</label>
                <select id="ap_type" name="project_type" class="form-select">
                  <option value="">Select…</option>
                  <option>Residential House</option>
                  <option>Commercial Building</option>
                  <option>Industrial / Warehouse</option>
                  <option>Infrastructure / Road</option>
                  <option>Bridge / Flyover</option>
                  <option>BIM Consultation</option>
                  <option>Structural Audit</option>
                  <option>Other</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label for="ap_date" class="form-label">Preferred Date *</label>
                <input type="date" id="ap_date" name="preferred_date" class="form-control" required
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
              </div>
              <div class="col-sm-6">
                <label for="ap_time" class="form-label">Preferred Time *</label>
                <select id="ap_time" name="preferred_time" class="form-select" required>
                  <option value="">Select time…</option>
                  <option value="09:00">09:00 AM</option>
                  <option value="10:00">10:00 AM</option>
                  <option value="11:00">11:00 AM</option>
                  <option value="12:00">12:00 PM</option>
                  <option value="14:00">02:00 PM</option>
                  <option value="15:00">03:00 PM</option>
                  <option value="16:00">04:00 PM</option>
                  <option value="17:00">05:00 PM</option>
                </select>
              </div>
              <div class="col-12">
                <label for="ap_notes" class="form-label">Notes</label>
                <textarea id="ap_notes" name="notes" class="form-control" rows="3" placeholder="Any specific topics or requirements…" maxlength="1000"></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-accent" id="apptSubmitBtn" form="appointmentForm">
                  <i class="fas fa-calendar-check me-2"></i>Book Appointment
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== LOCATION MAP ===== -->
<section class="section section-alt" aria-label="Location Map">
  <div class="container">
    <div class="section-title-wrap" data-aos="fade-up">
      <span class="section-label">Visit Us</span>
      <h2 class="section-title">Our <span class="accent">Location</span></h2>
      <div class="section-divider"></div>
      <p class="mt-3" style="max-width:600px;margin:0 auto;">Find us at Aroodha Jyoti Complex in Gokak, Karnataka. Click below for directions or explore the area.</p>
    </div>
    
    <div class="row justify-content-center" data-aos="fade-up">
      <div class="col-12">
        <div class="map-container">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3843.2674477278!2d74.8166199!3d16.1590252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc0af005b7ec913%3A0xeefdd49cc4e27fa5!2sAroodha+Jyoti+Complex%2C+204%2F1a%2C+Laxmi+Temple+Rd%2C+Gokak%2C+Karnataka+591307!5e0!3m2!1sen!2sin!4v1234567890!5m2!1sen!2sin"
            width="100%" 
            height="450" 
            style="border:0; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.4);" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade"
            aria-label="Google Maps location of PATIL's Construction & Interior's">
          </iframe>
          
          <div class="map-actions mt-4 text-center">
            <a href="https://maps.app.goo.gl/wJf55AdyiHQSJizNA" target="_blank" rel="noopener noreferrer" class="btn-accent">
              <i class="fas fa-directions me-2"></i>Get Directions
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  
  
<script>
// Appointment form AJAX
document.getElementById('appointmentForm')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('apptSubmitBtn');
  const msgEl = document.getElementById('apptMessage');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Booking…';
  try {
    const resp = await fetch('/contraction/includes/appointment_handler.php', {
      method: 'POST', body: new FormData(e.target),
    });
    const data = await resp.json();
    msgEl.className = 'alert-custom ' + (data.success ? 'alert-success' : 'alert-error');
    msgEl.innerHTML = `<i class="fas fa-${data.success ? 'check-circle' : 'exclamation-circle'}"></i> ${data.message}`;
    msgEl.style.display = 'flex';
    if (data.success) e.target.reset();
  } catch {
    msgEl.className = 'alert-custom alert-error';
    msgEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error. Please try again.';
    msgEl.style.display = 'flex';
  }
  btn.disabled = false;
  btn.innerHTML = '<i class="fas fa-calendar-check me-2"></i>Book Appointment';
});
</script>

<?php include 'includes/footer.php'; ?>
