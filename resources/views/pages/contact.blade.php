@extends('layouts.app')

@section('title', 'Contact Us | BUGGXIT Couture | Geometric Luxury Fashion')

@section('content')
    {{-- Hero Section --}}
    <section class="relative mb-20 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-yellow-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-yellow-500/5 rounded-full blur-3xl"></div>

        <div class="relative z-10 bg-gradient-to-br from-black via-gray-900 to-black border-y border-gray-800/50 py-16 md:py-24">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto">
                <div class="max-w-4xl mx-auto text-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-500 border border-yellow-500/30 mb-6 backdrop-blur-sm">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                        Get in Touch
                    </span>

                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                        Connect with <span class="text-yellow-500 block mt-2">Precision</span>
                    </h1>

                    <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto">
                        Our geometric approach extends to customer service. Reach out for inquiries,
                        consultations, or collaborations. We respond with the same precision we design with.
                    </p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-black to-transparent pointer-events-none"></div>
    </section>

    {{-- Contact Grid: Form + Info --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="grid md:grid-cols-2 gap-12">
            {{-- Contact Form --}}
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Send a <span class="text-yellow-500">Message</span>
                </h2>

                <form id="contactForm" class="bg-black/90 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 md:p-8">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-gray-400 text-sm uppercase tracking-wider mb-2">Name *</label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 bg-black/50 border border-gray-700 rounded-lg text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all"
                               placeholder="Your full name">
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-400 text-sm uppercase tracking-wider mb-2">Email *</label>
                        <input type="email" name="email" required
                               class="w-full px-4 py-3 bg-black/50 border border-gray-700 rounded-lg text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all"
                               placeholder="your.email@example.com">
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-400 text-sm uppercase tracking-wider mb-2">Subject *</label>
                        <select name="subject" required
                                class="w-full px-4 py-3 bg-black/50 border border-gray-700 rounded-lg text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="order">Order Support</option>
                            <option value="returns">Returns & Exchanges</option>
                            <option value="custom">Custom Design Inquiry</option>
                            <option value="wholesale">Wholesale & Partnerships</option>
                            <option value="press">Press & Media</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-400 text-sm uppercase tracking-wider mb-2">Message *</label>
                        <textarea name="message" required rows="6"
                                  class="w-full px-4 py-3 bg-black/50 border border-gray-700 rounded-lg text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/30 transition-all resize-vertical"
                                  placeholder="Your message..."></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-start gap-3 text-gray-400 text-sm">
                            <input type="checkbox" name="newsletter" class="mt-1 accent-yellow-500">
                            <span>Subscribe to our newsletter for updates on new collections, exclusive offers, and geometric design insights.</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-bold py-3 rounded-full hover:from-yellow-400 hover:to-yellow-500 transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>

            {{-- Contact Information --}}
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Contact <span class="text-yellow-500">Information</span>
                </h2>

                <div class="bg-black/90 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 md:p-8 mb-8">
                    <div class="space-y-6">
                        {{-- Address --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-yellow-500/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-yellow-500 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg">Physical Address</h3>
                                <p class="text-gray-400 leading-relaxed">
                                    [approx. location],[building number]<br>
                                    [street name], South Africa
                                </p>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-yellow-500/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-yellow-500 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg">Phone & WhatsApp</h3>
                                <p class="text-gray-400 leading-relaxed">
                                    +27 31 234 5678<br>
                                    <span class="texon-Fri: 9AM-6PM CET</span>
                                </p>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-yellow-500/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-yellow-500 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg">Email</h3>
                                <p class="text-gray-400 leading-relaxed">
                                    info@buggxit.com<br>
                                    clientservices@buggxit.com
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Response Times --}}
                    <div class="mt-8 pt-6 border-t border-gray-800">
                        <h3 class="text-yellow-500 font-semibold text-xl mb-4">Response Time</h3>
                        <div class="bg-black/50 border border-gray-800 rounded-xl p-5 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">General Inquiries</span>
                                <span class="text-yellow-500 font-semibold">24-48 hours</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Order Support</span>
                                <span class="text-yellow-500 font-semibold">12-24 hours</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Custom Design</span>
                                <span class="text-yellow-500 font-semibold">3-5 business days</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Social Media --}}
                <div>
                    <h3 class="text-white font-semibold text-xl mb-4">Connect Socially</h3>
                    <div class="flex gap-4">
                        @foreach(['instagram', 'facebook-f', 'twitter', 'linkedin-in', 'pinterest'] as $icon)
                            <a href="#" class="w-12 h-12 bg-black/50 border border-gray-700 rounded-full flex items-center justify-center hover:border-yellow-500 hover:bg-yellow-500/10 transition-all duration-300 group">
                                <i class="fab fa-{{ $icon }} text-gray-400 group-hover:text-yellow-500 text-lg"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Section (Accordion) --}}
    <section class="bg-black/90 backdrop-blur-sm border-y border-gray-800 py-20 mb-20">
        <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">
                Frequently Asked <span class="text-yellow-500">Questions</span>
            </h2>

            <div class="max-w-3xl mx-auto space-y-4">
                @php
                    $faqs = [
                        ['q' => 'What is your return and exchange policy?',
                         'a' => 'We accept returns within 30 days of delivery for full-price items in their original condition with tags attached. Sale items are final sale. Exchanges are available for size or color within 14 days. All returns are processed within 5-7 business days after we receive your item.'],
                        ['q' => 'Do you offer custom sizing or alterations?',
                         'a' => 'Yes, we offer complimentary basic alterations on all full-price items. For custom sizing beyond our standard range, we provide made-to-measure services starting at $500. Please contact our custom design team for a consultation.'],
                        ['q' => 'How can I track my order?',
                         'a' => 'Once your order ships, you\'ll receive a tracking number via email. You can also track your order by logging into your account on our website and visiting the "Order History" section. For international orders, tracking updates may take 24-48 hours to appear.'],
                        ['q' => 'What shipping methods do you offer?',
                         'a' => 'We offer express worldwide shipping via DHL and FedEx. Standard shipping (5-7 business days) is free on orders over $500. Express shipping (2-3 business days) is available for $35. For Milan residents, we offer same-day delivery within the city center.'],
                        ['q' => 'Do you have physical stores?',
                         'a' => 'Our flagship atelier and showroom is located in Milan at Via Monte Napoleone, 23. We also have seasonal pop-up stores in major fashion capitals including Paris, New York, Tokyo, and Dubai. Check our Instagram for current pop-up locations and dates.']
                    ];
                @endphp

                @foreach($faqs as $index => $faq)
                    <div class="faq-item border-b border-gray-800">
                        <button class="faq-question w-full text-left py-5 text-white font-semibold text-lg flex justify-between items-center hover:text-yellow-500 transition-colors">
                            <span>{{ $faq['q'] }}</span>
                            <i class="fas fa-chevron-down text-yellow-500 transition-transform duration-300"></i>
                        </button>
                        <div class="faq-answer hidden pb-5 text-gray-400 leading-relaxed">
                            <p>{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Map Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="relative h-96 md:h-[500px] rounded-2xl overflow-hidden border border-gray-800 bg-black/50">
            <div class="absolute inset-0 flex flex-col items-center justify-center z-10 bg-black/60 backdrop-blur-sm">
                <i class="fas fa-map-marked-alt text-6xl text-yellow-500 mb-4"></i>
                <h3 class="text-2xl font-bold text-white text-center">Via Monte Napoleone, 23<br>20121 Milano MI, Italy</h3>
                <p class="text-gray-300 text-center max-w-md mt-3">Our Milan atelier is open by appointment only. Please contact us to schedule a private viewing of our collections.</p>
                <button id="getDirections" class="mt-6 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold px-6 py-3 rounded-full transition-all">
                    <i class="fas fa-directions mr-2"></i> Get Directions
                </button>
            </div>
            <!-- Decorative pattern overlay -->
            <div class="absolute inset-0 opacity-10 pointer-events-none"
                 style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\' fill=\'%23d4af37\' fill-opacity=\'0.3\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');">
            </div>
        </div>
    </section>

    {{-- Departments Section --}}
    <section class="bg-black/90 backdrop-blur-sm border-y border-gray-800 py-20 mb-20">
        <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">
                Contact by <span class="text-yellow-500">Department</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $depts = [
                        ['icon' => 'shopping-bag', 'title' => 'Customer Service', 'desc' => 'Orders, returns, exchanges, and general inquiries', 'email' => 'customerservice@buggxit.com'],
                        ['icon' => 'pencil-ruler', 'title' => 'Custom Design', 'desc' => 'Made-to-measure, bespoke commissions, and alterations', 'email' => 'custom@buggxit.com'],
                        ['icon' => 'users', 'title' => 'Wholesale & Partnerships', 'desc' => 'Retail partnerships, collaborations, and bulk orders', 'email' => 'wholesale@buggxit.com'],
                        ['icon' => 'microphone', 'title' => 'Press & Media', 'desc' => 'Editorial requests, press kits, and media inquiries', 'email' => 'press@buggxit.com']
                    ];
                @endphp
                @foreach($depts as $dept)
                    <div class="text-center p-6 bg-black/50 border border-gray-800 rounded-2xl hover:border-yellow-500/50 transition-all duration-300 hover:-translate-y-1">
                        <div class="w-16 h-16 mx-auto bg-yellow-500/10 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-{{ $dept['icon'] }} text-2xl text-yellow-500"></i>
                        </div>
                        <h3 class="text-white font-semibold text-xl mb-2">{{ $dept['title'] }}</h3>
                        <p class="text-gray-400 text-sm mb-3">{{ $dept['desc'] }}</p>
                        <a href="mailto:{{ $dept['email'] }}" class="text-yellow-500 hover:text-yellow-400 font-semibold text-sm transition-colors">
                            {{ $dept['email'] }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="relative bg-gradient-to-br from-gray-900 to-black border border-gray-800 rounded-3xl p-8 md:p-12 overflow-hidden text-center">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-yellow-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-yellow-500/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                    Still Have <span class="text-yellow-500">Questions</span>?
                </h2>
                <p class="text-gray-300 text-lg mb-10">
                    Our geometric approach ensures precise and thoughtful responses.
                    We're committed to providing exceptional service that matches the quality of our designs.
                </p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center">
                    <a href="tel:+390212345678"
                       class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-bold rounded-full hover:from-yellow-400 hover:to-yellow-500 transition-all duration-300 shadow-2xl shadow-yellow-500/25">
                        <i class="fas fa-phone mr-2"></i> Call Us
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* FAQ accordion styles */
        .faq-item.active .faq-question {
            color: #D4AF37;
        }
        .faq-item.active .faq-answer {
            display: block;
        }
        .faq-item.active .fa-chevron-down {
            transform: rotate(180deg);
        }
        .faq-answer {
            display: none;
        }
        /* Form success alert */
        .alert-success {
            background: rgba(76, 175, 80, 0.1);
            border: 1px solid #4CAF50;
            color: #4CAF50;
            padding: 1rem;
            margin-top: 1.5rem;
            border-radius: 0.75rem;
            text-align: center;
        }
        /* Smooth transitions */
        .faq-question, .fa-chevron-down {
            transition: all 0.3s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const faqItem = button.closest('.faq-item');
                faqItem.classList.toggle('active');
            });
        });

        // Contact form submission
        document.getElementById('contactForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;

            fetch('{{ route("contact.submit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: form.name.value,
                    email: form.email.value,
                    subject: form.subject.value,
                    message: form.message.value,
                    newsletter: form.newsletter.checked ? 1 : 0
                })
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Message Sent';
                submitBtn.style.background = '#4CAF50';
                const successDiv = document.createElement('div');
                successDiv.className = 'alert-success';
                successDiv.innerHTML = `<strong>Thank you!</strong><br>${data.message}`;
                form.appendChild(successDiv);
                form.reset();
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.style.background = '';
                    successDiv.remove();
                }, 5000);
            })
            .catch(err => {
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error';
                alert('Something went wrong. Please try again.');
                submitBtn.disabled = false;
                setTimeout(() => { submitBtn.innerHTML = originalText; }, 3000);
            });
        });

        // Get directions
        document.getElementById('getDirections')?.addEventListener('click', () => {
            window.open('https://www.google.com/maps/search/?api=1&query=Via+Monte+Napoleone+23+Milano+Italy', '_blank');
        });
    </script>
@endpush