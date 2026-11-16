@extends('layouts.main')

@section('content')
<main class="w-full max-w-[760px] mx-4 min-[800px]:mx-auto mt-6 mb-12 p-4 min-[901px]:p-5 bg-[#F1F1F1] rounded-[20px] border-[3px] border-[#006A8B] flex flex-col gap-6">

    <!-- Header Section -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#006A8B] px-6 py-4 flex items-center justify-between flex-wrap gap-3">
            <div class="text-white">
                <h1 class="font-display text-[1.8rem] leading-none uppercase">Tambah Program Baru</h1>
                <p class="text-[0.78rem] mt-1 tracking-wide opacity-80">Method create() &rarr; Form input program latihan PresidentKu</p>
            </div>
            <a href="{{ route('programs.index') }}" class="font-display px-5 py-2 text-[0.85rem] uppercase text-[#006A8B] bg-white rounded-full transition-all duration-200 hover:bg-[#FBC436] hover:text-white hover:-translate-y-0.5">
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

    <!-- Form CREATE Section -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#FBC436] px-5 py-3">
            <h2 class="font-display text-[1.3rem] uppercase text-white">+ Form Program Baru</h2>
        </div>

        <form action="{{ route('programs.store') }}" method="POST" class="px-6 py-6 flex flex-col gap-5">
            @csrf

            <div class="flex flex-col gap-1.5">
                <label for="nama" class="text-[0.85rem] font-semibold text-[#333]">
                    Nama Program <span class="text-[#FF4444]">*</span>
                </label>
                <input
                    id="nama"
                    type="text"
                    name="nama"
                    value="{{ old('nama') }}"
                    required
                    placeholder="Contoh: Pembentukan Otot"
                    class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2.5 text-base focus:outline-none focus:border-[#C42B6F] transition-colors"
                >
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="deskripsi" class="text-[0.85rem] font-semibold text-[#333]">
                    Deskripsi Program <span class="text-[#FF4444]">*</span>
                </label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    required
                    rows="4"
                    placeholder="Tuliskan deskripsi lengkap mengenai program ini..."
                    class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2.5 text-base focus:outline-none focus:border-[#C42B6F] transition-colors resize-none"
                >{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex items-center gap-4 pt-2 flex-wrap">
                <button type="submit" class="font-display px-8 py-2.5 text-[1rem] uppercase text-white bg-[#C42B6F] rounded-full cursor-pointer transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#d93a82] hover:shadow-[0_5px_0_#7d1a45] focus-visible:outline-3 focus-visible:outline-[#C42B6F] focus-visible:outline-offset-3">
                    Simpan Program
                </button>
                <a href="{{ route('programs.index') }}" class="font-display px-8 py-2 text-[1rem] uppercase text-[#006A8B] border-2 border-[#006A8B] rounded-full transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#006A8B] hover:text-white focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                    Batal
                </a>
            </div>
        </form>
    </section>

</main>
@endsection
