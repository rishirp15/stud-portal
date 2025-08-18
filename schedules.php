<?php
require_once 'lib.php';
$filename = 'schedules.json';
$schedules = load_json($filename);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course = trim($_POST['course'] ?? '');
    $day    = trim($_POST['day'] ?? '');
    $time   = trim($_POST['time'] ?? '');
    $room   = trim($_POST['room'] ?? '');

    if ($course && $day && $time) {
        $schedules[] = [
            'id' => uniqid('sch_', true),
            'course' => $course,
            'day' => $day,
            'time' => $time,
            'room' => $room,
            'created_at' => date('c')
        ];
        save_json($filename, $schedules);
        header('Location: schedules.php?ok=1');
        exit;
    } else {
        $error = "Course, Day and Time are required.";
    }
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $schedules = array_values(array_filter($schedules, fn($s) => $s['id'] !== $id));
    save_json($filename, $schedules);
    header('Location: schedules.php?deleted=1');
    exit;
}

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
          <td><?= e($s['day']) ?></td>
          <td><?= e($s['time']) ?></td>
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