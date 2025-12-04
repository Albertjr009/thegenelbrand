<?php
include_once __DIR__ . '/includes/db_connect.php';

// Get about content
$sql = "SELECT * FROM about_content WHERE id = 1";
$result = $conn->query($sql);
$about = $result->fetch_assoc();

// Get portfolio items
$sql = "SELECT * FROM portfolio_items ORDER BY display_order ASC";
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
    <title>About — thegenelbrand</title>
    <link rel="icon" href="assets/images/genelLogo.jpg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="antialiased text-gray-800 bg-white">
    <?php
  include_once __DIR__ . '/includes/header.php';
  ?>

    <main class="max-w-4xl mx-auto px-6 py-12">
      <?php
      $aboutHeading = $about['heading'];
      $aboutText = $about['content_text'];
      $aboutImage = $about['image_path'];
      ?>

      <h1 class="text-3xl font-bold mb-6"><?php echo htmlspecialchars($aboutHeading); ?></h1>
      <section class="bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 py-12">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <!-- Left: Image -->
            <div>
              <img src="<?php echo htmlspecialchars($aboutImage); ?>" alt="About image" class="w-full h-64 md:h-80 object-cover rounded-lg shadow-sm" />
            </div>

            <!-- Right: Text -->
            <div class="text-center md:text-left">
              <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($aboutHeading); ?></h3>
              <p class="mt-4 text-gray-700"><?php echo nl2br(htmlspecialchars($aboutText)); ?></p>
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
