<?php include('includes/header.php'); ?>

<section class="text-center">
  <?php
  if ($accounts->num_rows > 0) {
    while ($account = $accounts->fetch_assoc()) {
      echo '<a class="card" href="/accounts/' . $account['account_id'] . '">';
      echo '<span class="figure">' . str_replace('£-', '-£', '£' . number_format($account['balance'], 2, '.', '')) . '</span>';
      echo '<span class="name">' . $account['name'] . '</span>';
      echo '<span class="tag">Synced ' . date('d/m/Y H:i', strtotime($account['updated_at'])) . '</span>';
      echo '<span class="tag">' . ucwords($account['status']) . '</span>';
      echo '</a>';
    }
  } else {
    echo '<p>No accounts to show.</p>';
  }
  ?>
</section>

<?php include('includes/footer.php'); ?>