<?php include('includes/header.php'); ?>

<section class="text-center">
  <?php if (!empty($institutions)) { ?>
    <form method="post">
      <?php
      foreach ($institutions as $institution) {
        echo '<a class="institution" href="?institutionid=' . $institution['id'] . '" style="background-image: url(\'' . $institution['logo'] . '\');">';
        echo $institution['name'];
        echo '<span>(Past ' . $institution['transaction_total_days'] . ' days)</span>';
        echo '</a>';
      }
      ?>
    </form>
  <?php } else { ?>
    <p>No institutions found!</p>
  <?php } ?>


</section>

<?php include('includes/footer.php'); ?>