<?php
/**
 * @var \TriGym\Models\Member $member
 * @var \TriGym\Models\GymStatus $gymStatus
 * @var \TriGym\Models\GymClass|null $primary
 * @var \TriGym\Models\GymClass[] $others
 * @var int[] $enrolledIds
 * @var array{visits:int, classes:int, daysLeft:int} $stats
 */
?>
<main class="w-full max-w-[960px] mx-4 min-[1010px]:mx-auto mt-6 mb-12 p-4 min-[901px]:p-5 bg-[#F1F1F1] rounded-[20px] border-[3px] border-[#006A8B] flex flex-col gap-6">

    <!-- Kartu Profil -->
    <section id="profil" class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#0090BE] h-[36px] px-5 flex items-center">
            <span class="font-display text-white text-[0.85rem] tracking-[2px] uppercase">TriGym Member Card</span>
        </div>
        <div class="mt-[6px] bg-[#0090BE] p-5 min-[901px]:p-7 flex flex-col min-[601px]:flex-row gap-5 min-[601px]:gap-6 min-[601px]:items-center">
            <div class="flex-1 flex flex-col gap-1 text-white self-stretch justify-between">
                <div class="mt-auto">
                    <h1 id="user-name" class="font-display text-[1.4rem] min-[901px]:text-[2.3rem] leading-[1.05] mb-3"><?= e($member->name) ?></h1>
                    <p id="user-id" class="font-display text-[1rem] mb-1">ID : <?= e($member->memberCode()) ?></p>
                    <p id="user-email" class="font-display text-[1rem] mb-1 break-all">Email : <?= e($member->email) ?></p>
                    <p class="font-display text-[1rem]">Status : <span id="user-status" class="text-[#FBC436]"><?= e($member->status) ?></span></p>
                </div>
                <p class="mt-4 text-right text-[0.9rem]">Paket member berlaku hingga <?= e($member->expiryLabel()) ?></p>
            </div>
        </div>
    </section>

    <!-- Status Gym + Kelas | Gym-Bud -->
    <div class="grid grid-cols-1 min-[901px]:grid-cols-[1fr_320px] gap-6 items-start">

        <div class="flex flex-col gap-5">

            <section id="status" class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_rgba(0,0,0,0.25)]">
                <div class="<?= e($gymStatus->bannerClass()) ?> px-6 py-4 flex items-center justify-between gap-4 flex-wrap">
                    <div class="text-white">
                        <h2 class="font-display text-[1.7rem] leading-none">Status Gym :</h2>
                        <p class="font-display text-[0.7rem] mt-2 tracking-wide"><?= e($gymStatus->summary()) ?></p>
                    </div>
                    <span class="<?= e($gymStatus->badgeClass()) ?> font-display text-[1.5rem] uppercase text-white rounded-[10px] px-6 py-1 select-none"><?= e($gymStatus->label()) ?></span>
                </div>
            </section>

            <section id="kelas" class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_rgba(0,0,0,0.25)]">
                <details open class="group bg-[#FDDE8F]">
                    <summary class="list-none cursor-pointer bg-[#FBC436] px-5 py-4 flex items-center gap-4 flex-wrap focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                        <?php if ($primary !== null): ?>
                            <span class="font-display text-[1.4rem] uppercase text-white bg-[#BF9A3B] rounded-[10px] px-4 py-1.5 leading-tight"><?= e($primary->name) ?></span>
                            <span class="flex-1 min-w-[160px] text-white">
                                <span class="font-display text-[1rem] block">Trainer : <?= e($primary->trainer) ?></span>
                                <span class="font-display text-[0.7rem] block"><?= e($primary->timeLabel()) ?></span>
                                <span class="font-display text-[0.7rem] block">Kelas yang anda ikuti</span>
                            </span>
                        <?php else: ?>
                            <span class="font-display text-[1.4rem] uppercase text-white bg-[#BF9A3B] rounded-[10px] px-4 py-1.5 leading-tight">Belum ada kelas</span>
                            <span class="flex-1 min-w-[160px] text-white">
                                <span class="font-display text-[0.7rem] block">Pilih kelas di bawah untuk mulai ikut</span>
                            </span>
                        <?php endif; ?>
                        <svg class="w-5 h-5 text-white transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                    </summary>

                    <ul class="px-5 py-5 flex flex-col gap-4">
                        <?php foreach ($others as $class): ?>
                            <?php $joined = in_array($class->id, $enrolledIds, true); ?>
                            <li class="flex items-center gap-4">
                                <span class="font-display w-[190px] shrink-0 text-center text-[1.4rem] uppercase text-white bg-[#FBC436] rounded-[10px] px-3 py-2 [text-shadow:0_1px_2px_rgba(0,0,0,0.25)]"><?= e($class->name) ?></span>
                                <div class="flex-1 min-w-[110px] text-[#5A4100]">
                                    <p class="font-display text-[1rem] leading-tight text-white"><?= e($class->days) ?></p>
                                    <p class="font-display text-[0.75rem] leading-tight text-white"><?= e($class->timeLabel()) ?></p>
                                </div>
                                <form action="enroll.php" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="class_id" value="<?= (int) $class->id ?>">
                                    <button type="submit" <?= $joined ? 'disabled' : '' ?> class="btn-ikuti shrink-0 font-display px-7 py-1.5 text-[1.05rem] uppercase text-white bg-[#FBC436] rounded-full cursor-pointer transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#E5A913] disabled:opacity-70 disabled:cursor-default disabled:hover:translate-y-0 focus-visible:outline-3 focus-visible:outline-[#C42B6F] focus-visible:outline-offset-3 [text-shadow:0_1px_2px_rgba(0,0,0,0.25)]"><?= $joined ? 'Terdaftar' : 'Ikuti' ?></button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </details>
            </section>
        </div>

        <aside class="relative aspect-square w-full rounded-[16px] overflow-hidden border border-[#FF4444] bg-[#E5E5E5] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_rgba(0,0,0,0.25)]">
            <img src="assets/LOWONGAN.png" alt="GYM-BUD" class="w-full h-full object-cover">
            <div class="absolute inset-x-0 bottom-0 bg-[#FF4444] text-white text-center px-3 py-2 rounded-t-[10px]">
                <p class="font-display text-[1.25rem] leading-tight">GYM-BUD Sedang marah</p>
                <p class="font-display text-[0.7rem]">Karna Avatarnya masih bentuk lowongan</p>
            </div>
        </aside>
    </div>

    <!-- Aktivitas -->
    <section id="aktivitas" class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#8DD63F] px-5 py-5 min-[901px]:px-8">
            <h2 class="font-display text-[1.4rem] text-white mb-3 uppercase">Aktivitas Bulan Ini</h2>
            <div class="grid grid-cols-1 min-[601px]:grid-cols-3 gap-3">
                <div class="bg-white/25 rounded-[14px] px-4 py-3 text-white text-center">
                    <p class="font-display text-[2rem] leading-none"><?= (int) $stats['visits'] ?>x</p>
                    <p class="text-[0.85rem] mt-1">Kunjungan</p>
                </div>
                <div class="bg-white/25 rounded-[14px] px-4 py-3 text-white text-center">
                    <p class="font-display text-[2rem] leading-none"><?= (int) $stats['classes'] ?></p>
                    <p class="text-[0.85rem] mt-1">Kelas diikuti</p>
                </div>
                <div class="bg-white/25 rounded-[14px] px-4 py-3 text-white text-center">
                    <p class="font-display text-[2rem] leading-none"><?= (int) $stats['daysLeft'] ?></p>
                    <p class="text-[0.85rem] mt-1">Hari tersisa</p>
                </div>
            </div>
        </div>
    </section>
</main>