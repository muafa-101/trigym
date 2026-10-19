<header class="sticky top-0 z-50 flex items-center gap-6 px-5 h-[52px] bg-[#006A8B] max-[900px]:h-auto max-[900px]:py-2 max-[900px]:flex-wrap">
    <a href="index.php" class="inline-block shrink-0">
        <img src="assets/logo.png" alt="TriGym" class="h-[34px] w-auto">
    </a>
    <nav class="ml-0 min-[901px]:ml-10 max-[900px]:order-3 max-[900px]:w-full">
        <ul class="flex items-center gap-8">
            <li><a href="#status" class="text-white text-base py-1 hover:underline underline-offset-4 decoration-2 decoration-[#C42B6F] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">Status</a></li>
            <li><a href="#kelas" class="text-white text-base py-1 hover:underline underline-offset-4 decoration-2 decoration-[#C42B6F] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">Kelas</a></li>
        </ul>
    </nav>
    <div class="ml-auto flex items-center gap-3">
        <button id="btn-generate" type="button" class="font-display min-w-[150px] px-6 py-[5px] text-[1rem] uppercase text-white bg-black rounded-full cursor-pointer transition-all duration-200 hover:bg-[#C42B6F] hover:-translate-y-0.5 focus-visible:outline-3 focus-visible:outline-[#C42B6F] focus-visible:outline-offset-3">
            Generate Code
        </button>
        <form action="logout.php" method="POST">
            <?= csrf_field() ?>
            <button type="submit" class="font-display cursor-pointer text-[0.95rem] px-5 py-[5px] text-white border-2 border-white rounded-full transition-all duration-200 hover:bg-white hover:text-[#006A8B] hover:-translate-y-0.5 focus-visible:outline-3 focus-visible:outline-[#C42B6F]">Keluar</button>
        </form>
    </div>
</header>
