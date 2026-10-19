<script>
    // Generate Code: minta kode check-in baru dari server
    const btnGen = document.getElementById("btn-generate");
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    btnGen.addEventListener("click", async () => {
        btnGen.disabled = true;
        try {
            const res = await fetch("generate-code.php", {
                method: "POST",
                headers: { "X-CSRF-Token": csrf }
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || "Gagal");
            btnGen.textContent = data.code;
            setTimeout(() => { btnGen.textContent = "Generate Code"; }, 8000);
        } catch (err) {
            btnGen.textContent = "Gagal, coba lagi";
            setTimeout(() => { btnGen.textContent = "Generate Code"; }, 3000);
        } finally {
            btnGen.disabled = false;
        }
    });
</script>
