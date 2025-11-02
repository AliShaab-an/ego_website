// Configuration file for API and asset URLs
// Update this file when deploying to production

const Config = {
  // Auto-detect environment based on hostname
  // Local: '/Ego_website/public'
  // Production: '' (empty string if deployed to root)
  BASE_URL: "/Ego_website/public",

  // API endpoints
  getApiUrl(endpoint) {
    return `${this.BASE_URL}/api/${endpoint}`;
  },

  // Admin API endpoints
  getAdminApiUrl(endpoint) {
    return `${this.BASE_URL}/admin/api/${endpoint}`;
  },

  // Asset URLs (images, css, js)
  getAssetUrl(path) {
    return `${this.BASE_URL}/${path}`;
  },
};

// Export for use in modules
export default Config;
