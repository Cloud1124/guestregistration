<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <title>Guest Registration - eGOV PH LGU</title>
    <!-- We will link to style.css here -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="tsparticles"></div>
    <div class="container">
        <div class="info-section">
            <div class="">
                  
            </div>
          <img src="images/digital_bayanihan.png" alt="Digital Bayanihan Banner" class="banner-image">
            <div class="content-wrapper" style="padding: 0 40px;">
                <h2 class="sub-title">Welcome to the Guest Registration!</h2>
            
            <p class="event-details">
                The Department of Information and Communications Technology (DICT) cordially invites you to the
                <strong>National ICT Month 2025 Kick-Off Celebration</strong>. Join us as we embark on this momentous occasion on 
                <strong>Thursday, June 5, 2025, in the scenic Basco, Batanes.</strong>
            </p>
            <p class="event-mission">
                This year's theme, "Walang Iwanan sa Digital Bayanihan," echoes DICT's unwavering commitment to the "Bagong Pilipinas" vision—a future where every Filipino reaps the benefits of digitalization and cutting-edge technologies. We are dedicated to enhancing public service delivery, nurturing our nation's ICT sector, building a robust ICT talent pipeline, securing our digital landscape, and ensuring that no Filipino is left behind in our journey towards a digitally empowered nation.
            </p>
           
            <p class="call-to-action">
                Please complete the registration form below to confirm your participation. 
                <br>Dios Mamajes!
            </p>
            </div> <!-- Close content-wrapper -->
        </div>

        <div class="registration-section">
            <h1>Guests Registration</h1>
            <form id="registrationForm" method="POST" action="submit_registration.php">
                <input type="hidden" name="csrf_token" value="0f6d386b8be62a10a792988d170198121744bde9b36ef730ba7ddf557c6c52d5">
                <div class="form-group">
                    <label for="name">Complete Name</label>
                    <input type="text" id="name" name="name" placeholder="Juan Dela Cruz" required>
                </div>

                <div class="form-group">
                    <label for="agency">Agency / LGU / School / Organization</label>
                    <input type="text" id="agency" name="agency_org" placeholder="e.g., DICT, LGU Manila, DepEd">
                </div>

                <div class="form-group">
                    <label for="designation">Designation</label>
                    <input type="text" id="designation" name="designation" placeholder="e.g., Director, Teacher, Student">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="juan.delacruz@example.com" required>
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="tel" id="mobile" name="mobile" placeholder="09xxxxxxxxx">
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected>Select your gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Non-binary">Non-binary</option>
                        <option value="Prefer not to say">Prefer not to say</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="signature_pad_canvas">Signature</label>
                    <div style="width:100%;"> <!-- Removed max-width:400px -->
                        <canvas id="signature_pad_canvas" height="220"></canvas>
                    </div>
                    <input type="hidden" id="signature_data" name="signature_data" required>
                </div>

                <div class="buttons">
                    <button type="button" id="clear_signature">Clear Signature</button>
                    <button type="submit">Submit</button>
                </div>

                <div class="privacy-notice">
                    By submitting this form, I agree to the collection and processing of my data stated in this <a href="#" id="privacyNoticeLink">Privacy Notice</a>.
                </div>
            </form>
        </div>
    </div>

    <!-- Signature Pad Library (CDN) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
    
    <!-- Custom JavaScript for form handling and signature pad -->
    <script src="script.js"></script>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Registration Successful!</h2>
            <p>Dios Mamajes and Thank you for registering. This page will reload in <span id="countdownTimer">5</span> seconds...</p>
            <p>Ready for the next guest.</p>
        </div>
    </div>

    <!-- Privacy Notice Modal -->
    <div id="privacyModal" class="modal">
        <div class="modal-content">
            <span class="close-button privacy-modal-close">&times;</span>
            <h2>Privacy Notice (R.A. 10173)</h2>
            <p>We need your personal data to provide verifiable evidence in support of this event and that you participated therein. We will include your data in our printed and electronic reports that we will send through secured channels.</p>
            <p>By signing herein, we will continuously keep your data under lock and key, and will limit their use to authorized staff. If you do not agree, please inform us and we will permanently destroy your data after we have sent our reports.</p>
        </div>
    </div>

    <div id="toastContainer"></div>

    <!-- tsParticles library (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.12.0/tsparticles.bundle.min.js"></script>
    <!-- tsParticles configuration -->
    <script src="particle-config.js"></script>

    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Registration successful!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Registration failed. Please fill in all required fields.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
</body>
</html>
