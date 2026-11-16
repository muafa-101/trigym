@extends('layouts.main')

@section('content')
<main class="w-full max-w-[1000px] mx-4 min-[1050px]:mx-auto mt-6 mb-12 p-4 min-[901px]:p-5 bg-[#F1F1F1] rounded-[20px] border-[3px] border-[#006A8B] flex flex-col gap-6">

    <!-- Header Section -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#006A8B] px-6 py-4 flex items-center justify-between flex-wrap gap-3">
            <div class="text-white">
                <h1 class="font-display text-[1.8rem] leading-none uppercase">Pengelolaan Member & User</h1>
                <p class="text-[0.78rem] mt-1 tracking-wide opacity-80">Kelola data informasi member TriGym — CRUD lengkap Controller (Pertemuan 10)</p>
            </div>
            <a href="{{ route('users.create') }}" class="font-display px-6 py-2 text-[0.95rem] uppercase text-[#006A8B] bg-white rounded-full transition-all duration-200 hover:bg-[#FBC436] hover:text-white hover:-translate-y-0.5 focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                + Tambah Member
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

    <!-- Tabel READ Member -->
    <section class="border-[4px] border-white rounded-[20px] overflow-hidden bg-white">
        <div class="bg-[#0090BE] px-5 py-3 flex items-center justify-between flex-wrap gap-2">
            <h2 class="font-display text-[1.3rem] uppercase text-white">Daftar Pengguna TriGym</h2>
            <span class="text-white text-[0.82rem] opacity-80">{{ count($users) }} Member Terdaftar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#F1F1F1] text-left">
                        <th class="px-4 py-3 font-semibold text-[#333] w-12 text-center">No</th>
                        <th class="px-4 py-3 font-semibold text-[#333]">Member / Nama</th>
                        <th class="px-4 py-3 font-semibold text-[#333]">Email</th>
                        <th class="px-4 py-3 font-semibold text-[#333]">Paket</th>
                        <th class="px-4 py-3 font-semibold text-[#333] text-center">Status</th>
                        <th class="px-4 py-3 font-semibold text-[#333]">Masa Aktif</th>
                        <th class="px-4 py-3 font-semibold text-[#333] text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $i => $u)
                        <tr class="border-t border-[#E5E5E5] hover:bg-[#F9F9F9] transition-colors">
                            <td class="px-4 py-3 text-[#555] text-center">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-[#222]">{{ $u->name }}</div>
                                <div class="text-[0.75rem] text-[#006A8B] font-display">{{ $u->memberCode() }}</div>
                            </td>
                            <td class="px-4 py-3 text-[#555] break-all">{{ $u->email }}</td>
                            <td class="px-4 py-3 text-[#555]">
                                {{ $u->package ? $u->package->name : 'Tanpa Paket' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($u->status === 'Aktif')
                                    <span class="inline-block px-3 py-0.5 rounded-full text-xs font-semibold bg-[#E8F8EE] text-[#14602B] border border-[#22A94F]">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-0.5 rounded-full text-xs font-semibold bg-[#FEE4E2] text-[#B42318] border border-[#FF4444]">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-[#555]">
                                {{ $u->expiryLabel() }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit -->
                                    <a href="{{ route('users.edit', $u->id) }}" class="font-display px-3 py-1 text-[0.82rem] uppercase text-white bg-[#006A8B] rounded-full transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#005070] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                                        Edit
                                    </a>
                                    <!-- Delete -->
                                    @if ($u->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus user «{{ addslashes($u->name) }}»?')">
                                            @csrf
                                            <button type="submit" class="font-display px-3 py-1 text-[0.82rem] uppercase text-white bg-[#C42B6F] rounded-full cursor-pointer transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#d93a82] focus-visible:outline-3 focus-visible:outline-[#C42B6F]">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-[#777]">
                                Belum ada user terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</main>
@endsection
