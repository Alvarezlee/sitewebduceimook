<x-site-layout :title="$event->title" :description="$event->description">
    @push('head')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Event',
                'name' => $event->title,
                'description' => $event->description,
                'startDate' => $event->starts_at->toIso8601String(),
                'endDate' => $event->ends_at?->toIso8601String(),
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'eventStatus' => 'https://schema.org/EventScheduled',
                'location' => ['@type' => 'Place', 'name' => $event->location ?? 'CEIMO'],
                'organizer' => ['@type' => 'Organization', 'name' => 'CEIMO'],
            ]) !!}
        </script>
    @endpush

    <article class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16">
        <p class="text-xs font-semibold text-brand-600">{{ $event->starts_at->translatedFormat('d M Y H:i') }}</p>
        <h1 class="section-title mt-2">{{ $event->title }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ $event->location }}</p>

        <div class="prose dark:prose-invert mt-8 max-w-none">
            {!! nl2br(e($event->description)) !!}
        </div>
    </article>
</x-site-layout>
