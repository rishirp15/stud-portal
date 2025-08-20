<?php
require_once 'lib.php';
require_once 'db.php'; // Include the new database connection

// --- Handle POST request to add a schedule ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course = trim($_POST['course'] ?? '');
    $day    = trim($_POST['day'] ?? '');
    $time   = trim($_POST['time'] ?? '');
    $room   = trim($_POST['room'] ?? '') ?: null; // Set to null if empty

    if ($course && $day && $time) {
        $stmt = $pdo->prepare("INSERT INTO schedules (course, day_of_week, time_slot, room) VALUES (?, ?, ?, ?)");
        $stmt->execute([$course, $day, $time, $room]);

        header('Location: schedules.php?ok=1');
        exit;
    } else {
        $error = "Course, Day and Time are required.";
    }
}

// --- Handle GET request to delete a schedule ---
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $stmt = $pdo->prepare("DELETE FROM schedules WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: schedules.php?deleted=1');
    exit;
}

// --- Fetch all schedules from the database ---
$stmt = $pdo->query("SELECT id, course, day_of_week, time_slot, room FROM schedules ORDER BY course, day_of_week");
$schedules = $stmt->fetchAll();

header_html('Schedules');
?>
<section class="card">
  <h2>Course Schedule</h2>
  <?php if (!empty($error)): ?><p class="note">⚠️ <?= e($error) ?></p><?php endif; ?>
  <form method="post">
    <div class="grid">
      <div>
        <label>Course</label>
        <input name="course" required placeholder="e.g., OS-305">
      </div>
      <div>
        <label>Day</label>
        <select name="day">
          <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
          <option>Thursday</option><option>Friday</option><option>Saturday</option>
        </select>
      </div>
      <div>
        <label>Time</label>
        <input name="time" required placeholder="e.g., 10:30-11:30">
      </div>
      <div>
        <label>Room (optional)</label>
        <input name="room" placeholder="e.g., B-203">
      </div>
    </div>
    <div style="margin-top:12px;"><button type="submit">Add Slot</button></div>
  </form>
</section>

<section class="card">
  <h2>Scheduled Slots</h2>
  <table>
    <thead><tr><th>Course</th><th>Day</th><th>Time</th><th>Room</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($schedules as $s): ?>
        <tr>
          <td><span class="badge"><?= e($s['course']) ?></span></td>
          <td><?= e($s['day_of_week']) ?></td>
          <td><?= e($s['time_slot']) ?></td>
          <td><?= e($s['room']) ?></td>
          <td><a href="schedules.php?del=<?= e($s['id']) ?>" onclick="return confirm('Delete this slot?')">🗑️</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($schedules)): ?>
        <tr><td colspan="5" class="note">No schedule slots yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>
<?php footer_html(); ?>