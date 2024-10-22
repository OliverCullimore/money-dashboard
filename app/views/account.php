<?php include('includes/header.php'); ?>

<section class="text-center">
  <?php
  while ($transaction = $transactions->fetch_assoc()) {
    echo '<div class="card">';
    echo '<span class="figure">' . ($transaction['value'] > 0 ? '£' . $transaction['value'] : '-£' . str_replace('-', '', $transaction['value'])) . '</span>';
    echo '<span class="description">' . $transaction['description'] . '</span>';
    echo '<span class="tag">' . date('d/m/Y', strtotime($transaction['created_at'])) . '</span>';
    echo '</div>';
  }
  ?>
</section>

<?php include('includes/footer.php'); ?>