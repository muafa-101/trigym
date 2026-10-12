document.addEventListener("DOMContentLoaded", () => {
    // 1. Auth Guard: Cek apakah user sudah login
    const savedUser = localStorage.getItem("userSession");
    if (!savedUser) {
        window.location.href = "signin.html";
        return;
    }

    // 2. Render Data Profil dari localStorage
    try {
        const user = JSON.parse(savedUser);
        if (document.getElementById("user-name")) document.getElementById("user-name").textContent = user.name;
        if (document.getElementById("user-id")) document.getElementById("user-id").textContent = "ID : " + user.id;
        if (document.getElementById("user-email")) document.getElementById("user-email").textContent = user.Email;
        if (document.getElementById("user-status")) document.getElementById("user-status").textContent = user.Status;
        if (document.getElementById("user-photo") && user.photo) {
            document.getElementById("user-photo").src = user.photo;
        }
    } catch (e) {
        console.error("Gagal membaca session user:", e);
    }

    // 3. Tombol Generate Code Member
    const btnGen = document.getElementById("btn-generate");
    if (btnGen) {
        btnGen.addEventListener("click", () => {
            const code = "TG-" + Math.random().toString(36).slice(2, 7).toUpperCase();
            btnGen.textContent = code;
            setTimeout(() => { 
                btnGen.textContent = "Generate Code"; 
            }, 8000);
        });
    }

    // 4. Tombol Ikuti Kelas
    document.querySelectorAll(".btn-ikuti").forEach((btn) => {
        btn.addEventListener("click", () => {
            btn.textContent = "Terdaftar";
            btn.disabled = true;
        });
    });
});