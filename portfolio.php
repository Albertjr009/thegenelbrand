<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Portfolio — thegenelbrand</title>
  <link rel="icon" href="assets/images/genelLogo.jpg" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="antialiased text-gray-800 bg-white">
  <?php
  include_once __DIR__ . '/includes/header.php';
  ?>

    <main class="max-w-6xl mx-auto px-6 py-12">
    <?php
  $contentPath = __DIR__ . '/content/site.json';
  $items = [];
  if (file_exists($contentPath)) {
    $data = json_decode(file_get_contents($contentPath), true);
    if (!empty($data['portfolio']) && is_array($data['portfolio'])) {
      $items = $data['portfolio'];
    }
  }
    ?>

    <h1 class="text-3xl font-bold mb-6">Portfolio</h1>
    <p class="text-gray-700 mb-8">A curated selection of recent collections and commissioned pieces.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php if (empty($items)): ?>
        <article class="rounded overflow-hidden shadow-sm">
          <img src="https://images.unsplash.com/photo-1520975918595-3b9f9bf9d7e4?auto=format&fit=crop&w=1200&q=60" alt="Work 1" class="w-full h-64 object-cover" />
          <div class="p-4">
            <h3 class="font-semibold">Evening Gown</h3>
            <p class="text-sm text-gray-600 mt-1">Hand-stitched detailing and silk lining.</p>
          </div>
        </article>
      <?php else: ?>
        <?php foreach ($items as $it): ?>
          <article class="rounded overflow-hidden shadow-sm">
            <img src="<?php echo htmlspecialchars($it['image']); ?>" alt="<?php echo htmlspecialchars($it['title']); ?>" class="w-full h-64 object-cover" />
            <div class="p-4">
              <h3 class="font-semibold"><?php echo htmlspecialchars($it['title']); ?></h3>
              <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($it['caption']); ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </main>
  <?php
  include_once __DIR__ . '/includes/footer.php';
  ?>
</body>
<script src="scripts/app.js"></script>

</html>