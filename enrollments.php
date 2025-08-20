<?php
require_once 'lib.php';
require_once 'db.php'; // Include the new database connection

// --- Handle POST request to add an enrollment ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student = trim($_POST['student'] ?? '');
    $course  = trim($_POST['course'] ?? '');
    $email   = trim($_POST['email'] ?? '') ?: null; // Set to null if empty

    if ($student && $course) {
        // Use a prepared statement to prevent SQL injection
        $stmt = $pdo->prepare("INSERT INTO enrollments (student, course, email) VALUES (?, ?, ?)");
        $stmt->execute([$student, $course, $email]);

        header('Location: enrollments.php?ok=1');
        exit;
    } else {
        $error = "Student and Course are required.";
    }
}

// --- Handle GET request to delete an enrollment ---
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $stmt = $pdo->prepare("DELETE FROM enrollments WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: enrollments.php?deleted=1');
    exit;
}

// --- Fetch all enrollments from the database ---
$stmt = $pdo->query("SELECT id, student, course, email, created_at FROM enrollments ORDER BY created_at DESC");
$enrollments = $stmt->fetchAll();

header_html('Enrollments');
?>
<section class="card">
  <h2>Add Enrollment</h2>
  <?php if (!empty($error)): ?><p class="note">⚠️ <?= e($error) ?></p><?php endif; ?>
  <form method="post">
    <div class="grid">
      <div>
        <label>Student Name</label>
        <input name="student" required placeholder="e.g., Ananya Sharma">
      </div>
      <div>
        <label>Course</label>
        <input name="course" required placeholder="e.g., DBMS-202">
      </div>
      <div>
        <label>Email (optional)</label>
        <input name="email" type="email" placeholder="e.g., ananya@college.edu">
      </div>
    </div>
    <div style="margin-top:12px;"><button type="submit">Add</button></div>
  </form>
</section>

<section class="card">
  <h2>Current Enrollments</h2>
  <table>
    <thead><tr><th>Student</th><th>Course</th><th>Email</th><th>Created</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($enrollments as $e): ?>
        <tr>
          <td><?= e($e['student']) ?></td>
          <td><span class="badge"><?= e($e['course']) ?></span></td>
          <td><?= e($e['email']) ?></td>
          <td><?= e(date('Y-m-d H:i', strtotime($e['created_at']))) ?></td>
          <td><a href="enrollments.php?del=<?= e($e['id']) ?>" onclick="return confirm('Delete this enrollment?')">🗑️</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($enrollments)): ?>
        <tr><td colspan="5" class="note">No enrollments yet. Add one above.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>
<?php footer_html(); ?>