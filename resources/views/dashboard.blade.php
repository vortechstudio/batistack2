<x-layouts.app :title="__('Dashboard')">
    @if(\QCod\Settings\Setting\Setting::where('name', 'dashboard_message_active')->exists() && \QCod\Settings\Setting\Setting::where('name', 'dashboard_message_active')->first()->val == 1)
        @if(!empty(\QCod\Settings\Setting\Setting::where('name', 'dashboard_message_date_start')->first()->val) && \QCod\Settings\Setting\Setting::where('name', 'dashboard_message_date_start')->first()->val >= now() && \QCod\Settings\Setting\Setting::where('name', 'dashboard_message_date_end')->first()->val <= now())
            <div role="alert" class="alert alert-vertical mb-5 sm:alert-horizontal">
                <div>
                    <h3 class="font-bold">{{ \QCod\Settings\Setting\Setting::where('name', 'dashboard_message_title')->first()->val }} ({{ \QCod\Settings\Setting\Setting::where('name', 'dashboard_message_date_start')->first()->val }} - {{ \QCod\Settings\Setting\Setting::where('name', 'dashboard_message_date_end')->first()->val }})</h3>
                    <div class="text-xs">{{ \QCod\Settings\Setting\Setting::where('name', 'dashboard_message_body')->first()->val }}</div>
                </div>
            </div>
        @else
            <div role="alert" class="alert alert-vertical mb-5 sm:alert-horizontal">
                <div>
                    <h3 class="font-bold">{{ \QCod\Settings\Setting\Setting::where('name', 'dashboard_message_title')->first()->val }}</h3>
                    <div class="text-xs">{{ \QCod\Settings\Setting\Setting::where('name', 'dashboard_message_body')->first()->val }}</div>
                </div>
            </div>
        @endif
    @endif
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
