// signin-signup js

document.addEventListener("DOMContentLoaded", () => {
    // 1. Feature Toggle View Password
    document.querySelectorAll("[data-toggle]").forEach((btn) => {
        btn.addEventListener("click", () => {
            const input = document.getElementById(btn.dataset.toggle);
            if (!input) return;
            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            
            const eyeOn = btn.querySelector('[data-eye="on"]');
            const eyeOff = btn.querySelector('[data-eye="off"]');
            if (eyeOn && eyeOff) {
                eyeOn.classList.toggle("hidden", isPassword);
                eyeOff.classList.toggle("hidden", !isPassword);
            }
        });
    });

    // 2. Client-side Authentication via JSON Fetch
    const loginForm = document.getElementById("login-form");
    const errorMessage = document.getElementById("error-message");
    const btnSubmit = document.getElementById("btn-submit");

    if (loginForm) {
        loginForm.addEventListener("submit", async (event) => {
            event.preventDefault();

            const emailInput = document.getElementById("email").value.trim();
            const passwordInput = document.getElementById("password").value;

            if (errorMessage) errorMessage.classList.add("hidden");
            if (btnSubmit) {
                btnSubmit.textContent = "Memproses...";
                btnSubmit.disabled = true;
            }

            try {
                const response = await fetch("data/user.json");
                if (!response.ok) throw new Error("Gagal memuat database user.");

                const users = await response.json();
                const matchedUser = users.find(
                    (u) => u.Email === emailInput && u.Password === passwordInput
                );

                if (matchedUser) {
                    localStorage.setItem("userSession", JSON.stringify(matchedUser));
                    window.location.href = "dashboard.html";
                } else {
                    if (errorMessage) {
                        errorMessage.textContent = "Email atau Password salah!";
                        errorMessage.classList.remove("hidden");
                    }
                }
            } catch (error) {
                console.error(error);
                if (errorMessage) {
                    errorMessage.textContent = "Terjadi kesalahan sistem saat membaca data.";
                    errorMessage.classList.remove("hidden");
                }
            } finally {
                if (btnSubmit) {
                    btnSubmit.textContent = "Masuk";
                    btnSubmit.disabled = false;
                }
            }
        });
    }
});