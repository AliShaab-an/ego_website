<div class="min-h-screen bg-gray-50 py-12 px-4">
  <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-8 md:p-12">
    
    <!-- Header -->
    <div class="mb-8 pb-6 border-b border-gray-200">
      <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-2">
        <?= htmlspecialchars($termsTitle) ?>
      </h1>
      <p class="text-sm text-gray-500">
        Last updated: <?= date('F d, Y') ?>
      </p>
    </div>

    <!-- Content -->
    <div class="space-y-6">
      <?php if (!empty($termsContent)): ?>
        <div class="text-gray-700 leading-relaxed space-y-4 text-base md:text-lg">
          <?php
          // Safely render content - convert plain text newlines to <br> tags
          // and allow basic HTML formatting if present
          $content = $termsContent;
          
          // If content looks like plain text (no HTML tags), convert newlines to <br>
          if (strip_tags($content) === $content) {
              // Plain text - convert newlines to <br>
              echo nl2br(htmlspecialchars($content));
          } else {
              // Contains HTML - output with allowed tags only (security)
              echo strip_tags($content, '<p><br><strong><em><u><h2><h3><h4><ul><ol><li><a>');
          }
          ?>
        </div>
      <?php else: ?>
        <!-- Fallback message when no content is available -->
        <div class="text-center py-12">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
            <i class="fi fi-rr-document text-2xl text-gray-400"></i>
          </div>
          <h3 class="text-xl font-semibold text-gray-700 mb-2">Terms & Conditions Coming Soon</h3>
          <p class="text-gray-500 mb-6">
            We're working on our terms and conditions. Please check back later.
          </p>
          <a href="<?= page_url('contact') ?>" 
             class="inline-flex items-center gap-2 px-6 py-3 bg-brand text-white rounded-lg hover:bg-opacity-90 transition">
            <i class="fi fi-rr-envelope"></i>
            Contact Us for Questions
          </a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Back to Home Link -->
    <div class="mt-12 pt-8 border-t border-gray-200 text-center">
      <a href="<?= page_url('home') ?>" 
         class="inline-flex items-center gap-2 text-brand hover:text-opacity-80 transition">
        <i class="fi fi-rr-arrow-small-left"></i>
        Back to Home
      </a>
    </div>

  </div>
</div>
