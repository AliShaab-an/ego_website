<?php 
    require_once __DIR__ . '/../../config/path.php';
?>

<div class="w-full">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Website Settings</h1>
        <button id="save-settings-btn" class="bg-[rgba(183,146,103,1)] hover:bg-[rgba(160,120,80,1)] text-white font-semibold py-2 px-6 rounded-lg shadow transition">
            Save Settings
        </button>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-t-lg shadow-md border-b border-gray-200">
        <div class="flex gap-2 px-6 pt-4 flex-wrap">
            <button class="settings-tab active px-4 py-3 border-b-2 border-[rgba(183,146,103,1)] text-gray-800 font-semibold hover:text-gray-900" data-tab="general">
                <i class="fa-solid fa-sliders mr-2"></i>General
            </button>
            <button class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800" data-tab="branding">
                <i class="fa-solid fa-palette mr-2"></i>Branding
            </button>
            <button class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800" data-tab="pages">
                <i class="fa-solid fa-file mr-2"></i>Pages
            </button>
            <button class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800" data-tab="seo">
                <i class="fa-solid fa-magnifying-glass mr-2"></i>SEO
            </button>
            <button class="settings-tab px-4 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800" data-tab="social">
                <i class="fa-solid fa-share-nodes mr-2"></i>Social Media
            </button>
        </div>
    </div>

    <!-- Tab Contents -->
    <form id="settings-form">
        <!-- General Settings Tab -->
        <div class="settings-content active" data-tab="general">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-4 mb-6">
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
                    <label class="block text-gray-700 font-semibold mb-2">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" placeholder="+1 (555) 000-0000" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Company Description</label>
                    <textarea id="company_description" name="company_description" rows="4" placeholder="Enter your company description..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                </div>
            </div>
        </div>

        <!-- Branding Settings Tab -->
        <div class="settings-content" data-tab="branding">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Logo</label>
                    <div class="flex gap-4">
                        <div id="logo-preview" class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                            <span class="text-gray-400 text-sm">No image</span>
                        </div>
                        <input type="file" id="logo_file" name="logo_file" accept="image/*" 
                            class="flex-1 border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Primary Color</label>
                    <input type="color" id="primary_color" name="primary_color" value="#b7926f"
                        class="w-16 h-12 border border-gray-300 rounded-lg cursor-pointer">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Secondary Color</label>
                    <input type="color" id="secondary_color" name="secondary_color" value="#9e7e59"
                        class="w-16 h-12 border border-gray-300 rounded-lg cursor-pointer">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Accent Color</label>
                    <input type="color" id="accent_color" name="accent_color" value="#88663d"
                        class="w-16 h-12 border border-gray-300 rounded-lg cursor-pointer">
                </div>
            </div>
        </div>

        <!-- Pages Settings Tab -->
        <div class="settings-content" data-tab="pages">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Homepage Background Image</label>
                    <input type="file" id="homepage_bg" name="homepage_bg" accept="image/*" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Shop Page Background Image</label>
                    <input type="file" id="shop_bg" name="shop_bg" accept="image/*" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)]">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">About Page Content</label>
                    <textarea id="about_content" name="about_content" rows="4" placeholder="Enter your about page content..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                </div>
            </div>
        </div>

        <!-- SEO Settings Tab -->
        <div class="settings-content" data-tab="seo">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" placeholder="Ego Clothing - Premium Fashion" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="3" placeholder="Enter meta description for search engines..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Meta Keywords</label>
                    <input type="text" id="meta_keywords" name="meta_keywords" placeholder="clothing, fashion, style, ego" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Google Analytics ID</label>
                    <input type="text" id="google_analytics_id" name="google_analytics_id" placeholder="GA-XXXXXXXXXXXX" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                </div>
            </div>
        </div>

        <!-- Social Media Settings Tab -->
        <div class="settings-content" data-tab="social">
            <div class="bg-white rounded-b-lg shadow-md p-6 space-y-4">
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
                        <i class="fa-brands fa-twitter text-blue-400 mr-2"></i>Twitter URL
                    </label>
                    <input type="url" id="twitter_url" name="twitter_url" placeholder="https://twitter.com/your-handle" 
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
    </form>
</div>
