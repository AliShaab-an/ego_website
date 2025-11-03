// Configuration file for API and asset URLs
// THIS IS THE PRODUCTION VERSION - Upload this to production server
// For production, BASE_URL should be empty string or "/"

const Config = {
  // Production: empty string (files are in document root)
  BASE_URL: "",

  // API endpoints
  getApiUrl(endpoint) {
    // Ensure no double slashes
    const url = this.BASE_URL
      ? `${this.BASE_URL}/api/${endpoint}`
      : `/api/${endpoint}`;
    return url.replace(/([^:]\/)\/+/g, "$1");
  },

  // Admin API endpoints
  getAdminApiUrl(endpoint) {
    const url = this.BASE_URL
      ? `${this.BASE_URL}/admin/api/${endpoint}`
      : `/admin/api/${endpoint}`;
    return url.replace(/([^:]\/)\/+/g, "$1");
  },

  // Asset URLs (images, css, js)
  getAssetUrl(path) {
    // Remove leading slash if BASE_URL is empty
    if (!this.BASE_URL && path.startsWith("/")) {
      return path;
    }
    return `${this.BASE_URL}/${path}`.replace("//", "/");
  },

  // Base URL for redirects
  getBaseUrl() {
    return this.BASE_URL || "/";
  },
};

// Export for use in modules
export default Config;
