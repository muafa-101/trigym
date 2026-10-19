<?php
/**
 * @var array<string, string> $errors
 * @var array<string, string> $old
 */
$labelClass = 'pointer-events-none absolute left-5 top-1/2 -translate-y-1/2 text-sm text-slate-500 transition-all peer-focus:top-3 peer-focus:translate-y-0 peer-focus:text-[11px] peer-focus:text-[#C42B6F] peer-[:not(:placeholder-shown)]:top-3 peer-[:not(:placeholder-shown)]:translate-y-0 peer-[:not(:placeholder-shown)]:text-[11px]';
?>
<main class="grid flex-1 place-items-center px-4 py-10">
    <div class="w-full max-w-md rounded-[20px] border-2 border-blue-300 bg-[#F1F1F1] p-6 shadow-[0_16px_30px_rgba(0,0,0,0.2)] sm:p-9">
        <h1 class="font-display text-center text-[2.2rem] leading-tight text-[#C42B6F]">Selamat Datang Kembali</h1>
        <p class="mt-1 text-center text-sm text-slate-600">Masuk untuk melihat dashboard member kamu.</p>

        <?php if ($errors !== []): ?>
            <div class="mt-5 rounded-[14px] border-2 border-[#FF4444] bg-white px-4 py-3 text-sm text-[#B42318]" role="alert">
                <?= e(reset($errors)) ?>
            </div>
        <?php endif; ?>

        <form action="signin.php" method="POST" class="mt-7 space-y-4">
            <?= csrf_field() ?>
            <div class="relative">
                <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" placeholder=" " autocomplete="email" required class="peer w-full rounded-full border-2 border-slate-200 bg-white px-5 pb-2 pt-6 text-sm text-black placeholder:text-transparent transition-colors focus:border-[#C42B6F] focus:outline-none">
                <label for="email" class="<?= $labelClass ?>">Email</label>
            </div>
            <div class="relative">
                <input type="password" id="password" name="password" placeholder=" " autocomplete="current-password" required class="peer w-full rounded-full border-2 border-slate-200 bg-white px-5 pb-2 pt-6 text-sm text-black placeholder:text-transparent transition-colors focus:border-[#C42B6F] focus:outline-none">
                <label for="password" class="<?= $labelClass ?>">Password</label>
            </div>
            <button type="submit" class="font-display w-full cursor-pointer rounded-full px-6 py-[10px] text-[1.15rem] text-white transition-all duration-200 hover:-translate-y-0.5 focus-visible:outline-3 focus-visible:outline-offset-3 focus-visible:outline-[#C42B6F] bg-[#C42B6F] hover:bg-[#d93a82] hover:shadow-[0_5px_0_#7d1a45]">Masuk</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">Belum punya akun? <a href="signup.php" class="font-semibold text-[#C42B6F] hover:underline">Daftar</a></p>
    </div>
</main>
