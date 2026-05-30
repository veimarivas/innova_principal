<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');

// Check what course ID and sections exist
$courseId = 57; // your moodle_course_id from modulo 108
echo "Course sections for course $courseId:\n";
$q = $pdo->query("SELECT id, course, section, name FROM moodle5.mdl_course_sections WHERE course = $courseId ORDER BY section");
while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
    echo "  id={$r['id']} section={$r['section']} name=\"{$r['name']}\"\n";
}

// Check if the sec.id from API matches DB id
echo "\nSample data check - all section ids from API should match these ids:\n";
$q2 = $pdo->query("SELECT id, section FROM moodle5.mdl_course_sections WHERE course = $courseId");
while ($r = $q2->fetch(PDO::FETCH_ASSOC)) {
    // The section number 0 is the "General" section
    echo "  DB id={$r['id']} -> section number={$r['section']}\n";
}
