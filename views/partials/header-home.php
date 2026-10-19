<?php /** @var \TriGym\Models\Member|null $user */ ?>
<header class="sticky top-0 z-50 flex items-center gap-6 px-5 h-[52px] bg-[#006A8B] max-[900px]:h-auto max-[900px]:py-2 max-[900px]:flex-wrap">
    <a href="index.php" class="inline-block shrink-0">
        <img src="assets/logo.png" alt="TriGym" class="h-[34px] w-auto">
    </a>

    <nav class="ml-0 min-[901px]:ml-10 max-[900px]:w-full max-[900px]:order-3">
        <ul class="flex items-center gap-8 max-[900px]:justify-between max-[900px]:gap-4">
            <li><a href="#fasilitas" class="text-white text-base py-1 hover:underline underline-offset-4 decoration-2 decoration-[#C42B6F] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">Fasilitas</a></li>
            <li><a href="#kelas" class="text-white text-base py-1 hover:underline underline-offset-4 decoration-2 decoration-[#C42B6F] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">Kelas</a></li>
            <li><a href="#paket" class="text-white text-base py-1 hover:underline underline-offset-4 decoration-2 decoration-[#C42B6F] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">Membership</a></li>
        </ul>
    </nav>

    <div class="auth-buttons ml-auto flex items-center gap-3">
        <?php if ($user !== null): ?>
            <a href="dashboard.php" class="font-display text-[0.95rem] px-5 py-[5px] text-white bg-[#C42B6F] border-2 border-[#C42B6F] rounded-full transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#d93a82] hover:border-[#d93a82] hover:shadow-[0_5px_0_#7d1a45] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">Dashboard</a>
            <form action="logout.php" method="POST">
                <?= csrf_field() ?>
                <button type="submit" class="font-display cursor-pointer text-[0.95rem] px-5 py-[5px] text-white border-2 border-white rounded-full transition-all duration-200 hover:bg-white hover:text-[#006A8B] hover:-translate-y-0.5 focus-visible:outline-3 focus-visible:outline-[#C42B6F]">Keluar</button>
            </form>
        <?php else: ?>
            <a href="signin.php" class="font-display text-[0.95rem] px-5 py-[5px] text-white border-2 border-white rounded-full transition-all duration-200 hover:bg-white hover:text-[#006A8B] hover:-translate-y-0.5 focus-visible:outline-3 focus-visible:outline-[#C42B6F]">Sign in</a>
            <a href="signup.php" class="font-display text-[0.95rem] px-5 py-[5px] text-white bg-[#C42B6F] border-2 border-[#C42B6F] rounded-full transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#d93a82] hover:border-[#d93a82] hover:shadow-[0_5px_0_#7d1a45] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">Sign up</a>
        <?php endif; ?>
    </div>
</header>
