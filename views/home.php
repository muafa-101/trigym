<?php
/**
 * @var \TriGym\Models\Facility[] $facilities
 * @var \TriGym\Models\GymClass[] $classes
 * @var \TriGym\Models\Package[] $packages
 */
?>
<main class="max-w-[1072px] mx-4 min-[1120px]:mx-auto mt-8 mb-16 px-6 min-[901px]:px-9 pb-10 bg-[#F1F1F1] rounded-[20px] border-[2px] border-blue-300">

    <!-- Hero -->
    <section id="hero" class="flex flex-col min-[901px]:flex-row items-center gap-6 min-[901px]:gap-[28px] pt-[28px] pb-[40px]">
        <img src="assets/logo.png" alt="TriGym" class="w-[310px] max-w-full h-auto shrink-0">
        <div class="hero-copy">
            <p id="copyright" class="max-w-[60ch] text-black text-[1.08rem] leading-[2.1] mb-1">
                Tempat latihan yang lengkap, bersih, dan nyaman. Alat modern, pelatih berpengalaman, dan jadwal kelas yang fleksibel untuk semua level.
            </p>
            <a href="#paket" class="font-display inline-block px-[24px] py-[8px] bg-black text-white text-[1.15rem] rounded-full transition-all duration-200 hover:bg-[#C42B6F] hover:-translate-y-0.5 focus-visible:outline-3 focus-visible:outline-[#C42B6F] focus-visible:outline-offset-3">
                Lihat Paket
            </a>
        </div>
    </section>

    <!-- Fasilitas -->
    <section id="fasilitas" class="grid grid-cols-1 min-[901px]:grid-cols-3 gap-[30px] min-[901px]:gap-[50px] border-t-[10px] border-[#C42B6F] mt-14 pt-2 pb-8 px-0">
        <h2 class="font-display col-span-full text-[2.6rem] leading-tight text-[#C42B6F] mt-3 mb-2">Fasilitas Kami</h2>

        <?php foreach ($facilities as $facility): ?>
            <div class="bg-[#0090BE] rounded-[20px] transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_16px_30px_rgba(0,0,0,0.3)] px-[19px] py-[22px] min-h-[400px]">
                <h3 class="font-display text-[2rem] leading-tight text-white mb-4 text-center"><?= e($facility->title) ?></h3>
                <p class="text-white text-base leading-[2.1]"><?= e($facility->description) ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Kelas -->
    <section id="kelas" class="border-t-[10px] border-[#C42B6F] mt-14 pt-2 pb-8">
        <h2 class="font-display text-[2.6rem] leading-tight text-[#C42B6F] mt-3 mb-3">Pilihan Kelas</h2>
        <div class="grid grid-cols-1 min-[701px]:grid-cols-2 gap-x-[46px] gap-y-[22px] items-start">

            <?php foreach ($classes as $class): ?>
                <details class="group bg-[#5CB300] rounded-[16px] overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_rgba(0,0,0,0.3)]">
                    <summary class="font-display list-none cursor-pointer px-6 py-[10px] text-[2rem] leading-tight text-white text-center focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                        <?= e($class->name) ?>
                    </summary>
                    <p class="px-[16px] pt-[12px] pb-[18px] bg-[#A3DE68] text-white text-[0.95rem] leading-[2.1]">
                        <?= e($class->description) ?>
                    </p>
                </details>
            <?php endforeach; ?>

        </div>
    </section>

    <!-- Membership -->
    <section id="paket" class="grid grid-cols-1 min-[901px]:grid-cols-2 gap-[40px] border-t-[10px] border-[#C42B6F] mt-14 pt-2">
        <h2 class="font-display col-span-full text-[2.6rem] leading-tight text-[#C42B6F] mt-3 mb-1">Paket Membership</h2>

        <?php foreach ($packages as $package): ?>
            <div class="flex flex-col items-start gap-1 bg-[#FF7070] rounded-[20px] transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_16px_30px_rgba(0,0,0,0.3)] px-[30px] py-[24px] min-h-[400px] text-white">
                <h3 class="font-display text-[3rem] leading-tight"><?= e($package->name) ?></h3>
                <p class="text-base leading-[2.1]"><?= e($package->priceLabel()) ?><br><?= e($package->description) ?></p>
                <a href="signup.php?paket=<?= (int) $package->id ?>" class="font-display mt-auto inline-block px-[28px] py-[10px] text-[1.1rem] uppercase text-[#FF7070] bg-white border-2 border-white rounded-full cursor-pointer transition-all duration-200 hover:-translate-y-0.5 hover:bg-transparent hover:text-white focus-visible:outline-3 focus-visible:outline-[#C42B6F] focus-visible:outline-offset-3">
                    Pilih Paket
                </a>
            </div>
        <?php endforeach; ?>
    </section>

</main>
