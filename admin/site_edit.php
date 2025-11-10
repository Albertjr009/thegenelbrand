<?php
// admin/site_edit.php — edit About and Portfolio content
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/includes/auth.php';
include_once __DIR__ . '/includes/config.php';

$contentPath = __DIR__ . '/../content/site.json';
$data = [];
if (file_exists($contentPath)) {
    $data = json_decode(file_get_contents($contentPath), true);
}

// ensure structure
if (empty($data['about'])) $data['about'] = ['heading' => 'Hi, I\'m Genevieve', 'text' => ''];
if (empty($data['portfolio']) || !is_array($data['portfolio'])) {
    $data['portfolio'] = [
        ['image' => 'assets/images/project1.jpg','title'=>'Evening Gown','caption'=>'Hand-stitched detailing and silk lining.'],
        ['image' => 'assets/images/project2.jpg','title'=>'Tradition Reimagined','caption'=>'Modern silhouettes with artisanal prints.'],
        ['image' => 'assets/images/project3.jpg','title'=>'Daywear Collection','caption'=>'Lightweight, sustainable fabrics.']
    ];
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // About
    $data['about']['heading'] = trim($_POST['about_heading'] ?? $data['about']['heading']);
    $data['about']['text'] = trim($_POST['about_text'] ?? $data['about']['text']);

    // optional about image upload
    if (!empty($_FILES['about_image']) && $_FILES['about_image']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['about_image']['tmp_name'];
        $name = basename($_FILES['about_image']['name']);
        $targetDir = __DIR__ . '/../assets/images/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $target = $targetDir . $name;
        if (move_uploaded_file($tmp, $target)) {
            $data['about']['image'] = 'assets/images/' . $name;
        }
    }

    // Portfolio items — iterate existing items
    for ($i = 0; $i < count($data['portfolio']); $i++) {
        $data['portfolio'][$i]['title'] = trim($_POST["title_$i"] ?? $data['portfolio'][$i]['title']);
        $data['portfolio'][$i]['caption'] = trim($_POST["caption_$i"] ?? $data['portfolio'][$i]['caption']);

        $fileKey = "file_$i";
        if (!empty($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES[$fileKey]['tmp_name'];
            $name = basename($_FILES[$fileKey]['name']);
            $targetDir = __DIR__ . '/../assets/images/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $target = $targetDir . $name;
            if (move_uploaded_file($tmp, $target)) {
                $data['portfolio'][$i]['image'] = 'assets/images/' . $name;
            }
        }
    }

    // Save JSON
    if (file_put_contents($contentPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
        $message = 'Content saved.';
    } else {
        $message = 'Failed to save content.';
    }
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin — Edit Site Content</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50">
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Site Content</h1>
    <?php if ($message): ?><div class="mb-4 text-sm text-green-700"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded shadow">
      <section>
        <h2 class="font-semibold mb-2">About</h2>
        <label class="block text-sm">Heading</label>
        <input name="about_heading" class="mt-1 block w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($data['about']['heading']); ?>">
        <label class="block text-sm mt-3">Text</label>
        <textarea name="about_text" rows="5" class="mt-1 block w-full border rounded px-3 py-2"><?php echo htmlspecialchars($data['about']['text']); ?></textarea>
        <label class="block text-sm mt-3">Replace About Image (optional)</label>
        <input type="file" name="about_image" accept="image/*" class="mt-1" />
      </section>

      <section>
        <h2 class="font-semibold mb-2">Portfolio Items</h2>
        <?php foreach ($data['portfolio'] as $i => $item): ?>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center mb-4">
            <div>
              <img src="<?php echo htmlspecialchars('../' . $item['image']); ?>" class="w-full h-28 object-cover rounded" alt="item">
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm">Title</label>
              <input name="title_<?php echo $i; ?>" class="mt-1 block w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($item['title']); ?>">
              <label class="block text-sm mt-2">Caption</label>
              <input name="caption_<?php echo $i; ?>" class="mt-1 block w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($item['caption']); ?>">
              <label class="block text-sm mt-2">Replace Image (optional)</label>
              <input type="file" name="file_<?php echo $i; ?>" accept="image/*" class="mt-1">
            </div>
          </div>
        <?php endforeach; ?>
      </section>

      <div class="flex justify-end">
        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Save Changes</button>
      </div>
    </form>
  </div>
</body>
</html>
