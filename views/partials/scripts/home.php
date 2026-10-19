<script>
    const copyrightMode = [
        "Tempat latihan yang lengkap, bersih, dan nyaman. Alat modern, pelatih berpengalaman, dan jadwal kelas yang fleksibel untuk semua level.",
        "Liat paket dan join sekarang!"
    ];
    let copyrightFlag = 0;
    const copyrightDom = document.getElementById("copyright");

    setInterval(() => {
        copyrightFlag = copyrightFlag === 0 ? 1 : 0;
        copyrightDom.textContent = copyrightMode[copyrightFlag];
    }, 3000);
</script>
