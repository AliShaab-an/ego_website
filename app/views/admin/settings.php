<?php 
    require_once __DIR__ . '/../../config/path.php';
?>

<style>
    .settings-content {
        display: none;
    }
    .settings-content.active {
        display: block;
    }
</style>

<div class="w-full">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Website Settings</h1>
        <button id="save-settings-btn" class="bg-[rgba(183,146,103,1)] hover:bg-[rgba(160,120,80,1)] text-white font-semibold py-2 px-6 rounded-lg shadow transition">
            <i class="fa-solid fa-floppy-disk mr-2"></i>Save Changes
        </button>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-t-lg shadow-md border-b border-gray-200 overflow-x-auto">
        <div class="flex gap-2 px-6 pt-4 flex-wrap">
            <button type="button" class="settings-tab active px-4 py-3 border-b-2 border-[rgba(183,146,103,1)] text-gray-800 font-semibold hover:text-gray-900 whitespace-nowrap transition-colors" data-tab="general">
                <i class="fa-solid fa-sliders mr-2"></i>General
            </button>
            <button type="button" class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800 whitespace-nowrap transition-colors" data-tab="branding">
                <i class="fa-solid fa-palette mr-2"></i>Branding
            </button>
            <button type="button" class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800 whitespace-nowrap transition-colors" data-tab="contact">
                <i class="fa-solid fa-map-pin mr-2"></i>Contact & Location
            </button>
            <button type="button" class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800 whitespace-nowrap transition-colors" data-tab="social">
                <i class="fa-solid fa-share-nodes mr-2"></i>Social Links
            </button>
            <button type="button" class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800 whitespace-nowrap transition-colors" data-tab="seo">
                <i class="fa-solid fa-magnifying-glass mr-2"></i>SEO
            </button>
            <button type="button" class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800 whitespace-nowrap transition-colors" data-tab="payments">
                <i class="fa-solid fa-credit-card mr-2"></i>Payments
            </button>
            <button type="button" class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800 whitespace-nowrap transition-colors" data-tab="policies">
                <i class="fa-solid fa-file-contract mr-2"></i>Policies
            </button>
            <button type="button" class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800 whitespace-nowrap transition-colors" data-tab="email">
                <i class="fa-solid fa-envelope mr-2"></i>Email/SMTP
            </button>
            <button type="button" class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800 whitespace-nowrap transition-colors" data-tab="analytics">
                <i class="fa-solid fa-chart-line mr-2"></i>Analytics
            </button>
            <button type="button" class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800 whitespace-nowrap transition-colors" data-tab="security">
                <i class="fa-solid fa-shield mr-2"></i>Security
            </button>
        </div>
    </div>

    <!-- Tab Contents -->
    <form id="settings-form">
        <!-- 1) General Settings Tab -->
        <div class="settings-content active" data-tab="general">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Website Name</label>
                        <input type="text" id="website_name" name="website_name" placeholder="Ego Clothing" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Website URL</label>
                        <input type="url" id="website_url" name="website_url" placeholder="https://ego-clothing.com" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email" placeholder="contact@ego-clothing.com" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Support Email <span class="text-gray-400 text-sm">(Optional)</span></label>
                        <input type="email" id="support_email" name="support_email" placeholder="support@ego-clothing.com" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Primary Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" placeholder="+961XXXXXXXX" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Working Hours <span class="text-gray-400 text-sm">(Optional)</span></label>
                    <textarea id="working_hours" name="working_hours" rows="3" placeholder="Monday - Friday: 9:00 AM - 6:00 PM&#10;Saturday: 10:00 AM - 4:00 PM&#10;Sunday: Closed" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                </div>
            </div>
        </div>

        <!-- 2) Branding Settings Tab -->
        <div class="settings-content" data-tab="branding">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-8 mb-6">
                <!-- Logos Section -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Logos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Logo</label>
                            <div class="flex flex-col gap-3">
                                <div id="logo-preview" class="w-full h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                    <span class="text-gray-400 text-sm">No image</span>
                                </div>
                                <input type="file" id="logo_file" name="logo_file" accept="image/*" 
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Logo (Light) <span class="text-gray-400 text-sm">(Optional)</span></label>
                            <div class="flex flex-col gap-3">
                                <div id="logo_light-preview" class="w-full h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                    <span class="text-gray-400 text-sm">No image</span>
                                </div>
                                <input type="file" id="logo_light_file" name="logo_light_file" accept="image/*" 
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Logo (Dark) <span class="text-gray-400 text-sm">(Optional)</span></label>
                            <div class="flex flex-col gap-3">
                                <div id="logo_dark-preview" class="w-full h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-900">
                                    <span class="text-gray-400 text-sm">No image</span>
                                </div>
                                <input type="file" id="logo_dark_file" name="logo_dark_file" accept="image/*" 
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favicon -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Favicon</h3>
                    <div class="max-w-xs">
                        <label class="block text-gray-700 font-semibold mb-2">Favicon <span class="text-gray-400 text-sm">(Optional)</span></label>
                        <div class="flex flex-col gap-3">
                            <div id="favicon-preview" class="w-16 h-16 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                <span class="text-gray-400 text-xs">No image</span>
                            </div>
                            <input type="file" id="favicon_file" name="favicon_file" accept="image/*" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                        </div>
                    </div>
                </div>

                <!-- Colors Section -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Brand Colors</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Primary Color</label>
                            <div class="flex gap-3">
                                <input type="color" id="primary_color" name="primary_color" value="#b7926f"
                                    class="w-20 h-12 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" id="primary_color_hex" name="primary_color_hex" placeholder="#b7926f" value="b7926f" maxlength="7"
                                    class="flex-1 border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Secondary Color</label>
                            <div class="flex gap-3">
                                <input type="color" id="secondary_color" name="secondary_color" value="#9e7e59"
                                    class="w-20 h-12 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" id="secondary_color_hex" name="secondary_color_hex" placeholder="#9e7e59" value="9e7e59" maxlength="7"
                                    class="flex-1 border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Accent Color</label>
                            <div class="flex gap-3">
                                <input type="color" id="accent_color" name="accent_color" value="#88663d"
                                    class="w-20 h-12 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" id="accent_color_hex" name="accent_color_hex" placeholder="#88663d" value="88663d" maxlength="7"
                                    class="flex-1 border border-gray-300 rounded-lg p-2 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fonts Section -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Fonts</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Primary Font <span class="text-gray-400 text-sm">(Optional)</span></label>
                            <input type="text" id="primary_font" name="primary_font" placeholder="e.g., Poppins, Roboto" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Secondary Font <span class="text-gray-400 text-sm">(Optional)</span></label>
                            <input type="text" id="secondary_font" name="secondary_font" placeholder="e.g., Open Sans, Lato" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                        </div>
                    </div>
                </div>

                <!-- Page Banners Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Page Background Images</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Home Page Background</label>
                            <div class="flex flex-col gap-3">
                                <div id="homepage_bg-preview" class="w-full h-40 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                    <span class="text-gray-400 text-sm">No image</span>
                                </div>
                                <input type="file" id="homepage_bg" name="homepage_bg" accept="image/*" 
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Shop Page Background</label>
                            <div class="flex flex-col gap-3">
                                <div id="shop_bg-preview" class="w-full h-40 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                    <span class="text-gray-400 text-sm">No image</span>
                                </div>
                                <input type="file" id="shop_bg" name="shop_bg" accept="image/*" 
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Contact Page Background</label>
                            <div class="flex flex-col gap-3">
                                <div id="contact_bg-preview" class="w-full h-40 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                    <span class="text-gray-400 text-sm">No image</span>
                                </div>
                                <input type="file" id="contact_bg" name="contact_bg" accept="image/*" 
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Login Page Image</label>
                            <div class="flex flex-col gap-3">
                                <div id="login_bg-preview" class="w-full h-40 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                    <span class="text-gray-400 text-sm">No image</span>
                                </div>
                                <input type="file" id="login_bg" name="login_bg" accept="image/*" 
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Sign Up Page Image</label>
                            <div class="flex flex-col gap-3">
                                <div id="signup_bg-preview" class="w-full h-40 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                    <span class="text-gray-400 text-sm">No image</span>
                                </div>
                                <input type="file" id="signup_bg" name="signup_bg" accept="image/*" 
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3) Contact & Location Tab -->
        <div class="settings-content" data-tab="contact">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Address</label>
                    <textarea id="address" name="address" rows="3" placeholder="123 Fashion Street, Beirut, Lebanon" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Google Maps Link <span class="text-gray-400 text-sm">(Optional)</span></label>
                    <input type="url" id="google_maps_link" name="google_maps_link" placeholder="https://maps.google.com/..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    <p class="text-xs text-gray-500 mt-1">Embed link from Google Maps</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">WhatsApp Number <span class="text-gray-400 text-sm">(Optional)</span></label>
                    <input type="tel" id="whatsapp_number" name="whatsapp_number" placeholder="+961XXXXXXXX" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                </div>
            </div>
        </div>

        <!-- 4) Social Links Tab -->
        <div class="settings-content" data-tab="social">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-brands fa-instagram text-pink-600 mr-2"></i>Instagram URL
                        </label>
                        <input type="url" id="instagram_url" name="instagram_url" placeholder="https://instagram.com/your-handle" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-brands fa-facebook text-blue-600 mr-2"></i>Facebook URL
                        </label>
                        <input type="url" id="facebook_url" name="facebook_url" placeholder="https://facebook.com/your-page" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-brands fa-tiktok text-black mr-2"></i>TikTok URL
                        </label>
                        <input type="url" id="tiktok_url" name="tiktok_url" placeholder="https://tiktok.com/@your-handle" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-brands fa-twitter text-blue-400 mr-2"></i>Twitter/X URL
                        </label>
                        <input type="url" id="twitter_url" name="twitter_url" placeholder="https://twitter.com/your-handle" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-brands fa-linkedin text-blue-700 mr-2"></i>LinkedIn URL
                        </label>
                        <input type="url" id="linkedin_url" name="linkedin_url" placeholder="https://linkedin.com/company/your-company" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-brands fa-youtube text-red-600 mr-2"></i>YouTube URL
                        </label>
                        <input type="url" id="youtube_url" name="youtube_url" placeholder="https://youtube.com/your-channel" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    </div>
                </div>
            </div>
        </div>

        <!-- 5) SEO Settings Tab -->
        <div class="settings-content" data-tab="seo">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" placeholder="Ego Clothing - Premium Fashion" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    <p class="text-xs text-gray-500 mt-1">Shown in search results (optimal: 50-60 characters)</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="3" placeholder="Discover premium fashion at Ego. Shop trendy clothing for every occasion..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Shown in search results (optimal: 150-160 characters)</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Meta Keywords</label>
                    <textarea id="meta_keywords" name="meta_keywords" rows="2" placeholder="clothing, fashion, style, ego, premium fashion" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Comma-separated keywords</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">OG / Share Image <span class="text-gray-400 text-sm">(Optional)</span></label>
                    <div class="flex flex-col gap-3">
                        <div id="og_image-preview" class="w-full h-48 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                            <span class="text-gray-400 text-sm">No image</span>
                        </div>
                        <input type="file" id="og_image" name="og_image" accept="image/*" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                        <p class="text-xs text-gray-500">Used for link previews on WhatsApp, Facebook, etc. (Optimal: 1200x630px)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6) Payments Tab -->
        <div class="settings-content" data-tab="payments">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-8 mb-6">
                <!-- Currency & Payment Proof -->
                <div class="border-b pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Currency</label>
                            <select id="currency" name="currency" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                                <option value="USD">USD ($)</option>
                                <option value="LBP">LBP (ل.ل)</option>
                                <option value="EUR">EUR (€)</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="require_payment_proof" name="require_payment_proof" 
                                    class="w-5 h-5 border-gray-300 rounded-lg cursor-pointer">
                                <span class="text-gray-700 font-semibold">Require Payment Proof Upload</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Methods</h3>
                    
                    <!-- Cash on Delivery -->
                    <div class="border rounded-lg p-4 mb-4">
                        <div class="flex items-center gap-3 mb-4">
                            <input type="checkbox" id="enable_cod" name="enable_cod" class="w-5 h-5 rounded" checked>
                            <label for="enable_cod" class="text-gray-700 font-semibold cursor-pointer">Cash on Delivery (COD)</label>
                        </div>
                        <div id="cod-fields" class="pl-8 space-y-3">
                            <textarea id="cod_instructions" name="cod_instructions" rows="2" placeholder="Instructions for COD orders..." 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]"></textarea>
                        </div>
                    </div>

                    <!-- Wish Money -->
                    <div class="border rounded-lg p-4 mb-4">
                        <div class="flex items-center gap-3 mb-4">
                            <input type="checkbox" id="enable_wish_money" name="enable_wish_money" class="w-5 h-5 rounded">
                            <label for="enable_wish_money" class="text-gray-700 font-semibold cursor-pointer">Wish Money</label>
                        </div>
                        <div id="wish_money-fields" class="pl-8 space-y-3 hidden">
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Account Number</label>
                                <input type="text" id="wish_money_number" name="wish_money_number" placeholder="Wish Money Account Number" 
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Account Name</label>
                                <input type="text" id="wish_money_name" name="wish_money_name" placeholder="Account Holder Name" 
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Instructions</label>
                                <textarea id="wish_money_instructions" name="wish_money_instructions" rows="2" placeholder="Instructions for Wish Money payment..." 
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-[rgba(183,146,103,1)]"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Transfer -->
                    <div class="border rounded-lg p-4 mb-4">
                        <div class="flex items-center gap-3 mb-4">
                            <input type="checkbox" id="enable_bank_transfer" name="enable_bank_transfer" class="w-5 h-5 rounded">
                            <label for="enable_bank_transfer" class="text-gray-700 font-semibold cursor-pointer">Bank Transfer</label>
                        </div>
                        <div id="bank_transfer-fields" class="pl-8 space-y-3 hidden">
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Bank Name</label>
                                <input type="text" id="bank_name" name="bank_name" placeholder="e.g., Bank of Lebanon" 
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Account Number / IBAN</label>
                                <input type="text" id="bank_account" name="bank_account" placeholder="Account Number or IBAN" 
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Account Name</label>
                                <input type="text" id="bank_account_name" name="bank_account_name" placeholder="Account Holder Name" 
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Instructions</label>
                                <textarea id="bank_instructions" name="bank_instructions" rows="2" placeholder="Instructions for bank transfer..." 
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-[rgba(183,146,103,1)]"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- OMT / Western Union -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-4">
                            <input type="checkbox" id="enable_omt" name="enable_omt" class="w-5 h-5 rounded">
                            <label for="enable_omt" class="text-gray-700 font-semibold cursor-pointer">OMT / Western Union</label>
                        </div>
                        <div id="omt-fields" class="pl-8 space-y-3 hidden">
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Name / Account Number</label>
                                <input type="text" id="omt_name" name="omt_name" placeholder="Recipient Name or Number" 
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-[rgba(183,146,103,1)]">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Instructions</label>
                                <textarea id="omt_instructions" name="omt_instructions" rows="2" placeholder="Instructions for OMT/Western Union..." 
                                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-[rgba(183,146,103,1)]"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7) Policies Tab -->
        <div class="settings-content" data-tab="policies">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">About Us</label>
                    <textarea id="about_us" name="about_us" rows="4" placeholder="Tell your customers about your brand..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Return & Exchange Policy</label>
                    <textarea id="return_policy" name="return_policy" rows="4" placeholder="Define your return and exchange terms..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Shipping Policy</label>
                    <textarea id="shipping_policy" name="shipping_policy" rows="4" placeholder="Explain your shipping policy..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Privacy Policy</label>
                    <textarea id="privacy_policy" name="privacy_policy" rows="4" placeholder="Detail how you handle customer data..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Terms & Conditions</label>
                    <textarea id="terms_conditions" name="terms_conditions" rows="4" placeholder="Set your terms and conditions..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                </div>
            </div>
        </div>

        <!-- 8) Email / SMTP Tab -->
        <div class="settings-content" data-tab="email">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-6 mb-6">
                <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
                    <input type="checkbox" id="enable_smtp" name="enable_smtp" class="w-5 h-5 rounded">
                    <label for="enable_smtp" class="text-gray-700 font-semibold cursor-pointer">Enable SMTP</label>
                </div>

                <div id="smtp-fields" class="space-y-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">SMTP Host</label>
                            <input type="text" id="smtp_host" name="smtp_host" placeholder="smtp.gmail.com" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">SMTP Port</label>
                            <input type="number" id="smtp_port" name="smtp_port" placeholder="587" min="1" max="65535"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">SMTP Username</label>
                            <input type="text" id="smtp_username" name="smtp_username" placeholder="your-email@gmail.com" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">SMTP Password</label>
                            <input type="password" id="smtp_password" name="smtp_password" placeholder="Your app password" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">SMTP Encryption</label>
                            <select id="smtp_encryption" name="smtp_encryption" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                                <option value="none">None</option>
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">From Name</label>
                            <input type="text" id="smtp_from_name" name="smtp_from_name" placeholder="Ego Clothing" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">From Email</label>
                            <input type="email" id="smtp_from_email" name="smtp_from_email" placeholder="noreply@ego-clothing.com" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 9) Analytics & Tracking Tab -->
        <div class="settings-content" data-tab="analytics">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Google Analytics ID</label>
                    <input type="text" id="google_analytics_id" name="google_analytics_id" placeholder="G-XXXXXXXXXX" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    <p class="text-xs text-gray-500 mt-1">Format: G-XXXXXXXXXX (GA4) or UA-XXXXXXXX (Universal Analytics)</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Google Tag Manager ID</label>
                    <input type="text" id="gtm_id" name="gtm_id" placeholder="GTM-XXXXXXX" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    <p class="text-xs text-gray-500 mt-1">Google Tag Manager Container ID</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Meta Pixel ID</label>
                    <input type="text" id="meta_pixel_id" name="meta_pixel_id" placeholder="XXXXXXXXXXXX" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    <p class="text-xs text-gray-500 mt-1">Facebook / Meta Pixel ID for conversion tracking</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">TikTok Pixel ID</label>
                    <input type="text" id="tiktok_pixel_id" name="tiktok_pixel_id" placeholder="XXXXXXXXXXXX" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                    <p class="text-xs text-gray-500 mt-1">TikTok Pixel ID for conversion tracking</p>
                </div>
            </div>
        </div>

        <!-- 10) Maintenance & Security Tab -->
        <div class="settings-content" data-tab="security">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-8 mb-6">
                <!-- Maintenance Mode -->
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Maintenance Mode</h3>
                    <div class="flex items-start gap-4">
                        <div class="flex items-center gap-3 pt-1">
                            <input type="checkbox" id="enable_maintenance" name="enable_maintenance" class="w-5 h-5 rounded">
                            <label for="enable_maintenance" class="text-gray-700 font-semibold cursor-pointer">Enable Maintenance Mode</label>
                        </div>
                    </div>
                    <div id="maintenance-fields" class="mt-4 hidden">
                        <label class="block text-gray-700 font-semibold mb-2">Maintenance Message</label>
                        <textarea id="maintenance_message" name="maintenance_message" rows="4" placeholder="We're currently under maintenance. We'll be back online soon!" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                    </div>
                </div>

                <!-- reCAPTCHA -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">reCAPTCHA</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <input type="checkbox" id="enable_recaptcha" name="enable_recaptcha" class="w-5 h-5 rounded">
                        <label for="enable_recaptcha" class="text-gray-700 font-semibold cursor-pointer">Enable reCAPTCHA v3</label>
                    </div>
                    <div id="recaptcha-fields" class="space-y-4 hidden">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">reCAPTCHA Site Key</label>
                            <input type="text" id="recaptcha_site_key" name="recaptcha_site_key" placeholder="Your reCAPTCHA Site Key" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                            <p class="text-xs text-gray-500 mt-1">Get from Google reCAPTCHA Console</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">reCAPTCHA Secret Key</label>
                            <input type="password" id="recaptcha_secret_key" name="recaptcha_secret_key" placeholder="Your reCAPTCHA Secret Key" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                            <p class="text-xs text-gray-500 mt-1">Keep this secret - never share publicly</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


