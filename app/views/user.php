<?php include('includes/header.php'); ?>

<section class="text-center">
  <h1><?php echo $title; ?></h1>
  <p>Update password</p>
  <form method="post">
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit"><?php echo $title; ?></button>
  </form>
</section>

<?php include('includes/footer.php'); ?>