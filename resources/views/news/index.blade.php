<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tin tức & Sự kiện – {{ $site->site_name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=be-vietnam-pro:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Be Vietnam Pro', ui-sans-serif, system-ui, sans-serif; } </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50">
    {{-- Header --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">{{ $site->site_name }}</a>
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ url('/') }}" class="text-slate-600 hover:text-teal-600 font-medium transition">Trang chủ</a>
                    <a href="{{ url('/') }}#gioi-thieu" class="text-slate-600 hover:text-teal-600 font-medium transition">Giới thiệu</a>
                    <a href="{{ url('/') }}#khoa-hoc" class="text-slate-600 hover:text-teal-600 font-medium transition">Khóa học</a>
                    <a href="{{ route('news.index') }}" class="text-teal-600 font-medium">Tin tức</a>
                    <a href="{{ url('/') }}#lien-he" class="text-slate-600 hover:text-teal-600 font-medium transition">Liên hệ</a>
                </nav>
                <a href="{{ url('/') }}" class="text-slate-600 hover:text-teal-600 text-sm font-medium">← Về trang chủ</a>
            </div>
        </div>
    </header>

    <main class="py-12 sm:py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-2">Tin tức & Sự kiện</h1>
                <p class="text-slate-600">Cập nhật hoạt động và thông tin hữu ích từ {{ $site->site_name }}.</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($news as $item)
                    <article class="rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:border-teal-200 transition bg-white">
                        <a href="{{ route('news.show', $item->slug) }}" class="block aspect-[16/10] bg-slate-100 overflow-hidden">
                            <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/news-placeholder.svg') }}" alt="{{ $item->title }}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                        </a>
                        <div class="px-6 py-6 sm:px-6 sm:py-7">
                            <h2 class="font-bold text-slate-800 text-lg leading-snug mb-3 line-clamp-2">{{ $item->title }}</h2>
                            <p class="text-slate-600 text-sm leading-relaxed line-clamp-2 mb-3">{{ $item->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($item->body), 100) }}</p>
                            <p class="text-slate-400 text-xs">{{ $item->published_at?->format('d/m/Y') }}</p>
                            <a href="{{ route('news.show', $item->slug) }}" class="inline-block mt-4 text-teal-600 font-medium text-sm hover:underline">Xem thêm →</a>
                        </div>
                    </article>
                @empty
                    <div class="sm:col-span-2 lg:col-span-3 rounded-2xl border border-slate-200 bg-white p-12 text-center text-slate-500">
                        <p>Chưa có bài viết nào.</p>
                        <a href="{{ url('/') }}" class="inline-block mt-4 text-teal-600 font-medium hover:underline">Quay lại trang chủ</a>
                    </div>
                @endforelse
            </div>
            @if($news->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $news->links('vendor.pagination.news') }}
                </div>
            @endif
        </div>
    </main>

    <footer class="bg-slate-800 text-slate-300 py-8 mt-12">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm">
            <a href="{{ url('/') }}" class="text-white font-semibold">{{ $site->site_name }}</a>
            <span class="mx-2">·</span>
            <a href="{{ route('news.index') }}" class="hover:text-white transition">Tin tức</a>
            <p class="mt-2 text-slate-500">&copy; {{ date('Y') }} {{ $site->site_name }}</p>
        </div>
    </footer>
</body>
</html>
