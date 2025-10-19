<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>thegenelbrand — Fashion Designer</title>
    <meta name="description" content="thegenelbrand — Creative Fashion Designer blending tradition with modern elegance." />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="assets/images/genelLogo.jpg" />
  </head>
  <body class="antialiased text-gray-800 bg-white">

  <?php
  include_once __DIR__ . '/includes/header.php';
  ?>

    <!-- HERO -->
    <main>
      <section class="relative h-[65vh] md:h-[80vh] flex items-center">
        <img src="https://images.unsplash.com/photo-1520975918595-3b9f9bf9d7e4?auto=format&fit=crop&w=1600&q=80" alt="Fashion" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-6xl mx-auto px-6 text-center text-white">
          <h1 class="text-3xl md:text-5xl font-bold">Creative Fashion Designer</h1>
          <p class="mt-4 text-lg md:text-xl opacity-90">Blending tradition with modern elegance</p>
          <div class="mt-8">
            <a href="#featured" id="viewWorksBtn" class="inline-block bg-white text-black px-6 py-3 rounded shadow hover:opacity-90">View My Works</a>
          </div>
        </div>
      </section>

      <!-- FEATURED DESIGNS -->
      <section id="featured" class="max-w-6xl mx-auto px-6 py-16">
        <h2 class="text-2xl font-semibold mb-6">Featured Designs</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <a href="portfolio.php" class="group block overflow-hidden rounded-lg">
            <img src="assets/images/project1.jpg" alt="Design 1" class="w-full h-56 object-cover transform group-hover:scale-105 transition" />
            <div class="mt-3 text-sm">Hand-stitched evening gown</div>
          </a>

          <a href="portfolio.php" class="group block overflow-hidden rounded-lg">
            <img src="assets/images/project2.jpg" alt="Design 2" class="w-full h-56 object-cover transform group-hover:scale-105 transition" />
            <div class="mt-3 text-sm">Modern take on traditional motifs</div>
          </a>

          <a href="portfolio.php" class="group block overflow-hidden rounded-lg">
            <img src="assets/images/project3.jpg" alt="Design 3" class="w-full h-56 object-cover transform group-hover:scale-105 transition" />
            <div class="mt-3 text-sm">Daywear collection highlights</div>
          </a>
        </div>
      </section>

      <!-- ABOUT PREVIEW -->
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
              <div class="mt-6">
                <a href="about.php" class="inline-block bg-gray-900 text-white px-5 py-2 rounded">Read More About Me</a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <?php
    include_once __DIR__ . '/includes/footer.php';
    ?>

    <script src="scripts/app.js"></script>
  </body>
</html>
