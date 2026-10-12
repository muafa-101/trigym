document.addEventListener("DOMContentLoaded", () => {
    // Rotasi Teks Tampilan Hero Landing Page
    const copyrightModes = [
        "Tempat latihan yang lengkap, bersih, dan nyaman. Alat modern, pelatih berpengalaman, dan jadwal kelas yang fleksibel untuk semua level.",
        "Lihat paket dan join sekarang!"
    ];
    let copyrightFlag = 0;
    const copyrightDom = document.getElementById("copyright");

    if (copyrightDom) {
        setInterval(() => {
            copyrightFlag = copyrightFlag === 0 ? 1 : 0;
            copyrightDom.textContent = copyrightModes[copyrightFlag];
        }, 3000);
    }

    // Event listener paket
    const paket1 = document.getElementById("paket-1");
    const paket2 = document.getElementById("paket-2");

    if (paket1) paket1.addEventListener("click", () => alert("Kamu memilih Paket Bulanan!"));
    if (paket2) paket2.addEventListener("click", () => alert("Kamu memilih Paket Tahunan!"));
});