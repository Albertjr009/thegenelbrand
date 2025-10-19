<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>About — thegenelbrand</title>
    <link rel="icon" href="assets/images/genelLogo.jpg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="antialiased text-gray-800 bg-white">
    <?php
  include_once __DIR__ . '/includes/header.php';
  ?>

    <main class="max-w-4xl mx-auto px-6 py-12">
      <h1 class="text-3xl font-bold mb-6">About</h1>
      <section class="bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 py-12">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <!-- Left: Image -->
            <div>
              <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=1200&q=80" alt="Genevieve at work" class="w-full h-64 md:h-80 object-cover rounded-lg shadow-sm" />
            </div>

            <!-- Right: Text -->
            <div class="text-center md:text-left">
              <h3 class="text-xl font-semibold">Hi, I'm Genevieve</h3>
              <p class="mt-4 text-gray-700">I'm a passionate fashion designer focused on creating garments that tell stories — combining handcrafted techniques with contemporary lines to produce wearable art.</p>
            </div>
          </div>
        </div>
      </section>

    </main>

    <?php
    include_once __DIR__ . '/includes/footer.php';
    ?>
  </body>
  <script src="scripts/app.js"></script>
</html>
