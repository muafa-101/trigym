@extends('layouts.main')

@section('content')
<main class="w-full max-w-[960px] mx-4 min-[1010px]:mx-auto mt-6 mb-12 p-4 min-[901px]:p-5 bg-[#F1F1F1] rounded-[20px] border-[3px] border-[#006A8B] flex flex-col gap-6">

    <!-- Judul Halaman -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#006A8B] px-6 py-4 flex items-center justify-between flex-wrap gap-3">
            <div class="text-white">
                <h1 class="font-display text-[1.8rem] leading-none uppercase">Program Management</h1>
                <p class="text-[0.78rem] mt-1 tracking-wide opacity-80">Kelola Program PresidentKu — data tersimpan langsung dari database (Pertemuan 10: Controller CRUD)</p>
            </div>
            <a href="{{ route('programs.create') }}" class="font-display px-6 py-2 text-[0.95rem] uppercase text-[#006A8B] bg-white rounded-full transition-all duration-200 hover:bg-[#FBC436] hover:text-white hover:-translate-y-0.5 focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                + Tambah Program
            </a>
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

    <!-- Tabel READ -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#0090BE] px-5 py-3 flex items-center justify-between flex-wrap gap-2">
            <h2 class="font-display text-[1.3rem] uppercase text-white">Daftar Program PresidentKu</h2>
            <span class="text-white text-[0.82rem] opacity-80">{{ count($programs) }} Program tersimpan</span>
        </div>

        @if ($programs->isEmpty())
            <div class="px-5 py-10 text-center">
                <p class="text-[#777] mb-4">Belum ada program. Tambahkan program pertama kamu.</p>
                <a href="{{ route('programs.create') }}" class="font-display px-6 py-2 text-[0.9rem] uppercase text-white bg-[#C42B6F] rounded-full inline-block transition-all hover:bg-[#d93a82]">
                    + Tambah Program Sekarang
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#F1F1F1] text-left">
                            <th class="px-4 py-3 font-semibold text-[#333] w-12 text-center">No</th>
                            <th class="px-4 py-3 font-semibold text-[#333]">Nama Program</th>
                            <th class="px-4 py-3 font-semibold text-[#333]">Deskripsi</th>
                            <th class="px-4 py-3 font-semibold text-[#333] text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programs as $i => $prog)
                            <tr class="border-t border-[#E5E5E5] hover:bg-[#F9F9F9] transition-colors">
                                <td class="px-4 py-3 text-[#555] text-center">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-[#222]">{{ $prog->nama }}</td>
                                <td class="px-4 py-3 text-[#555] max-w-[360px]">
                                    <span class="line-clamp-2">{{ $prog->deskripsi }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        <!-- Edit (GET -> edit()) -->
                                        <a href="{{ route('programs.edit', $prog->id) }}" class="font-display px-4 py-1 text-[0.85rem] uppercase text-white bg-[#006A8B] rounded-full transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#005070] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                                            Edit
                                        </a>
                                        <!-- Delete (POST/DELETE -> destroy()) -->
                                        <form action="{{ route('programs.destroy', $prog->id) }}" method="POST" onsubmit="return confirm('Hapus program «{{ addslashes($prog->nama) }}»?')">
                                            @csrf
                                            <button type="submit" class="font-display px-4 py-1 text-[0.85rem] uppercase text-white bg-[#C42B6F] rounded-full cursor-pointer transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#d93a82] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
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
