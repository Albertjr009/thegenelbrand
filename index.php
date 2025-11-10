<?php
include_once __DIR__ . '/includes/config.php';

// Get about content
$sql = "SELECT * FROM about_content WHERE id = 1";
$result = $conn->query($sql);
$about = $result->fetch_assoc();

// Get portfolio items
$sql = "SELECT * FROM portfolio_items ORDER BY display_order ASC LIMIT 3";
$result = $conn->query($sql);
$portfolio_items = [];
while ($row = $result->fetch_assoc()) {
    $portfolio_items[] = $row;
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>thegenelbrand — Fashion Designer</title>
    <meta name="description" content="thegenelbrand — Creative Fashion Designer blending tradition with modern elegance." />
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="icon" href="assets/images/genelLogo.jpg" />
    <script src="https://cdn.tailwindcss.com"></script>
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
          <?php foreach ($portfolio_items as $item): ?>
          <a href="portfolio.php" class="group block overflow-hidden rounded-lg">
            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" 
                 alt="<?php echo htmlspecialchars($item['title']); ?>" 
                 class="w-full h-56 object-cover transform group-hover:scale-105 transition" />
            <div class="mt-3 text-sm"><?php echo htmlspecialchars($item['title']); ?></div>
          </a>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- ABOUT PREVIEW -->
      <section class="bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 py-12">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <!-- Left: Image -->
            <div>
              <img src="<?php echo htmlspecialchars($about['image_path']); ?>" 
                   alt="<?php echo htmlspecialchars($about['heading']); ?>" 
                   class="w-full h-64 md:h-80 object-cover rounded-lg shadow-sm" />
            </div>

            <!-- Right: Text -->
            <div class="text-center md:text-left">
              <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($about['heading']); ?></h3>
              <p class="mt-4 text-gray-700"><?php echo htmlspecialchars($about['content_text']); ?></p>
              <div class="mt-6">
                <a href="about.php" class="inline-block bg-gray-900 text-white px-5 py-2 rounded">Read More</a>
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
