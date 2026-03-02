<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $news->title }} – {{ $site->site_name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=be-vietnam-pro:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Be Vietnam Pro', ui-sans-serif, system-ui, sans-serif; } .prose img { max-width: 100%; height: auto; } </style>
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
                <a href="{{ route('news.index') }}" class="text-slate-600 hover:text-teal-600 text-sm font-medium">← Tin tức</a>
            </div>
        </div>
    </header>

    <main class="py-12 sm:py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="mb-6">
                <a href="{{ route('news.index') }}" class="text-teal-600 hover:text-teal-700 font-medium">← Quay lại Tin tức</a>
            </p>
            <article class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-row">
                @if($news->image)
                    <div class="w-28 sm:w-48 lg:w-56 shrink-0 p-4 sm:pl-6 sm:pt-6 sm:pb-4">
                        <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-full aspect-square object-cover rounded-xl border border-slate-200">
                    </div>
                @endif
                <div class="flex-1 p-6 sm:p-8 sm:pr-8">
                    <time class="text-slate-500 text-sm">{{ $news->published_at?->format('d/m/Y H:i') }}</time>
                    <h1 class="text-3xl font-bold mt-2 mb-4">{{ $news->title }}</h1>
                    @if($news->excerpt)
                        <p class="text-lg text-slate-600 mb-6">{{ $news->excerpt }}</p>
                    @endif
                    <div class="prose prose-slate max-w-none">
                        {!! $news->body ?? '' !!}
                    </div>
                    @if($news->images->isNotEmpty())
                        <div class="mt-8 pt-6 border-t border-slate-200">
                            <p class="text-sm font-medium text-slate-600 mb-3">Hình ảnh</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($news->images as $img)
                                    <a href="{{ asset('storage/' . $img->path) }}" target="_blank" rel="noopener" class="block rounded-lg overflow-hidden border border-slate-200 hover:opacity-90">
                                        <img src="{{ asset('storage/' . $img->path) }}" alt="" class="w-full h-32 object-cover">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <p class="mt-8 pt-6 border-t border-slate-200">
                        @if($site->facebook_url ?? null)<a href="{{ $site->facebook_url }}" target="_blank" rel="noopener noreferrer" class="text-teal-600 hover:underline">Theo dõi chúng tôi trên Facebook →</a>@endif
                    </p>
                </div>
            </article>
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
