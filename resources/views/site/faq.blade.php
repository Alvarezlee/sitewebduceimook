<x-site-layout title="FAQ">
    @push('head')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqs->flatten()->map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
                ])->values()->all(),
            ]) !!}
        </script>
    @endpush

    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16" x-data="{ open: null }">
        <span class="section-eyebrow">Aide</span>
        <h1 class="section-title mt-1 mb-10">Questions fréquentes</h1>

        @foreach ($faqs as $category => $items)
            <h2 class="text-lg font-bold mb-4">{{ $category }}</h2>
            <div class="space-y-3 mb-10">
                @foreach ($items as $faq)
                    <div class="glass-card">
                        <button @click="open = open === {{ $faq->id }} ? null : {{ $faq->id }}" class="w-full text-left px-6 py-4 font-semibold flex items-center justify-between">
                            {{ $faq->question }}
                            <span x-text="open === {{ $faq->id }} ? '−' : '+'"></span>
                        </button>
                        <div x-show="open === {{ $faq->id }}" x-cloak class="px-6 pb-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $faq->answer }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</x-site-layout>
