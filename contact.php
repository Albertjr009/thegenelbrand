<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact — thegenelbrand</title>
    <link rel="icon" href="assets/images/genelLogo.jpg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="antialiased text-gray-800 bg-white">
    <?php
  include_once __DIR__ . '/includes/header.php';
  ?>

    <main class="max-w-3xl mx-auto px-6 py-12">
      <h1 class="text-3xl font-bold mb-4">Contact</h1>
      <p class="text-gray-700 mb-6">For commissions, collaborations, or press inquiries, drop a message below or email me at <a href="mailto:ama@example.com" class="underline">genevieveappiah16@gmail.com</a>.</p>

      <form class="grid grid-cols-1 gap-4" action="mailto:ama@example.com" method="post" enctype="text/plain">
        <input type="text" name="name" placeholder="Your name" class="border px-3 py-2 rounded" required />
        <input type="email" name="email" placeholder="Your email" class="border px-3 py-2 rounded" required />
        <textarea name="message" rows="6" placeholder="Message" class="border px-3 py-2 rounded" required></textarea>
        <div>
          <button type="submit" class="bg-gray-900 text-white px-5 py-2 rounded">Send Message</button>
        </div>
      </form>
    </main>

    <?php
    include_once __DIR__ . '/includes/footer.php';
    ?>
  </body>
  <script src="scripts/app.js"></script>
</html>
