<?php
/* Note: No credit is provided for submitting design and/or code that is     */
/*       taken from course-provided examples.                                */
/*                                                                           */
/* Do not copy this code into your project submission and then change it.    */
/*                                                                           */
/* Write your own code from scratch. Use this example as a REFERENCE only.   */
/*                                                                           */
/* You may not copy this code, change a few names/variables, and then claim  */
/* it as your own.                                                           */
/*                                                                           */
/* Examples are provided to help you learn. Copying the example and then     */
/* changing it a bit, does not help you learn the learning objectives of     */
/* this assignment. You need to write your own code from scratch to help you */
/* learn.                                                                    */

$page_title = "Transcript";

$nav_transcript_class = "active_page";

include_once("includes/transcript-values.php");

// CSS classes for sort arrows
$sort_css_classes = array(
  "course_asc" => "inactive",
  "course_desc" => "inactive"
);

// get the "order" query string parameter
$order_param = $_GET['order'] ?? NULL;
// $order_param = $_GET['order'] == "" ? NULL : $_GET["order"];

if ($order_param == "asc") {
  $sort_css_classes[$sort_param . "course_asc"] = "";
  $sort_css_classes[$sort_param . "course_desc"] = "hidden";
} else if ($order_param == "desc") {
  $sort_css_classes[$sort_param . "course_asc"] = "hidden";
  $sort_css_classes[$sort_param . "course_desc"] = "";
}

// query grades table
$records = exec_sql_query($db, "SELECT
  grades.id AS 'grades.id',
  courses.number AS 'courses.number',
  courses.credits AS 'courses.credits',
  grades.term AS 'grades.term',
  grades.acad_year AS 'grades.acad_year',
  grades.grade AS 'grades.grade'
FROM grades INNER JOIN courses ON (grades.course_id = courses.id);")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<?php include "includes/meta.php" ?>

<body>
  <?php include "includes/header.php" ?>

  <main class="transcript">
    <h2><?php echo $page_title; ?></h2>

    <!-- Note: You may not copy the instructor's code/design and submit it  -->
    <!--       as your own.                                                 -->
    <!--                                                                    -->
    <!-- We studied textual catalog design patterns in class, there are     -->
    <!-- many design alternatives for for presenting textual information.   -->
    <!-- You may use this design for inspiration. However, you must design  -->
    <!-- your own.                                                          -->
    <!--                                                                    -->
    <!-- Remember, design is a learning objective of this class. Your your  -->
    <!-- future employers will expect you to  be able to design on your own -->
    <!-- without copying someone else's work. Use this experience as        -->
    <!-- practice.                                                          -->

    <p style="text-align: right; margin-bottom: 0.5em;">
      <!-- TODO: add "order" query string parameters to URL -->
      <a href="/transcript?<?php echo http_build_query(array("order" => "asc"));?>">Sort Alphabetically</a>
      |
      <!-- TODO: add "order" query string parameters to URL -->
      <a href="/transcript?<?php echo http_build_query(array("order" => "desc"));?>">Sort Reverse Alphabetically</a>
      |
      <!-- TODO: add "order" query string parameters to URL -->
      <a href="/transcript">No Sort</a>
    </p>

    <table>
      <tr>
        <th class="column-course">
          Course
          <svg class="icon" version="1.1" viewBox="0 0 2.1391 4.2339" xmlns="http://www.w3.org/2000/svg">
            <g transform="translate(-38.257 -61.073)">
              <path class="sort_desc <?php echo $sort_css_classes["course_desc"]; ?>" d="m40.396 63.455-1.0695 1.8521-1.0695-1.8521z" />
              <path class="sort_asc <?php echo $sort_css_classes["course_asc"]; ?>" d="m40.396 62.925h-2.1391l1.0695-1.8521z" />
            </g>
          </svg>
        </th>

        <th class="column-term">
          Term
        </th>

        <th class="column-year">
          Year
        </th>

        <th class="column-credits">
          Credits
        </th>

        <th class="column-grade min">
          Grade
        </th>
      </tr>

      <?php
      // write a table row for each record
      foreach ($records as $record) {
        $course = $record["courses.number"];
        $term = TERM_CODINGS[$record["grades.term"]];
        $year = ACADEMIC_YEAR_CODINGS[$record["grades.acad_year"]];
        $credits = $record["courses.credits"];
        $grade = $record["grades.grade"] ?? "";

        // row partial
        include "includes/transcript-record.php";
      } ?>

    </table>

  </main>

  <?php include "includes/footer.php" ?>
</body>

</html>
