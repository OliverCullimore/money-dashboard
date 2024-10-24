<?php include('includes/header.php'); ?>

<section class="text-center">
  <?php
  if ($transactions->num_rows > 0) {
    while ($transaction = $transactions->fetch_assoc()) {
      echo '<div class="card">';
      echo '<span class="figure">' . str_replace('£-', '-£', '£' . number_format($transaction['value'], 2, '.', '')) . '</span>';
      echo '<span class="description">' . $transaction['description'] . '</span>';
      echo '<span class="tag">' . date('d/m/Y', strtotime($transaction['created_at'])) . '</span>';
      echo '</div>';
    }
  } else {
    echo '<p>No transactions to show.</p>';
  }
  ?>
</section>

<?php include('includes/footer.php'); ?>