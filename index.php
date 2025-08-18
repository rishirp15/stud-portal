<?php
require_once 'lib.php';
header_html('Home');
?>
<section class="card">
  <h2>Welcome 👋</h2>
  <p>This is a demo PHP app for managing <strong>course enrollments</strong>, <strong>attendance</strong>, and <strong>schedules</strong>.
     Data is stored in JSON files under your app's writable <code>/home/data</code> folder.</p>
  <ul>
    <li>Use <strong>Enrollments</strong> to add students to courses.</li>
    <li>Mark daily <strong>Attendance</strong> for students.</li>
    <li>Maintain course <strong>Schedules</strong>.</li>
  </ul>
</section>
<?php footer_html(); ?>