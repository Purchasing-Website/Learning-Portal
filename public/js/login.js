    // Simple front-end validation demo
    document.getElementById("loginForm").addEventListener("submit", function (e) {
      e.preventDefault();

      const emailEl = document.getElementById("email");
      const passEl = document.getElementById("password");

      let ok = true;

      // Email validity
      if (!emailEl.value || !emailEl.checkValidity()) {
        emailEl.classList.add("is-invalid");
        ok = false;
      } else {
        emailEl.classList.remove("is-invalid");
        emailEl.classList.add("is-valid");
      }

      // Password validity
      if (!passEl.value || passEl.value.length < 6) {
        passEl.classList.add("is-invalid");
        ok = false;
      } else {
        passEl.classList.remove("is-invalid");
        passEl.classList.add("is-valid");
      }

      if (!ok) return;

      // TODO: replace with your real login request (Laravel/your API)
      alert("Login submitted:\\nEmail: " + emailEl.value);
    });