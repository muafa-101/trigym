@extends('layouts.main')

@section('content')
<main class="w-full max-w-[760px] mx-4 min-[800px]:mx-auto mt-6 mb-12 p-4 min-[901px]:p-5 bg-[#F1F1F1] rounded-[20px] border-[3px] border-[#006A8B] flex flex-col gap-6">

    <!-- Header Section -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#006A8B] px-6 py-4 flex items-center justify-between flex-wrap gap-3">
            <div class="text-white">
                <h1 class="font-display text-[1.8rem] leading-none uppercase">Edit Informasi Member</h1>
                <p class="text-[0.78rem] mt-1 tracking-wide opacity-80">Method edit() &rarr; Perbarui data member {{ $targetUser->memberCode() }} ({{ $targetUser->name }})</p>
            </div>
            <a href="{{ route('users.index') }}" class="font-display px-5 py-2 text-[0.85rem] uppercase text-[#006A8B] bg-white rounded-full transition-all duration-200 hover:bg-[#FBC436] hover:text-white hover:-translate-y-0.5">
                &larr; Kembali
            </a>
        </div>
    </section>

    @if ($errors->any())
        <div class="rounded-[14px] border-2 border-[#FF4444] bg-white px-5 py-3 text-sm text-[#B42318]" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form EDIT Section -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#FBC436] px-5 py-3">
            <h2 class="font-display text-[1.3rem] uppercase text-white">✏️ Form Edit Member</h2>
        </div>

        <form action="{{ route('users.update', $targetUser->id) }}" method="POST" class="px-6 py-6 flex flex-col gap-5">
            @csrf

            <div class="flex flex-col gap-1.5">
                <label for="name" class="text-[0.85rem] font-semibold text-[#333]">
                    Nama Lengkap <span class="text-[#FF4444]">*</span>
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $targetUser->name) }}"
                    required
                    placeholder="Contoh: Budi Santoso"
                    class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2.5 text-base focus:outline-none focus:border-[#C42B6F] transition-colors"
                >
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="email" class="text-[0.85rem] font-semibold text-[#333]">
                    Alamat Email <span class="text-[#FF4444]">*</span>
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $targetUser->email) }}"
                    required
                    placeholder="Contoh: budi@example.com"
                    class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2.5 text-base focus:outline-none focus:border-[#C42B6F] transition-colors"
                >
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="password" class="text-[0.85rem] font-semibold text-[#333]">
                    Password Baru (Biarkan kosong jika tidak ingin mengubah password)
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Ketik password baru jika ingin diganti (min. 8 karakter)..."
                    class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2.5 text-base focus:outline-none focus:border-[#C42B6F] transition-colors"
                >
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label for="status" class="text-[0.85rem] font-semibold text-[#333]">
                        Status Member <span class="text-[#FF4444]">*</span>
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2.5 text-base bg-white focus:outline-none focus:border-[#C42B6F] transition-colors"
                    >
                        <option value="Aktif" {{ old('status', $targetUser->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status', $targetUser->status) === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="package_id" class="text-[0.85rem] font-semibold text-[#333]">
                        Pilihan Paket Membership
                    </label>
                    <select
                        id="package_id"
                        name="package_id"
                        class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2.5 text-base bg-white focus:outline-none focus:border-[#C42B6F] transition-colors"
                    >
                        <option value="">-- Tanpa Paket --</option>
                        @foreach ($packages as $pkg)
                            <option value="{{ $pkg->id }}" {{ (string) old('package_id', $targetUser->package_id) === (string) $pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }} ({{ $pkg->months }} Bulan)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="expires_at" class="text-[0.85rem] font-semibold text-[#333]">
                    Tanggal Kedaluwarsa Membership
                </label>
                <input
                    id="expires_at"
                    type="date"
                    name="expires_at"
                    value="{{ old('expires_at', $targetUser->expires_at ? $targetUser->expires_at->format('Y-m-d') : '') }}"
                    class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2.5 text-base focus:outline-none focus:border-[#C42B6F] transition-colors"
                >
            </div>

            <div class="flex items-center gap-4 pt-2 flex-wrap">
                <button type="submit" class="font-display px-8 py-2.5 text-[1rem] uppercase text-white bg-[#006A8B] rounded-full cursor-pointer transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#005070] focus-visible:outline-3 focus-visible:outline-[#C42B6F] focus-visible:outline-offset-3">
                    Perbarui Informasi
                </button>
                <a href="{{ route('users.index') }}" class="font-display px-8 py-2 text-[1rem] uppercase text-[#006A8B] border-2 border-[#006A8B] rounded-full transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#006A8B] hover:text-white focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                    Batal
                </a>
            </div>
        </form>
    </section>

</main>
@endsection
