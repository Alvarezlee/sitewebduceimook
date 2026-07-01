<x-site-layout :title="$page->seo_title ?? $page->title" :description="$page->seo_description">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="section-title">{{ $page->title }}</h1>
        <div class="prose dark:prose-invert mt-8 max-w-none">
            {!! nl2br(e($page->content)) !!}
        </div>
    </div>
</x-site-layout>
