<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduKids – Nền tảng giáo dục đa công nghệ cho trẻ em</title>
    <meta name="description" content="Tổ Hợp Công Nghệ Giáo Dục EduKids – Đào tạo Robotics, STEM, Lập trình, Kỹ năng và Bồi dưỡng kiến thức cho trẻ em.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="EduKids – Nền tảng giáo dục đa công nghệ cho trẻ em">

        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=be-vietnam-pro:400,500,600,700|instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

            <style>
        [x-cloak] { display: none !important; }
            </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50" style="font-family: 'Be Vietnam Pro', ui-sans-serif, system-ui, sans-serif;">
    {{-- Header --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-18">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">{{ $site->site_name }}</span>
                </a>
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#trang-chu" class="text-slate-600 hover:text-teal-600 font-medium transition">Trang chủ</a>
                    <a href="#gioi-thieu" class="text-slate-600 hover:text-teal-600 font-medium transition">Giới thiệu</a>
                    <a href="#khoa-hoc" class="text-slate-600 hover:text-teal-600 font-medium transition">Khóa học</a>
                    <a href="{{ route('news.index') }}" class="text-slate-600 hover:text-teal-600 font-medium transition">Tin tức</a>
                    <a href="#lien-he" class="text-slate-600 hover:text-teal-600 font-medium transition">Liên hệ</a>
                </nav>
                <div class="flex items-center gap-4">
                    @if($site->facebook_url ?? null)
                    <a href="{{ $site->facebook_url }}" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-teal-600 transition" aria-label="Facebook {{ $site->site_name }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
        @endif
            @if (Route::has('login'))
                    @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-lg bg-teal-600 text-white font-medium hover:bg-teal-700 transition">Dashboard</a>
                    @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-slate-600 hover:text-teal-600 font-medium">Đăng nhập</a>
                        @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-teal-600 text-white font-medium hover:bg-teal-700 transition">Đăng ký</a>
                        @endif
                    @endauth
            @endif
                </div>
            </div>
        </div>
        </header>

    <main>
        {{-- Hero Slider --}}
        <section id="trang-chu" class="relative overflow-hidden bg-gradient-to-br from-teal-50 via-white to-cyan-50 py-16 sm:py-24 lg:py-32">
            <div class="absolute inset-0 opacity-30 pointer-events-none">
                <div class="absolute top-20 left-10 w-72 h-72 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl"></div>
                <div class="absolute bottom-20 right-10 w-72 h-72 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <div id="hero-slides" class="flex transition-transform duration-500 ease-out" style="transform: translateX(0%);">
                        {{-- Slide 1 --}}
                        <div class="hero-slide min-w-full flex-shrink-0 px-2">
                            <div class="text-center max-w-3xl mx-auto">
                                <p class="text-teal-600 font-semibold uppercase tracking-wider text-sm mb-4">Nền tảng giáo dục đa công nghệ</p>
                                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-800 leading-tight mb-6">
                                    Đào tạo <span class="text-teal-600">Robotics</span>, <span class="text-amber-500">STEM</span>, <span class="text-cyan-600">Lập trình</span> &amp; Kỹ năng cho trẻ em
                                </h1>
                                <p class="text-lg text-slate-600 mb-10">
                                    Bồi dưỡng kiến thức, phát triển tư duy sáng tạo và kỹ năng công nghệ từ sớm – nền tảng vững chắc cho tương lai.
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <a href="#lien-he" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-teal-600 text-white font-semibold hover:bg-teal-700 shadow-lg shadow-teal-500/30 transition">Liên hệ tư vấn</a>
                                    <a href="#khoa-hoc" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white border-2 border-teal-600 text-teal-600 font-semibold hover:bg-teal-50 transition">Xem khóa học</a>
                                </div>
                            </div>
                        </div>
                        {{-- Slide 2 --}}
                        <div class="hero-slide min-w-full flex-shrink-0 px-2">
                            <div class="text-center max-w-3xl mx-auto">
                                <p class="text-amber-600 font-semibold uppercase tracking-wider text-sm mb-4">Học mà chơi – Chơi mà học</p>
                                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-800 leading-tight mb-6">
                                    Khơi dậy <span class="text-amber-500">đam mê</span> công nghệ từ những bước đầu tiên
                                </h1>
                                <p class="text-lg text-slate-600 mb-10">
                                    Chương trình được thiết kế phù hợp từng độ tuổi, giúp trẻ làm quen Robotics, lập trình và tư duy logic một cách tự nhiên, vui vẻ.
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <a href="#khoa-hoc" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-amber-500 text-white font-semibold hover:bg-amber-600 shadow-lg shadow-amber-500/30 transition">Khám phá khóa học</a>
                                    <a href="#gioi-thieu" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white border-2 border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition">Về chúng tôi</a>
                                </div>
                            </div>
                        </div>
                        {{-- Slide 3 --}}
                        <div class="hero-slide min-w-full flex-shrink-0 px-2">
                            <div class="text-center max-w-3xl mx-auto">
                                <p class="text-cyan-600 font-semibold uppercase tracking-wider text-sm mb-4">Đa trung tâm – Một nền tảng</p>
                                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-800 leading-tight mb-6">
                                    Kết nối <span class="text-cyan-600">phụ huynh</span> với trung tâm uy tín gần bạn
                                </h1>
                                <p class="text-lg text-slate-600 mb-10">
                                    Quản lý lớp học, lịch học và tài liệu trực tuyến. Phụ huynh dễ dàng theo dõi tiến độ và đăng ký khóa học phù hợp.
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <a href="#lien-he" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-cyan-600 text-white font-semibold hover:bg-cyan-700 shadow-lg shadow-cyan-500/30 transition">Liên hệ ngay</a>
                                    <a href="{{ route('news.index') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white border-2 border-cyan-600 text-cyan-600 font-semibold hover:bg-cyan-50 transition">Tin tức & Sự kiện</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Nút Prev / Next --}}
                <button type="button" id="hero-prev" aria-label="Slide trước" class="absolute left-2 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-white/90 hover:bg-white shadow-lg border border-slate-200 flex items-center justify-center text-slate-700 hover:text-teal-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="hero-next" aria-label="Slide tiếp theo" class="absolute right-2 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-white/90 hover:bg-white shadow-lg border border-slate-200 flex items-center justify-center text-slate-700 hover:text-teal-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                {{-- Dots --}}
                <div class="flex justify-center items-center gap-2 mt-8">
                    <button type="button" data-slide="0" aria-label="Slide 1" class="hero-dot h-2.5 rounded-full bg-teal-400 transition-all duration-300 w-8"></button>
                    <button type="button" data-slide="1" aria-label="Slide 2" class="hero-dot w-2.5 h-2.5 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-300"></button>
                    <button type="button" data-slide="2" aria-label="Slide 3" class="hero-dot w-2.5 h-2.5 rounded-full bg-slate-300 hover:bg-slate-400 transition-all duration-300"></button>
                </div>
            </div>
        </section>

        {{-- Lĩnh vực đào tạo --}}
        <section id="khoa-hoc" class="py-16 sm:py-24 bg-white">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-14">
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-4">Lĩnh vực đào tạo</h2>
                    <p class="text-slate-600 max-w-2xl mx-auto">Chương trình đa dạng, phù hợp từng lứa tuổi và sở thích của trẻ.</p>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="group p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-teal-200 hover:shadow-lg hover:shadow-teal-500/10 transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-teal-500 group-hover:text-white transition">🤖</div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Robotics</h3>
                        <p class="text-slate-600">Lắp ráp, lập trình robot – phát triển tư duy logic và kỹ năng kỹ thuật.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-amber-200 hover:shadow-lg hover:shadow-amber-500/10 transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-amber-500 group-hover:text-white transition">🔬</div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">STEM</h3>
                        <p class="text-slate-600">Khoa học – Công nghệ – Kỹ thuật – Toán học, học qua thực hành.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-cyan-200 hover:shadow-lg hover:shadow-cyan-500/10 transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-cyan-500 group-hover:text-white transition">💻</div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Lập trình</h3>
                        <p class="text-slate-600">Từ kéo thả đến code – xây dựng nền tảng lập trình từ nhỏ.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-violet-200 hover:shadow-lg hover:shadow-violet-500/10 transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-violet-500 group-hover:text-white transition">✨</div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Kỹ năng</h3>
                        <p class="text-slate-600">Làm việc nhóm, thuyết trình, tư duy phản biện và sáng tạo.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Giới thiệu / Ưu điểm --}}
        <section id="gioi-thieu" class="py-16 sm:py-24 bg-gradient-to-br from-slate-50 to-teal-50/50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-6">Vì sao chọn EduKids?</h2>
                        <p class="text-slate-600 mb-8 text-lg leading-relaxed">
                            Chúng tôi là nền tảng giáo dục đa công nghệ, kết nối phụ huynh với các trung tâm uy tín. Chương trình được thiết kế khoa học, giúp trẻ vừa học vừa chơi, phát triển toàn diện.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-teal-500 text-white flex items-center justify-center text-sm font-bold">✓</span>
                                <span class="text-slate-700">Đội ngũ giáo viên giàu kinh nghiệm, tận tâm với trẻ.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-teal-500 text-white flex items-center justify-center text-sm font-bold">✓</span>
                                <span class="text-slate-700">Cơ sở vật chất hiện đại, an toàn cho học viên.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-teal-500 text-white flex items-center justify-center text-sm font-bold">✓</span>
                                <span class="text-slate-700">Lộ trình học rõ ràng, bám sát từng độ tuổi.</span>
                        </li>
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-teal-500 text-white flex items-center justify-center text-sm font-bold">✓</span>
                                <span class="text-slate-700">Quản lý lớp học, điểm danh và tài liệu trực tuyến.</span>
                        </li>
                    </ul>
                    </div>
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-teal-400 to-cyan-500 shadow-2xl shadow-teal-500/20 flex items-center justify-center text-white/90">
                            <span class="text-6xl">🎓</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Tin tức nổi bật --}}
        <section id="tin-tuc" class="py-16 sm:py-24 bg-white">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-2">Tin tức & Sự kiện</h2>
                        <p class="text-slate-600">Cập nhật hoạt động và thông tin hữu ích từ EduKids.</p>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($news ?? [] as $item)
                        <article class="rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:border-teal-200 transition bg-white">
                            <a href="{{ route('news.show', $item->slug) }}" class="block aspect-[16/10] bg-slate-100 overflow-hidden">
                                <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/news-placeholder.svg') }}" alt="{{ $item->title }}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                            </a>
                            <div class="px-6 py-6 sm:px-6 sm:py-7">
                                <h3 class="font-bold text-slate-800 text-lg leading-snug mb-3 line-clamp-2">{{ $item->title }}</h3>
                                <p class="text-slate-600 text-sm leading-relaxed line-clamp-2 mb-3">{{ $item->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($item->body), 100) }}</p>
                                <p class="text-slate-400 text-xs">{{ $item->published_at?->format('d/m/Y') }}</p>
                                <a href="{{ route('news.show', $item->slug) }}" class="inline-block mt-4 text-teal-600 font-medium text-sm hover:underline">Xem thêm →</a>
                            </div>
                        </article>
                    @empty
                        <article class="rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition sm:col-span-2 lg:col-span-3">
                            <div class="p-8 text-center text-slate-500 bg-slate-50 rounded-2xl">
                                <p>Tin tức &amp; sự kiện sẽ được hiển thị tại đây khi có dữ liệu từ trang quản trị.</p>
                            </div>
                        </article>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- CTA Liên hệ --}}
        <section id="lien-he" class="py-16 sm:py-24 bg-gradient-to-br from-teal-600 to-cyan-700 text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">Liên hệ tư vấn khóa học</h2>
                <p class="text-teal-100 text-lg mb-10">Để lại thông tin, chúng tôi sẽ liên hệ sớm nhất.</p>
                <form action="#" method="post" class="max-w-md mx-auto space-y-4 text-left">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4">
                        <input type="text" name="name" placeholder="Họ tên phụ huynh" required
                            class="w-full px-4 py-3 rounded-xl border-0 bg-white/10 placeholder-white/70 text-white focus:ring-2 focus:ring-white/50">
                        <input type="tel" name="phone" placeholder="Số điện thoại" required
                            class="w-full px-4 py-3 rounded-xl border-0 bg-white/10 placeholder-white/70 text-white focus:ring-2 focus:ring-white/50">
                    </div>
                    <input type="email" name="email" placeholder="Email" required
                        class="w-full px-4 py-3 rounded-xl border-0 bg-white/10 placeholder-white/70 text-white focus:ring-2 focus:ring-white/50">
                    <textarea name="message" rows="3" placeholder="Nội dung cần tư vấn (khóa học, trung tâm...)"
                        class="w-full px-4 py-3 rounded-xl border-0 bg-white/10 placeholder-white/70 text-white focus:ring-2 focus:ring-white/50 resize-none"></textarea>
                    <button type="submit" class="w-full py-4 rounded-xl bg-white text-teal-700 font-semibold hover:bg-teal-50 transition">Gửi liên hệ</button>
                </form>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-800 text-slate-300 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <div>
                    <span class="text-xl font-bold text-white">{{ $site->site_name }}</span>
                    @if($site->footer_description)
                    <p class="mt-2 text-sm">{{ $site->footer_description }}</p>
                    @endif
                    @if($site->facebook_url)
                    <a href="{{ $site->facebook_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 mt-3 text-sm text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Theo dõi chúng tôi trên Facebook
                    </a>
                    @endif
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3">Liên kết</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#trang-chu" class="hover:text-white transition">Trang chủ</a></li>
                        <li><a href="#gioi-thieu" class="hover:text-white transition">Giới thiệu</a></li>
                        <li><a href="#khoa-hoc" class="hover:text-white transition">Khóa học</a></li>
                        <li><a href="{{ route('news.index') }}" class="hover:text-white transition">Tin tức</a></li>
                        <li><a href="#lien-he" class="hover:text-white transition">Liên hệ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3">Khóa học</h4>
                    <ul class="space-y-2 text-sm">
                        <li>Robotics</li>
                        <li>STEM</li>
                        <li>Lập trình</li>
                        <li>Kỹ năng</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3">Liên hệ</h4>
                    @if($site->address)<p class="text-sm">{{ $site->address }}</p>@endif
                    @if($site->email)<p class="text-sm">Email: {{ $site->email }}</p>@endif
                    @if($site->phone)<p class="text-sm">Điện thoại: {{ $site->phone }}</p>@endif
                    @if($site->hotline)<p class="text-sm">Hotline: {{ $site->hotline }}</p>@endif
                    @if($site->facebook_url)<a href="{{ $site->facebook_url }}" target="_blank" rel="noopener noreferrer" class="text-sm text-teal-400 hover:text-teal-300 transition">Facebook / {{ $site->site_name }}</a>@endif
                </div>
            </div>
            <div class="pt-8 border-t border-slate-700 text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} {{ $site->site_name }}. Bảo lưu mọi quyền.
            </div>
        </div>
    </footer>

    <script>
        (function () {
            var totalSlides = 3;
            var current = 0;
            var slidesEl = document.getElementById('hero-slides');
            var prevBtn = document.getElementById('hero-prev');
            var nextBtn = document.getElementById('hero-next');
            var dots = document.querySelectorAll('.hero-dot');

            function goTo(index) {
                current = (index + totalSlides) % totalSlides;
                slidesEl.style.transform = 'translateX(-' + (current * 100) + '%)';
                dots.forEach(function (dot, i) {
                    dot.classList.toggle('bg-teal-400', i === current);
                    dot.classList.toggle('bg-slate-300', i !== current);
                    dot.classList.toggle('w-2.5', i !== current);
                    dot.classList.toggle('w-8', i === current);
                });
            }

            if (prevBtn) prevBtn.addEventListener('click', function () { goTo(current - 1); });
            if (nextBtn) nextBtn.addEventListener('click', function () { goTo(current + 1); });
            dots.forEach(function (dot, i) {
                dot.addEventListener('click', function () { goTo(i); });
            });

            goTo(0);
        })();
    </script>
    </body>
</html>
