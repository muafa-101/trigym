@extends('layouts.main')

@section('content')
<main class="w-full max-w-[960px] mx-4 min-[1010px]:mx-auto mt-6 mb-12 p-4 min-[901px]:p-5 bg-[#F1F1F1] rounded-[20px] border-[3px] border-[#006A8B] flex flex-col gap-6">

    <!-- Judul Halaman -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#006A8B] px-6 py-4 flex items-center justify-between flex-wrap gap-3">
            <div class="text-white">
                <h1 class="font-display text-[1.8rem] leading-none uppercase">Program Management</h1>
                <p class="text-[0.78rem] mt-1 tracking-wide opacity-80">Kelola Program PresidentKu — data tersimpan langsung dari database</p>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="rounded-[14px] border-2 border-[#22A94F] bg-white px-5 py-3 text-sm text-[#14602B] font-semibold" role="alert">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-[14px] border-2 border-[#FF4444] bg-white px-5 py-3 text-sm text-[#B42318]" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Tambah / Edit -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#FBC436] px-5 py-3">
            <h2 class="font-display text-[1.3rem] uppercase text-white">
                {{ $editItem !== null ? '✏️ Edit Program' : '+ Tambah Program' }}
            </h2>
        </div>

        @if ($editItem !== null)
            <!-- Form UPDATE -->
            <form action="{{ route('programs.update', $editItem->id) }}" method="POST" class="px-5 py-5 flex flex-col gap-4">
                @csrf
                <div class="flex flex-col gap-1">
                    <label for="edit-nama" class="text-[0.85rem] font-semibold text-[#333]">Nama Program</label>
                    <input
                        id="edit-nama"
                        type="text"
                        name="nama"
                        value="{{ old('nama', $editItem->nama) }}"
                        required
                        placeholder="Contoh: Pembentukan Otot"
                        class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2 text-base focus:outline-none focus:border-[#C42B6F] transition-colors"
                    >
                </div>
                <div class="flex flex-col gap-1">
                    <label for="edit-deskripsi" class="text-[0.85rem] font-semibold text-[#333]">Deskripsi</label>
                    <textarea
                        id="edit-deskripsi"
                        name="deskripsi"
                        required
                        rows="3"
                        placeholder="Deskripsi singkat program..."
                        class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2 text-base focus:outline-none focus:border-[#C42B6F] transition-colors resize-none"
                    >{{ old('deskripsi', $editItem->deskripsi) }}</textarea>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <button type="submit" class="font-display px-8 py-2 text-[1rem] uppercase text-white bg-[#006A8B] rounded-full cursor-pointer transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#005070] focus-visible:outline-3 focus-visible:outline-[#C42B6F] focus-visible:outline-offset-3">
                        Update
                    </button>
                    <a href="{{ route('programs.index') }}" class="font-display px-8 py-2 text-[1rem] uppercase text-[#006A8B] border-2 border-[#006A8B] rounded-full transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#006A8B] hover:text-white focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                        Batal
                    </a>
                </div>
            </form>
        @else
            <!-- Form CREATE -->
            <form action="{{ route('programs.store') }}" method="POST" class="px-5 py-5 flex flex-col gap-4">
                @csrf
                <div class="flex flex-col gap-1">
                    <label for="nama" class="text-[0.85rem] font-semibold text-[#333]">Nama Program</label>
                    <input
                        id="nama"
                        type="text"
                        name="nama"
                        value="{{ old('nama') }}"
                        required
                        placeholder="Contoh: Pembentukan Otot"
                        class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2 text-base focus:outline-none focus:border-[#C42B6F] transition-colors"
                    >
                </div>
                <div class="flex flex-col gap-1">
                    <label for="deskripsi" class="text-[0.85rem] font-semibold text-[#333]">Deskripsi</label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        required
                        rows="3"
                        placeholder="Deskripsi singkat program..."
                        class="w-full border-2 border-[#006A8B] rounded-[10px] px-4 py-2 text-base focus:outline-none focus:border-[#C42B6F] transition-colors resize-none"
                    >{{ old('deskripsi') }}</textarea>
                </div>
                <div>
                    <button type="submit" class="font-display px-8 py-2 text-[1rem] uppercase text-white bg-[#C42B6F] rounded-full cursor-pointer transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#d93a82] hover:shadow-[0_5px_0_#7d1a45] focus-visible:outline-3 focus-visible:outline-[#C42B6F] focus-visible:outline-offset-3">
                        Simpan
                    </button>
                </div>
            </form>
        @endif
    </section>

    <!-- Tabel READ -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#0090BE] px-5 py-3 flex items-center justify-between flex-wrap gap-2">
            <h2 class="font-display text-[1.3rem] uppercase text-white">Program PresidentKu</h2>
            <span class="text-white text-[0.82rem] opacity-80">{{ count($programs) }} Program tersimpan</span>
        </div>

        @if ($programs->isEmpty())
            <p class="px-5 py-6 text-center text-[#777]">Belum ada program. Tambahkan program pertama di atas.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#F1F1F1] text-left">
                            <th class="px-4 py-3 font-semibold text-[#333] w-10">No</th>
                            <th class="px-4 py-3 font-semibold text-[#333]">Nama Program</th>
                            <th class="px-4 py-3 font-semibold text-[#333]">Deskripsi</th>
                            <th class="px-4 py-3 font-semibold text-[#333] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programs as $i => $program)
                            <tr class="border-t border-[#E5E5E5] hover:bg-[#F9F9F9] transition-colors">
                                <td class="px-4 py-3 text-[#555]">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-[#222]">{{ $program->nama }}</td>
                                <td class="px-4 py-3 text-[#555] max-w-[320px]">
                                    <span class="line-clamp-2">{{ $program->deskripsi }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        <!-- Edit -->
                                        <a href="{{ route('programs.index', ['edit' => $program->id]) }}" class="font-display px-5 py-1 text-[0.85rem] uppercase text-white bg-[#006A8B] rounded-full transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#005070] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                                            Edit
                                        </a>
                                        <!-- Delete -->
                                        <form action="{{ route('programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Hapus program «{{ addslashes($program->nama) }}»?')">
                                            @csrf
                                            <button type="submit" class="font-display px-5 py-1 text-[0.85rem] uppercase text-white bg-[#C42B6F] rounded-full cursor-pointer transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#d93a82] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

</main>
@endsection
