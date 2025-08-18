<?php
require_once 'lib.php';
$filename = 'attendance.json';
$attendance = load_json($filename);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date    = trim($_POST['date'] ?? '');
    $student = trim($_POST['student'] ?? '');
    $course  = trim($_POST['course'] ?? '');
    $status  = trim($_POST['status'] ?? 'Present');

    if ($date && $student && $course) {
        $attendance[] = [
            'id' => uniqid('att_', true),
            'date' => $date,
            'student' => $student,
            'course' => $course,
            'status' => $status,
            'created_at' => date('c')
        ];
        save_json($filename, $attendance);
        header('Location: attendance.php?ok=1');
        exit;
    } else {
        $error = "Date, Student, and Course are required.";
    }
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $attendance = array_values(array_filter($attendance, fn($r) => $r['id'] !== $id));
    save_json($filename, $attendance);
    header('Location: attendance.php?deleted=1');
    exit;
}

header_html('Attendance');
?>
<section class="card">
  <h2>Mark Attendance</h2>
  <?php if (!empty($error)): ?><p class="note">⚠️ <?= e($error) ?></p><?php endif; ?>
  <form method="post">
    <div class="grid">
      <div>
        <label>Date</label>
        <input name="date" type="date" required value="<?= e(date('Y-m-d')) ?>">
      </div>
      <div>
        <label>Student Name</label>
        <input name="student" required placeholder="e.g., Rohan Patil">
      </div>
      <div>
        <label>Course</label>
        <input name="course" required placeholder="e.g., SE-201">
      </div>
      <div>
        <label>Status</label>
        <select name="status">
          <option>Present</option>
          <option>Absent</option>
          <option>Late</option>
        </select>
      </div>
    </div>
    <div style="margin-top:12px;"><button type="submit">Save</button></div>
  </form>
</section>

<section class="card">
  <h2>Attendance Records</h2>
  <table>
    <thead><tr><th>Date</th><th>Student</th><th>Course</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($attendance as $r): ?>
        <tr>
          <td><?= e($r['date']) ?></td>
          <td><?= e($r['student']) ?></td>
          <td><span class="badge"><?= e($r['course']) ?></span></td>
          <td><?= e($r['status']) ?></td>
          <td><a href="attendance.php?del=<?= e($r['id']) ?>" onclick="return confirm('Delete this record?')">🗑️</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($attendance)): ?>
        <tr><td colspan="5" class="note">No attendance recorded yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>
<?php footer_html(); ?>