import { showToast } from "./messages.js";

/**
 * Get CSRF token from meta tag
 */
function getCsrfToken() {
  return $('meta[name="csrf-token"]').attr('content');
}

/**
 * Perform AJAX request with automatic CSRF token inclusion
 */
export function ajaxRequest(options) {
  const originalError = options.error;

  // Add CSRF token to POST/PUT/DELETE requests
  if (!options.method || ['POST', 'PUT', 'DELETE'].includes(options.method.toUpperCase())) {
    const csrfToken = getCsrfToken();
    
    if (csrfToken) {
      // If data is FormData, append token
      if (options.data instanceof FormData) {
        options.data.append('csrf_token', csrfToken);
      } 
      // If data is string, check if it's JSON or URL-encoded
      else if (typeof options.data === 'string') {
        const isJson = options.contentType && options.contentType.includes('application/json');
        
        if (isJson) {
          // Parse JSON, add token, re-stringify
          try {
            const parsed = JSON.parse(options.data);
            parsed.csrf_token = csrfToken;
            options.data = JSON.stringify(parsed);
          } catch (e) {
            console.error('Failed to parse JSON data for CSRF token injection:', e);
          }
        } else {
          // Assume URL-encoded string
          options.data += (options.data ? '&' : '') + 'csrf_token=' + encodeURIComponent(csrfToken);
        }
      }
      // If data is object, add token property
      else if (typeof options.data === 'object' && options.data !== null) {
        options.data.csrf_token = csrfToken;
      }
      // If no data, create data object with token
      else {
        options.data = { csrf_token: csrfToken };
      }
    }
  }

  $.ajax({
    ...options,
    dataType: "json",
    error: (xhr, status, error) => {
      console.error("AJAX Error:", {
        status: xhr.status,
        statusText: xhr.statusText,
        responseText: xhr.responseText,
        error: error,
      });

      // Call the original error handler if provided
      if (originalError) {
        originalError(xhr, status, error);
      } else {
        const response = xhr.responseJSON;
        const message = response?.message || "Server error. Please try again.";
        showToast(message, "error");
      }
    },
  });
}
