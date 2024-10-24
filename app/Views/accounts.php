<?php include('includes/header.php'); ?>

<section class="text-center">
  <?php
  while ($account = $accounts->fetch_assoc()) {
    echo '<a class="card" href="/accounts/' . $account['account_id'] . '">';
    echo '<span class="figure">' . ($account['balance'] > 0 ? '£' . $account['balance'] : '-£' . str_replace('-', '', $account['balance'])) . '</span>';
    echo '<span class="name">' . $account['name'] . '</span>';
    echo '<span class="tag">Synced ' . date('d/m/Y H:i', strtotime($account['updated_at'])) . '</span>';
    echo '<span class="tag">' . ucwords($account['status']) . '</span>';
    echo '</a>';
  }
  ?>
</section>

<?php include('includes/footer.php'); ?>