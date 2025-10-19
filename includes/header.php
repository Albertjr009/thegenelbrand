<!-- Header -->
    <header class="bg-white/60 backdrop-blur sticky top-0 z-30">
      <?php
      // Determine current page basename for active link highlighting
      $current = basename($_SERVER['PHP_SELF']);
    // helper to output link classes
    function nav_class($href, $current) {
      // Use a muted indigo for active state (not too bright) and keep hover subtle for others
      if ($href === $current) {
        return 'text-indigo-600 font-semibold';
      }
      return 'text-gray-600 hover:text-gray-800';
    }
      ?>
  <div class="max-w-6xl mx-auto flex items-center justify-between p-4 md:p-6">
        <a href="index.php" class="flex items-center gap-3">
        <img src="assets/images/genelLogo.jpg" alt="Ama's Designs logo" class="w-10 h-10 rounded object-cover" />
          <span class="font-semibold text-lg">thegenelbrand</span>
        </a>

        <?php
        // get username from session if available, fallback to 'Admin'
        $username = 'Admin';
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!empty($_SESSION['user_name'])) {
          $username = htmlspecialchars($_SESSION['user_name']);
        }
        ?>

        <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
          <a href="index.php" class="<?php echo nav_class('index.php', $current); ?>" <?php if ($current === 'index.php') echo 'aria-current="page"'; ?>>Home</a>
          <a href="portfolio.php" class="<?php echo nav_class('portfolio.php', $current); ?>" <?php if ($current === 'portfolio.php') echo 'aria-current="page"'; ?>>Portfolio</a>
          <a href="about.php" class="<?php echo nav_class('about.php', $current); ?>" <?php if ($current === 'about.php') echo 'aria-current="page"'; ?>>About</a>
          <a href="contact.php" class="<?php echo nav_class('contact.php', $current); ?>" <?php if ($current === 'contact.php') echo 'aria-current="page"'; ?>>Contact</a>

          <!-- profile UI moved to admin dashboard -->
        </nav>

        <button id="menuBtn" class="md:hidden p-2 rounded focus:outline-none" aria-label="Toggle menu">
          <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>

      <!-- Mobile menu -->
      <div id="mobileMenu" class="md:hidden hidden border-t">
        <div class="px-4 pt-4 pb-6 flex flex-col gap-3">
          <a href="index.php" class="py-2 <?php echo nav_class('index.php', $current); ?>" <?php if ($current === 'index.php') echo 'aria-current="page"'; ?>>Home</a>
          <a href="portfolio.php" class="py-2 <?php echo nav_class('portfolio.php', $current); ?>" <?php if ($current === 'portfolio.php') echo 'aria-current="page"'; ?>>Portfolio</a>
          <a href="about.php" class="py-2 <?php echo nav_class('about.php', $current); ?>" <?php if ($current === 'about.php') echo 'aria-current="page"'; ?>>About</a>
          <a href="contact.php" class="py-2 <?php echo nav_class('contact.php', $current); ?>" <?php if ($current === 'contact.php') echo 'aria-current="page"'; ?>>Contact</a>
          <!-- profile links moved to admin dashboard -->
        </div>
      </div>
    </header>