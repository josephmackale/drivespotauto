{!! view_render_event('bagisto.shop.layout.footer.before') !!}

@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $channel = core()->getCurrentChannel();

    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);

    $sections = $customization?->options ?? [];

    // Split sections into two groups (for columns 2 and 3)
    $mid = (int) ceil(count($sections) / 2);
    $sectionsLeft  = array_slice($sections, 0, $mid);
    $sectionsRight = array_slice($sections, $mid);

    // DriveSpot info
    $ds_whatsapp = '254792163144';
    $ds_phone_display = '+254 792 163 144';
    $ds_phone_tel = '+254792163144';
    $ds_email = 'support@drivespotauto.com';
    $ds_location = 'Sunset Arcade Waiyaki way, Nairobi';

    // Logo provision (put your logo file here)
    // Example: public/images/logo-footer.png
    $logoUrl = asset('images/logo-footer.png');
@endphp

<footer class="mt-10 bg-navyBlue text-white">
    <div class="mx-auto max-w-screen-2xl px-6 py-10 max-md:px-4">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:32px;align-items:start;">
            <!-- Column 1: Logo + short pitch -->
            <div class="col-span-12 lg:col-span-3">
                <div class="flex flex-col gap-4">
                    {{-- If you don't have a logo image yet, keep the text fallback --}}
                    <div class="flex items-center gap-3">
                        <img
                            src="{{ $logoUrl }}"
                            onerror="this.style.display='none'"
                            alt="DriveSpot Auto"
                            class="h-8 w-auto"
                        />
                        <span class="text-xl font-semibold tracking-wide">
                            DriveSpot Auto
                        </span>
                    </div>

                    <p class="text-sm text-zinc-200">
                        Genuine BMW, LAND ROVER, MERCEDES, PEUGEOT, VOLVO auto parts.
                    </p>
                    <div class="flex gap-4 text-xs text-zinc-300 pt-2">

                    <div class="flex items-center gap-1">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Genuine Parts
                    </div>

                    <div class="flex items-center gap-1">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                    Fast Delivery
                    </div>

                    <div class="flex items-center gap-1">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Trusted Seller
                    </div>

                    </div>

                    <div class="flex gap-3 mt-4">

                        <a href="#" class="hover:opacity-80">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                        </a>

                        <a href="#" class="hover:opacity-80">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                        </a>

                        <a href="https://wa.me/{{ $ds_whatsapp }}" target="_blank" class="hover:opacity-80">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                        </a>

                        </div>
                </div>
            </div>

            <!-- Column 2: Links group A (from Theme Customization) -->
            <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                <div class="text-sm font-semibold uppercase tracking-wide text-zinc-200">
                    Useful Information
                </div>

                <div class="mt-4 grid gap-6">
                    @forelse ($sectionsLeft as $footerLinkSection)
                        @php
                            usort($footerLinkSection, function ($a, $b) {
                                return ($a['sort_order'] ?? 0) - ($b['sort_order'] ?? 0);
                            });
                        @endphp

                        <ul class="grid gap-2 text-sm text-zinc-200">
                            @foreach ($footerLinkSection as $link)
                                @if (!empty($link['title']) && !empty($link['url']))
                                    <li>
                                        <a class="hover:text-white hover:underline underline-offset-4" href="{{ $link['url'] }}">
                                            {{ $link['title'] }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @empty
                        <ul class="mt-4 grid gap-2 text-sm text-zinc-200">
                            <li><a class="hover:text-white hover:underline underline-offset-4" href="{{ url('/') }}">Home</a></li>
                            <li><a class="hover:text-white hover:underline underline-offset-4" href="{{ route('shop.customer.session.index') }}">My account</a></li>
                        </ul>
                    @endforelse
                </div>
            </div>

            <!-- Column 3: Links group B (from Theme Customization) -->
            <div class="col-span-12 sm:col-span-6 lg:col-span-3">
                <div class="text-sm font-semibold uppercase tracking-wide text-zinc-200">
                    Customer Service
                </div>

                <div class="mt-4 grid gap-6">
                    @forelse ($sectionsRight as $footerLinkSection)
                        @php
                            usort($footerLinkSection, function ($a, $b) {
                                return ($a['sort_order'] ?? 0) - ($b['sort_order'] ?? 0);
                            });
                        @endphp

                        <ul class="grid gap-2 text-sm text-zinc-200">
                            @foreach ($footerLinkSection as $link)
                                @if (!empty($link['title']) && !empty($link['url']))
                                    <li>
                                        <a class="hover:text-white hover:underline underline-offset-4" href="{{ $link['url'] }}">
                                            {{ $link['title'] }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @empty
                        <ul class="mt-4 grid gap-2 text-sm text-zinc-200">
                            <li><a class="hover:text-white hover:underline underline-offset-4" href="{{ route('shop.customer.session.index') }}">Returns</a></li>
                            <li><a class="hover:text-white hover:underline underline-offset-4" href="{{ route('shop.customer.session.index') }}">Shipping</a></li>
                        </ul>
                    @endforelse
                </div>
            </div>

            <!-- Column 4: Contacts + newsletter (simple, one field) -->
            <div class="col-span-12 lg:col-span-3">
                <div class="text-sm font-semibold uppercase tracking-wide text-zinc-200">
                    Contacts
                </div>

                <div class="mt-4 grid gap-3 text-sm text-zinc-200">

                <div class="flex items-center gap-2">
                <i data-lucide="phone" class="w-4 h-4"></i>
                <a class="hover:underline underline-offset-4" href="tel:{{ $ds_phone_tel }}">
                {{ $ds_phone_display }}
                </a>
                </div>

                <div class="flex items-center gap-2">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <a class="hover:underline underline-offset-4" target="_blank" href="https://wa.me/{{ $ds_whatsapp }}">
                WhatsApp
                </a>
                </div>

                <div class="flex items-center gap-2">
                <i data-lucide="mail" class="w-4 h-4"></i>
                <a class="hover:underline underline-offset-4" href="mailto:{{ $ds_email }}">
                {{ $ds_email }}
                </a>
                </div>

                <div class="flex items-center gap-2 text-zinc-300">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
                {{ $ds_location }}
                </div>

                </div>

                {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

                @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                    <form action="{{ route('shop.subscription.store') }}" method="POST" class="mt-5">
                        @csrf

                        <label class="block text-xs text-zinc-300 mb-2">
                            Newsletter (optional)
                        </label>

                        <div class="flex gap-2">
                            <input
                                type="email"
                                name="email"
                                placeholder="email@example.com"
                                class="w-full rounded-xl bg-white/10 px-4 py-2.5 text-sm text-white placeholder:text-zinc-300 border border-white/15 focus:border-white/30"
                                required
                            />

                            <button
                                type="submit"
                                class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-navyBlue hover:bg-zinc-100"
                            >
                                Subscribe
                            </button>
                        </div>

                        @if ($errors->has('email'))
                            <p class="mt-2 text-xs text-red-300">{{ $errors->first('email') }}</p>
                        @else
                            <p class="mt-2 text-xs text-zinc-300">No spam. Unsubscribe anytime.</p>
                        @endif
                    </form>
                @endif

                {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
            </div>
        </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-white/10">
        <div class="mx-auto max-w-screen-2xl px-6 py-4 max-md:px-4">
            <div class="flex items-center justify-between gap-4 max-md:flex-col max-md:items-start">
                <div class="text-xs text-zinc-300">
                    {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}
                    © {{ date('Y') }} DriveSpot Auto. All rights reserved.
                    {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
                </div>

                <div class="text-xs text-zinc-300">
                    Payment methods: M-PESA • Card • Bank Transfer
                </div>
            </div>
        </div>
    </div>
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
<style>
.drivespot-footer{
    background:#1f3441;
    color:#829bab;
}

/* spacing from borders */
.drivespot-footer .max-w-screen-2xl{
    padding-left:40px;
    padding-right:40px;
}

/* headings */
.drivespot-footer h1,
.drivespot-footer h2,
.drivespot-footer h3,
.drivespot-footer h4,
.drivespot-footer span.font-semibold{
    color:#ffffff;
}

/* links */
.drivespot-footer a{
    color:#829bab;
    transition:all .2s ease;
}

/* hover color */
.drivespot-footer a:hover{
    color:#f85a00;
}

/* lucide icons */
.drivespot-footer svg{
    stroke:#829bab;
}

/* icon hover */
.drivespot-footer a:hover svg{
    stroke:#f85a00;
}

/* newsletter input */
.drivespot-footer input{
    background:#162833;
    color:#829bab;
    border:1px solid #2d4756;
}

/* subscribe button */
.drivespot-footer button{
    background:#f85a00;
    color:white;
}

.drivespot-footer button:hover{
    background:#ff6c1c;
}

/* bottom border */
.drivespot-footer .border-t{
    border-color:#2d4756;
}
</style>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}