@extends('layouts.app')

@section('title', $movie->title . ' - HCTV')

@section('content')
<div class="bg-cinematic-dark min-h-screen">
    <!-- Hero Section (Giống trang chủ) -->
    <div class="relative h-screen w-full">
        <!-- Backdrop Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $movie->backdrop_url }}');"></div>
        
        <!-- Gradient Overlays -->
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="absolute inset-0 bg-cinematic-gradient"></div>
        
        <!-- Content -->
        <div class="relative z-10 h-full flex flex-col justify-end pb-32 px-6 md:px-16 max-w-7xl mx-auto">
            <div class="max-w-3xl">
                <!-- Info Tags -->
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="bg-cinematic-red text-white text-xs font-bold px-2 py-1 rounded uppercase tracking-wider">{{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</span>
                    <span class="text-cinematic-gold text-sm font-semibold">{{ $movie->genre }}</span>
                    <span class="text-gray-300 text-sm flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $movie->duration }} Phút
                    </span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-serif font-bold text-white leading-tight mb-6 drop-shadow-lg">
                    {{ $movie->title }}
                </h1>
                
                <p class="text-gray-300 text-lg md:text-xl mb-8 line-clamp-3 md:line-clamp-none max-w-2xl drop-shadow-md">
                    {{ $movie->description }}
                </p>
                
                <div class="flex flex-wrap gap-4 items-center">
                    <a href="{{ route('showtimes') }}" class="group relative px-8 py-4 bg-cinematic-red text-white font-bold rounded overflow-hidden shadow-[0_0_20px_rgba(229,9,20,0.5)] transition-all hover:scale-105 hover:shadow-[0_0_30px_rgba(229,9,20,0.8)]">
                        <span class="relative z-10 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            MUA VÉ NGAY
                        </span>
                        <div class="absolute inset-0 h-full w-full bg-white/20 -translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out"></div>
                    </a>
                    
                    <a href="{{ $movie->trailer_url }}" target="_blank" class="px-8 py-4 border border-white/30 text-white font-bold rounded hover:bg-white/10 transition-colors flex items-center gap-2 backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        XEM TRAILER
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN BODY: CAST & DETAILS -->
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-16 md:py-24">
        <!-- DIỄN VIÊN & VAI DIỄN SECTION -->
        @if($movie->actors && $movie->actors->count() > 0)
        <div class="mb-16">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/10">
                <div>
                    <h2 class="text-2xl md:text-3xl font-serif font-bold text-white uppercase tracking-wider flex items-center gap-3">
                        <span class="w-2.5 h-7 bg-cinematic-red rounded inline-block"></span>
                        Diễn Viên & Nhân Vật
                    </h2>
                    <p class="text-gray-400 text-sm mt-1">Bấm vào diễn viên để xem ảnh chân dung và thông tin chi tiết</p>
                </div>
                <span class="text-xs text-cinematic-gold bg-cinematic-gold/10 border border-cinematic-gold/30 px-3 py-1 rounded-full font-semibold">
                    {{ $movie->actors->count() }} Diễn viên
                </span>
            </div>

            <!-- Cast Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach($movie->actors as $actor)
                <div class="actor-card group cursor-pointer text-center bg-white/5 hover:bg-white/10 border border-white/10 hover:border-cinematic-gold/60 rounded-2xl p-4 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_12px_30px_rgba(0,0,0,0.6)] flex flex-col items-center justify-between"
                     data-name="{{ $actor->name }}"
                     data-role="{{ $actor->role ?? 'Diễn viên' }}"
                     data-avatar="{{ $actor->avatar_url }}"
                     data-bio="{{ $actor->bio ?? 'Hiện chưa có tiểu sử chi tiết cho diễn viên này.' }}">
                    
                    <div class="w-full flex flex-col items-center">
                        <!-- Avatar Circle -->
                        <div class="relative w-24 h-24 mb-3 rounded-full overflow-hidden border-2 border-white/20 group-hover:border-cinematic-gold transition-colors duration-300 shadow-lg bg-gray-800">
                            <img src="{{ $actor->avatar_url }}" alt="{{ $actor->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>

                        <!-- Name & Role -->
                        <h4 class="font-bold text-white text-sm group-hover:text-cinematic-gold transition-colors line-clamp-1 w-full" title="{{ $actor->name }}">
                            {{ $actor->name }}
                        </h4>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-1 w-full" title="{{ $actor->role ?? 'Diễn viên' }}">
                            {{ $actor->role ?? 'Diễn viên' }}
                        </p>
                    </div>

                    <button type="button" class="mt-3 text-[11px] text-cinematic-gold/90 bg-black/40 border border-cinematic-gold/20 px-3 py-1 rounded-full group-hover:bg-cinematic-gold group-hover:text-black font-medium transition-all flex items-center gap-1">
                        <span>Chi tiết</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- THÔNG TIN PHIM BỔ SUNG -->
        <div class="movie-additional-info grid grid-cols-1 md:grid-cols-3 gap-8 p-8 bg-white/[0.03] border border-white/10 rounded-2xl">
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Đạo Diễn / Sản Xuất</h4>
                <p class="text-white font-medium">HCTV Studio Productions</p>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Định Dạng Chiếu</h4>
                <p class="text-white font-medium">2D, 3D, IMAX, ScreenX, 4DX</p>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ngôn Ngữ & Phụ Đề</h4>
                <p class="text-white font-medium">Tiếng Anh - Phụ đề Tiếng Việt</p>
            </div>
        </div>
    </div>
</div>

<!-- ACTOR DETAILS MODAL -->
<div id="actor-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-2 sm:p-6 bg-black/90 backdrop-blur-md transition-opacity duration-300">
    <div id="actor-modal-card" class="actor-modal-card relative bg-[#141414] border border-white/10 rounded-2xl max-w-5xl w-full h-[70vh] min-h-[400px] max-h-[600px] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.9)] transform transition-all duration-300 scale-95 opacity-0 flex flex-row">
        
        <!-- Close Button -->
        <button id="close-actor-modal" class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-black/50 hover:bg-cinematic-red text-white flex items-center justify-center transition-colors border border-white/10 backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Left: Actor Image (50%) -->
        <div class="actor-modal-left w-1/2 h-full shrink-0 bg-black relative border-r border-white/10">
            <!-- Decorative gradient behind image -->
            <div class="actor-modal-gradient absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-[#141414] z-10 pointer-events-none"></div>
            <img id="modal-actor-avatar" src="" alt="Actor" class="w-full h-full object-cover object-center opacity-100">
        </div>

        <!-- Right: Info (50%) -->
        <div class="actor-modal-right w-1/2 h-full p-6 md:p-10 flex flex-col relative z-20 bg-[#141414] overflow-y-auto">
            <div class="mb-6">
                <h3 id="modal-actor-name" class="actor-modal-name text-3xl md:text-4xl font-serif font-bold text-white mb-3"></h3>
                <div class="actor-role-badge inline-flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-1.5 rounded-full">
                    <span class="actor-role-label text-gray-400 text-sm">Vai diễn:</span>
                    <strong id="modal-actor-role" class="actor-role-value text-cinematic-gold text-sm font-semibold tracking-wide"></strong>
                </div>
            </div>

            <div class="flex-1">
                <h4 class="actor-bio-heading text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="text-cinematic-red shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tiểu Sử & Thông Tin
                </h4>
                <div class="prose prose-invert prose-sm max-w-none">
                    <p id="modal-actor-bio" class="actor-bio-text text-gray-300 text-sm leading-relaxed whitespace-pre-line"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('actor-modal');
    const modalCard = document.getElementById('actor-modal-card');
    const closeBtn = document.getElementById('close-actor-modal');
    const modalAvatar = document.getElementById('modal-actor-avatar');
    const modalName = document.getElementById('modal-actor-name');
    const modalRole = document.getElementById('modal-actor-role');
    const modalBio = document.getElementById('modal-actor-bio');

    function openModal(data) {
        modalAvatar.src = data.avatar;
        modalName.textContent = data.name;
        modalRole.textContent = data.role;
        modalBio.textContent = data.bio;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modalCard.classList.remove('scale-95', 'opacity-0');
            modalCard.classList.add('scale-100', 'opacity-100');
        }, 10);

        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modalCard.classList.remove('scale-100', 'opacity-100');
        modalCard.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }, 200);
    }

    document.querySelectorAll('.actor-card').forEach(card => {
        card.addEventListener('click', function () {
            const data = {
                name: this.getAttribute('data-name'),
                role: this.getAttribute('data-role'),
                avatar: this.getAttribute('data-avatar'),
                bio: this.getAttribute('data-bio')
            };
            openModal(data);
        });
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>
@endsection
